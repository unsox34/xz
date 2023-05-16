<?php

namespace ru\yarka\vkbotconnector\socket;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use ru\yarka\vkbotconnector\packet\MinecraftDefaultPacket;
use ru\yarka\vkbotconnector\VKBotConnector;

class Receiver extends AsyncTask {

	private $max_len;
	private $port;
	private $token;

	public function __construct(int $max_len, int $port, string $token) {
		$this->max_len = $max_len;
		$this->port = $port;
		$this->token = $token;
	}

	public function onRun() {
		$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		socket_set_block($socket);
		socket_bind($socket, gethostbyname('localhost'), $this->port);
		$token_len = strlen($this->token);

		while(true) {
			if(socket_recvfrom($socket, $buffer, $this->max_len, 0, $addr, $port)) {
				if(strlen($buffer) > $token_len + 4 && substr($buffer, 0, 4) === MinecraftDefaultPacket::PACKET_IDENTIFIER && substr($buffer, 4, 32) === $this->token) {
					$this->publishProgress([substr($buffer, $token_len + 4, strlen($buffer) - $token_len + 3), $addr, $port]);
				}
			}
		}
	}

	public function onProgressUpdate(Server $server, $progress): void{
		$data = $progress[0];
		$custom_packet_id = ord($data[0]);

		$handlers = VKBotConnector::getInstance()->getHandlers();
		foreach($handlers as $handler) {
			$handler->channelRead(new DataObject($custom_packet_id, substr($data, 1, strlen($data) - 1), $progress[1], $progress[2]));
		}
	}
}