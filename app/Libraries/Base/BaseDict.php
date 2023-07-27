<?php


namespace App\Libraries\Base;


class BaseDict
{
    const SHOW = 1;
    const NONE = 0;

    protected static $instance = null;
    protected static $type = null;
    /**
     * 字典模型
     * @var
     */
    protected string $model;
    /**
     * 字典数据
     * @var array
     */
    protected array $data;

    private function __construct($type)
    {
        $this->data = $this->model::where('type',$type)->where('is_show',BaseDict::SHOW)->pluck('code','name');
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function load($type)
    {
        if (!(self::$instance instanceof self) || self::$type != $type) {
            self::$instance = new self($type);
        }
        return self::$instance;
    }

    public function __get($name)
    {
        if (!isset($this->data[$name])) {
            throw new \Exception("未知的数据字典");
        }
        return $this->data[$name];
    }
}
