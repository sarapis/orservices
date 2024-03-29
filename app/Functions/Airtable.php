<?php

namespace App\Functions;

use GuzzleHttp, Config, Auth;
use \DOMDocument, \SimpleXMLElement;

/**
 * Airtable API Class
 *
 * @author Sleiman Tanios
 * @copyright Sleiman Tanios - TANIOS 2017
 * @version 1.0
 */


class Airtable
{
    const API_URL = "https://api.airtable.com/v0/";

    private $_key;

    private $_token;

    private $_base;

    public function __construct($config)
    {
        if (is_array($config)) {
            $this->setAccessToken($config['access_token']);
            $this->setBase($config['base']);
        } else {
            echo 'Error: __construct() - Configuration data is missing.';
        }
    }

    public function setKey($key)
    {
        $this->_key = $key;
    }

    public function setAccessToken($token)
    {
        $this->_token = $token;
    }

    public function getKey()
    {
        return $this->_key;
    }

    public function getAccessToken()
    {
        return $this->_token;
    }

    public function setBase($base)
    {
        $this->_base = $base;
    }

    public function getBase()
    {
        return $this->_base;
    }

    public function getApiUrl($request)
    {
        $request = str_replace(' ', '%20', $request);
        $url = self::API_URL . $this->getBase() . '/' . $request;
        return $url;
    }

    function getContent($content_type, $params = "", $relations = false)
    {
        return new Request($this, $content_type, $params, false, $relations);
    }

    function saveContent($content_type, $fields)
    {

        $fields = array('fields' => $fields);

        $request = new Request($this, $content_type, $fields, true);

        return $request->getResponse();
    }

    function updateContent($content_type, $fields)
    {

        $fields = array('fields' => $fields);

        $request = new Request($this, $content_type, $fields, 'patch');

        return $request->getResponse();
    }

    function deleteContent($content_type)
    {

        $request = new Request($this, $content_type, [], 'delete');

        return $request->getResponse();
    }
}
