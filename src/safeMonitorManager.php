<?php

namespace App;

include('../vendor/autoload.php');

while( true ){

	if( Utils::check_asterisk_status() ) {
		shell_exec('php daemon_manager.php &');
	}
	else {
		Utils::put_log("O asterisk nao esta rondando");
	}
	sleep(5);
}
