<?php

namespace Cb;

use pocketmine\scheduler\Task;
use Cb\Manager;
use pocketmine\utils\Config;

class SBTask extends Task {
	
    public $plugin;
    
	public function __construct(Manager $plugin){
		$this->plugin = $plugin;
	}
	
	public function onRun(int $currentTick) : void {
        $pl = $this->plugin->getServer()->getOnlinePlayers();
        foreach ($pl as $player) {
            $name = $player->getName();
            $ping = $player->getPing();
            $server = "CityBuild";

            //SCOREBOARD\\
            $this->plugin->rmScoreboard($player, "objektName");
            $this->plugin->createScoreboard($player, "§7*§aMelonenGames§7*", "objektName");
            $this->plugin->setScoreboardEntry($player, 1, "", "objektName");
            $this->plugin->setScoreboardEntry($player, 2, "§aName§7:", "objektName");
            $this->plugin->setScoreboardEntry($player, 3, "§7$name", "objektName");
            $this->plugin->setScoreboardEntry($player, 4, " ", "objektName");
            $this->plugin->setScoreboardEntry($player, 5, "§aPing§7:", "objektName");
            $this->plugin->setScoreboardEntry($player, 6, "§7$ping", "objektName");

        }
	}
}