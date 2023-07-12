<?php


namespace App\Http\Controllers\Text;


use App\Services\File\WordService;
use Illuminate\Routing\Controller as BaseController;

class FileController extends BaseController
{
    public function Read()
    {
        $service = new WordService();
        $data = $service->read(public_path("/sources/1/default.docx"));
        var_dump($data);
    }
}
