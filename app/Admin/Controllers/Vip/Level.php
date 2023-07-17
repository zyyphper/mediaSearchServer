<?php


namespace App\Admin\Controllers\Vip;


use App\Libraries\Base\BaseAdminController;
use App\Models\Vip\VipLevel;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class Level extends BaseAdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员等级';

    /**
     * @var VipLevel
     */
    protected $model;

    /**
     * @var
     */
    protected $service;

    public function __construct(VipLevel $model)
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


        $grid->column('level', 'LEVEL');
        $grid->column('name', '会员等级')->editable();
        $grid->column('requirement_score','等级达标分数');
        $grid->column('space_capacity','空间容量');
        $grid->column('type_change_times','类型转换次数');
        $grid->column('created_at', '创建时间');


        $grid->actions(function ($actions) {
        });

        return $grid;
    }

    /**
     *
     */
    protected function form()
    {
        $form = new Form($this->model);
        $form->text('name', '平台名称');
        $status = [
            'on' => ['value'=>0,'text'=>'启用','color'=>'primary'],
            'off' => ['value'=>1,'text'=>'禁用','color'=>'default']
        ];
        $form->switch('status','状态')->states($status);
        $form->saving(function (Form $form) {
            $form->model()->id = app('snowFlake')->id;
            $form->model()->vip_level = VipLevel::$defaultLevel;
        });

        return $form;
    }

}
