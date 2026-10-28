<?php

namespace App\Services\Llm;

interface LlmClient
{
    /**
     * Refine a deterministic tool plan and explain the decision.
     *
     * @param  list<string>  $tools
     * @return array{tools?: mixed, rationale?: mixed}
     */
    public function refine(string $question, array $tools): array;
}
