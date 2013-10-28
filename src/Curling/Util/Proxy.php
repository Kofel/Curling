<?php

namespace Curling\Util;

class Proxy
{
	/**
	 * Proxy host address
	 */
	public $host;

	/**
	 * Proxy port
	 */
	public $port;

	/**
	 * Proxy auth username
	 */
	public $username;

	/**
	 * Proxy auth password
	 */
	public $password;

	/**
	 * Constructor
	 */
	public function __construct( $host, $port = 3128, $username = null, $password = null )
	{
		$this->host = $host;
		$this->port = (int) $port; 
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * Checks proxy need to be authorized
	 */
	public function authorized()
	{
		return !is_null( $this->username );
	}

	/**
	 * Pings proxy for response
	 */
	public function ping( $timeout = 5 )
	{
		$socket = @fsockopen( $this->host, $this->port, &$e, &$e, $timeout );

		if ( $socket )
		{
			fclose( $socket );
			return true;
		}

		return false;
	}
}