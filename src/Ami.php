<?php

namespace App;

class Ami{

	private $ami_value_parent = '';
	private $ami_key_parent   = '';
	private $event_separator  = 'false';

	function __construct()	{
		$config = new Config();

		try {
			$this->socket = fsockopen(
				$config->getManagerConfigHost(),
				$config->getManagerport(),
				$errno, $errstr,
				1
			);
		
		}
		catch (\Exception $ex) {
			echo "Falha ao conectar ao AMI";
			echo $ex;
		}

		if ( $errstr ) {
			echo $errno.PHP_EOL;
			echo $errstr.PHP_EOL;
			exit();
		}

		$username = $config->getManagerUsername();
		$secret   = $config->getManagerSecret();

		fputs($this->socket, "Action: Login\r\n");
		fputs($this->socket, "UserName: $username\r\n");
		fputs($this->socket, "Secret: $secret\r\n\r\n");
		return '1';
	}

	function sendAction( Array $command = [], Array $eventsFilter = [] ) {

		$line                = '';
		$block               = [];
		$event_separator     = false;
		$filter              = [];

		$ami_value_parent = '';
		$block_count      = 0;

		$i=1;
		foreach ($command as $key => $value){
			$end_line = (count($command)==$i?"\r\n\r\n":"\r\n");
			fputs($this->socket,"$key:$value$end_line");
			$i++;
		}

		fputs($this->socket, "Action: Logoff\r\n\r\n");

		while( ! @feof( $this->socket ) ) {

			$read  = @fread($this->socket, 1);
			$line .= $read;

			//Fim de Linha
			if ("\n" == $read) {
			 //  echo $line;

				$complete_line  = explode( ': ', $line ); // Espaço obrigatorio depois dos ":"
				$ami_key_data   = @trim( $complete_line[0] );
				$ami_value_data = @trim( $complete_line[1] );

			 	if ( $event_separator ) {
					$ami_key_parent   = $ami_key_data;
					$ami_value_parent = $ami_value_data;
				} 

				if ( !empty( $eventsFilter ) && ( in_array( $ami_value_parent, $eventsFilter ) || in_array( "All", $eventsFilter ) ) ) {
					if ( "\r\n" != $line ) {
						if ( $ami_key_data == "ChanVariable" || $ami_key_data == 'DestChanVariable' ) {
							$this->processChanVariables( $ami_key_data, $ami_value_data );
						}

						$filter[ $block_count ][ $ami_key_data ] = $ami_value_data;
					}
				}

			 	$event_separator = false;
				//Fim de Bloco
				if ( "\r\n" == $line ) {
					$event_separator = true;
					$block_count++; //Conta Quantidade de blocos já verificados
				}
				else {
					$block[ $block_count ][ $ami_key_data ] = trim( $ami_value_data );
				}
				$line=''; 
			}
		}

		if ( $eventsFilter && is_array( $filter ) ) {
			$reindex_array_values = array_values( $filter ); //reindexa as chaves dos arrays para facilitar utilização e estetica
		}
		else if( is_array( $block ) ) {
			$reindex_array_values = array_values( $block );
		}
		return ( $reindex_array_values ?? '' );
	}

	// Captura todos os Eventos que estao no events_filter Array;
	function getEvent( $events_filter = array() ) {
		$line                = '';
		$filter              = [];
		$block_count         = 0;

		while ( !feof( $this->socket ) ) {
			$read   = fread($this->socket, 1);
			$line  .= $read;

			//End of line
			if ("\n" == $read) {
				$complete_line  = explode(': ',$line);
				$ami_key_data   = @trim($complete_line[0]);
				$ami_value_data = @trim($complete_line[1]);

				if ( $this->event_separator ) {
					$this->ami_key_parent   = $ami_key_data;
					$this->ami_value_parent = $ami_value_data;
				}

				if ( in_array( $this->ami_value_parent, $events_filter ) || in_array( 'All', $events_filter ) ) {
					if ( "\r\n" != $line ) {
						if ( $ami_key_data == "ChanVariable" || $ami_key_data == 'DestChanVariable' ) {
							$this->processChanVariables( $ami_key_data, $ami_value_data );
						}

						$filter[ $block_count ][ $ami_key_data ] = $ami_value_data;
					}
				}

				$this->event_separator = false;

				//End of block
				if ( "\r\n" == $line ) {
					$this->event_separator = true;
					$block_count++; //Conta Quantidade de blocos já verificado;

					if ( in_array( $this->ami_value_parent, $events_filter ) || in_array( 'All', $events_filter ) ) {
						return array_values( $filter )[0];
					}
				}
				$line='';
			}
		}
	}

	function astdb( $type, $family, $key, $value = null ) {

		$command = [
			"Action" => "DB$type",
			"Family" => $family,
			"Key" => $key
		];
		( $value ? $command["Val"] = $value : "" );

		return $this->sendAction( $command );
	}

	/**
	 *Processa as chaves ChanVariables para nao serem sobrescritas;
	 *
	 * Prefixos "_" para Variaveis de canal e "__" Para variaveis de desitino de canal
	 * 
	 * */ 
	private function processChanVariables( &$ami_key_data, &$ami_value_data ) {

		$chan_variable_prefix = '_';

		if ( $ami_key_data == 'DestChanVariable' ) {
			$chan_variable_prefix = '__';
		}

		$chan_variable_data = explode( '=', $ami_value_data );
		$ami_key_data       = $chan_variable_prefix.$chan_variable_data[0];
		$ami_value_data     = ( $chan_variable_data[1] ?? '' ); 
	}
}