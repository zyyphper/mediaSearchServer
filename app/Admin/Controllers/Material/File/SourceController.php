<?php

namespace App\Admin\Controllers\Material\File;


use App\Admin\Extensions\Tools\MaterialFileImportTool;
use App\Helpers\Tools;
use App\Libraries\Base\BaseAdminController;
use App\Models\Material\Enums\FileOriginType;
use App\Models\Material\Enums\FileType;
use App\Models\Material\FileSource;
use Encore\Admin\Grid;

class SourceController extends BaseAdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '文件资源';

    protected $model;

    public function __construct(FileSource $model)
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

        $grid->tools(function ($tools) use ($grid) {
            $tools->append(new MaterialFileImportTool());
        });
        $grid->disableCreateButton();

        $grid->column('id', '文件资源ID');
        if ($this->isRootPlatform()) {
            $grid->platform()->name("平台");
        }
        $grid->groups()->name("分组集合");
        $grid->column('name', '文件名称')->editable();
        $grid->column('type','文件类型')->using(FileType::$texts);
        $grid->column('origin_type', '来源类型')->using(FileOriginType::$texts);

        $grid->column('created_at', '创建时间');
        $grid->operator()->name('操作员');

        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        return $grid;
    }


    /**
     * 数据导入
     * @return mixed
     */
    public function import()
    {
        $data = Tools::checkRequest('import_data');
        $importData = json_decode($data['import_data'], JSON_UNESCAPED_UNICODE);
        $this->service->importData($importData);
        return $this->ajaxSuccess('导入成功');
    }

}
