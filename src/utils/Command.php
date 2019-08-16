<?php
namespace VaKKuum\AshokaBot\utils;

use VaKKuum\AshokaBot\Handler;

abstract class Command{
	private $name;
	private $level = 0;
	
	public function __construct(string $name, int $level = 0){
		$this->name = $name;
		$this->level = $level;
	}
	
	public function getName(): string{
		return $this->name;
	}
	
	abstract public function getUsage(): string;
	
	abstract public function execCommand(Handler $handler, User $user, string $type, int $local_id, int $from_id, array $args): bool;
	
	public function execute(Handler $handler, User $user, string $type, int $local_id, int $from_id, array $args): bool{
		if((!$user->isBanned()) or ($from_id == $handler->api->getBotId())){
			if(($user->getLevel() >= $this->level) or ($from_id == $handler->api->getBotId())){
				if($this->execCommand($handler, $user, $type, $local_id, $from_id, $args)){
					return true;
				}else{
					Logger::info("Ошибка выполнения команды \"".$this->name."\": ".$type.":".$local_id." - ".$from_id." - ".implode(" ", $args));
				}
			}else{
				$handler->api->sendMessage($type, (int) $local_id, "Совет не допускает использование этой команды не форсюзерам.");
			}
		}
		return false;
	}
	
	
}

?>