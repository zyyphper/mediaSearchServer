<?php

namespace App\Admin\Controllers\Material;

use App\Admin\Extensions\Tools\SourceExportTool;
use App\Helpers\Tools;
use App\Libraries\Base\BaseAdminController;
use App\Models\Base\Project;
use App\Models\Material\Album;
use App\Models\Material\Copyright;
use App\Models\Material\Enums\FileType;
use App\Models\Material\Enums\ImageType;
use App\Models\Material\Enums\SourceType;
use App\Models\Material\MediaSources;
use App\Models\Material\Source;
use App\Services\Excel\ExcelService;
use App\Services\Excel\Exports\Tpl\SourceExportTpl;
use App\Services\Material\SourceService;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SourceController extends BaseAdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '素材';

    public function __construct()
    {
    }
    /**
     * 表格
     * @return Grid
     * @throws \Exception
     */
    protected function grid()
    {
        $grid = new Grid(new MediaSources());
        $grid->model()->latest();

//        $grid->tools(function ($tools) use ($grid) {
//            $tools->append(new SourceExportTool());
//        });
        $grid->disableCreateButton();
        $grid->disableExport();

//        $grid->column('id', 'ID');
//        $grid->column('encode_code', '媒资编码')->editable();
        $grid->column('name', '媒资名称')->editable();
        $grid->column('type','类型')->using(FileType::$texts);

//        $status = [
//            'on' => ['value'=>0,'text'=>'启用','color'=>'primary'],
//            'off' => ['value'=>1,'text'=>'禁用','color'=>'default']
//        ];
//        $grid->column('status', '状态')->switch($status);
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
//        $grid->operateUser()->name('操作员');

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


}
