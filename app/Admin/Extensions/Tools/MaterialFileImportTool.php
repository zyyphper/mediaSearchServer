<?php

namespace App\Admin\Extensions\Tools;

use App\Libraries\Base\Platform;
use App\Models\Admin\Platform as PlatformModel;
use App\Models\Material\FileGroup;
use Encore\Admin\Actions\Action;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class MaterialFileImportTool extends Action
{
    use Platform;
    public $name = '导入媒资数据';

    protected $selector = '.import-post';

    public function handle(Request $request)
    {
        $platformId = $request->get("platform_id") ?? Admin::user()->platform_id;
        $groupIds = $request->get("group_ids");
        var_dump($platformId);
        var_dump($groupIds);
        $request->file('file');

        return $this->response()->success('导入完成！')->refresh();
    }

    public function form()
    {
        if ($this->isRootPlatform()) {
            $this->select('platform_id', '平台')->options(function () {
                return PlatformModel::pluck('name', 'id');
            })->required();
        }
        $this->multipleSelect('group_ids', '分组')->options(function () {
            return FileGroup::pluck('name', 'id');
        })->required();
        $this->file('file', '请选择文件');
    }


    public function render()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-post" style="margin-right: 10px"><i class="fa fa-upload"></i>文件导入</a>
HTML;
    }
}
