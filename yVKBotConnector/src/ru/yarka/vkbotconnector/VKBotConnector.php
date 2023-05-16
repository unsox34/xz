<?php

namespace ru\yarka\vkbotconnector;

use pocketmine\plugin\PluginBase;
use ru\yarka\vkbotconnector\handler\ChannelInboundHandler;
use ru\yarka\vkbotconnector\socket\Receiver;

class VKBotConnector extends PluginBase {

	/**
	 * @var Receiver
	 */
	private $receiver;

	/**
	 * @var VKBotConnector
	 */
	private static $instance;

	/**
	 * @var ChannelInboundHandler[]
	 */
	private $handlers = [];

	public static $BOT_TOKEN;

	public function onEnable() {
		self::$instance = $this;

		$cfg = $this->getConfig();
		self::$BOT_TOKEN = $cfg->get('bot_token');

		$this->getLogger()->info('Opening socket...');
		$this->getLogger()->info('Bind to '.gethostbyname('localhost').':'.$cfg->get('listen_port'));
		$this->receiver = new Receiver($cfg->get('max_packet_len'), $cfg->get('listen_port'), self::$BOT_TOKEN);
        $this->getServer()->getScheduler()->scheduleAsyncTask($this->receiver);
	}

	public function onDisable() {
		$this->receiver->worker->kill();
	}

	public function registerHandler(ChannelInboundHandler $handler) {
		$this->handlers[] = $handler;
	}

	/**
	 * @return ChannelInboundHandler[]
	 */
	public function getHandlers(): array {
		return $this->handlers;
	}

	public static function getInstance(): VKBotConnector {
		return self::$instance;
	}
}