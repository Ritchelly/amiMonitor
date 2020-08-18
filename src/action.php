<?php
namespace App;

use App\Ami;

include('../vendor/autoload.php');

$ami = new AMI();
/* $command  = [
    "Action" =>"UserEvent",
    "UserEvent" =>"Agent",
    "EventType" =>" NextCall",
]; */

$command  = [
    "Action" =>"QueueStatus",
    "ActionID"=> "sdasda",
    "Queue" => "customer_advocate"
];


$rs = $ami->sendAction($command, ["QueueMember", "QueueParams"]);

print_r($rs);
