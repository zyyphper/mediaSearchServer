<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Actions\Action;


class MaterialFileImportTool extends Action
{
    public $name = '导入媒资数据';

    protected $selector = '.import-post';

    public function render()
    {
        $url = 'templates/import_page';
        return <<<EOT
<div class="btn-group pull-right" style="margin-right: 5px">
    <a href="{$url}" target="_self" class="btn btn-sm btn-tumblr" title="数据导入">
        <i class="fa fa-image"></i><span class="hidden-xs">&nbsp;&nbsp;数据导入</span>
    </a>
</div>
EOT;
    }
}
