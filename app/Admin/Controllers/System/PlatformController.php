<?php


namespace App\Admin\Controllers\System;


use App\Libraries\Base\BaseAdminController;
use App\Models\System\Platform;
use Encore\Admin\Grid;

class PlatformController extends BaseAdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '模板';

    /**
     * @var Platform
     */
    protected $model;

    /**
     * @var
     */
    protected $service;

    public function __construct(Platform $model)
    {
        $this->model = $model;
    }
    /**
     * 表格
     * @return Grid
     * @throws \Exception
     */
    protected function grid()
    {
        $grid = new Grid($this->model);
        $grid->model()->latest();


        $grid->column('id', 'ID');
        $grid->column('name', '平台名称')->editable();


        $status = [
            'on' => ['value'=>0,'text'=>'启用','color'=>'primary'],
            'off' => ['value'=>1,'text'=>'禁用','color'=>'default']
        ];
        $grid->column('status', '状态')->switch($status);
        $grid->column('created_at', '创建时间');


        $grid->actions(function ($actions) {
        });

        return $grid;
    }


}
