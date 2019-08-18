<?php
namespace VaKKuum\AshokaBot\callback;

final class CallbackManager{
	private $callbacks = [];
	
	public function addRepeatingCallback(Callback $callback, int $repeatingTicks = 1){
		$handler = new CallbackHandler($callback, $repeatingTicks, 0);
		$this->callbacks[] = $handler;
	}
	
	public function addDelayedCallback(Callback $callback, int $delayedTicks = 1){
		$handler = new CallbackHandler($callback, 0, $delayedTicks);
		$this->callbacks[] = $handler;
	}
	
	public function addRepeatingAndDelayedCallback(Callback $callback, int $repeatingTicks = 1, int $delayedTicks = 1){
		$handler = new CallbackHandler($callback, $repeatingTicks, $delayedTicks);
		$this->callbacks[] = $handler;
	}
	
	public function getCallbacks(): array{
		return $this->callbacks;
	}
}
?>