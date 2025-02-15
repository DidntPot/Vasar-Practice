<?php

declare(strict_types=1);

namespace Wqrro;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;

class Kits{

	public $plugin;

	public function __construct(Core $plugin){
		$this->plugin = $plugin;
	}

	public static function sendKit(Player $player, $kit){
		switch($kit){
			case "lobby":
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$duel = Item::get(Item::STONE_SWORD, 0, 1);
				$duel->setCustomName("§r§bDuel");
				$duel->setLore(["§r§9Play duels!"]);
				$duel->setNamedTagEntry(new ListTag("ench"));
				$unranked = Item::get(Item::STONE_SWORD, 0, 1);
				$unranked->setCustomName("§r§bUnranked");
				$unranked->setLore(["§r§9Play unranked duels!"]);
				$ranked = Item::get(Item::IRON_SWORD, 0, 1);
				$ranked->setCustomName("§r§bRanked");
				$ranked->setLore(["§r§9Play ranked duels!"]);
				$spectate = Item::get(Item::GOLDEN_AXE, 0, 1);
				$spectate->setCustomName("§r§bSpectate");
				$spectate->setLore(["§r§9Spectate duels!"]);
				$botduels = Item::get(Item::IRON_SWORD, 0, 1);
				$botduels->setCustomName("§r§bBot Duel");
				$botduels->setLore(["§r§9Play bot duels!"]);
				$botduels->setNamedTagEntry(new ListTag("ench"));
				$ffa = Item::get(Item::DIAMOND_SWORD, 0, 1);
				$ffa->setCustomName("§r§bFFA");
				$ffa->setLore(["§r§9Warp to FFA!"]);
				$ffa->setNamedTagEntry(new ListTag("ench"));
				$market = Item::get(421, 0, 1);
				$market->setCustomName("§r§bMarket");
				$market->setLore(["§r§9View the market!"]);
				$party = Item::get(421, 0, 1);
				$party->setCustomName("§r§bParty");
				$party->setLore(["§r§9Play with your friends!"]);
				$party->setNamedTagEntry(new ListTag("ench"));
				$daily = Item::get(399, 0, 1);
				$daily->setCustomName("§r§bDaily Rankings");
				$daily->setLore(["§r§9View daily rankings!"]);
				$daily->setNamedTagEntry(new ListTag("ench"));
				$cosmetics = Item::get(399, 0, 1);
				$cosmetics->setCustomName("§r§bCosmetics");
				$cosmetics->setLore(["§r§9Customize your gameplay!"]);
				$cosmetics->setNamedTagEntry(new ListTag("ench"));
				$profile = Item::get(54, 0, 1);
				$profile->setCustomName("§r§bPlayer Portal");
				$profile->setLore(["§r§9View your profile!"]);
				$profile->setNamedTagEntry(new ListTag("ench"));
				$events = Item::get(340, 0, 1);
				$events->setCustomName("§r§bEvents");
				$events->setLore(["§r§9Enter an event!"]);
				//$events->setNamedTagEntry(new ListTag("ench"));
				$player->getInventory()->setItem(0, $duel);
				$player->getInventory()->setItem(1, $botduels);
				$player->getInventory()->setItem(2, $ffa);
				$player->getInventory()->setItem(4, $party);
				$player->getInventory()->setItem(6, $daily);
				$player->getInventory()->setItem(7, $cosmetics);
				$player->getInventory()->setItem(8, $profile);
				//$player->getInventory()->setItem(8, $events);
				$player->getInventory()->setHeldItemIndex(0);
				break;
			case "nodebuff":
			case "NoDebuff":
				$mode = "§r§bNoDebuff";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(310, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(311, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(312, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(313, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setBoots($boots);
				$sword = Item::get(276, 0, 1);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->setItem(1, Item::get(368, 0, 16));
				/*$player->getInventory()->setItem(35, Item::get(373, 14, 1));//373:15 is speed ONE ---- 373:16 is speed TWO
				$player->getInventory()->setItem(26, Item::get(373, 14, 1));
				$player->getInventory()->setItem(17, Item::get(373, 14, 1));*/
				$player->getInventory()->addItem(Item::get(438, 22, 36));
				$player->addEffect(new EffectInstance(Effect::getEffect(1), 45 * 1200, 0, true));//speed 1 for 45 mins
				break;
			case "nodebuffjava":
			case "NoDebuffJava":
				$mode = "§r§bNoDebuff";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(310, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(311, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(312, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(313, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setBoots($boots);
				$sword = Item::get(276, 0, 1);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->setItem(1, Item::get(368, 0, 16));
				/*$player->getInventory()->setItem(35, Item::get(373, 14, 1));//373:15 is speed ONE ---- 373:16 is speed TWO
				$player->getInventory()->setItem(26, Item::get(373, 14, 1));
				$player->getInventory()->setItem(17, Item::get(373, 14, 1));*/
				$player->getInventory()->addItem(Item::get(438, 22, 36));
				$player->addEffect(new EffectInstance(Effect::getEffect(1), 45 * 1200, 1, true));//speed 2 for 45 mins
				break;
			case "gapple":
			case "Gapple":
				$mode = "§r§bGapple";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(310, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(311, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(312, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(313, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setBoots($boots);
				$sword = Item::get(276, 0, 1);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->setItem(1, Item::get(322, 0, 32));
				break;
			case "opgapple":
			case "OP Gapple":
				$mode = "§r§bGapple";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(310, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(311, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(312, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(313, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setBoots($boots);
				$sword = Item::get(276, 0, 1);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->setItem(1, Item::get(466, 0, 32));
				$player->addEffect(new EffectInstance(Effect::getEffect(1), 999999, 0, true));
				break;
			case "combo":
			case "Combo":
				$mode = "§r§bCombo";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(310, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 20));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(311, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 20));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(312, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 20));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(313, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 20));
				$player->getArmorInventory()->setBoots($boots);
				$sword = Item::get(276, 0, 1);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 20));
				$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->setItem(1, Item::get(466, 0, 64));
				$player->addEffect(new EffectInstance(Effect::getEffect(1), 999999, 0, true));
				break;
			case "fist":
			case "Fist":
				$mode = "§r§bFist";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				break;
			case "staff":
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(3);
				$player->setAllowFlight(true);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(9);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$teleporter = Item::get(345, 0, 1);
				$teleporter->setCustomName("§r§bTeleport");
				$teleporter->setLore(["§r§9Teleport to a world!"]);
				$staffportal = Item::get(339, 0, 1);
				$staffportal->setCustomName("§r§bStaff Portal");
				$staffportal->setLore(["§r§9Staff portal!"]);
				$leave = Item::get(355, 0, 1);
				$leave->setCustomName("§r§bLeave Staff Mode");
				$leave->setLore(["§r§9Leave staff mode!"]);
				$player->getInventory()->setItem(0, $staffportal);
				$player->getInventory()->setItem(1, $teleporter);
				//$player->getInventory()->setItem(8, $leave);
				break;
			case "spectator":
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(3);
				$player->setAllowFlight(true);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(9);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$leave = Item::get(355, 0, 1);
				$leave->setCustomName("§r§bLeave Duel");
				$leave->setLore(["§r§9Leave the duel!"]);
				$player->getInventory()->setItem(4, $leave);
				$player->getInventory()->setHeldItemIndex(4);
				break;
			default:
				return;
				break;
		}
	}

	public static function sendMatchKit($player, $kit){
		switch($kit){
			case "nodebuff":
			case "NoDebuff":
				$mode = "§r§bNoDebuff";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				if($player instanceof Player){
					$player->setGamemode(2);
					$player->setAllowFlight(false);
					$player->setFlying(false);
					$player->setXpLevel(0);
					$player->setXpProgress(0.0);
				}
				$player->setImmobile(false);
				$player->removeAllEffects();
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(310, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(311, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(312, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(313, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setBoots($boots);
				$sword = Item::get(276, 0, 1);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				/*$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->setItem(1, Item::get(368, 0, 16));
				$player->getInventory()->setItem(35, Item::get(373, 14, 1));//373:15 is speed ONE ---- 373:16 is speed TWO
				$player->getInventory()->setItem(26, Item::get(373, 14, 1));
				$player->getInventory()->setItem(17, Item::get(373, 14, 1));
				$player->getInventory()->addItem(Item::get(438, 22, 36));*/
				$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->setItem(1, Item::get(368, 0, 16));
				$player->getInventory()->addItem(Item::get(438, 22, 36));
				$player->addEffect(new EffectInstance(Effect::getEffect(1), 45 * 1200, 0, true));//speed 1 for 45 mins
				break;
			case "gapple":
			case "Gapple":
				$mode = "§r§bGapple";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(310, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(311, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(312, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(313, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setBoots($boots);
				$sword = Item::get(276, 0, 1);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->setItem(1, Item::get(322, 0, 16));
				break;
			case "combo":
			case "Combo":
				$mode = "§r§bCombo";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(306, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 15));
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(307, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 15));
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(308, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 15));
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(309, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 15));
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 1));
				$player->getArmorInventory()->setBoots($boots);
				$player->addEffect(new EffectInstance(Effect::getEffect(10), 999999, 2, true));
				$player->addEffect(new EffectInstance(Effect::getEffect(1), 999999, 0, true));
				break;
			case "sumo":
			case "Sumo":
				$mode = "§r§bSumo";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$player->addEffect(new EffectInstance(Effect::getEffect(10), 999999, 4, true));
				break;
			case "line":
			case "Line":
				$mode = "§r§bLine";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$player->getInventory()->setItem(0, Item::get(369, 0, 1));
				$player->addEffect(new EffectInstance(Effect::getEffect(10), 999999, 4, true));
				break;
			case "soup":
			case "Soup":
				$mode = "§r§bSoup";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(2);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(306, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 3));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(307, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 3));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(308, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 3));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(309, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), 3));
				$player->getArmorInventory()->setBoots($boots);
				$sword = Item::get(267, 0, 1);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->addItem(Item::get(282, 0, 36));
				$player->addEffect(new EffectInstance(Effect::getEffect(1), 45 * 1200, 0, true));//speed 1 for 45 mins
				break;
			case "builduhc":
			case "BuildUHC":
				$mode = "§r§bBuildUHC";
				$name = $player->getName();
				$player->extinguish();
				$player->setScale(1);
				$player->setGamemode(0);
				$player->setImmobile(false);
				$player->setImmobile(false);
				$player->setAllowFlight(false);
				$player->setFlying(false);
				$player->removeAllEffects();
				$player->setXpLevel(0);
				$player->setXpProgress(0.0);
				$player->setFood(20);
				$player->setHealth(20);
				$player->getInventory()->setSize(36);
				$player->getInventory()->clearAll();
				$player->getArmorInventory()->clearAll();
				$helmet = Item::get(310, 0, 1);
				$helmet->setCustomName($mode);
				$helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setHelmet($helmet);
				$chestplate = Item::get(311, 0, 1);
				$chestplate->setCustomName($mode);
				$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setChestplate($chestplate);
				$leggings = Item::get(312, 0, 1);
				$leggings->setCustomName($mode);
				$leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setLeggings($leggings);
				$boots = Item::get(313, 0, 1);
				$boots->setCustomName($mode);
				$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$player->getArmorInventory()->setBoots($boots);
				$sword = Item::get(276, 0, 1);
				$sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), 10));
				$goldenhead = Item::get(322, 0, 3);
				$goldenhead->setCustomName(Core::GOLDEN_HEAD);
				$player->getInventory()->setItem(0, $sword);
				$player->getInventory()->setItem(1, Item::get(346, 0, 1));
				$player->getInventory()->setItem(2, Item::get(261, 0, 1));
				$player->getInventory()->setItem(3, Item::get(322, 0, 6));
				$player->getInventory()->setItem(4, $goldenhead);
				$player->getInventory()->setItem(5, Item::get(278, 0, 1));
				$player->getInventory()->setItem(6, Item::get(279, 0, 1));
				$player->getInventory()->setItem(7, Item::get(5, 0, 64));
				$player->getInventory()->setItem(8, Item::get(4, 0, 64));
				$player->getInventory()->setItem(9, Item::get(262, 0, 64));
				$player->getInventory()->setItem(10, Item::get(325, 8, 1));
				$player->getInventory()->setItem(11, Item::get(325, 8, 1));
				$player->getInventory()->setItem(12, Item::get(325, 10, 1));
				$player->getInventory()->setItem(13, Item::get(325, 10, 1));
				break;
			default:
				return;
				break;
		}
	}

	public static function preComboKit(Player $player){
		$player->setGamemode(2);
		$player->setImmobile(false);
		$player->setAllowFlight(false);
		$player->setFlying(false);
		$player->removeAllEffects();
		$player->setFood(20);
		$player->setHealth(20);
		$player->getInventory()->setSize(36);
		$player->getInventory()->clearAll();
		$player->getArmorInventory()->clearAll();
		$item1 = Item::get(340, 0, 1);
		$item1->setCustomName("§r§bSelect Combo Kit");
		$item1->setLore(["§r§9Claim your kit!"]);
		$item2 = Item::get(399, 0, 1);
		$item2->setCustomName("§r§bLobby");
		$item2->setLore(["§r§9Warp to the lobby!"]);
		$player->getInventory()->setItem(0, $item1);
		$player->getInventory()->setItem(8, $item2);
		//$player->getInventory()->setHeldItemIndex(0);
	}

	public static function preNoDebuffKit(Player $player){
		$player->setGamemode(2);
		$player->setImmobile(false);
		$player->setAllowFlight(false);
		$player->setFlying(false);
		$player->removeAllEffects();
		$player->setFood(20);
		$player->setHealth(20);
		$player->getInventory()->setSize(36);
		$player->getInventory()->clearAll();
		$player->getArmorInventory()->clearAll();
		$item1 = Item::get(340, 0, 1);
		$item1->setCustomName("§r§bSelect NoDebuff Kit");
		$item1->setLore(["§r§9Claim your kit!"]);
		$item2 = Item::get(399, 0, 1);
		$item2->setCustomName("§r§bLobby");
		$item2->setLore(["§r§9Warp to the lobby!"]);
		$player->getInventory()->setItem(0, $item1);
		$player->getInventory()->setItem(8, $item2);
		//$player->getInventory()->setHeldItemIndex(0);
	}

	public static function preGappleKit(Player $player){
		$player->setGamemode(2);
		$player->setImmobile(false);
		$player->setAllowFlight(false);

		$player->setFlying(false);
		$player->removeAllEffects();
		$player->setFood(20);
		$player->setHealth(20);
		$player->getInventory()->setSize(36);
		$player->getInventory()->clearAll();
		$player->getArmorInventory()->clearAll();
		$item1 = Item::get(340, 0, 1);
		$item1->setCustomName("§r§bSelect Gapple Kit");
		$item1->setLore(["§r§9Claim your kit!"]);
		$item2 = Item::get(399, 0, 1);
		$item2->setCustomName("§r§bLobby");
		$item2->setLore(["§r§9Warp to the lobby!"]);
		$player->getInventory()->setItem(0, $item1);
		$player->getInventory()->setItem(8, $item2);
		//$player->getInventory()->setHeldItemIndex(0);
	}
}
