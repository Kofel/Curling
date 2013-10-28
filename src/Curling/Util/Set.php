<?php

namespace Curling\Util;

class Set
{
	/**
	 * Set data
	 * @var array
	 */
	public $set;
	
	public function setCookieFile( $cookieFile )
	{
		$this->cookieFile = $cookieFile;

		return $this;
	}

	public function getCookieFile()
	{
		return $this->cookieFile;
	}

	public function setUserAgent( $userAgent )
	{
		$this->userAgent = $userAgent;

		return $this;
	}

	public function getUserAgent()
	{
		return $this->userAgent;
	}

	public function setInterace( $interface )
	{
		$this->interface = $interface;

		return $this;
	}

	public function getInterface()
	{
		return $this->interface;
	}

	public function setProxy( $proxy )
	{
		$this->proxy = $proxy;

		return $this;
	}

	public function getProxy()
	{
		return $this->proxy;
	}

	public function setReferer( $referer )
	{
		$this->referer = $referer;
		return $this;
	}

	public function getReferer()
	{
		return $this->referer;
	}

	public function __get( $name )
	{
		return ( isset( $this->set[ $name ] ) ) ? $this->set[ $name ] : null;
	}

	public function __set( $name, $value )
	{
		return $this->set[ $name ] = $value;
	}
}