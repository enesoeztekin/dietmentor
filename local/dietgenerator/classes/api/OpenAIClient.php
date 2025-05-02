<?php

namespace local_dietgenerator\api;

class OpenAIClient {
    public function generate(string $prompt): string {
        $apikey = get_config('local_dietgenerator', 'openai_apikey');
        if (!$apikey) {
            return 'API anahtarı ayarlanmamış.';
        }

        $postdata = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apikey
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);
        return $data['choices'][0]['message']['content'] ?? 'OpenAI yanıtı alınamadı.';
    }
}