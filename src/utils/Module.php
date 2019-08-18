<?php
namespace VaKKuum\AshokaBot\utils;

use VaKKuum\AshokaBot\Handler;
use VaKKuum\AshokaBot\utils\Config;
use VaKKuum\AshokaBot\callback\CallbackManager;

abstract class Module{
	private $name;
	private $version;
	private $handler;
	private $source = false;
	private $data_dir;
	private $path;
	private $callbacksManager;
	
	abstract public function onEnable();
	
	final public function getName(): string{
		return $this->name;
	}
	
	final public function getVersion(): string{
		return $this->version;
	}
	
	final public function info(string $message){
		Logger::info("[".$this->name."] ".$message);
	}
	
	final public function init(Handler $handler, string $path, bool $source){
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
		
		$this->callbacksManager = new CallbackManager();
	}
	
	final public function getHandler(): ?Handler{
		return $this->handler;
	}
	
	final public function getPath(): string{
		return $this->path;
	}
	
	final public function getDataFolder(): string{
		return $this->data_dir;
	}
	
	final public static function loadConfig(string $config): array{
		$data = new Config($config, Config::YAML);
		return $data->getAll();
	}
	
	final public function isSource(): bool{
		return $this->source;
	}
	
	final public function getCallbackManager(): CallbackManager{
		return $this->callbacksManager;
	}
}
?>