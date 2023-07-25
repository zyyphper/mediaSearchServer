<?php

namespace App\Admin\Controllers\Material\File;


use App\Admin\Actions\MaterialImport;
use App\Helpers\Tools;
use App\Libraries\Base\BaseAdminController;
use App\Models\Material\Enums\FileType;
use App\Models\Material\FileTemplate;
use App\Services\Material\FileTemplateService;
use Encore\Admin\Grid;

class TemplateController extends BaseAdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '模板';

    protected $model;

    public function __construct(FileTemplate $model)
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
            $tools->append(new MaterialImport(FileTemplateService::class));
        });
        $grid->disableCreateButton();

        $grid->column('id', '模板ID');
        if ($this->isRootPlatform()) {
            $grid->platform()->name("平台");
        }
        $grid->column('groups','分组集合')->display(function ($groups) {

            $groups = array_map(function ($group) {
                return "<span class='label label-success'>{$group['name']}</span>";
            }, $groups);

            return join('&nbsp;', $groups);
        });
        $grid->column('name', '模板名称')->editable();
        $grid->column('type','文件类型')->using(FileType::$texts);

        $status = [
            'on' => ['value'=>1,'text'=>'启用','color'=>'primary'],
            'off' => ['value'=>0,'text'=>'禁用','color'=>'default']
        ];
        $grid->column('status', '状态')->switch($status);
//        $grid->column('label','媒资标签')->display(function ($labels) {
//            $str = "";
//            $labels = explode(',',$labels);
//            if (!empty($labels) && is_array($labels)) {
//                foreach ($labels as $key => $val) {
//                    if(!empty($val)) {
//                        $str .= "<span style='margin-bottom: 2px;display: inline-block;' class='label label-primary'>{$val}</span><br/>";
//                    }
//                }
//            }
//            return $str;
//        });
        $grid->column('created_at', '创建时间');
        $grid->operator()->name('操作员');

//        $grid->filter(function ($filter) {
//            $filter->disableIdFilter();
//            $filter->like('encode_code', '媒资编码');
//            $filter->like('name', '媒资名称');
//            $filter->in('project_id', '项目')->multipleSelect(function () {
//                return Project::getKeyValueArr();
//            });
//            $filter->in('copyright_id', '版权')->multipleSelect(function () {
//                return Copyright::getKeyValueArr();
//            });
//            $filter->in('album_id', '节目集')->multipleSelect(function () {
//                return Album::getKeyValueArr();
//            });
//            $filter->like('album.label', '节目标签');
//            $filter->like('label', '媒资标签');
//            $filter->scope('trashed', '被删除的数据')->onlyTrashed();
//        });

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
