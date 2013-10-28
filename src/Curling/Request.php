<?php

namespace Curling;

class Request extends Util\Set
{
	/**
	 * Curl handle
	 * @var handle
	 */
	protected $handle;

	/**
	 * Session instance
	 * @var \Curling\Session
	 */
	protected $session;

	/**
	 * Request url
	 * @var string
	 */
	public $url;

	/**
	 * Post data array
	 * @var array
	 */
	protected $post;

	/**
	 * Request max timeout
	 * @var integer
	 */
	protected $timeout;

	/**
	 * Do request follow location
	 * @var boolean
	 */
	protected $followLocation = true;

	/**
	 * Last response object
	 * @var Response
	 */
	public $response;

	/**
	 * Encoding of response (identity, deflate, gzip)
	 * @var string
	 */
	protected $encoding = false;

	public function __construct( $url, $session = null )
	{
		if ( $session )
		{
			$this->session = $session;
			$this->set = &$session->set;
		}

		$this->url = $url;

		$this->handle = curl_init();

		curl_setopt( $this->handle, CURLOPT_URL, $this->url );
		curl_setopt( $this->handle, CURLOPT_HEADER, true );
		curl_setopt( $this->handle, CURLOPT_RETURNTRANSFER, true );
	}

	public function getHandle()
	{
		return $this->handle;
	}

	public function url( $url )
	{
		$this->url = $url;
		curl_setopt( $this->handle, CURLOPT_URL, $this->url );

		return $this;
	}

	public function timeout( $seconds = 10 )
	{
		$this->timeout = $seconds;

		return $this;
	}

	public function followLocation( $followLocation )
	{
		$this->followLocation = (bool) $followLocation;

		return $this;
	}

	public function encoding($encoding)
	{
		$this->encoding = $encoding;

		return $this;
	}

	public function post( $data )
	{
		$this->post = $data;

		return $this;
	}
	
	public function perform()
	{
		if ( $this->session )
		{
			curl_setopt( $this->handle, CURLOPT_COOKIEFILE, $this->getCookieFile() );
			curl_setopt( $this->handle, CURLOPT_COOKIEJAR, $this->getCookieFile() );
		}

		if ( $useragent = $this->getUserAgent() )
			curl_setopt( $this->handle, CURLOPT_USERAGENT, $useragent );

		if ( $referer = $this->getReferer() )
			curl_setopt( $this->handle, CURLOPT_REFERER, $referer );

		if ( $interface = $this->getInterface() )
			curl_setopt( $this->handle, CURLOPT_INTERFACE, $interface );

		if ( $proxy = $this->getProxy() )
		{
			curl_setopt( $this->handle, CURLOPT_PROXY, $proxy->host . ':' . $proxy->port );

			if ( $proxy->authorized() )
				curl_setopt( $this->handle, CURLOPT_PROXYUSERPWD, $proxy->username . ':' . $proxy->password );
		}

		curl_setopt( $this->handle, CURLOPT_FOLLOWLOCATION, $this->followLocation );

		if ( $this->post )
		{
			curl_setopt( $this->handle, CURLOPT_POST, 1 );
			curl_setopt( $this->handle, CURLOPT_POSTFIELDS, $this->post );
		}

		if ($this->encoding)
		{
			curl_setopt($this->handle, CURLOPT_ENCODING, $this->encoding);
		}
	}

	public function execute()
	{
		$this->perform();

		$response = curl_exec( $this->handle );

		if( ( $error = curl_error( $this->handle ) ) != '' )
		{
		    throw new Exception\Request( $error );
		}

		$code = curl_getinfo( $this->handle, CURLINFO_HTTP_CODE );
		$this->setReferer( $this->url ); 
		$this->url = curl_getinfo( $this->handle, CURLINFO_EFFECTIVE_URL );

		return $this->response = new Response( $code, $response );
	}

	public function __destruct()
	{
	    curl_close($this->handle);
	}
}