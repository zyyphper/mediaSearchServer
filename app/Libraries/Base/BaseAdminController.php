<?php
/**
 * 基类控制器
 */

namespace App\Libraries\Base;

use App\Helpers\Tools;
use Encore\Admin\Http\Controllers\AdminController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;

class BaseAdminController extends AdminController
{
    use DispatchesJobs;
    use Platform;

    const IMPORT_LINE_NUM = 2000;
    const IMPORT_FILE_MAX_NUM = 10;

    /**
     * 定义服务名称
     * 字符串：指定模型名称
     * null：自动加载，模型名称与控制器名称一致
     * false：不加载
     * @var null
     */
    protected $serviceName = null;

    /**
     * 自动加载服务
     * @var null
     */
    protected $service = null;

    /**
     * BaseAdminController constructor.
     */
    public function __construct()
    {
        $this->autoService();
    }

    /**
     * 自动注册服务
     * @return null
     */
    private function autoService()
    {
        if ($this->serviceName === false) {
            return null;
        }

        if (is_null($this->serviceName)) {
            $classPath = $this->getClassPath();
            if ($classPath[0] == 'App') {
                $modelName = str_replace('Controller', '', $classPath[3]);
                $this->serviceName = 'App\\Services\\' . $modelName . 'Service';
            }
        } else if (!strstr($this->serviceName, 'Service') || !class_exists($this->serviceName)) {
            // 组装服务路径
            $this->serviceName = '\\App\\Services\\' . ucfirst($this->serviceName) . 'Service';
        }
        $this->setService($this->serviceName);
    }

    /**
     * 获取类路径信息
     * @return array
     */
    private function getClassPath()
    {
        $classPath = get_class($this);
        $classPath = str_replace('/', '\\', $classPath);
        return explode('\\', $classPath);
    }

    /**
     * 加载并且实例化服务类
     * @param string $service 服务名称
     * @return mixed
     */
    protected function setService(string $service)
    {
        if (is_object($service)) {
            $this->service = $service;
        } elseif (($service)) {
            $this->serviceName = $service;
            if (class_exists($this->serviceName)) {
                $this->service = app($this->serviceName);
            }
        }
        return $this->service;
    }

    /**
     * 获取参数
     * @return mixed
     */
    protected function getParams()
    {
        // 获取所有参数
        $params = $this->rq()->all();
        return $params ?? [];
    }

    /**
     * 获取参数
     * @param null $key
     * @param null $default
     * @param null $call
     * @return mixed
     */
    protected function rq($key = null, $default = null, $call = null)
    {
        return is_null($call) ? request($key, $default) : call_user_func($call, request($key, $default));
    }

    /**
     * 成功返回数据
     * @param array $data 返回数据
     * @param int $code 状态码
     * @param array $mergeData 合并到外层的数据
     * @param string $message 提示信息
     * @return mixed
     */
    protected function ajaxSuccess($data = [], $mergeData = [], int $code = 0, string $message = '操作成功！')
    {
        $layer = Tools::success($message, $code);
        if (is_array($data) && empty($data) && empty($mergeData)) {
            $layer['data'] = $data;
            return response()->json($layer);
        } else {
            if (is_array($data) && empty($data)) {
                $response = $data;
            } elseif ($data instanceof \ArrayObject) {
                $layer['data'] = $data;
                return response()->json($layer);
            } else {
                $collection = $this->getResources()::collection($data, $layer);
                /** @var \Illuminate\Http\JsonResponse $response */
                $response = $collection->toResponse($this->rq());
            }
            // 合并数据
            if (!empty($mergeData)) {
                $result = !empty($response) ? $response->getData()->data : $response;
                $layer['data'] = [];
                if (empty($result) || isset($result[0])) {
                    $layer['data']['list'] = $result;
                } else {
                    $layer['data']['detail'] = $result;
                }
                $layer['data'] = array_merge($layer['data'], $mergeData);
                if (!empty($response)) {
                    $response->setData($layer);
                } else {
                    $response = $layer;
                }
            }
            $response->setStatusCode(Response::HTTP_OK);
            return $response;
        }
    }

    /**
     * 获取资源类
     * @return mixed
     */
    protected function getResources()
    {
        $controllerInfo = $this->getControllerAndFunction();
        $classPath = $this->getClassPath();
        if (6 == count($classPath)) {
            $modeName = $classPath[3] ?? "";
            $moduleName = $classPath[4] ?? "";
            $controllerName = str_replace('Controller', '', $controllerInfo['controller']);
            $resourcesFile = "\\App\\Http\\Resources\\{$modeName}\\{$moduleName}\\" . ucfirst($controllerName) . 'Resource';
        } else {
            $modeName = $classPath[3] ?? "";
            $controllerName = str_replace('Controller', '', $controllerInfo['controller']);
            $resourcesFile = "\\App\\Admin\\Resources\\{$modeName}\\" . ucfirst($controllerName) . 'Resource';
        }
        if (class_exists($resourcesFile)) {
            return $resourcesFile;
        }
        return '\\App\\Libraries\\Base\\BaseResource';
    }

    /**
     * @return array
     * 获取控制器和方法名
     */
    function getControllerAndFunction()
    {
        $action = \Route::current()->getActionName();
        list($class, $method) = explode('@', $action);
        $class = substr(strrchr($class,'\\'),1);
        return ['controller' => $class, 'method' => $method];
    }

}
