<?php

return [
    \App\Services\File\PdfService::class => [
        'name' => 'pdfService',
        'method' => \App\Services\Rpc\Enum\CommMethod::JSON_RPC,

    ]
];
