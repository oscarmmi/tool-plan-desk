<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_jobs', function (Blueprint $table): void {
            $table->id();
            $table->text('question');
            $table->string('status')->default('planned');
            $table->timestamps();
        });

        Schema::create('tool_plans', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('plan_job_id')->unique()->constrained()->cascadeOnDelete();
            $table->json('tools');
            $table->text('rationale')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_plans');
        Schema::dropIfExists('plan_jobs');
    }
};
