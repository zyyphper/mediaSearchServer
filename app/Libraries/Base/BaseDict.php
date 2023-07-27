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
    protected $model;
    /**
     * 字典数据
     * @var array
     */
    protected array $data;
    /**
     * 字典描述
     * @var array
     */
    protected array $desc;

    public function __construct($model,$type)
    {
        $this->model = app($model);
        $this->data = $this->model->where('type',$type)->where('is_show',BaseDict::SHOW)->pluck('code','name')->toArray();
        $this->desc = $this->model->where('type',$type)->where('is_show',BaseDict::SHOW)->pluck('desc','code')->toArray();
    }


    /**
     * @param $type
     * @param  $model
     * @return BaseDict|null
     */
    public static function __load($model,$type)
    {
        if (!(self::$instance instanceof self) || self::$type != $type) {
            self::$instance = new self($model,$type);
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

    public function pluck()
    {
        return $this->desc;
    }
}
