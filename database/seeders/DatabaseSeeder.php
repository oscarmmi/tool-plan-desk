<?php

namespace Database\Seeders;

use App\Models\PlanJob;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $examples = [
            [
                'question' => 'Where is order HD-2048? It has not arrived yet.',
                'tools' => ['order_status', 'tracking'],
                'rationale' => 'Check the order record first, then use its shipment details to investigate delivery progress.',
            ],
            [
                'question' => 'Can I get a refund for the headphones I bought last week?',
                'tools' => ['refund_policy'],
                'rationale' => 'Review the return window and eligibility rules before responding about a refund.',
            ],
            [
                'question' => 'My tracking says delivered, but there is no package at my door.',
                'tools' => ['tracking'],
                'rationale' => 'Inspect the shipment events and delivery status before offering next steps.',
            ],
            [
                'question' => 'I need to cancel order HD-3190 and understand the refund policy.',
                'tools' => ['order_status', 'refund_policy'],
                'rationale' => 'Confirm the order state, then check whether it is still eligible for cancellation and refund.',
            ],
        ];

        foreach ($examples as $example) {
            $job = PlanJob::query()->updateOrCreate(
                ['question' => $example['question']],
                ['status' => 'planned'],
            );

            $job->toolPlan()->updateOrCreate([], [
                'tools' => $example['tools'],
                'rationale' => $example['rationale'],
            ]);
        }
    }
}
