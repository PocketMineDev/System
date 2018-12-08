<?php

namespace Cb;

use pocketmine\scheduler\Task;
use Cb\System;
use pocketmine\utils\Config;

class SBTask extends Task {
	
    public $plugin;
    
	public function __construct(System $plugin){
		$this->plugin = $plugin;
	}
	
	public function onRun(int $currentTick) : void {
        $pl = $this->plugin->getServer()->getOnlinePlayers();
        foreach ($pl as $player) {
        	$pp = $this->plugin->getServer()->getPluginManager()->getPlugin("PurePerms");
            $group = $pp->getUserDataMgr()->getGroup($player, null);
            $name = $player->getName();
            $ping = $player->getPing();
            $server = "CityBuild";

            //SCOREBOARD\\
            $this->plugin->rmScoreboard($player, "objektName");
            $this->plugin->createScoreboard($player, "§7*§aMelonenGames§7*", "objektName");
            $this->plugin->setScoreboardEntry($player, 1, "", "objektName");
            $this->plugin->setScoreboardEntry($player, 2, "§aUserName§7:", "objektName");
            $this->plugin->setScoreboardEntry($player, 3, "§7$name", "objektName");
            $this->plugin->setScoreboardEntry($player, 4, " ", "objektName");
            $this->plugin->setScoreboardEntry($player, 5, "§aKontostand§7:", "objektName");
            $this->plugin->setScoreboardEntry($player, 6, $this->plugin->getServer()->getPluginManager()->getPlugin('EconomyAPI')->myMoney($player), "objektName");
            $this->plugin->setScoreboardEntry($player, 7, "  ", "objektName");
            $this->plugin->setScoreboardEntry($player, 8, "§aRang", "objektName");
            $this->plugin->setScoreboardEntry($player, 9, $group, "objektName");

        }
	}
}