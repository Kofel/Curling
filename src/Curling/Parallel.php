<?php

namespace Curling;

class Parallel
{
    /**
     * Curl handle
     * @var handle
     */
    protected $handle;

    /**
     * Requests array
     * @var array
     */
    public $requests;

    public function __construct()
    {
        $this->handle = curl_multi_init();
    }

    public function push( Request $request )
    {
        $this->requests[] = $request;
    }

    public function execute()
    {
        foreach ($this->requests as $request)
        {
            curl_multi_add_handle($this->handle, $request->getHandle());
        }

        do {
            curl_multi_exec($this->handle, $running);
            curl_multi_select($this->handle);
        } while ($running > 0);

        $responseArray = array();

        foreach ($this->requests as $request)
        {
            $handle = $request->getHandle();

            if( ( $error = curl_error( $handle ) ) != '' )
            {
                throw new Exception\Request( $error );
            }

            $response = curl_multi_getcontent( $handle );

            $code = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
            $request->setReferer( $request->url );
            $request->url = curl_getinfo( $handle, CURLINFO_EFFECTIVE_URL );
            $request->response = new Response( $code, $response );

            $responseArray[] = $request->response;
        }

        return $responseArray;
    }

    public function __destruct()
    {
        curl_multi_close($this->handle);
    }
}