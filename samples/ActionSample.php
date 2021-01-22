<?php
namespace App;

use App\Ami;

include 'vendor/autoload.php';


$ami = new AMI();


$command  = [
    "Action" =>"ListCommands",
    "ActionID"=> "1234",
];


$rs = $ami->sendAction($command, ["All"]);

print_r($rs);
