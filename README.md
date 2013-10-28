Curling
=======

Library for smart HTTP requests. It wrapps php_curl.

Simple usage
-------
    $request = new Curling\Request('http://onet.pl/');

    var_dump($request->execute());