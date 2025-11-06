<?php

namespace App\Services;

use OpenAI;
use OpenAI\Client;
use OpenAI\Responses\Audio\TranscriptionResponse;
use OpenAI\Responses\Chat\CreateResponse;

class OpenAiService
{
    private Client $client;
    private $transcription;

    // construct
    public function __construct()
    {
        $this->client = OpenAI::client(
            config('services.openai.api_key')
        );
    }

    public function detectObjectsInImage(string $image): CreateResponse
    {
        $responseSchema = json_encode([
            'object_name' => 'string',
            'object_type' => 'string',
            'object_model' => 'string',
            'object_manufacturer' => 'string',
            'object_features' => 'string',
            'object_brand' => 'string',
            'object_color' => 'string',
            'object_material' => 'string',
        ]);

        return $this->chat(
            [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            "type" => "text",
                            "text" => "List all of the objects, give me details such as product name, type, model, manufacturer, features, brand, color and material. Return JSON with an array of objects containing:" . $responseSchema,
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => $image
                            ]
                        ]
                    ]
                ],
            ],
            [
                'model' => 'gpt-4-turbo',
                'response_format' => [
                    'type' => 'json_object',
                ],
            ]
        );
    }

    public function chat(array $messages, array $options = []): CreateResponse
    {
        return $this->client->chat()->create(array_merge($options, [
            'messages' => $messages,
            'max_tokens' => 4096
        ]));
    }

    public function transcribe(string $audioPath): TranscriptionResponse
    {
        $transcription = $this->client->audio()->transcribe([
            'model' => 'whisper-1',
            'file' => fopen($audioPath, 'r'),
            'response_format' => 'verbose_json',
        ]);


        $this->transcription = $transcription;
        return $this->transcription;
    }


    function getTranscription()
    {
        if (!isset($this->transcription)) {
            return null;
        }


        // Process the transcription to detect pauses and add breaks
        $transcriptionText = '';
        $previousEndTime = 0;

        foreach ($this->transcription->segments as $segment) {
            $startTime = $segment->start;
            $endTime = $segment->end;
            $text = $segment->text;

            // Detect pauses longer than 0.2 seconds
            if ($startTime - $previousEndTime > 0.2) {
                $transcriptionText .= "\n\n"; // Add break
            }

            $transcriptionText .= $text . ' ';
            $previousEndTime = $endTime;
        }

        return trim($transcriptionText);
    }


    public function healthCheck(string $model): true
    {
        $this->client->models()->retrieve($model); // client will throw error if not successful
        return true;
    }
}
