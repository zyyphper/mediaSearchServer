<?php


namespace App\Services\Rpc\Method;


interface RpcInterface
{
    public function getClient():object;

    public function call($functionName,$params):array;
}
