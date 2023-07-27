<?php
/**
 * 基类服务
 */

namespace App\Libraries\Base;


use App\Services\Rpc\Method\Rpc;
use App\Services\Rpc\Method\RpcInterface;
use Encore\Admin\Facades\Admin;
use function PHPUnit\Framework\isNull;


class BaseService
{
    /**
     * 平台ID
     * @var null
     */
    public $platformId = null;
    /**
     * 服务名
     * @var string
     */
    public static $serviceName = null;
    /**
     * 远程调用服务对象
     * @var self
     */
    protected $rpcClient = null;

    /**
     * 通信方式 默认内置服务
     * @var int
     */
    protected $commMethod = null;

    /**
     * 模型对象
     * @var null
     */
    protected $model = null;

    /**
     * 自动实例化模型名称
     * @var null
     */
    protected $modelName = null;

    public function __construct(RpcInterface $rpcClient = null,$rpcConfig = [])
    {
        //远程调用服务初始化
        if (!isNull($rpcClient)) {
            $this->rpcClient = $rpcClient;
        }
        // 自动加载模型
        $this->autoSetModel();
        // 自动初始化函数调用
        method_exists($this, '_init') && call_user_func(array($this, '_init'));
        //自动初始化平台ID
        $this->setPlatform(Admin::user()->platform_id);
    }

    public function setPlatform(int $id)
    {
        $this->platformId = $id;
        return $this;
    }

    /**
     * 设置模型名称并且初始化模型
     * @param string $model 模型名称
     * @return mixed
     */
    protected function setModel(string $model)
    {
        // 如果传进来一个对象，直接作为model
        if (is_object($model)) {
            $this->model = $model;
            return $this->model;
        } elseif (($model)) {
            $this->modelName = $model;
            if (class_exists($this->modelName)) {
                $this->model = app($this->modelName);
            }
        }
        return $this->model;
    }

    /**
     * 自动设置模型
     * @return mixed
     */
    protected function autoSetModel()
    {
        if ($this->modelName === false) {
            return null;
        }
        if (is_null($this->modelName)) {
            // 解析出领域名
            $classPath = $this->getClassPath();
            if ($classPath[0] == 'App') {
                $classPath[1] = 'Models';
                $classPath[2] = str_replace('Service', '', $classPath[2]);
            }
            $this->modelName = implode('\\', $classPath);
        } else if (!class_exists($this->modelName)) {
            $this->modelName = '\\App\\Models' . $this->modelName;
        }
        return $this->setModel($this->modelName);
    }

    /**
     * 路径解析
     * @return array
     */
    private function getClassPath()
    {
        // 解析出领域名
        $classPath = get_class($this);
        $classPath = str_replace('/', '\\', $classPath);
        return explode('\\', $classPath);
    }

    /**
     * 通过服务名和通信方式调用服务方法
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        //判断当前服务是否已经连接
        if (!isNull($this->rpcClient)) {
            return $this->rpcClient->call($name,$arguments[0]);
        }
        //非远程服务 报错弹出
        throw new \Exception("not exist function");
    }
}
