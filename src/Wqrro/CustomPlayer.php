<?php

declare(strict_types=1);

namespace Wqrro;

use pocketmine\entity\Attribute;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\SourceInterface;
use pocketmine\Player;
use pocketmine\Server;
use Wqrro\party\Party;
use Wqrro\party\PartyManager;

class CustomPlayer extends Player{

	public const LOBBY = 0;
	public const NODEBUFF = 1;
	public const GAPPLE = 2;
	public const OPGAPPLE = 3;
	public const COMBO = 4;
	public const FIST = 5;
	public const TOURNAMENT = 6;
	public const NODEBUFFLOWKB = 7;
	public const NODEBUFFJAVA = 8;
	public const MAX_ENDERPEARL_SEC = 10;
	public $re = null;
	protected $interface;
	protected $ip;
	protected $port;
	protected $location = 0;
	protected $cpsflags = 0;
	protected $reachflags = 0;
	protected $fishing = null;
	protected $frozen = false;
	protected $chatcooldown = false;
	protected $enderpearlcooldown = false;
	protected $staffmode = false;
	protected $vanished = false;
	protected $disguised = false;
	protected $messages = false;
	protected $coords = false;
	protected $anticheat = true;
	protected $hascape = false;
	private $rank = "Player";
	private $clantag = "";
	private $party = null;
	private $partyrank = null;
	private $plugin;
	private $maxEnderpearlTicks;
	private $pingTicks;
	private $enderpearlTick;
	private $currentTick;
	private $endTick;

	public function __construct(SourceInterface $interface, $ip, $port){
		parent::__construct($interface, $ip, $port);
		$plugin = $this->getServer()->getPluginManager()->getPlugin("Practice");
		if($plugin instanceof Core){
			$this->setPlugin($plugin);
		}else{
			$this->getServer()->shutdown();
		}
		$this->maxEnderpearlTicks = Utils::secondsToTicks(self::MAX_ENDERPEARL_SEC);
		$this->pingTicks = 0;
		$this->enderpearlTick = 0;
		$this->currentTick = 0;
	}

	public function getPlugin() : Core{
		return $this->plugin;
	}

	public function setPlugin($plugin){
		$this->plugin = $plugin;
	}

	public function hasRe() : bool{
		if($this->re === null) return false;
		$re = $this->getRe();
		if($re === null) return false;
		$player = $this->getRe();
		return $player !== null;
	}

	public function getRe(){
		return Server::getInstance()->getPlayerExact($this->re);
	}

	public function setRe($player){
		$re = $player;
		$this->re = ($re != null ? $re->getName() : "");
	}

