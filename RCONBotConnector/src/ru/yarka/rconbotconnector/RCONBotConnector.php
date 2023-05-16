<?php

namespace ru\yarka\rconbotconnector;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use ru\yarka\vkbotconnector\VKBotConnector;

class RCONBotConnector extends PluginBase {

	private static $instance;
	private $assoc;

	public function onEnable() {
		self::$instance = $this;
		VKBotConnector::getInstance()->registerHandler(new PacketHandler());

		$this->assoc = new Config($this->getDataFolder().'assoc_ids.yml', Config::YAML);
	}

	public function getAssocIds(): Config {
		return $this->assoc;
	}

	public static function getInstance(): RCONBotConnector {
		return self::$instance;
	}
}