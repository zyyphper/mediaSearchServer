<?php


namespace App\Services\Material;


use App\Helpers\Tools;
use App\Libraries\Base\BaseService;
use App\Models\Material\Enums\FileHandleStatus;
use App\Models\Material\Enums\FileStatus;
use App\Models\Material\Enums\FileType;
use App\Models\Material\FileTemplate;
use Encore\Admin\Facades\Admin;

class FileTemplateService extends BaseService
{
    /**
     * @var FileTemplate
     */
    protected $model;

    public function importData(string $url,array $groupIds)
    {
        //根据路径获取文件类型和名称
        $info = Tools::getFileInfo($url);
        $model = $this->model::create([
            'id' => app('snowFlake')->id,
            'platform_id' => $this->platformId,
            'name' => $info['filename'],
            'original_url' => $url,
            'file_type' => FileType::$extensionMap[$info['extension']],
            'status' => FileStatus::DISABLE,
            'handle_status' => FileHandleStatus::WAIT,
            'operator' => Admin::user()->id
        ]);
        $model->groups()->sync($groupIds);
    }

}
