<?php

namespace App;

class Config {

	public $asteriskManager = [];
	public $websocketConfig = [];

	function __construct(){

		$config = parse_ini_file( dirname( __DIR__, 4 ) ."/config.ini", true);
		$this->asteriskManager =  $config['AsteriskManager'];
		$this->websocketConfig =  $config['WebSocket'];
	}
   
	public function getManagerConfig() {
		return $this->asteriskManager;
	}

	public function getManagerConfigHost() {
		return $this->asteriskManager['host'];
	}

	public function getManagerUsername() {
		return $this->asteriskManager['username'];
	}

	public function getManagerSecret() {
		return $this->asteriskManager['secret'];
	}

	public function getManagerport() {
		return $this->asteriskManager['port'];
	}

	public function getWebsocketHost() {
		return $this->websocketConfig['host'];
	}

	public function getWebsocketPort() {
		return $this->websocketConfig['port'];
	}

	public function getWebsocketProtocol() {
		return $this->websocketConfig['protocol'];
	}

	public function getWebsocketEnabled() {
		return $this->websocketConfig['enabled'];
	}

}