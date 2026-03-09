<?php 
require __DIR__ . './../../config/database.php';
require __DIR__ . './../../app/models/user.php';

$users = [
    ["luiz", "email@gmail.com","2004-12-27"],
    ["fernando", "policarpo@gmail","2002-2-12"]
];



foreach($users as $userparams){
    $user = new User($pdo,$userparams[0],$userparams[1],$userparams[2]);
    $user->create();
    var_dump($user->all(),);
}



?>