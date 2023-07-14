<?php


namespace App\Services\File;


interface FileInterface
{
    public function create() :object;

    public function changeToHtml($filePath) :array;

    public function dataFilling($tplFilePath,$data) :void;

}
