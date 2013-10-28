<?php

namespace Curling\Util;

class Url
{
	/**
	 * Copy missing URL parts things from source to target
	 */
	public static function mix( $source, $target )
	{
		$source = parse_url( $source );
		
		if ( isset( $source['query'] ) ) unset( $source['query'] );

		return self::build( array_merge( $source, parse_url( $target ) ) );
	}

	/**
	 * Build url from parts
	 */
	public static function build( $parts )
	{
		$url = $parts['scheme'] . '://';

		if ( isset($parts['user']) )
		{
			$url .= $parts['user'];

			if ( isset($parts['pass']) )
				$url .= ':' . $parts['pass'];

			$url .= '@';
		}
		
		$url .= $parts['host'];

		if ( isset($parts['port']) )
			$url .= ':' . $parts['port'];

		if ( isset($parts['path']) )
			$url .= $parts['path'];

		if ( isset($parts['query']) )
			$url .= '?' . $parts['query'];

		if ( isset($parts['fragment']) )
			$url .= '#' . $parts['fragment'];

		return $url;
	}
}
