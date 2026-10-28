<?php

namespace App\Services\Llm;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiLlmClient implements LlmClient
{
    /** @param list<string> $tools
     * @return array{tools?: mixed, rationale?: mixed}
     */
    public function refine(string $question, array $tools): array
    {
        $key = (string) config('services.gemini.key');
        $model = (string) config('services.gemini.model', 'gemini-2.5-flash');
        $allowedTools = ['order_status', 'refund_policy', 'tracking'];

        $response = Http::acceptJson()
            ->withHeader('x-goog-api-key', $key)
            ->timeout(12)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                'systemInstruction' => [
                    'parts' => [[
                        'text' => 'You refine support tool plans. Return only JSON with tools (an array) and rationale (a short string). Tools must come only from this allow-list: '.implode(', ', $allowedTools).'. Keep every tool already selected by the keyword router. Never execute tools or invent order details.',
                    ]],
                ],
                'contents' => [[
                    'role' => 'user',
                    'parts' => [[
                        'text' => json_encode(['question' => $question, 'tools' => $tools], JSON_THROW_ON_ERROR),
                    ]],
                ]],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'temperature' => 0.1,
                ],
            ]);

        $response->throw();
        $text = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($text)) {
            throw new RuntimeException('Gemini returned no plan content.');
        }

        $decoded = json_decode($text, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('Gemini returned an invalid plan.');
        }

        return $decoded;
    }
}
