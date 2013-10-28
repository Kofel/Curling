<?php

require_once __DIR__ . '/../vendor/autoload.php';

$requests = array();
$parallel = new Curling\Parallel;

for($i=0;$i<10;$i++)
{
    $parallel->push(new Curling\Request('http://www.reddit.com'));
}

var_dump($parallel->execute());