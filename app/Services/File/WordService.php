<?php


namespace App\Services\File;


use App\Libraries\Base\BaseService;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use TheSeer\Tokenizer\Exception;

class WordService extends BaseService implements FileInterface
{

    public function create(): object
    {
        $phpWord = new PhpWord();
        return $phpWord;
    }

    public function changeToHtml($filePath): array
    {
        try {
            $phpWord = IOFactory::load($filePath);
            $xmlWriter = IOFactory::createWriter($phpWord,"HTML");
            $xmlWriter->save(public_path("/sources/1/default.html"));
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

    public function dataFilling($tplFilePath, $data): void
    {
        $tplProcessor = new TemplateProcessor($tplFilePath);
        $tplProcessor->setValues($data);
        $tplProcessor->saveAs(public_path("/sources/1/".time().".docx"));
    }
}
