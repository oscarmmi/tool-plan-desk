<?php

namespace App\Services\ToolPlan;

class KeywordRouter
{
    /** @return list<string> */
    public function route(string $question): array
    {
        $rules = [
            'order_status' => '/\b(order|purchase|charged|charge|payment)\b/i',
            'refund_policy' => '/\b(refund|return|exchange|cancel|cancellation)\b/i',
            'tracking' => '/\b(track|tracking|shipment|shipping|delivery|delivered|package|parcel|arriv\w*|lost)\b/i',
        ];

        $matches = [];

        foreach ($rules as $tool => $pattern) {
            if (preg_match($pattern, $question, $match, PREG_OFFSET_CAPTURE) === 1) {
                $matches[] = ['tool' => $tool, 'position' => $match[0][1]];
            }
        }

        usort($matches, fn (array $left, array $right): int => $left['position'] <=> $right['position']);

        return array_map(fn (array $match): string => $match['tool'], $matches);
    }
}
