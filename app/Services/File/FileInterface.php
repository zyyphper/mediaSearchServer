<?php


namespace App\Services\File;


interface FileInterface
{
    public function create() :object;

    public function read($filePath) :array;

}
