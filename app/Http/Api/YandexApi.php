<?php


namespace App\Http\Api;

use Illuminate\Support\Facades\Log;

define('YANDEX_API_ENDPOINT', 'https://tts.voicetech.yandex.net/generate');

class YandexApi
{

   public function yandexApi_getVoice($text)
    {
        $file_name = storage_path('audio') . '/audio_' . md5($text) . '.ogg';
        if (file_exists($file_name)) {
            return $file_name;
        }
        $file_handler = fopen($file_name, 'w+');
        $query = http_build_query(array(
            'format' => 'opus',
            'lang' => 'ru-RU',
            'speaker' => 'jane',
            'key' => env('YANDEX_API_KEY'),
            'emotion' => 'good',
            'text' => $text,
        ));
        $url = YANDEX_API_ENDPOINT . '?' . $query;
        $curl_handler = curl_init($url);
        curl_setopt($curl_handler, CURLOPT_FILE, $file_handler);
        curl_exec($curl_handler);
        $error = curl_error($curl_handler);
        if ($error) {
            Log::alert($error);
            throw new \Exception("Failed {$url} request");
        }
        curl_close($curl_handler);
        fclose($file_handler);
        return $file_name;
    }
}