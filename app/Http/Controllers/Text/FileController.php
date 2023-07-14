<?php


namespace App\Http\Controllers\Text;


use App\Services\File\WordService;
use Illuminate\Routing\Controller as BaseController;

class FileController extends BaseController
{
    public function changeToHtml()
    {
        $service = new WordService();
        $data = $service->changeToHtml(public_path("/sources/1/default.docx"));
        var_dump($data);
    }

    public function dataFilling()
    {
        $service = new WordService();
        $service->dataFilling(public_path("/sources/1/default.docx"),[
            'param1' => '谢江月',
            'param2' => 44050611111111,
            'param3' => '法学院',
            'param4' => '法学',
            'param5' => 111,
            'param6' => '2023-06',
            'param7' => '110'
        ]);
    }
}
