<?php

namespace App\Services\ImageGenerator\Contracts;

interface ImageGeneratorInterface
{
    public function generateImage(array $params): string;
}