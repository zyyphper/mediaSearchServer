<?php


namespace App\Admin\Controllers\Vip;


use App\Libraries\Base\BaseAdminController;
use App\Models\Vip\Dicts\EquityUnit;
use App\Models\Vip\VipDict;
use App\Models\Vip\VipEquity;
use App\Models\Vip\VipLevel;
use Encore\Admin\Form;
use Encore\Admin\Form\Field\Table;
use Encore\Admin\Grid;

class LevelController extends BaseAdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '会员等级';

    /**
     * @var VipLevel
     */
    protected $model;

    /**
     * @var
     */
    protected $service;

    public function __construct(VipLevel $model)
    {
        $this->model = $model;
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


        $grid->column('level', 'LEVEL');
//        $grid->column('name','会员等级')->expand(function ($model) {
//            $equityData = [];
//            foreach ($model->equities()->get() as $equity) {
//                $unitDict = VipDict::find($equity->pivot->unit);
//                $equityData = [$equity->id,$equity->name,$equity->pivot->num.$unitDict->desc];
//            }
//            return new Table(['ID', '权益','数量'], $equityData);
//        });
        $grid->column('requirement_score','达标分数');
        $grid->column('created_at', '创建时间');

        $grid->actions(function ($actions) {
        });

        return $grid;
    }

    /**
     *
     */
    protected function form()
    {
        $form = new Form($this->model);
        $form->tab('基本信息',function (Form $form) {
            $form->number("level",'等级')->rules(['required|max:6']);
            $form->text('name', '等级描述')->rules(['required|max:32']);
            $form->number('requirement_score', '等级达标分数')->rules(['required|min:1']);
        })->tab('等级权益',function (Form $form) {
            $form->hasMany('levelEquities','权益',function (Form\NestedForm $form) {
               $form->radio('equity_id','权益')->options(VipEquity::all()->pluck('name','id'))
                   ->when(VipEquity::SPACE_CAPACITY,function (Form $form) {
                        $form->number('num','数值');
                        $form->radio('unit','单位')->options(EquityUnit::load(EquityUnit::SPACE_CAPACITY)->pluck());
                   })->when(VipEquity::FILE_SEARCH,function (Form $form) {
                       $form->number('num','数值');
                       $form->radio('unit','单位')->options(EquityUnit::load(EquityUnit::FILE_SEARCH)->pluck());
                   })->when(VipEquity::TYPE_CONVERT,function (Form $form) {
                       $form->number('num','数值');
                       $form->radio('unit','单位')->options(EquityUnit::load(EquityUnit::TYPE_CONVERT)->pluck());
                   });
            });
        });

        return $form;
    }

}
