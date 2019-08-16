<?php
namespace VaKKuum\AshokaBot\utils;

class Logger{
	private $log;
	
	public function __construct(){
		//$this->log = new Config(realpath("./")."/logs/".date("d-m").".yml", Config::YAML);
	}
	
	public static function info(string $msg){
		$msg = "[".date("H:i")."] ".$msg."\n";
		//$log = $this->log->getAll();
		//$log[] = $msg;
		//$this->log->setAll($log);
		//$this->log->save();
		echo $msg;
	}
}
?>