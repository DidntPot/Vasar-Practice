<?php

declare(strict_types=1);

namespace Wqrro;

use pocketmine\entity\Entity;
use pocketmine\item\ItemFactory;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\EventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use SQLite3;
use Wqrro\bots\{EasyBot, HackerBot, HardBot, MediumBot, TestBot};
use Wqrro\commands\{AliasCommand,
	AnnounceCommand,
	AntiCheatCommand,
	CoordsCommand,
	DisguiseCommand,
	ExecCommand,
	FlyCommand,
	ForceKitCommand,
	ForceRankCommand,
	FreezeCommand,
	GamemodeCommand,
	HubCommand,
	KickAllCommand,
	KillCommand,
	KitCommand,
	ManageCommand,
	MessagesCommand,
	MuteChatCommand,
	MuteCommand,
	NickCommand,
	OnlineCommand,
	PartyCommand,
	PermBanCommand,
	PingCommand,
	RankCommand,
	ReplyCommand,
	ResetStatsCommand,
	SetClanTagCommand,
	StaffCommand,
	StopCommand,
	TempBanCommand,
	TpallCommand,
	VanishCommand,
	WhisperCommand,
	WhoCommand};
use Wqrro\entities\{DefaultPotion, FastPotion, Hook, Pearl, PotionEntity};
use Wqrro\handlers\{ArenaHandler, ClickHandler, DatabaseHandler, DuelHandler, PermissionHandler, ScoreboardHandler};
use Wqrro\items\{Rod};
use Wqrro\listeners\{AntiCheatListener, ItemListener, PlayerListener, ServerListener, WorldListener};
use Wqrro\tasks\{BroadcastTask,
	CombatTask,
	DatabaseTask,
	DropsTask,
	DuelTask,
	FlagsTask,
	MutesTask,
	NameTagTask,
	PlayerTask,
	TemporaryBansTask,
	TemporaryRankTask,
	VanishTask,
	VoteAccessTask};

class Core extends PluginBase{

	const PREFIX = "§b[Vasar]";
	const CASTPREFIX = "§8» §7";
	const WEBHOOK = "";
	const IP = "vasar.land";
	const SITE = "www.vasar.land";
	const APPEAL = "www.vasar.land/appeal";
	const CCAPPLY = "www.vasar.land/ccapply";
	const APPLY = "www.vasar.land/apply";
	const RULES = "www.vasar.land/rules";
	const DISCORD = "discord.gg/vasar";
	const TWITTER = "@VasarPractice";
	const STORE = "https://vasar.tebex.io/";
	const VOTE = "www.vasar.land/vote";
	const LOBBY = "world";
	const GOLDEN_HEAD = "§r§eGolden Head";
	const HOST = '';
	const USER = '';
	const PASS = '';
	const DATABASE = '';
	private static $instance;
	private static $databaseHandler;
	private static $permissionHandler;
	private static $scoreboardHandler;
	private static $clickHandler;
	private static $duelHandler;
	private static $arenaHandler;
	private static $staffUtils;
	private static $forms;
	public $globalMute = false;
	public $parties = [];
	public $partyinvites = [];
	public $duelInvites = [];
	public $taggedPlayer = [];
	public $nickedPlayer = [];
	public $queue = [];
	public $lists = [];
	public $updatingFloatingTexts = [];
	public $staticFloatingTexts = [];
	public $allCtrs = ["Unknown", "Mouse", "Touch", "Controller"];
	public $allOs = ["Unknown", "Android", "iOS", "macOS", "FireOS", "GearVR", "HoloLens", "Windows10", "Windows", "EducalVersion", "Dedicated", "PlayStation4", "Switch", "XboxOne"];
	public $controls = [];
	public $os = [];
	public $device = [];

	public static function getInstance() : Core{
		return self::$instance;
	}

	public static function getPermissionHandler() : PermissionHandler{
		return self::$permissionHandler;
	}

	public static function getScoreboardHandler() : ScoreboardHandler{
		return self::$scoreboardHandler;
	}

	public static function getClickHandler() : ClickHandler{
		return self::$clickHandler;
	}

