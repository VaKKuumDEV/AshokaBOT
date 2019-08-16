<?php
namespace VaKKuum\AshokaBot\utils;

use VaKKuum\AshokaBot\Handler;
use VaKKuum\AshokaBot\utils\Config;

abstract class Module{
	private $name;
	private $version;
	private $handler;
	private $source = false;
	private $data_dir;
	private $path;
	
	public function __construct(){
		
	}
	
	abstract public function onEnable();
	
	public function getName(): string{
		return $this->name;
	}
	
	public function getVersion(): string{
		return $this->version;
	}
	
	public function info(string $message){
		Logger::info("[".$this->name."] ".$message);
	}
	
	public function init(Handler $handler, string $path, bool $source){
		$this->handler = $handler;
		$this->path = $path."/";
		$this->source = $source;
		
		$config = $path."/module.yml";
		if(!$source){
			$config = "phar://".$path."/module.yml";
		}
		$data = self::loadConfig($config);
		
		$this->name = $data["name"];
		$this->version = $data["version"];
		$this->data_dir = Handler::getPath()."modules_data/".$this->name."/";
		if(!is_dir($this->data_dir)){
			@mkdir($this->data_dir);
		}
	}
	
	public function getHandler(): ?Handler{
		return $this->handler;
	}
	
	public function getPath(): string{
		return $this->path;
	}
	
	public function getDataFolder(): string{
		return $this->data_dir;
	}
	
	public static function loadConfig(string $config): array{
		$data = new Config($config, Config::YAML);
		return $data->getAll();
	}
	
	public function isSource(): bool{
		return $this->source;
	}
}
?>