<?php


namespace App\Services\Rpc;


use App\Services\Rpc\Enum\CommMethod;
use App\Services\Rpc\Method\GRpc;
use App\Services\Rpc\Method\HttpRpc;
use App\Services\Rpc\Method\JsonRpc;

/**
 * 远程调用服务
 * Class RpcService
 * @package App\Services\Rpc
 */
class RpcService
{
    public function getClient($service,$serviceConfig)
    {
        switch ($serviceConfig['method']) {
            case CommMethod::HTTP: $client = (new HttpRpc($serviceConfig))->getClient();break;
            case CommMethod::JSON_RPC: $client = (new JsonRpc($serviceConfig))->getClient();break;
            case CommMethod::G_RPC: $client = (new GRpc($serviceConfig))->getClient();break;
            default:throw new \Exception("not allowed method");
        }
        //将获取到的客户端注入回指定服务中
        return new $service($client);
    }
}
