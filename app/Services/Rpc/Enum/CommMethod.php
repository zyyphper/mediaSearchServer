<?php


namespace App\Services\Rpc\Enum;


use App\Libraries\Base\BaseEnum;

class CommMethod extends BaseEnum
{
    /**
     * HTTP接口服务(http协议)
     */
    const HTTP = 1;
    /**
     * RPC服务连接（json_rpc协议）
     */
    const JSON_RPC = 2;
    /**
     * GRPC服务连接（protocol协议）
     */
    const G_RPC = 3;
}
