<?php

include 'autoload.php';

use App\Counter;
use App\Enum\CommandOptions;

try {
    $options = getopt(CommandOptions::getShortOptionsString(), CommandOptions::getLongOptionsArray());

    if (!$action = $options[CommandOptions::Action->getShortName()] ?? $options[CommandOptions::Action->getLongName()] ?? null) {
        throw new Exception('The action option is required');
    }

    $file = $options[CommandOptions::File->getShortName()] ?? $options[CommandOptions::File->getLongName()] ?? 'notexists.csv';

    $countClass = new Counter($action, $file);
    $countClass->count();

} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}