<?php


namespace App\Admin\Controllers\Material\File;


use App\Libraries\Base\BaseAdminController;
use App\Models\Material\FileGroup;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class GroupController extends BaseAdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '分组';

    protected $model;

    public function __construct(FileGroup $model)
    {
        $this->model = $model;
        parent::__construct();
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

        $grid->disableExport();

        $grid->column('id', '分组ID');
        if ($this->isRootPlatform()) {
            $grid->platform()->name("平台");
        }
        $grid->column('name', '名称')->editable();

        $grid->column('created_at', '创建时间');

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        return $grid;
    }

    public function form()
    {
        $form = new Form($this->model);
        $form->text("name","分组描述");
        $form->saving(function (Form $form) {
            $form->model()->id = app("snowFlake")->id;
            $form->model()->platform_id = Admin::user()->platform_id;
        });
        return $form;
    }

}
