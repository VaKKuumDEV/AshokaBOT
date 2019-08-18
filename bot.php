<?php
$main_dir = "src/";
$core_file = "bot-core.phar";
if(is_dir($main_dir)){
	echo "[DEV] Бот запускается из открытой структуры...\n";
}else{
	if(is_file($core_file)){
		$main_dir = "phar://".$core_file."/".$main_dir;
		if(!is_dir($main_dir)){
			echo "[Error] Не найден исполняемый файл!\n";
			exit();
		}
	}else{
		echo "[Error] Не найден исполняемый файл!\n";
		exit();
	}
}

$utils = $main_dir."utils/";

if(!is_dir($utils)){
	@mkdir($utils);
}

$files = [];
foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($main_dir)) as $file){
	if(is_file($file)){
		$s = @basename($file);
		if(preg_match('/\.(php)/', $s)){
			$files[] = $file;
		}
	}
}

foreach($files as $file){
	require_once $file;
}

use VaKKuum\AshokaBot\Handler;
use VaKKuum\AshokaBot\VkAPI;

$last = 0;
$handler = new Handler();

while(true){
	if((time() - $last) >= 1){
		$last = time();
		$handler->tick();
	}
}
?>