<?php
namespace VaKKuum\AshokaBot;

use VaKKuum\AshokaBot\VkAPI;
use VaKKuum\AshokaBot\utils\Module;
use VaKKuum\AshokaBot\utils\User;
use VaKKuum\AshokaBot\utils\Config;
use VaKKuum\AshokaBot\utils\Logger;
use VaKKuum\AshokaBot\utils\Command;

class Handler{
	public $api;
	public $operated = 0;
	public $startTime;
	private $config;
	
	private $modules = [];
	private $handled = [];
	
	private $configs_path;
	private $conf_path;
	
	public function __construct(){
		$this->startTime = time();
		
		Logger::info("Запуск бота...");
		$this->configs_path = self::getPath()."configs/";
		if(!is_dir($this->configs_path)){
			@mkdir($this->configs_path);
		}
		
		$this->conf_path = $this->configs_path."config.yml";
		$this->config = new Config($this->conf_path, Config::YAML, $this->getDefaultConfig());
		$this->config->save();
		
		$this->api = new VkAPI($this->config->get("access_key"), $this->config->get("api_version"));
		
		$this->modules = $this->initModules();
		foreach($this->modules as $module){
			Logger::info("Включение ".$module->getName()." v".$module->getVersion(). ($module->isSource() == true ? ' (source)' : ''));
			$module->onEnable();
		}
		
		Logger::info("Бот загружен!");
	}
	
	public static function getPath(): string{
		return realpath("./")."/";
	}
	
	public function getConfig(): ?array{
		if($this->config instanceof Config){
			return $this->config->getAll();
		}
		return null;
	}
	
	public function getDefaultConfig(): array{
		$data = [
			"access_key" => "key",
			"api_version" => "5.101",
			"not_permission_message" => "Совет не допускает использование этой команды не форсюзерам.",
		];
		return $data;
	}
	
	private function initModules(): array{
		Logger::info("Инициализация модулей...");
		$modules = [];
		$path = self::getPath()."modules/";
		$data = self::getPath()."modules_data/";
		if(!is_dir($path)){
			@mkdir($path);
		}
		if(!is_dir($data)){
			@mkdir($data);
		}
		
		$files = @scandir($path);
		foreach($files as $f){
			$file = $path.$f;
			$source = false;
			
			if(is_dir($file) and ($f != ".") and ($f != "..")){
				$config = $file."/module.yml";
				$src = $file."/src/";
				$source = true;
			}else{
				if(is_file($file)){
					$phar = @basename($f);
					if(preg_match('/\.(phar)/', $phar)){
						$config = "phar://".$file."/module.yml";
						$src = "phar://".$file."/src/";
					}
				}
			}
			
			if(isset($config) and isset($src)){
				if(is_file($config) and is_dir($src)){
					$c = Module::loadConfig($config);
					if(isset($c["main"]) and isset($c["name"]) and isset($c["version"])){
						foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($src)) as $sub_file){
							$s = @basename($sub_file);
							if(preg_match('/\.(php)/', $s)){
								include_once $sub_file;
							}
						}
						
						$main = str_replace("/", "\\", $c["main"]);
						$module = new $main();
						if($module instanceof Module){
							$module->init($this, $file, $source);
							$modules[] = $module;
							Logger::info("Загружен модуль ".$module->getName());
							
							$m_dir = $data.$module->getName();
							if(!is_dir($m_dir)){
								@mkdir($m_dir);
							}
						}else{
							Logger::info("Не найден главный класс для ".$config->get("name")."!");
						}
					}else{
						Logger::info("Повреждение конфига модуля ".$file);
					}
				}
			}
		}
		
		return $modules;
	}
	
	public function getModules(): array{
		return $this->modules;
	}
	
	public function getModule(string $name): ?Module{
		foreach($this->modules as $module){
			if($module->getName() == $name){
				return $module;
			}
		}
		return null;
	}
	
	public function getBotStats(): Config{
		$path = self::getPath()."configs/stats.yml";
		$base = ["operated" => 0, "commands" => []];
		$stats = new Config($path, Config::YAML, $base);
		$stats->save();
		return $stats;
	}
	
	public function getUsers(): array{
		$users = [];
		$files = @scandir(self::getPath()."configs/users/");
		foreach ($files as $file){
			if(preg_match('/\.(yml)/', $file)){
				$users[] = new User(substr($file, 0, -4));
			}
		}
		
		return $users;
	}
	
	public function getGroups(): array{
		$groups = [
			"Не чувствительный",
			"Падаван",
			"Рыцарь-джедай",
			"Гранд-мастер",
		];
		return $groups;
	}
	
	public function getGroupName(int $id): string{
		$groups = $this->getGroups();
		if(isset($groups[$id])){
			return $groups[$id];
		}
		return "Не определено";
	}
	
	public function getDirContents(string $path): array{
	    $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
	    $files = [];
  	  foreach ($rii as $file)
	        if(!$file->isDir())
  	          $files[] = $file->getPathname();
 	   return $files;
	}
	
	public function tick(){
		foreach($this->getModules() as $module){
			$manager = $module->getCallbackManager();
			foreach($manager->getCallbacks() as $callbackHandler){
				$callbackHandler->handle();
			}
		}
	}
}
?>