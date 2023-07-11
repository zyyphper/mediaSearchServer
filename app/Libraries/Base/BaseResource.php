<?php
/**
 * 基础数据资源
 *
 * @return mixed
 */
namespace App\Libraries\Base;

use App\Helpers\Tools;
use Illuminate\Http\Resources\Json\Resource;

class BaseResource extends Resource
{
    /**
     * HTTP请求
     * @var null
     */
    protected $request = null;

    /**
     * 默认的公共返回方法
     *
     * @return mixed
     */
    public function return()
    {
        return parent::toArray($this->request);
    }

    /**
     * 数据类转数组
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function toArray($request)
    {
        $this->request = $request;
        // 调用自身方法：子类方法
        return $this->distribute();
    }

    /**
     * 数据中转分配处理
     *
     * @return mixed
     */
    public function distribute()
    {
        $requestMethod = $this->getControllerAndFunction()['method'];
        $method = method_exists($this, $requestMethod) ? $requestMethod : 'return';
        return $this->$method();
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

    /**
     * 封装最外层数据结构（重写了父类方法）
     *
     * @param mixed $resource
     * @param array $data
     * @return mixed
     */
    public static function collection($resource, array $data = [])
    {
        // 默认返回数组
        if (empty($data)) {
            $data = Tools::success();
        }
        if (is_array($resource)) {  // 数组
            // 一维数组处理
            if (!isset($resource[0])) {
                return (new static($resource))->additional($data);
            } elseif (empty($resource)) {
                return (new static($resource))->additional($data);
            }
        } elseif (is_bool($resource)) {  // 布尔值
            return (new static(['bool' => $resource]))->additional($data);
        } elseif (is_string($resource)) {  // 字符串
            return (new static(['string' => $resource]))->additional($data);
        } elseif (is_numeric($resource)) {  // 数字
            return (new static(['number' => $resource]))->additional($data);
        } elseif (is_float($resource)) {  // 浮点数
            return (new static(['float' => $resource]))->additional($data);
        } elseif (is_null($resource)) {  // null
            return (new static(['null' => $resource]))->additional($data);
        } elseif ($resource instanceof \ArrayObject) {  // 空对象
            return $data;
        } else {  // 对象
            if ($resource &&
                !($resource instanceof \Illuminate\Pagination\LengthAwarePaginator) &&  // 非分页
                !($resource instanceof \Illuminate\Database\Eloquent\Collection)  // 非多维数组
            ) {
                return (new static($resource))->additional($data); // 单维数组
            } elseif (empty($resource)) {
                return (new static($resource))->additional($data);
            }
        }
        if (is_array($resource)) {
            $resource = collect($resource);
        }
        return parent::collection($resource)->additional($data);
    }
}
