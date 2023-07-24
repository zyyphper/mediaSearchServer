<?php


namespace App\Services\Rpc\Method;


class Rpc
{
    public $config;

    public function __construct($config)
    {
        $this->config = $config;
    }
}
