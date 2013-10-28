<?php

namespace Curling;

class Headers implements \ArrayAccess
{
	protected $headers;

	public function __construct( $headers = array() )
	{
		$this->headers = $headers;
	}

	public static function parse( $header )
	{
		$headers = array();

		$fields = explode( "\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header) );

		foreach ( $fields as $field )
		{
		    if( preg_match('/([^:]+): (.+)/m', $field, $match) )
		    {
		        $match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower( trim( $match[1] ) ) );

		        if( isset( $headers[ $match[1] ] ) )
		        {
		            $headers[ $match[1]] = array( $headers[ $match[1] ], $match[2] );
		        }
		        else
		        {
		            $headers[ $match[1] ] = trim( $match[2] );
		        }
		    }
		}

		return new self( $headers );
	}

	public function offsetSet( $field, $value )
	{
	    if ( !is_null( $field ) )
	    {
	        $this->headers[ $field ] = $value;
	    }
	    else
	    {
	    	throw new Exception\Headers('Unknown header field.');
	    }
	}

	public function offsetExists( $field )
	{
	    return isset( $this->headers[ $field ] );
	}

	public function offsetUnset( $field )
	{
	    unset( $this->headers[ $field ] );
	}
	public function offsetGet( $field )
	{
	    return ( $this->offsetExists( $field ) ) ? $this->headers[ $field ] : null;
	}
}