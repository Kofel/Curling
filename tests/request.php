<?php

require_once __DIR__ . '/../vendor/autoload.php';

$request = new Curling\Request('http://reddit.com');

var_dump($request->execute());