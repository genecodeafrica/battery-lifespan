<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Exception;

class MachineLearningService
{
    /**
     * Send battery features to the Python ML API.
     */
    public function predict(array $features): array
    {
        $apiUrl = rtrim(
            config('services.ml.url'),
            '/'
        );

        try {

            $response = Http::timeout(30)
                ->acceptJson()
                ->post(
                    $apiUrl . '/predict',
                    $features
                );

            if ($response->failed()) {

                throw new Exception(
                    'Machine learning API returned HTTP ' .
                    $response->status() .
                    ': ' .
                    $response->body()
                );
            }

            return $response->json();

        } catch (ConnectionException $e) {

            throw new Exception(
                'Unable to connect to the Python machine learning service. ' .
                'Make sure FastAPI is running on ' .
                $apiUrl
            );
        }
    }
}