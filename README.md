# amiMonitor
This is a new colaboration of asterisk AMI with PHP. 

I Will improve this code and 
write the way to use later. But if you only read this code, mainly `monitorManager.php` or run `php monitorManager.php`, You can use.

# Configuration

1) Copy the `config.ini.sample` to `config.ini` and put your configs
2) If you want use web socket, run `node webSocketServer.js`, dont forget to put the configuration in `config.ini`
3) Run `php samples/amiMonitor.php` or make your self monitor file for example:

```
php
<?php 

namespace App;

//The path of autoload
include 'vendor/autoload.php';

use App\WebSocket;
use App\Ami;

set_time_limit(0);

$webSocket = new WebSocket();
$ami = new Ami();

//Filter some events or show All;
$event = [
	'All',
	/* 'AgentLogin',
	'AgentLogoff',
	'AgentComplete',
	'DeviceStateChange',
	'QueueCallerAbandon',
	'QueueMemberPause',
	'OriginateResponse',
	'DialBegin',
	'UserEvent',
	'Newexten',
	'Hangup',
	'VarSet',
	'BridgeEnter',
	'AgentConnect',
	'SoftHangupRequest',
	'Registry',
	'QueueMember' */
];

do {
	switch ( @$amiEvent->Event ) {
		//If you has filtering some event, here you can do your logic, or send to websocket,
		case "Hangup":
			// You code here.
			$amiEvent = $ami->getEvent($event);
			$webSocket->emit( "MyCustomAction", [ $amiEvent ] );
			print_r($amiEvent);
		break;

		case "AgentConnect":
			// You code here.
		break;

		default:
			$amiEvent = $ami->getEvent($event);
			$webSocket->emit( "ami", [ $amiEvent ] );
			print_r($amiEvent);
		break;
	}
}
while ( Utils::check_asterisk_status() );

?>

```
