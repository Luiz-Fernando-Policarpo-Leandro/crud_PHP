<?php

$command = $argv[1] ?? null;

switch ($command) {

    case 'create':
        require __DIR__.'/db/create.php';
        break;

    case 'migrate':
        require __DIR__.'/db/migrate.php';
        break;

    case 'drop':
        require __DIR__.'/db/drop.php';
        break;

    case 'reset':
        require __DIR__.'/db/drop.php';
        require __DIR__.'/db/create.php';
        require __DIR__.'/db/migrate.php';
        break;

    default:
        echo "Commands:\n";
        echo "\tcreate\n";
        echo "\tmigrate\n";
        echo "\tdrop\n";
}