<?php

namespace App\Http\Controllers;

use App\Models\PlanJob;
use App\Services\ToolPlan\ToolPlanGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlanJobController extends Controller
{
    public function index(Request $request): Response
    {
        $recentPlans = PlanJob::query()
            ->with('toolPlan')
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(fn (PlanJob $job): array => [
                'id' => $job->id,
                'question' => $job->question,
                'tools' => $job->toolPlan->tools ?? [],
                'created_at' => $job->created_at?->toIso8601String(),
            ])
            ->all();

        $selectedJob = $request->integer('plan') > 0
            ? PlanJob::query()->with('toolPlan')->find($request->integer('plan'))
            : null;

        return Inertia::render('Welcome', [
            'recentPlans' => $recentPlans,
            'selectedPlan' => $selectedJob === null || $selectedJob->toolPlan === null
                ? null
                : [
                    'id' => $selectedJob->id,
                    'question' => $selectedJob->question,
                    'status' => $selectedJob->status,
                    'tools' => $selectedJob->toolPlan->tools,
                    'rationale' => $selectedJob->toolPlan->rationale,
                ],
            'aiEnabled' => filled(config('services.gemini.key')),
        ]);
    }

    public function store(Request $request, ToolPlanGenerator $generator): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'min:8', 'max:1000'],
        ]);

        $planJob = PlanJob::query()->create([
            'question' => $validated['question'],
            'status' => 'planned',
        ]);
        $plan = $generator->generate($planJob->question);

        $planJob->toolPlan()->create([
            'tools' => $plan['tools'],
            'rationale' => $plan['rationale'],
        ]);

        return redirect()->route('home', ['plan' => $planJob->id]);
    }
}
