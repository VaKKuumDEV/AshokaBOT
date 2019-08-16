<?php
namespace VaKKuum\AshokaBot\utils;

use VaKKuum\AshokaBot\Handler;
use VaKKuum\AshokaBot\utils\Config;

class User{
	private $id;
	private $level = 0;
	private $blocked = false;
	private $path;
	private $config;
	
	public function __construct(int $id){
		$this->id = $id;
		
		$this->path = Handler::getPath()."configs/users/";
		if(!is_dir($this->path)){
			@mkdir($this->path);
		}
		
		$this->config = new Config($this->path.$id.".yml", Config::YAML, self::getBase());
		$this->config->save();
		
		$this->level = $this->config->get("level");
		$this->blocked = (bool) $this->config->get("blocked");
	}
	
	public static function getBase(): array{
		$base = [
			"level" => 0,
			"blocked" => false,
		];
		
		return $base;
	}
	
	public function getId(): int{
		return $this->id;
	}
	
	public function getLevel(): int{
		return $this->level;
	}
	
	public function setLevel(int $level){
		$this->level = $level;
	}
	
	public function isBanned(): bool{
		return $this->blocked;
	}
	
	public function setBanned(bool $value = true){
		$this->blocked = $value;
	}
	
	public function save(){
		$this->config->set("level", $this->level);
		$this->config->set("blocked", $this->blocked);
		$this->config->save();
	}
	
	
}

?>