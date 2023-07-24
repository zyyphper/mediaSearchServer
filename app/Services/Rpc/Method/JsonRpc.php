<?php


namespace App\Services\Rpc\Method;


class JsonRpc extends Rpc implements RpcInterface
{
    public $socket;

    public function getClient(): object
    {
        //创建socket
        $this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        if ($this->socket === false) {
            throw new \Exception("unable to create socket:".socket_strerror(socket_last_error()));
        }

        $result = socket_connect($this->socket,$this->config['address'],$this->config['port']);
        if ($result === false) {
            throw new \Exception("unable to connect socket:".socket_strerror(socket_last_error()));
        }
        return $this;
    }

    public function call($functionName, $params): array
    {
        $requestBody = json_encode([
            'jsonrpc' => '2.0',
            'method' => $functionName,
            'params' => $params,
            'id' => 1
        ]);
        $result = socket_write($this->socket,$requestBody."\n",strlen($requestBody)+1);
        if ($result === false) {
            throw new \Exception("unable to send request:".socket_strerror(socket_last_error()));
        }
        $response = "";
        do {
            $buffer = socket_read($this->socket,1024);
            $response .= $buffer;
            if (strlen($response) < 1024) {
                break;
            }
        } while(true);
        return json_decode($response,true);
    }
}
