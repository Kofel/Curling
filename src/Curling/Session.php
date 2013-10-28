<?php

namespace Curling;

class Session extends Util\Set
{
	public function __construct( $cookieFile = null )
	{
		if ( !$cookieFile )
		{
			$cookieFile = tempnam( sys_get_temp_dir(), 'curling_cookie' );
		}

		$this->setCookieFile( $cookieFile );
	}

	public function request( $url )
	{
		$request = new Request( $url, $this );

		return $request;
	}
}