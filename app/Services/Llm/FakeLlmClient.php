<?php

namespace App\Services\Llm;

class FakeLlmClient implements LlmClient
{
    /** @param array{tools?: mixed, rationale?: mixed}|null $response */
    public function __construct(private readonly ?array $response = null) {}

    /** @param list<string> $tools
     * @return array{tools?: mixed, rationale?: mixed}
     */
    public function refine(string $question, array $tools): array
    {
        if ($this->response !== null) {
            return $this->response;
        }

        return [
            'tools' => $tools,
            'rationale' => $tools === []
                ? 'No supported tool is clearly needed yet. Ask the customer for an order number or more context.'
                : 'The request maps to the listed support tools. Review their results together before replying to the customer.',
        ];
    }
}
