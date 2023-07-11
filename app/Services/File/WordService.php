<?php


namespace App\Services\File;


use App\Libraries\Base\BaseService;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use TheSeer\Tokenizer\Exception;

class WordService extends BaseService implements FileInterface
{

    public function create(): object
    {
        $phpWord = new PhpWord();
        return $phpWord;
    }

    public function read($filePath): array
    {
        try {
            $phpWord = IOFactory::load($filePath);
            $xmlWriter = IOFactory::createWriter($phpWord,"HTML");
            $xmlWriter->save("/source/1.html");
            return [
                'code' => 0,
                'msg' => '读取成功',
                'data' => []
            ];
        } catch (Exception $exception) {
            return [
                'code' => 1,
                'msg' => $exception,
                'data' => []
            ];
        }
    }
}
