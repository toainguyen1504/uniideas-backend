<?php

use Illuminate\Support\Facades\Route;
use Dedoc\Scramble\Scramble;

Scramble::registerApi('v1', [
    'file' => base_path('routes/api.php'),
]);

Scramble::registerUiRoute('v1', 'v1');
Scramble::registerJsonSpecificationRoute('v1.json', 'v1');

Scramble::configure('v1')->withDocumentTransformers(function (\Dedoc\Scramble\Support\Generator\OpenApi $openApi) {
    $scheme = \Dedoc\Scramble\Support\Generator\SecurityScheme::http('bearer', 'JWT')->as('bearerAuth');

    $openApi->secure($scheme);

    return $openApi;
});
