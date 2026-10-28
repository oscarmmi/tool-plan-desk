<?php

namespace App\Services\ToolPlan;

use App\Services\Llm\LlmClient;
use Throwable;

class ToolPlanGenerator
{
    private const ALLOWED_TOOLS = ['order_status', 'refund_policy', 'tracking'];

    public function __construct(
        private readonly KeywordRouter $router,
        private readonly LlmClient $llm,
    ) {}

    /**
     * @return array{tools: list<string>, rationale: string}
     */
    public function generate(string $question): array
    {
        $routedTools = $this->router->route($question);
        $fallbackRationale = $routedTools === []
            ? 'No supported tool is clearly needed yet. Ask the customer for an order number or more context.'
            : 'The keyword router selected these tools. Review their results together before replying to the customer.';

        try {
            $refinement = $this->llm->refine($question, $routedTools);
            $refinedTools = $refinement['tools'] ?? [];
            $refinedTools = is_array($refinedTools) ? $refinedTools : [];
            $tools = array_values(array_unique([
                ...array_filter($refinedTools, fn (mixed $tool): bool => is_string($tool) && in_array($tool, self::ALLOWED_TOOLS, true)),
                ...$routedTools,
            ]));
            $rationale = $refinement['rationale'] ?? null;

            return [
                'tools' => $tools,
                'rationale' => is_string($rationale) && trim($rationale) !== ''
                    ? mb_substr(trim($rationale), 0, 1000)
                    : $fallbackRationale,
            ];
        } catch (Throwable) {
            return [
                'tools' => $routedTools,
                'rationale' => $fallbackRationale,
            ];
        }
    }
}