	public function update() : void{
		$this->rank = $this->plugin->getDatabaseHandler()->getRank(Utils::getPlayerName($this));
		if($this->getPing() >= 300){
			$this->pingTicks++;
		}else{
			$this->pingTicks = 0;
		}
		if($this->pingTicks >= 1 * 20 * 20){
			$message = $this->plugin->getStaffUtils()->sendStaffAlert("ping");
			$message = str_replace("{name}", $this->getName(), $message);
			$message = str_replace("{details}", $this->getPing(), $message);
			foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
				if($online->hasPermission("cp.staff.cheatalerts")){
					$online->sendMessage($message);
				}
			}
			$this->kick("§cYour ping is too high.\n§fVia Anti-Cheat", false);
		}
		$this->currentTick++;
	}

	public function getPartyRank() : ?string{
		return $this->partyrank;
	}

	public function setPartyRank($rank){
		$this->partyrank = $rank;
	}

	public function getClanTag() : string{
		$result = $this->clantag;
		return $result;
	}

	public function setClanTag(string $clantag = ""){
		$this->clantag = $clantag;
		Utils::setPlayerData($this, "clan-tag", $clantag);
	}

	public function isPlayer() : bool{
		return $this->getRank() == "Player";
	}

	public function getRank() : string{
		$result = $this->rank;
		return $result;
	}

	public function setRank(string $rank = "Player"){
		$this->rank = $rank;
	}

	public function isStaff() : bool{
		return $this->isTrainee() or $this->isHelper() or $this->isMod() or $this->isAdmin() or $this->isManager() or $this->isOwner();
	}

	public function isTrainee() : bool{
		return $this->getRank() == "Trainee";
	}

	public function isHelper() : bool{
		return $this->getRank() == "Helper";
	}

	public function isMod() : bool{
		return $this->getRank() == "Mod";
	}

	public function isAdmin() : bool{
		return $this->getRank() == "Admin";
	}

	public function isManager() : bool{
		return $this->getRank() == "Manager";
	}

	public function isOwner() : bool{
		return $this->getRank() == "Owner";
	}

	public function isVip() : bool{
		return $this->getRank() == "VIP";
	}

	public function isElite() : bool{
		return $this->getRank() == "Elite";
	}

	public function isPremium() : bool{
		return $this->getRank() == "Premium";
	}

	public function getPlayerLocation() : int{
		return $this->location;
	}

	public function addCpsFlag(){
		$this->cpsflags = $this->cpsflags + 1;
	}

	public function getCpsFlags() : int{
		return $this->cpsflags;
	}

	public function setCpsFlags(int $int){
		$this->cpsflags = $int;
	}

	public function addReachFlag(){
		$this->reachflags = $this->reachflags + 1;
	}

	public function getReachFlags() : int{
		return $this->reachflags;
	}

	public function setReachFlags(int $int){
		$this->reachflags = $int;
	}

	public function isFrozen() : bool{
		return $this->frozen !== false;
	}

	public function setFrozen(bool $value){
		$this->frozen = $value;
	}

	public function isStaffMode() : bool{
		return $this->staffmode !== false;
	}

	public function setStaffMode(bool $value){
		$this->staffmode = $value;
	}

	public function isDisguised() : bool{
		return $this->disguised !== false;
	}

	public function setDisguised(bool $value){
		$this->disguised = $value;
	}

	public function isVanished() : bool{
		return $this->vanished !== false;
	}

	public function setVanished(bool $value){
		$this->vanished = $value;
	}

	public function isMessages() : bool{
		return $this->messages !== false;
	}

	public function setMessages(bool $value){
		$this->messages = $value;
	}

	public function setCoordins(bool $value){
		$this->coords = $value;
	}

	public function isCoordins() : bool{
		return $this->coords !== false;
	}

	public function setAntiCheat(bool $value){
		$this->anticheat = $value;
	}

	public function isAntiCheatOn() : bool{
		return $this->anticheat !== false;
	}

	public function isChatCooldown() : bool{
		return $this->chatcooldown !== false;
	}

	public function setChatCooldown(bool $value){
		$this->chatcooldown = $value;
	}

	public function isEnderPearlCooldown() : bool{
		return $this->enderpearlcooldown !== false;
	}

	public function setEnderPearlCooldown(bool $value){
		$this->enderpearlcooldown = $value;
	}

	public function startFishing($obj) : void{
		if($this->isOnline()){
			if(!$this->isFishing()){
				$this->fishing = $obj;
			}
		}
	}

	public function isFishing() : bool{
		return $this->fishing !== null;
	}

	public function getFishing(){
		return $this->fishing;
	}

	public function stopFishing(bool $click = true, bool $killEntity = true) : void{
		if($this->isFishing()){
			$this->fishing = null;
		}
	}

	public function setHasCape(bool $value){
		$this->hascape = $value;
	}

	public function hasCape() : bool{
		return $this->hascape !== false;
	}

	public function setTagged($value = true){
		if($value){
			$this->plugin->taggedPlayer[Utils::getPlayerName($this)] = 21;
		}else{
			unset($this->plugin->taggedPlayer[Utils::getPlayerName($this)]);
		}
	}

	public function isTagged(){
		return isset($this->plugin->taggedPlayer[Utils::getPlayerName($this)]);
	}

	public function getTagDuration(){
		return (Utils::isTagged(Utils::getPlayerName($this)) ? $this->plugin->taggedPlayer[Utils::getPlayerName($this)] : 0);
	}

	public function setNicked($value = true, $nick = null){
		if($value){
			$this->plugin->nickedPlayer[Utils::getPlayerName($this)] = $nick;
		}else{
			unset($this->plugin->nickedPlayer[Utils::getPlayerName($this)]);
		}
	}

	public function isNicked() : bool{
		return isset($this->plugin->nickedPlayer[Utils::getPlayerName($this)]);
	}

	public function handleLevelSoundEvent(LevelSoundEventPacket $packet) : bool{
		if($packet->sound === LevelSoundEventPacket::SOUND_ATTACK_STRONG or $packet->sound === LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE){
			return false;
		}
		Utils::broadcastPacketToViewers($this, $packet, function(Player $player, DataPacket $packet){
			if($player instanceof CustomPlayer and $packet instanceof LevelSoundEventPacket){
				if(!isset(Utils::SWISH_SOUNDS[$packet->sound])){
					return true;
				}
				return false;
			}
			return true;
		});
		return true;
	}

	public function attack(EntityDamageEvent $source) : void{
		parent::attack($source);
		if($source->isCancelled()){
			return;
		}
		if($source instanceof EntityDamageByEntityEvent){
			$damager = $source->getDamager();
			if($damager instanceof Player){
				if($this->plugin->getDuelHandler()->isInPartyDuel($damager)){
					$duel = $this->plugin->getDuelHandler()->getPartyDuel($damager);
				}elseif($this->plugin->getDuelHandler()->isInDuel($damager)){
					$duel = $this->plugin->getDuelHandler()->getDuel($damager);
					switch(strtolower($duel->getQueue())){
						case "combo":
							$this->attackTime = 2;
							break;
						case "gapple":
							$this->attackTime = 10;
							break;
						default:
							$this->attackTime = 10;
							break;
					}
				}else{
					switch($this->getLevel()){
						case $this->plugin->getServer()->getLevelByName("nodebuff");
							$this->attackTime = 10;
							break;
						case $this->plugin->getServer()->getLevelByName("gapple");
							$this->attackTime = 10;
							break;
						case $this->plugin->getServer()->getLevelByName("opgapple");
							$this->attackTime = 10;
							break;
						case $this->plugin->getServer()->getLevelByName("combo");
							$this->attackTime = 2;
							break;
						case $this->plugin->getServer()->getLevelByName("fist");
							$this->attackTime = 7;
							break;
					}
				}
			}
		}
	}

	public function knockBack($damager, float $damage, float $x, float $z, float $base = 0.4) : void{
		$xzKB = 0.388;
		$yKb = 0.390;
		if($damager instanceof Player){
			if($this->plugin->getDuelHandler()->getPartyDuel($damager) === null and $this->plugin->getDuelHandler()->getDuel($damager) === null){
				switch($this->getLevel()){
					case $this->plugin->getServer()->getLevelByName("nodebuff");
						$xzKB = 0.388;
						$yKb = 0.390;
						break;
					case $this->plugin->getServer()->getLevelByName("nodebuff-low");
						$xzKB = 0.385;
						$yKb = 0.380;
						break;
					case $this->plugin->getServer()->getLevelByName("nodebuff-java");
						$xzKB = 0.390;
						$yKb = 0.366;
						break;
					case $this->plugin->getServer()->getLevelByName("gapple");
						$xzKB = 0.393;
						$yKb = 0.392;
						break;
					case $this->plugin->getServer()->getLevelByName("opgapple");
						$xzKB = 0.391;
						$yKb = 0.391;
						break;
					case $this->plugin->getServer()->getLevelByName("combo");
						$xzKB = 0.330;
						$yKb = 0.300;
						break;
					case $this->plugin->getServer()->getLevelByName("fist");
						$xzKB = 0.405;
						$yKb = 0.408;
						break;
				}
			}elseif($this->plugin->getDuelHandler()->isInPartyDuel($damager)){
				$duel = $this->plugin->getDuelHandler()->getPartyDuel($damager);
				//$xzKB=$duel->getHorizontalKb();
				//$yKb=$duel->getVerticalKb();
			}elseif($this->plugin->getDuelHandler()->isInDuel($damager)){
				$duel = $this->plugin->getDuelHandler()->getDuel($damager);
				switch(strtolower($duel->getQueue())){
					case "nodebuff":
						$xzKB = 0.388;
						$yKb = 0.390;
						break;
					case "gapple":
						$xzKB = 0.393;
						$yKb = 0.392;
						break;
					case "soup":
						$xzKB = 0.388;
						$yKb = 0.390;
						break;
					case "builduhc":
						$xzKB = 0.392;
						$yKb = 0.392;
						break;
					case "combo":
						$xzKB = 0.350;
						$yKb = 0.300;
						break;
					case "sumo":
						$xzKB = 0.375;
						$yKb = 0.380;
						break;
					default:
						$xzKB = 0.388;
						$yKb = 0.390;
						break;
				}
			}
		}
		$f = sqrt($x * $x + $z * $z);
		if($f <= 0){
			return;
		}
		if(mt_rand() / mt_getrandmax() > $this->getAttributeMap()->getAttribute(Attribute::KNOCKBACK_RESISTANCE)->getValue()){
			$f = 1 / $f;
			$motion = clone $this->motion;
			$motion->x /= 2;
			$motion->y /= 2;
			$motion->z /= 2;
			$motion->x += $x * $f * $xzKB;
			$motion->y += $yKb;
			$motion->z += $z * $f * $xzKB;
			if($motion->y > $yKb){
				$motion->y = $yKb;
			}
			$this->setMotion($motion);
		}
	}

	public function initializeLogin(){
		$this->plugin->getDatabaseHandler()->rankAdd(Utils::getPlayerName($this));
		$this->plugin->getDatabaseHandler()->levelsAdd(Utils::getPlayerName($this));
		$this->plugin->getDatabaseHandler()->essentialStatsAdd(Utils::getPlayerName($this));
		$this->plugin->getDatabaseHandler()->tempStatisticsAdd(Utils::getPlayerName($this));
		$this->plugin->getDatabaseHandler()->matchStatsAdd(Utils::getPlayerName($this));
		$this->plugin->getDatabaseHandler()->warnPointsAdd(Utils::getPlayerName($this));
		Utils::initPlayer($this);

		$this->rank = $this->plugin->getDatabaseHandler()->getRank(Utils::getPlayerName($this));
		$this->clantag = Utils::clanTag($this);

		$ip = $this->getAddress();
		$cid = $this->getClientId();
		if(file_exists($this->plugin->getDataFolder() . "aliases/" . $ip)){
			$file = explode(", ", file_get_contents($this->plugin->getDataFolder() . "aliases/" . $ip, true));
			if(!in_array(Utils::getPlayerName($this), $file)){
				file_put_contents($this->plugin->getDataFolder() . "aliases/" . $ip, Utils::getPlayerName($this) . ", ", FILE_APPEND);
			}
		}else{
			file_put_contents($this->plugin->getDataFolder() . "aliases/" . $ip, Utils::getPlayerName($this) . ", ");
		}
		if(file_exists($this->plugin->getDataFolder() . "aliases/" . $cid)){
			$file = explode(", ", file_get_contents($this->plugin->getDataFolder() . "aliases/" . $cid, true));
			if(!in_array(Utils::getPlayerName($this), $file)){
				file_put_contents($this->plugin->getDataFolder() . "aliases/" . $cid, Utils::getPlayerName($this) . ", ", FILE_APPEND);
			}
		}else{
			file_put_contents($this->plugin->getDataFolder() . "aliases/" . $cid, Utils::getPlayerName($this) . ", ");
		}
	}

	public function initializeJoin(){
		if(!$this->hasPlayedBefore()){
			$this->sendMessage("§eBe sure to check out our discord at " . $this->plugin->getDiscord() . " to stay updated with the network!");
		}
		$this->setDisplayName(Utils::getPlayerName($this));
		$this->plugin->getPermissionHandler()->addPermission($this, $this->getRank());
		$this->sendTo(0, true);
		$this->plugin->getClickHandler()->addToArray($this);
		Utils::spawnStaticTextsToPlayer($this);
		Utils::spawnUpdatingTextsToPlayer($this);
		Utils::teleportSound($this);
	}

	public function sendTo(int $loc, bool $kit = false, bool $title = false, $leader = true){
		switch($loc){
			case 0:
				$x = -0.5;
				$y = 103;
				$z = 0.5;
				$world = $this->plugin->getServer()->getLevelByName($this->plugin->getLobby());
				$this->teleport(new Location($x, $y, $z, 180, 0, $world));
				$this->setPlayerLocation($loc);
				if($kit === true){
					Kits::sendKit($this, "lobby");
				}
				$this->plugin->getScoreboardHandler()->sendMainScoreboard($this);
				break;
			case 1:
				$x = 100.5;
				$y = 130;
				$z = 100.5;
				$world = $this->plugin->getServer()->getLevelByName("nodebuff");
				$this->teleport(new Location($x, $y, $z, 90, 0, $world));
				$this->setPlayerLocation($loc);
				if($kit === true){
					Kits::sendKit($this, "nodebuff");
				}
				if($title === true){
					$this->sendMessage("You selected §bNoDebuff");
				}
				$this->plugin->getScoreboardHandler()->sendFFAScoreboard($this);
				break;
			case 2:
				$x = 100.5;
				$y = 130;
				$z = 100.5;
				$world = $this->plugin->getServer()->getLevelByName("gapple");
				$this->teleport(new Location($x, $y, $z, 90, 0, $world));
				$this->setPlayerLocation($loc);
				if($kit === true){
					Kits::sendKit($this, "gapple");
				}
				if($title === true){
					$this->sendMessage("You selected §bGapple");
				}
				$this->plugin->getScoreboardHandler()->sendFFAScoreboard($this);
				break;
			case 3:
				$x = 100.5;
				$y = 130;
				$z = 100.5;
				$world = $this->plugin->getServer()->getLevelByName("opgapple");
				$this->teleport(new Location($x, $y, $z, 90, 0, $world));
				$this->setPlayerLocation($loc);
				if($kit === true){
					Kits::sendKit($this, "opgapple");
				}
				if($title === true){
					$this->sendMessage("You selected §bOP Gapple");
				}
				$this->plugin->getScoreboardHandler()->sendFFAScoreboard($this);
				break;
			case 4:
				$x = mt_rand(85, 115);
				$y = 50;
				$z = mt_rand(85, 115);
				$yaw = mt_rand(0, 180);
				$world = $this->plugin->getServer()->getLevelByName("combo");
				$this->teleport(new Location($x, $y, $z, $yaw, 0, $world));
				$this->setPlayerLocation($loc);
				if($kit === true){
					Kits::sendKit($this, "combo");
				}
				if($title === true){
					$this->sendMessage("You selected §bCombo");
				}
				$this->plugin->getScoreboardHandler()->sendFFAScoreboard($this);
				break;
			case 5:
				$x = mt_rand(95, 105);
				$y = 50;
				$z = mt_rand(95, 105);
				$yaw = mt_rand(0, 180);
				$world = $this->plugin->getServer()->getLevelByName("fist");
				$this->teleport(new Location($x, $y, $z, $yaw, 0, $world));
				$this->setPlayerLocation($loc);
				if($kit === true){
					Kits::sendKit($this, "fist");
				}
				if($title === true){
					$this->sendMessage("You selected §bFist");
				}
				$this->plugin->getScoreboardHandler()->sendFFAScoreboard($this);
				break;
			case 7:
				$x = 100.5;
				$y = 130;
				$z = 100.5;
				$world = $this->plugin->getServer()->getLevelByName("nodebuff-low");
				$this->teleport(new Location($x, $y, $z, 90, 0, $world));
				$this->setPlayerLocation($loc);
				if($kit === true){
					Kits::sendKit($this, "nodebuff");
				}
				if($title === true){
					$this->sendMessage("You selected §bNoDebuff (Low KB)");
				}
				$this->plugin->getScoreboardHandler()->sendFFAScoreboard($this);
				break;
			case 8:
				$x = 100.5;
				$y = 130;
				$z = 100.5;
				$world = $this->plugin->getServer()->getLevelByName("nodebuff-java");
				$this->teleport(new Location($x, $y, $z, 90, 0, $world));
				$this->setPlayerLocation($loc);
				if($kit === true){
					Kits::sendKit($this, "nodebuffjava");
				}
				if($title === true){
					$this->sendMessage("You selected §bNoDebuff (Java)");
				}
				$this->plugin->getScoreboardHandler()->sendFFAScoreboard($this);
				break;
			default:
				return;
				break;
		}
	}

	public function setPlayerLocation(int $loc){
		$this->location = $loc;
	}

	public function initializeQuit(){
		$this->plugin->getClickHandler()->removeFromArray($this);
		$this->plugin->getDuelHandler()->removePlayerFromQueue($this);
		$duel = $this->plugin->getDuelHandler()->getDuelFromSpec($this);
		$party = $this->getParty();
		if(!is_null($duel)) $duel->removeSpectator($this);
		foreach(PartyManager::getInvites($this) as $invite){
			$invite->clear();
		}
		if(!is_null($party)){
			if($party->isLeader($this)){
				if($this->plugin->getDuelHandler()->isInPartyDuel($this)){
					$pduel = $this->plugin->getDuelHandler()->getPartyDuel($this);
					$pduel->endDuelPrematurely();
				}
				$party->disband();
			}else{
				if($this->plugin->getDuelHandler()->isInPartyDuel($this)){
					$pduel = $this->plugin->getDuelHandler()->getPartyDuel($this);
					if($pduel->isAlive($this)) $pduel->setAlive($pduel->getAlive() - 1, $this);
				}
				$party->removeMember($this);
			}
		}
	}

	public function getParty() : ?Party{
		$result = null;
		if(!$this->isInParty()){
			$result = null;
		}else{
			$result = $this->party;
		}
		return $result;
	}

	public function setParty($party){
		$this->party = $party;
	}

	public function isInParty() : bool{
		return PartyManager::getPartyFromPlayer($this) !== null;
	}
}
