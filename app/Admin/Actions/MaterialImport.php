<?php

namespace App\Admin\Actions;

use App\Libraries\Base\Platform;
use App\Models\Material\FileGroup;
use Encore\Admin\Actions\Action;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class MaterialImport extends Action
{
    use Platform;
    protected $selector = '.material-import';

    public function handle(Request $request)
    {
        $file = $request->file('file');
        $platformId = $request->get('platform_id') ?? Admin::user()->platform_id;
        $groupIds = $request->get('group_ids');
        return $this->response()->success($platformId."-".$groupIds)->refresh();
    }

    public function form()
    {
        if ($this->isRootPlatform()) {
            $this->select('platform_id', '平台')->options(function () {
                return $this->getUserPlatform();
            })->required();
        }
        $this->multipleSelect('group_ids', '分组')->options(function () {
            return FileGroup::pluck('name', 'id');
        })->required();
        $this->file('file','文件');
    }

    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default material-import">导入数据</a>
HTML;
    }
}
