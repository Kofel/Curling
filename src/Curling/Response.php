<?php

namespace Curling;

class Response
{
	public $code;
	public $headers;
	public $body;

	public function __construct( $code, $body )
	{
		$this->code = $code;

		$body = explode("\r\n\r\n", $body, 2);

		if ( empty($body) )
		{
			throw new Exception\Response('Response seems to be empty.');
		}

		if ( $body[0] == 'HTTP/1.1 100 Continue' )
		{
			$body = explode("\r\n\r\n", $body[1], 2);
		}

		$this->headers = Headers::parse( $body[0] );

		if ( isset( $body[1] ) )
			$this->body = $body[1];
	}

	public function __toString()
	{
		return $this->body;
	}
}