<?php

declare(strict_types=1);

namespace Tatsomi\TimeUI;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->getLogger()->info(TextFormat::GREEN . "TimeUI enabled!");

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() === "timeui") {
            if ($sender instanceof Player) {
                $this->openTimeSetUI($sender);
                return true;
            } else {
                $sender->sendMessage("This command can only be used in-game.");
            }
        }
        return false;
    }

    public function openTimeSetUI(Player $player): void
    {
        $form = new SimpleForm(function (Player $player, $data) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $this->setTime($player, 0);
                    break;
                case 1:
                    $this->setTime($player, 6000);
                    break;
                case 2:
                    $this->setTime($player, 12000);
                    break;
                case 3:
                    $this->setTime($player, 18000);
                    break;
            }
        });

        $form->setTitle("TimeUI");
        $form->setContent("Select the time you want to set:");
        $form->addButton("Day");
        $form->addButton("Noon");
        $form->addButton("Night");
        $form->addButton("Midnight");
        $form->addButton("Close");

        $form->sendToPlayer($player);
    }

    public function setTime(Player $player, int $time): void
    {
        $world = $player->getWorld();
        $world->setTime($time);
        $player->sendMessage("§aThe time has been set to $time.");
    }
}
