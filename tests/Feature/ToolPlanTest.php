<?php

use App\Models\PlanJob;
use App\Models\ToolPlan;
use App\Services\Llm\FakeLlmClient;
use App\Services\Llm\LlmClient;
use App\Services\ToolPlan\ToolPlanGenerator;

it('plans an order status question for a guest', function () {
    config(['services.gemini.key' => null]);

    $response = $this->followingRedirects()->post('/plans', [
        'question' => 'Can you check the status of order HD-1042?',
    ]);

    $response->assertOk();
    expect(app(LlmClient::class))->toBeInstanceOf(FakeLlmClient::class);

    $job = PlanJob::query()->with('toolPlan')->firstOrFail();

    expect($job->status)->toBe('planned')
        ->and($job->toolPlan->tools)->toContain('order_status')
        ->and($job->toolPlan->rationale)->not->toBeEmpty();
});

it('includes the refund policy tool for refund questions', function () {
    config(['services.gemini.key' => null]);

    $this->followingRedirects()->post('/plans', [
        'question' => 'What is your refund policy for a recent purchase?',
    ])->assertOk();

    expect(ToolPlan::query()->firstOrFail()->tools)->toContain('refund_policy');
});

it('drops tools outside the allow-list from llm output', function () {
    $this->app->instance(LlmClient::class, new FakeLlmClient([
        'tools' => ['tracking', 'delete_customer', 'tracking'],
        'rationale' => 'Check the delivery event.',
    ]));

    $plan = app(ToolPlanGenerator::class)->generate('A package delivery is late.');

    expect($plan['tools'])->toBe(['tracking']);
});

it('falls back to the keyword plan when llm refinement fails', function () {
    $this->app->instance(LlmClient::class, new class implements LlmClient
    {
        public function refine(string $question, array $tools): array
        {
            throw new RuntimeException('The optional provider is unavailable.');
        }
    });

    $plan = app(ToolPlanGenerator::class)->generate('Please check my order status.');

    expect($plan['tools'])->toBe(['order_status'])
        ->and($plan['rationale'])->toContain('keyword router');
});
