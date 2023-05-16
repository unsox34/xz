<?php


namespace ru\yarka\vkbotconnector\socket;


class DataObject {

	/**
	 * @var string
	 */
	private $buffer;

	/**
	 * @var string
	 */
	private $ip_address;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var int
	 */
	private $custom_packet_id;

	public function __construct(int $custom_packet_id, string $buffer, string $ip_address, int $port) {
		$this->buffer = $buffer;
		$this->ip_address = $ip_address;
		$this->port = $port;
		$this->custom_packet_id = $custom_packet_id;
	}

	public function getBuffer(): string {
		return $this->buffer;
	}

	public function getIpAddress(): string {
		return $this->ip_address;
	}

	public function getPort(): int {
		return $this->port;
	}

	public function getCustomPacketId(): int {
		return $this->custom_packet_id;
	}
}