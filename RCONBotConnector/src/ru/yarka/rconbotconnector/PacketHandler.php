<?php

namespace ru\yarka\rconbotconnector;

use pocketmine\command\RemoteConsoleCommandSender;
use pocketmine\Server;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\TextFormat;
use ru\yarka\vkbotconnector\handler\ChannelInboundHandler;
use ru\yarka\vkbotconnector\socket\DataObject;

class PacketHandler extends ChannelInboundHandler {

	public function channelRead(DataObject $object) {
		if($object->getCustomPacketId() === 5) {
			$stream = new BinaryStream($object->getBuffer());
			$vk_id = $stream->getLong();
			$msgType = $stream->getByte();

			$cmd = $stream->get($stream->getShort());
			if($msgType === 1) {
				$cmds = explode('|', $cmd);
				$vk_uid = $cmds[0];
				array_shift($cmds);
				$cmd = implode('|', $cmds);
			} else {
				$vk_uid = $vk_id;
			}

			var_dump($vk_uid, RCONBotConnector::getInstance()->getAssocIds()->get('ids'));
			if(!in_array($vk_uid, RCONBotConnector::getInstance()->getAssocIds()->get('ids'))) {
				$this->send($vk_id, $msgType, $object->getIpAddress(), $object->getPort(), 'У вас нет удаленного доступа!');
				return;
			}

			$sender = new RemoteConsoleCommandSender();
			Server::getInstance()->dispatchCommand($sender, $cmd);
			$this->send($vk_id, $msgType, $object->getIpAddress(), $object->getPort(), TextFormat::clean($sender->getMessage()));
		}
	}

	private function send(int $vk_id, int $type, string $address, int $port, string $message) {
		$pk = new ResponsePacket($vk_id, $type, $message);
		$pk->build();
		Server::getInstance()->getNetwork()->sendPacket($address, $port, $pk->getBuffer());
	}
}