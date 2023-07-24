<?php


namespace App\Services\File;


use App\Libraries\Base\BaseService;

class PdfService extends BaseService implements FileInterface
{
    public function create(): object
    {
        parent::create();
        // TODO: Implement create() method.
    }
    public function changeToHtml($filePath): array
    {
        parent::changeToHtml($filePath);
        // TODO: Implement changeToHtml() method.

    }
    public function dataFilling($tplFilePath, $data): void
    {
        // TODO: Implement dataFilling() method.
    }
}
