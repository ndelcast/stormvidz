<?php

namespace App\Services\ImageGenerator;

use App\Services\ImageGenerator\Providers\NanoBananaGenerator;
use App\Services\ImageGenerator\Contracts\ImageGeneratorInterface;


class ImageGeneratorFactory
{
    public static function create(string $provider): ImageGeneratorInterface
    {
        return match ($provider) {
            'nano-banana' => new NanoBananaGenerator(),

            default => throw new \InvalidArgumentException("Provider {$provider} not supported"),
        };
    }
}