	public static function getDuelHandler() : DuelHandler{
		return self::$duelHandler;
	}

	public static function getArenaHandler() : ArenaHandler{
		return self::$arenaHandler;
	}

	public static function getStaffUtils() : StaffUtils{
		return self::$staffUtils;
	}

	public static function getForms() : Forms{
		return self::$forms;
	}

	public function getPrefix() : string{
		return self::PREFIX;
	}

	public function getCastPrefix() : string{
		return self::CASTPREFIX;
	}

	public function getSite() : string{
		return self::SITE;
	}

	public function getAppeal() : string{
		return self::APPEAL;
	}

	public function getCcApply() : string{
		return self::CCAPPLY;
	}

	public function getApply() : string{
		return self::APPLY;
	}

	public function getRules() : string{
		return self::RULES;
	}

	public function getTwitter() : string{
		return self::TWITTER;
	}

	public function getEventLobby() : string{
		return self::EVENTLOBBY;
	}

	public function getKothArena() : string{
		return self::KOTHARENA;
	}

	public function onEnable() : void{
		self::$instance = $this;

		@mkdir($this->getDataFolder() . "aliases/");
		@mkdir($this->getDataFolder() . "playerdata/");
		$this->updatingFloatingText = new Config($this->getDataFolder() . "updatingfloatingtexts.yml", Config::YAML);
		$this->staticFloatingText = new Config($this->getDataFolder() . "staticfloatingtexts.yml", Config::YAML);

		$this->getServer()->loadLevel("lobby");
		$this->getServer()->loadLevel("nodebuff");
		$this->getServer()->loadLevel("nodebuff-low");
		$this->getServer()->loadLevel("nodebuff-java");
		$this->getServer()->loadLevel("gapple");
		$this->getServer()->loadLevel("opgapple");
		$this->getServer()->loadLevel("combo");
		$this->getServer()->loadLevel("fist");

		foreach($this->getServer()->getLevels() as $levels){
			foreach($levels->getEntities() as $entity){
				$entity->close();
			}
		}

		$this->disableCommands();
		$this->setListeners();
		$this->setHandlers();
		$this->setCommands();
		$this->setTasks();
		$this->setEntities();
		$this->setItems();
		$this->loadUpdatingFloatingTexts();
		$this->loadStaticFloatingTexts();

		$this->main = new SQLite3($this->getDataFolder() . "Vasar.db");
		$this->main->exec("CREATE TABLE IF NOT EXISTS rank (player TEXT PRIMARY KEY, rank TEXT);");
		$this->main->exec("CREATE TABLE IF NOT EXISTS essentialstats (player TEXT PRIMARY KEY, kills INT, deaths INT, kdr REAL, killstreak INT, bestkillstreak INT, coins INT, elo INT);");
		$this->main->exec("CREATE TABLE IF NOT EXISTS matchstats (player TEXT PRIMARY KEY, elo INT, wins INT, losses INT, elogained INT, elolost INT);");
		$this->main->exec("CREATE TABLE IF NOT EXISTS temporary (player TEXT PRIMARY KEY, dailykills INT, dailydeaths INT);");
		$this->main->exec("CREATE TABLE IF NOT EXISTS temporaryranks (player TEXT PRIMARY KEY, temprank TEXT, duration INT, oldrank TEXT);");
		$this->main->exec("CREATE TABLE IF NOT EXISTS voteaccess (player TEXT PRIMARY KEY, bool TEXT, duration INT);");
		$this->main->exec("CREATE TABLE IF NOT EXISTS levels (player TEXT PRIMARY KEY, level INT, neededxp INT, currentxp INT, totalxp INT);");
		$this->staff = new SQLite3($this->getDataFolder() . "VasarStaff.db");
		$this->staff->exec("CREATE TABLE IF NOT EXISTS mutes (player TEXT PRIMARY KEY, reason TEXT, duration INT, staff TEXT, date TEXT);");
		$this->staff->exec("CREATE TABLE IF NOT EXISTS temporarybans (player TEXT PRIMARY KEY, reason TEXT, duration INT, staff TEXT, givenpoints INT, date TEXT);");
		$this->staff->exec("CREATE TABLE IF NOT EXISTS permanentbans (player TEXT PRIMARY KEY, reason TEXT, staff TEXT, date TEXT);");
		$this->staff->exec("CREATE TABLE IF NOT EXISTS warnpoints (player TEXT PRIMARY KEY, points INT);");
		$this->staff->exec("CREATE TABLE IF NOT EXISTS staffstats (player TEXT PRIMARY KEY, timesjoined INT, timesleft INT, pointsgiven INT, mutesissued INT, kicksissued INT, temporarybansissued INT, permanentbansissued INT);");

		$this->getLogger()->info("
		
		 --- Vasar Practice ---
		");

		$this->getServer()->getNetwork()->setName("§bVasar §f- Practice");
	}

	public function disableCommands(){
		$map = $this->getServer()->getCommandMap();
		$map->unregister($map->getCommand("kill"));
		$map->unregister($map->getCommand("me"));
		$map->unregister($map->getCommand("op"));
		$map->unregister($map->getCommand("deop"));
		$map->unregister($map->getCommand("enchant"));
		$map->unregister($map->getCommand("effect"));
		$map->unregister($map->getCommand("defaultgamemode"));
		$map->unregister($map->getCommand("difficulty"));
		$map->unregister($map->getCommand("spawnpoint"));
		$map->unregister($map->getCommand("setworldspawn"));
		$map->unregister($map->getCommand("title"));
		$map->unregister($map->getCommand("seed"));
		$map->unregister($map->getCommand("particle"));
		$map->unregister($map->getCommand("gamemode"));
		$map->unregister($map->getCommand("tell"));
		$map->unregister($map->getCommand("say"));
	}

	public function setListeners(){
		$map = $this->getServer()->getPluginManager();
		$map->registerEvents(new ServerListener($this), $this);
		$map->registerEvents(new AntiCheatListener($this), $this);
		$map->registerEvents(new WorldListener($this), $this);
		$map->registerEvents(new ItemListener($this), $this);
		$map->registerEvents(new PlayerListener($this), $this);
	}

	public function setHandlers(){
		self::$databaseHandler = new DatabaseHandler();
		self::$permissionHandler = new PermissionHandler();
		self::$scoreboardHandler = new ScoreboardHandler();
		self::$clickHandler = new ClickHandler();
		self::$duelHandler = new DuelHandler();
		self::$arenaHandler = new ArenaHandler();
		self::$staffUtils = new StaffUtils();
		self::$forms = new Forms();
	}

	public function setCommands(){
		$map = $this->getServer()->getCommandMap();
		$map->register("kickall", new KickAllCommand($this));
		$map->register("anticheat", new AntiCheatCommand($this));
		$map->register("reset", new ResetStatsCommand($this));
		$map->register("setclantag", new SetClanTagCommand($this));
		$map->register("reply", new ReplyCommand($this));
		//$map->register("duel", new DuelCommand($this));
		$map->register("forcekit", new ForceKitCommand($this));
		$map->register("kit", new KitCommand($this));
		$map->register("nick", new NickCommand($this));
		$map->register("mute", new MuteCommand($this));
		$map->register("tban", new TempBanCommand($this));
		$map->register("pban", new PermBanCommand($this));
		$map->register("kill", new KillCommand($this));
		$map->register("coords", new CoordsCommand($this));
		$map->register("messages", new MessagesCommand($this));
		$map->register("vanish", new VanishCommand($this));
		$map->register("kill", new KillCommand($this));
		$map->register("party", new PartyCommand($this));
		$map->register("fly", new FlyCommand($this));
		$map->register("stop", new StopCommand($this));
		$map->register("exec", new ExecCommand($this));
		$map->register("tpall", new TpallCommand($this));
		$map->register("alias", new AliasCommand($this));
		$map->register("ping", new PingCommand($this));
		$map->register("forcerank", new ForceRankCommand($this));
		$map->register("manage", new ManageCommand($this));
		$map->register("online", new OnlineCommand($this));
		$map->register("disguise", new DisguiseCommand($this));
		$map->register("mutechat", new MuteChatCommand($this));
		$map->register("who", new WhoCommand($this));
		$map->register("staff", new StaffCommand($this));
		$map->register("hub", new HubCommand($this));
		//$map->register("test", new TestCommand($this));
		$map->register("whisper", new WhisperCommand($this));
		$map->register("announce", new AnnounceCommand($this));
		$map->register("freeze", new FreezeCommand($this));
		$map->register("rank", new RankCommand($this));
		$map->register("gm", new GamemodeCommand($this));
	}

	public function setTasks(){
		$map = $this->getScheduler();
		$map->scheduleRepeatingTask(new FlagsTask($this), 20 * 10);
		$map->scheduleRepeatingTask(new PlayerTask($this), 1);
		$map->scheduleRepeatingTask(new VoteAccessTask($this), 1200);
		$map->scheduleRepeatingTask(new TemporaryBansTask($this), 1200);
		$map->scheduleRepeatingTask(new MutesTask($this), 1200);
		$map->scheduleRepeatingTask(new DuelTask($this), 1);
		$map->scheduleRepeatingTask(new CombatTask($this), 20);
		$map->scheduleRepeatingTask(new TemporaryRankTask($this), 1200);
		$map->scheduleRepeatingTask(new DatabaseTask($this), 1200);
		$map->scheduleRepeatingTask(new DropsTask($this), 2400);
		$map->scheduleDelayedRepeatingTask(new BroadcastTask($this), 300, 12000);
		$map->scheduleRepeatingTask(new NameTagTask($this), 5);
		$map->scheduleRepeatingTask(new VanishTask($this), 5);
	}

	public function setEntities(){
		Entity::registerEntity(FastPotion::class, true, ["FastPotion"]);
		Entity::registerEntity(DefaultPotion::class, true, ["DefaultPotion"]);
		Entity::registerEntity(Pearl::class, true, ["CPPearl"]);
		Entity::registerEntity(Hook::class, false, ["FishingHook", "minecraft:fishing_rod"]);
		Entity::registerEntity(TestBot::class, true, ["TestBot"]);
		Entity::registerEntity(EasyBot::class, true);
		Entity::registerEntity(MediumBot::class, true);
		Entity::registerEntity(HardBot::class, true);
		Entity::registerEntity(HackerBot::class, true);

		Entity::registerEntity(PotionEntity::class, true);
	}

	public function setItems(){
		ItemFactory::registerItem(new Rod(), true);
	}

	public function loadUpdatingFloatingTexts(){
		foreach($this->getUpdatingFloatingTexts()->getAll() as $id => $array){
			$this->updatingFloatingTexts[$id] = new FloatingTextParticle(new Vector3($array["x"], $array["y"], $array["z"]), $array["text"], $array["title"]);
		}
	}

	public function getUpdatingFloatingTexts() : Config{
		return $this->updatingFloatingText;
	}

	public function loadStaticFloatingTexts(){
		foreach($this->getStaticFloatingTexts()->getAll() as $id => $array){
			$this->staticFloatingTexts[$id] = new FloatingTextParticle(new Vector3($array["x"], $array["y"], $array["z"]), $array["text"], $array["title"]);
		}
	}

	public function getStaticFloatingTexts() : Config{
		return $this->staticFloatingText;
	}

	public function onDisable(){
		foreach($this->getServer()->getLevels() as $levels){
			foreach($levels->getEntities() as $entity){
				$entity->close();
			}
		}
		$players = $this->getServer()->getLoggedInPlayers();
		if(sizeof($players) === 0) return;
		Utils::transferPlayers($players);
	}

	public function doesFileExist($filename) : bool{
		$result = file_exists($this->getFile() . "resources/" . $filename . ".png");
		return $result !== null;
	}

	public function toGetFile($filename){
		$file = $this->getFile() . "resources/" . $filename . ".png";
		return $file;
	}

	public function getPlayerControls(Player $player) : ?string{
		if(!isset($this->controls[$player->getName()]) or $this->controls[$player->getName()] == null){
			return null;
		}
		return $this->allCtrs[$this->controls[$player->getName()]];
	}

	public function getPlayerOs(Player $player) : ?string{
		if(!isset($this->os[$player->getName()]) or $this->os[$player->getName()] == null){
			return null;
		}
		return $this->allOs[$this->os[$player->getName()]];
	}

	public function getPlayerDevice(Player $player) : ?string{
		if(!isset($this->device[$player->getName()]) or $this->device[$player->getName()] == null){
			return null;
		}
		return $this->device[$player->getName()];
	}

	public function replaceProcess(Player $player, string $string) : string{
		$string = str_replace("{world}", $this->getLobby(), $string);
		$string = str_replace("{ip}", $this->getIp(), $string);
		$string = str_replace("{discord}", $this->getDiscord(), $string);
		$string = str_replace("{shop}", $this->getStore(), $string);
		$string = str_replace("{vote}", $this->getVote(), $string);
		$string = str_replace("{doubleline}", "\n \n", $string);
		$string = str_replace("{line}", "\n", $string);
		$string = str_replace("{player}", $player->getName(), $string);
		$string = str_replace("{kills}", "" . $this->getDatabaseHandler()->getKills($player->getName()), $string);
		$string = str_replace("{deaths}", "" . $this->getDatabaseHandler()->getDeaths($player->getName()), $string);
		$string = str_replace("{kdr}", "" . $this->getDatabaseHandler()->getKdr($player->getName()), $string);
		$string = str_replace("{kills}", "" . $this->getDatabaseHandler()->getKills($player->getName()), $string);
		$string = str_replace("{deaths}", "" . $this->getDatabaseHandler()->getDeaths($player->getName()), $string);
		$string = str_replace("{elo}", "" . $this->getDatabaseHandler()->getElo($player->getName()), $string);
		$string = str_replace("{coins}", "" . $this->getDatabaseHandler()->getCoins($player->getName()), $string);
		$string = str_replace("{streak}", "" . $this->getDatabaseHandler()->getKillstreak($player->getName()), $string);
		$string = str_replace("{player_health}", "" . round($player->getHealth(), 1), $string);
		$string = str_replace("{player_max_health}", $player->getMaxHealth(), $string);
		$string = str_replace("{online_players}", "" . count($this->getServer()->getOnlinePlayers()), $string);
		$string = str_replace("{online_max_players}", $this->getServer()->getMaxPlayers(), $string);
		$string = str_replace("{topkills}", $this->getDatabaseHandler()->topKills($player->getName()), $string);
		$string = str_replace("{topdeaths}", $this->getDatabaseHandler()->topDeaths($player->getName()), $string);
		$string = str_replace("{topkdr}", $this->getDatabaseHandler()->topKdr($player->getName()), $string);
		$string = str_replace("{topelo}", $this->getDatabaseHandler()->topElo($player->getName()), $string);
		$string = str_replace("{toplevels}", $this->getDatabaseHandler()->topLevels($player->getName()), $string);
		$string = str_replace("{topwins}", $this->getDatabaseHandler()->topWins($player->getName()), $string);
		$string = str_replace("{toplosses}", $this->getDatabaseHandler()->topLosses($player->getName()), $string);
		$string = str_replace("{topkillstreaks}", $this->getDatabaseHandler()->topKillstreaks($player->getName()), $string);
		$string = str_replace("{topdailykills}", $this->getDatabaseHandler()->topDailyKills($player->getName()), $string);
		$string = str_replace("{topdailydeaths}", $this->getDatabaseHandler()->topDailyDeaths($player->getName()), $string);
		return $string;
	}

	public function getLobby() : string{
		return self::LOBBY;
	}

	public function getIp() : string{
		return self::IP;
	}

	public function getDiscord() : string{
		return self::DISCORD;
	}

	public function getStore() : string{
		return self::STORE;
	}

	public function getVote() : string{
		return self::VOTE;
	}

	public static function getDatabaseHandler() : DatabaseHandler{
		return self::$databaseHandler;
	}
}
