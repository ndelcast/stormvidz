<?php

namespace App\Services\ImageGenerator\Providers;

use RuntimeException;
use Illuminate\Support\Facades\Http;
use App\Services\ImageGenerator\Contracts\ImageGeneratorInterface;


class NanoBananaGenerator implements ImageGeneratorInterface
{
    private string $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image-preview:generateContent';


    public function generateImage(array $params): string
    {
        $apiKey = env('GEMINI_API_KEY');

        $imgBytes = file_get_contents($params['image']);
        $imgB64 = base64_encode($imgBytes);

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'inline_data' => [
                                'mime_type' => 'image/png',
                                'data' => $imgB64,
                            ],
                        ],
                        ['text' => '
                            Using the two provided images (a company logo and a representative), create a single, cohesive, professional ad visual.
                            The final image MUST have a 16:9 aspect ratio. This is a strict requirement.
                            - The representative should be the main focus, presented in a professional and friendly manner.
                            - Place the logo tastefully, usually in a corner, ensuring it\'s clear but not intrusive.
                            - Do not add any text unless specifically instructed in the prompt.
                        '],
                    ],
                ]
            ],
        ];

        $ch = curl_init($this->url);

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-goog-api-key: ' . $apiKey,
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $res = curl_exec($ch);

        if ($res === false) { 
            throw new RuntimeException(curl_error($ch)); 
        }
        
        $data = json_decode($res, true);

        $b64 = $data['candidates'][0]['content']['parts'][0]['inlineData']['data'] ?? null;

        if(!$b64) {
            $b64 = $data['candidates'][0]['content']['parts'][1]['inlineData']['data'] ?? null;
        }

        file_put_contents(storage_path('result.png'), base64_decode($b64));
        
        curl_close($ch);
        
        return storage_path('result.png');
    }
}