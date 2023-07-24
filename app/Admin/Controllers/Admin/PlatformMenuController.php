<?php


namespace App\Admin\Controllers\Admin;


use App\Libraries\Base\Platform;
use Encore\Admin\Controllers\MenuController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;
use App\Models\Admin\Platform AS PlatformModel;

class PlatformMenuController extends MenuController
{
    use Platform;
    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title(trans('admin.menu'))
            ->description(trans('admin.list'))
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('auth/menu'));

                    $menuModel = config('admin.database.menu_model');
                    $permissionModel = config('admin.database.permissions_model');
                    $roleModel = config('admin.database.roles_model');

                    $status = [
                        'on' => ['value'=>1,'text'=>'启用','color'=>'primary'],
                        'off' => ['value'=>0,'text'=>'禁用','color'=>'default']
                    ];

                    if ($this->isRootPlatform()) {
                        $form->hasMany('platformConfigs','平台配置',function (Form $form) use($status){
                            $form->select('platform_id','平台')->options(PlatformModel::all()->pluck('name','id'));
                            $form->switch('status','状态')->states($status)->default();
                        });
                    }
                    $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
                    $form->text('title', trans('admin.title'))->rules('required');
                    $form->icon('icon', trans('admin.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('admin.uri'));
                    $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
                    if ((new $menuModel())->withPermission()) {
                        $form->select('permission', trans('admin.permission'))->options($permissionModel::pluck('name', 'slug'));
                    }
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
                });
            });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        $menuModel = config('admin.database.menu_model');

        $tree = new Tree(new $menuModel());
        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

            if (!isset($branch['children'])) {
                if (url()->isValidUrl($branch['uri'])) {
                    $uri = $branch['uri'];
                } else {
                    $uri = admin_url($branch['uri']);
                }

                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
            }

            return $payload;
        });

        return $tree;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $menuModel = config('admin.database.menu_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');
        $status = [
            'on' => ['value'=>1,'text'=>'启用','color'=>'primary'],
            'off' => ['value'=>0,'text'=>'禁用','color'=>'default']
        ];

        $form = new Form(new $menuModel());

        $form->display('id', 'ID');
        if ($this->isRootPlatform()) {
            $form->hasMany('platformConfigs','平台配置',function (Form $form) use($status){
               $form->select('platform_id','平台')->options(PlatformModel::all()->pluck('name','id'));
               $form->switch('status','状态')->states($status);
            });
        }
        $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
        $form->text('title', trans('admin.title'))->rules('required');
        $form->icon('icon', trans('admin.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
        $form->text('uri', trans('admin.uri'));

        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::getPlatformRole(Admin::user()->platform_id));
        if ($form->model()->withPermission()) {
            $form->select('permission', trans('admin.permission'))->options($permissionModel::pluck('name', 'slug'));
        }

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
