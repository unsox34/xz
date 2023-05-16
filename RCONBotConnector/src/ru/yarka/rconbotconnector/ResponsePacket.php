<?php

namespace ru\yarka\rconbotconnector;

use pocketmine\utils\BinaryStream;
use ru\yarka\vkbotconnector\packet\MinecraftDefaultPacket;

class ResponsePacket extends MinecraftDefaultPacket {

	private $type;
	private $vk_id;
	private $response;

	public function __construct(int $vk_id, int $type, string $response) {
		$this->type = $type;
		$this->response = $response;
		$this->vk_id = $vk_id;
	}

	public function build() {
		parent::build();

		$stream = new BinaryStream();
		$stream->putByte(0x06);
		$stream->putLong($this->vk_id);
		$stream->putByte($this->type);
		$stream->putShort(strlen($this->response));
		$stream->put($this->response);

		$this->buffer .= $stream->buffer;
	}
}