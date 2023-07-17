<?php


namespace App\Admin\Controllers\Admin;


use App\Libraries\Base\Platform;
use App\Models\Admin\PlatformOperationLog;
use Encore\Admin\Auth\Database\OperationLog;
use Encore\Admin\Controllers\LogController;
use Encore\Admin\Grid;
use Illuminate\Support\Arr;

class PlatformLogController extends LogController
{
    use Platform;
    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PlatformOperationLog());
        $this->platformAuth($grid);

        $grid->model()->orderBy('id', 'DESC');

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'User');
        $grid->column('method')->display(function ($method) {
            $color = Arr::get(OperationLog::$methodColors, $method, 'grey');

            return "<span class=\"badge bg-$color\">$method</span>";
        });
        $grid->column('path')->label('info');
        $grid->column('ip')->label('primary');
        $grid->column('input')->display(function ($input) {
            $input = json_decode($input, true);
            $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
            if (empty($input)) {
                return '<code>{}</code>';
            }

            return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_HEX_TAG).'</pre>';
        });

        $grid->column('created_at', trans('admin.created_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();
        });

        $grid->disableCreateButton();

        $grid->filter(function (Grid\Filter $filter) {
            $userModel = config('admin.database.users_model');

            $filter->equal('user_id', 'User')->select($userModel::all()->pluck('name', 'id'));
            $filter->equal('method')->select(array_combine(OperationLog::$methods, OperationLog::$methods));
            $filter->like('path');
            $filter->equal('ip');
        });

        return $grid;
    }
}
