<?php

namespace App;

class Utils{

	static function check_daemon_manager() {

		$cmd = "ps ax | grep -i daemon_manager.php | grep -v color | grep -v grep | awk '{print $1}'";
		exec($cmd, $rs);
		return $rs[0];
	}

	static function kill_daemon_manager() {

		$pid = self::check_daemon_manager();
		$cmd = "sudo kill -9 $pid > /dev/null";
		$rs =  shell_exec($cmd);
		$pid_aux = self::check_daemon_manager();
		print_r($rs);
	}

	/**
	 * Only check the Asterisk service, if this script is running in localhost.
	 */
	static function check_asterisk_status() {

		if ( ! self::isLocalInstance() ) {
			return true;
		}

		exec("ps ax | grep -i /usr/sbin/asterisk | grep -v grep | awk '{print $1}'", $rs);
		return @$rs[0];
	}

	static function put_log( $msg)  {

		$datetime =  @date('[d/m/Y H:i:s]');
		file_put_contents('/var/log/amiMonitor',"$datetime - $msg\n", FILE_APPEND);
		echo $msg;
	}

	static function isLocalInstance() {

		$config = new Config();
		$host   = $config->getManagerConfigHost();
		
		if ( $host == '127.0.0.1' || $host == "localhost" )  {
			return true;
		}

		return false;

	}

}

?>