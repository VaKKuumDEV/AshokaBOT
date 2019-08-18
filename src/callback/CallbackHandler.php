<?php
namespace VaKKuum\AshokaBot\callback;

final class CallbackHandler{
	private $repeatingTicks;
	private $delayedTicks;
	private $currentTicks = 0;
	private $callback;
	
	private $cancelled = false;
	
	public function __construct(Callback $callback, int $repeatingTicks, int $delayedTicks){
		$this->callback = $callback;
		$this->repeatingTicks = $repeatingTicks;
		$this->delayedTicks = $delayedTicks;
	}
	
	public function getCallback(): Callback{
		return $this->callback;
	}
	
	public function setCancelled(bool $value = true){
		$this->cancelled = $value;
	}
	
	public function isCancelled(): bool{
		return $this->cancelled;
	}
	
	final public function handle(){
		if(!$this->isCancelled()){
			if($this->delayedTicks > 0){
				$this->delayedTicks--;
			}else{
				if($this->currentTicks >= $this->repeatingTicks){
					$this->currentTicks = 0;
					$this->callback->run();
				}else{
					$this->currentTicks++;
				}
			}
		}
	}
}
?>