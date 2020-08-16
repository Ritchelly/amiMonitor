<?php

namespace App;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use ElephantIO\Exception\ServerConnectionFailureException;

class WebSocket {

	public $webSocketConnection = '';

	private $protocol;
	private $host;
	private $port;
	private $enabled;

	public function __construct() {

		$config = new Config();

		$this->protocol = $config->getWebsocketProtocol();
		$this->host     = $config->getWebsocketHost();
		$this->port     = $config->getWebsocketPort();
		$this->enabled  = $config->getWebsocketEnabled();

		try {
				$this->webSocketConnection = new Client(
					new Version2X("$this->protocol://$this->host:$this->port")
				);
			
		}
		catch (ServerConnectionFailureException $e) {
			echo $e;
		}
	}

	public function emit( String $event, Array $msg ){

		if ( ! $this->enabled ) {
			return;
		}

		try {

			$this->webSocketConnection->initialize();
			$this->webSocketConnection->emit( $event, $msg );
			$this->webSocketConnection->close();
			
		}
		catch (ServerConnectionFailureException $e) {
			echo "========================================="."\n";
			echo "Falha ao conectar ao webserver"."\n";
			echo "========================================="."\n";
			//echo $e;
		}
		
	}

}