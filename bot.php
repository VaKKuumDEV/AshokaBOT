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

$files = @scandir($utils);
foreach ($files as $file){
	if(preg_match('/\.(php)/', $file)){
		require_once $utils.$file;
	}
}

require_once $main_dir."VkAPI.php";
require_once $main_dir."Handler.php";

use VaKKuum\AshokaBot\Handler;
use VaKKuum\AshokaBot\VkAPI;

$last = 0;
$handler = new Handler();

while(true){
	if((time() - $last) >= 1){
		$last = time();
		$handler->check();
	}
}
?>