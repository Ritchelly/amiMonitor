<?php

namespace App;

class Config {

	public $asteriskManager = [];
	public $websocketConfig = [];
	protected $configFileName;

	function __construct(){
		$backLevel = 4;
		$this->configFileName = "config.ini";

		if ( ! file_exists( dirname( __DIR__, $backLevel ) ."/". $this->configFileName ) ) {
			$backLevel = 1;
		} 
		
		$config = parse_ini_file( dirname( __DIR__, $backLevel ) ."/". $this->configFileName, true);
		
		$this->asteriskManager = $config['AsteriskManager'];
		$this->websocketConfig = $config['WebSocket'];
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