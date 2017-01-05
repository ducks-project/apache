<?php

namespace Ducks\Component\Apache {

    /**
     * @see http://php.net/manual/en/ref.apache.php
     */
    class Apache {

        /**
         * Use for internal cache
         * @var array
         */
        protected $requestHeaders;

        /**
         * Use for internal cache
         * @var array
         */
        protected $responseHeaders;

        /**
         * Constructor
         */
        public function __construct() {
            $this->requestHeaders = array();
            $this->responseHeaders = array();
        }

        /**
         * Fetch all HTTP request headers
         *
         * @return mixed<array|boolean> An associative array of all the HTTP headers in the current request, or FALSE on failure.
         */
        public function requestHeaders() {
            if (empty($this->requestHeaders)) {
                foreach ($_SERVER as $name => $value)  {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                        $this->requestHeaders[$name] = $value;
                    } elseif ($name == 'CONTENT_TYPE') {
                        $this->requestHeaders['Content-Type'] = $value;
                    } elseif ($name == 'CONTENT_LENGTH') {
                        $this->requestHeaders["Content-Length"] = $value;
                    }
                }
            }
            return (!empty($this->requestHeaders)) ? $this->requestHeaders : false;
        }

        /**
         * Fetch all HTTP request headers
         *
         * This function is an alias for requestHeaders()
         *
         * @return mixed<array|boolean> An associative array of all the HTTP headers in the current request, or FALSE on failure.
         * @see Ducks\Component\Apache\Apache::requestHeaders
         */
        public function getAllHeaders() {
            return $this->requestHeaders();
        }

        /**
         * Fetch all HTTP response headers
         *
         * @return mixed<array|boolean> An array of all Apache response headers on success or FALSE on failure.
         */
        public function responseHeaders() {
            if (empty($this->responseHeaders)) {
                $headers = headers_list();
                foreach ($headers as $header) {
                    $header = explode(':', $header);
                    $this->responseHeaders[array_shift($header)] = trim(implode(':', $header));
                }
            }
            return (!empty($this->responseHeaders)) ? $this->responseHeaders : false;
        }

    }

}

