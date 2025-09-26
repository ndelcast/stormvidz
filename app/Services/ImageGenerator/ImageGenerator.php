<?php

namespace App\Services\ImageGenerator;

use App\Services\ImageGenerator\ImageGeneratorFactory;

class ImageGenerator
{
    public static function generate(string $providerName, array $params): string
    {
        $generator = ImageGeneratorFactory::create($providerName);
        
        return $generator->generateImage($params);
    }
}