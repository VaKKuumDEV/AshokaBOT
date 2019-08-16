<?php
namespace VaKKuum\AshokaBot;

use VaKKuum\AshokaBot\utils\Logger;

class VkAPI{
	public $token;
	public $api;
	
	public function __construct(string $token, string $version){
		$this->token = $token;
		$this->api = $version;
		
		if($this->getBotId() == -1){
			Logger::info("Выключение бота...");
			exit();
		}
	}
	
	public function sendMessage(string $type, int $id, string $message): string{
		$random = mt_rand(10000, 90000);
    	$receiver = "";
    	if($type == "user"){ //сообщение на страницу
    		$receiver = "user_id=".$id;
    	}elseif($type == "chat"){ //сообщение в беседу
    		$receiver = "chat_id=".$id;
    	}
    	
		if($receiver != ""){
			$message = urlencode($message);
   	 	$link = "https://api.vk.com/method/messages.send?access_token=".$this->token."&".$receiver."&message=".$message."&v=".$this->api."&random_id=".$random;
			if($curl = curl_init()){
				curl_setopt($curl, CURLOPT_URL, $link);
  		 	 curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
  			  $out = curl_exec($curl);
				curl_close($curl);
				$result = json_decode($out, true);
				return $result["response"];
			}
		}
		
		return "error";
	}
	
	public function getDialogs(): array{
		$link = "https://api.vk.com/method/messages.getConversations?access_token=".$this->token."&v=".$this->api;
		try{
			return $this->execute($link);
		}catch(\Exception $e){
			Logger::info($e->getMessage());
		}
		return [];
	}
	
	public function getMessages(int $id): array{
		$link = "https://api.vk.com/method/messages.getHistory?access_token=".$this->token."&v=".$this->api."&peer_id=".$id;
		try{
			return $this->execute($link);
		}catch(\Exception $e){
			Logger::info($e->getMessage());
		}
		return [];
	}
	
	public function getProfile(int $id): array{
		$link = "https://api.vk.com/method/users.get?access_token=".$this->token."&v=".$this->api."&user_ids=".$id;
		try{
			return $this->execute($link)[0];
		}catch(\Exception $e){
			Logger::info($e->getMessage());
		}
		return [];
	}
	
	public function getBotId(): int{
		$link = "https://api.vk.com/method/users.get?access_token=".$this->token."&v=".$this->api;
		try{
			return $this->execute($link)[0]["id"];
		}catch(\Exception $e){
			Logger::info($e->getMessage());
		}
		return -1;
	}
	
	public function setOnline(): int{
		$link = "https://api.vk.com/method/account.setOnline?access_token=".$this->token."&v=".$this->api;
		try{
			return $this->execute($link);
		}catch(\Exception $e){
			Logger::info($e->getMessage());
		}
		return -1;
	}
	
	private function execute(string $link){
		if($curl = curl_init()){
			curl_setopt($curl, CURLOPT_URL, $link);
  		  curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
  		  $out = curl_exec($curl);
			curl_close($curl);
			$result = json_decode($out, true);
			if(isset($result["error"])){
				throw new \Exception('VK API error: '.json_encode($result["error"], true));
			}
			return $result["response"];
		}
		
		throw new \Exception('cURL не был инициализирован!');
	}
}
?>