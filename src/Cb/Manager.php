<?php

/**
 * Created by PhpStorm.
 * User: bySuartix
 * Date: 6.12.2018
 * Time: 18:48
 */

namespace Cb;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;

class Manager extends PluginBase implements Listener {
	
	public $cb = "§cSystem §7|";
	
	public function onEnable() {
	$this->getLogger()->info("§cLade Farmwelten...");
	$this->getServer()->loadLevel("FarmWelt");
	 @mkdir($this->getDataFolder());
        $config = new Config($this->getDataFolder()."config.yml", Config::YAML);
	}
	
	public function onJoin(PlayerJoinEvent $event) {
		
	$player = $event->getPlayer();
	$name = $player->getName();
	$event->setJoinMessage(TF::RED . $name . " hat den Server betreten!");
	$this->getScheduler()->scheduleRepeatingTask(new SBTask($this), 10);
	}
	
	public function onQuit(PlayerQuitEvent $event) {
		
	$player = $event->getPlayer();
	$name = $player->getName();
	$event->setQuitMessage(TF::RED . $name . " hat den Server verlassen!");
	}
	
	public function setScoreboardEntry(Player $player, int $score, string $msg, string $objName) {
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $objName;
        $entry->type = 3;
        $entry->customName = " $msg   ";
        $entry->score = $score;
        $entry->scoreboardId = $score;
        $pk = new SetScorePacket();
        $pk->type = 0;
        $pk->entries[$score] = $entry;
        $player->sendDataPacket($pk);
    }

    public function rmScoreboardEntry(Player $player, int $score) {
        $pk = new SetScorePacket();
        if(isset($pk->entries[$score])) {
            unset($pk->entries[$score]);
            $player->sendDataPacket($pk);
        }
    }

    public function createScoreboard(Player $player, string $title, string $objName, string $slot = "sidebar", $order = 0) {
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = $slot;
        $pk->objectiveName = $objName;
        $pk->displayName = $title;
        $pk->criteriaName = "dummy";
        $pk->sortOrder = $order;
        $player->sendDataPacket($pk);
    }

    public function rmScoreboard(Player $player, string $objName) {
        $pk = new RemoveObjectivePacket();
        $pk->objectiveName = $objName;
        $player->sendDataPacket($pk);
    }
	
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
		$config = new Config($this->getDataFolder()."config.yml", Config::YAML);
		
		if($command->getName() === "hub") {
    	if($sender instanceof Player) {
      	  $hub = $this->getServer()->getDefaultLevel()->getSafeSpawn();
      	   $sender->teleport($hub);
               $sender->sendMessage($this->cb . TF::GRAY . " Du wurdest zur" . TF::GOLD . " Hub " . TF::GRAY . "teleportiert");
               }
              return true;
           }
    	
	
	if($command->getName() === "fw") {
        		if($sender instanceof Player) {
            $x = $config->getNested("Farmwelt.X");
            $y = $config->getNested("Farmwelt.Y");
            $z = $config->getNested("Farmwelt.Z");
            $yaw = $config->getNested("Farmwelt.Yaw");
            $pitch = $config->getNested("Farmwelt.Pitch");
            $level = $this->getServer()->getLevelByName("FarmWelt");
            $player->teleport(new Vector3($x, $y, $z), $yaw, $pitch, $level);
          			$sender->sendMessage($this->cb . TF::GREEN . " Du wurdest in die Farmwelt teleportiert");
   			}
             return true;
   		}
   
   if($command->getName() === "citybuild") {
        		if($sender instanceof Player) {
            $x = $config->getNested("CityBuild.X");
            $y = $config->getNested("CityBuild.Y");
            $z = $config->getNested("CityBuild.Z");
            $yaw = $config->getNested("CityBuild.Yaw");
            $pitch = $config->getNested("CityBuild.Pitch");
            $level = $this->getServer()->getLevelByName("PlotWelt");
            $player->teleport(new Vector3($x, $y, $z), $yaw, $pitch, $level);
          			$sender->sendMessage($this->cb . TF::GREEN . " Du wurdest in die PlotWelt teleportiert");
   			}
             return true;
   		}
   
       if($command->getName() == "setspawn"){
            if(!empty($args[0])){
                if(strtolower($args[0]) == "help"){
                    if($sender->isOp()){
                    	$sender->sendMessage("§7====================");
                        $sender->sendMessage("§b /setspawn setfarmwelt");
                        $sender->sendMessage("§b /setspawn setcitybuild");
                    }
                    $sender->sendMessage("§7====================");
                } elseif(strtolower($args[0]) == "setfarmwelt" && $sender->isOp()){
                    if($sender instanceof Player){
                        //$config->setNested("Farmwelt.Welt", $sender->getLevel()->getName());
                        $config->setNested("Farmwelt.X", $sender->getX());
                        $config->setNested("Farmwelt.Y", $sender->getY());
                        $config->setNested("Farmwelt.Z", $sender->getZ());
                        $config->setNested("Farmwelt.Yaw", $sender->getYaw());
                        $config->setNested("Farmwelt.Pitch", $sender->getPitch());
                        $config->save();
                        $sender->sendMessage($this->cb."§aDu hast Erfolgreich den Spawn von der Farmwelt gesetzt");
                    }
                    
                    } elseif(strtolower($args[0]) == "setcitybuild" && $sender->isOp()){
                    if($sender instanceof Player){
                        //$config->setNested("CityBuild.Welt", $sender->getLevel()->getName());
                        $config->setNested("CityBuild.X", $sender->getX());
                        $config->setNested("CityBuild.Y", $sender->getY());
                        $config->setNested("CityBuild.Z", $sender->getZ());
                        $config->setNested("CityBuild.Yaw", $sender->getYaw());
                        $config->setNested("CityBuild.Pitch", $sender->getPitch());
                        $config->save();
                        $sender->sendMessage($this->cb."§aDu hast Erfolgreich den Spawn von CityBuild gesetzt");
                   }
                } 
             }
            return true;
          }
       }