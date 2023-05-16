<?php


namespace ru\yarka\vkbotconnector\packet;


use ru\yarka\vkbotconnector\VKBotConnector;

class MinecraftDefaultPacket {

	public const PACKET_IDENTIFIER = "\xde\xad\xbe\xef";

	protected $buffer = "";

	public function build() {
		$this->buffer = self::PACKET_IDENTIFIER;
		$this->buffer .= VKBotConnector::$BOT_TOKEN;
	}

	public function parse() {
	}

	public function writeByte($byte) {
		$this->buffer .= chr($byte);
	}

	public function getBuffer(): string {
		return $this->buffer;
	}
}