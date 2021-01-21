<?php

namespace App;

$backLevel = 3;
/*
Checks if this code has in to vendor or not.
If has downloaded by Composer, it is in to vendor folder.
If has downloaded by GitHub, it is in base dir.
*/
if ( file_exists( dirname( __DIR__, $backLevel ). '/autoload.php' ) ) {
	include dirname( __DIR__, $backLevel ). '/autoload.php';
} else {
	include dirname( __DIR__, 1 ). '/vendor/autoload.php';
}

set_time_limit(0);

$webSocket = new WebSocket();
$ami = new Ami();

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

		case "AgentLogin":
			// You code here.
		break;

		case "AgentComplete":
			// You code here.
		break;

		case "DeviceStateChange":
			// You code here.
		break;

		case "AgentLogoff":
			// You code here.
		break;

		case "QueueCallerAbandon":
			// You code here.
		break;

		case "QueueMemberPause":
			// You code here.
		break;

		case "OriginateResponse":
			// You code here.
		break;

		case "DialBegin":
			// You code here.
		break;

		case "Hangup":
			// You code here.
		break;

		case "SoftHangupRequest":
			// You code here.
		break;

		case "BridgeEnter":
			// You code here.
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
//utils::put_log("O asterisk parou de rodar");
?>
