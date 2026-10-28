<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

type ToolName = 'order_status' | 'refund_policy' | 'tracking';

type RecentPlan = {
    id: number;
    question: string;
    tools: ToolName[];
    created_at: string | null;
};

type SelectedPlan = {
    id: number;
    question: string;
    status: string;
    tools: ToolName[];
    rationale: string | null;
} | null;

defineProps<{
    recentPlans: RecentPlan[];
    selectedPlan: SelectedPlan;
    aiEnabled: boolean;
}>();

const form = useForm({ question: '' });

const toolDetails: Record<
    ToolName,
    { label: string; description: string; number: string }
> = {
    order_status: {
        label: 'Order status',
        description:
            'Look up order details, payment state, and fulfillment progress.',
        number: '01',
    },
    refund_policy: {
        label: 'Refund policy',
        description:
            'Check return windows, refund eligibility, and cancellation rules.',
        number: '02',
    },
    tracking: {
        label: 'Shipment tracking',
        description:
            'Review carrier updates, delivery scans, and package location.',
        number: '03',
    },
};

const toolKeys = Object.keys(toolDetails) as ToolName[];
const toolEntries = Object.entries(toolDetails) as [
    ToolName,
    (typeof toolDetails)[ToolName],
][];

function submit(): void {
    form.post('/plans', { preserveScroll: true });
}

function useQuestion(question: string): void {
    form.question = question;
    document.getElementById('question')?.focus();
}
</script>

<template>
    <Head title="Tool Plan Desk">
        <meta
            name="description"
            content="Plan which Harbor Desk support tools to call, without executing them."
        />
    </Head>

    <div class="min-h-screen bg-[#f5f6f4] text-[#202b37]">
        <aside
            class="sidebar fixed inset-y-0 left-0 z-20 hidden w-[248px] flex-col border-r border-[#e8ebe8] bg-white lg:flex"
        >
            <div
                class="flex h-[76px] items-center gap-3 border-b border-[#edf0ed] px-6"
            >
                <div
                    class="brand-mark flex h-9 w-9 items-center justify-center rounded-xl bg-[#1b5748] text-white"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        class="h-5 w-5"
                        aria-hidden="true"
                    >
                        <path
                            d="M5 5.5h14M5 12h9M5 18.5h5"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                        <circle
                            cx="18"
                            cy="17.5"
                            r="3"
                            fill="#b8e2bd"
                            stroke="currentColor"
                            stroke-width="1.5"
                        />
                    </svg>
                </div>
                <div>
                    <p
                        class="text-[14px] font-semibold tracking-[-0.02em] text-[#1f2d38]"
                    >
                        Tool Plan Desk
                    </p>
                    <p class="mt-0.5 text-[11px] text-[#8a9695]">
                        Support operations
                    </p>
                </div>
            </div>

            <div class="px-4 pt-7">
                <p
                    class="px-3 text-[10px] font-semibold tracking-[0.15em] text-[#a0aaa8] uppercase"
                >
                    Workspace
                </p>
                <button
                    class="mt-3 flex w-full items-center gap-3 rounded-lg bg-[#eff5f1] px-3 py-2.5 text-left text-[13px] font-medium text-[#205848]"
                >
                    <svg
                        viewBox="0 0 20 20"
                        fill="none"
                        class="h-[17px] w-[17px]"
                        aria-hidden="true"
                    >
                        <path
                            d="M3.5 5.5h13v10h-13zM6.5 3.5v4M13.5 3.5v4M3.5 8h13"
                            stroke="currentColor"
                            stroke-width="1.4"
                            stroke-linejoin="round"
                        />
                    </svg>
                    Planner
                    <span
                        class="ml-auto rounded-md bg-white px-1.5 py-0.5 text-[10px] text-[#79918a]"
                        >⌘ 1</span
                    >
                </button>
            </div>

            <div class="px-4 pt-8">
                <div class="flex items-center justify-between px-3">
                    <p
                        class="text-[10px] font-semibold tracking-[0.15em] text-[#a0aaa8] uppercase"
                    >
                        Recent plans
                    </p>
                    <span class="text-[10px] text-[#a4aeab] tabular-nums">{{
                        recentPlans.length
                    }}</span>
                </div>
                <div v-if="recentPlans.length" class="mt-3 space-y-1">
                    <button
                        v-for="plan in recentPlans.slice(0, 5)"
                        :key="plan.id"
                        type="button"
                        class="recent-link group flex w-full items-center gap-2.5 rounded-lg px-3 py-2.5 text-left text-[12px] leading-[1.4] text-[#73817f] transition hover:bg-[#f5f7f5] hover:text-[#344b45]"
                        @click="useQuestion(plan.question)"
                    >
                        <span
                            class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#c6d8cb] group-hover:bg-[#518371]"
                        />
                        <span class="line-clamp-2">{{ plan.question }}</span>
                    </button>
                </div>
                <p v-else class="px-3 pt-3 text-xs leading-5 text-[#9aa5a2]">
                    Your plans will show up here.
                </p>
            </div>

            <div class="mt-auto p-4">
                <div
                    class="rounded-xl border border-[#e9eeeb] bg-[#fafbf9] p-3.5"
                >
                    <div class="flex items-center gap-2">
                        <span
                            class="flex h-6 w-6 items-center justify-center rounded-md bg-[#e7f1e9] text-[#4f8068]"
                        >
                            <svg
                                viewBox="0 0 20 20"
                                fill="none"
                                class="h-3.5 w-3.5"
                                aria-hidden="true"
                            >
                                <path
                                    d="M10 2.8v2.1m0 10.2v2.1M2.8 10h2.1m10.2 0h2.1M4.9 4.9l1.5 1.5m7.2 7.2 1.5 1.5m0-10.2-1.5 1.5m-7.2 7.2-1.5 1.5M10 7.1a2.9 2.9 0 1 0 0 5.8 2.9 2.9 0 0 0 0-5.8Z"
                                    stroke="currentColor"
                                    stroke-width="1.4"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </span>
                        <p class="text-[11px] font-semibold text-[#52625d]">
                            Planning mode
                        </p>
                    </div>
                    <p class="mt-2 text-[10px] leading-[1.55] text-[#8c9995]">
                        Plans suggest tools only. No customer records are
                        accessed.
                    </p>
                </div>
                <div class="mt-4 flex items-center gap-2.5 px-1">
                    <div
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-[#dfe9e2] text-[10px] font-semibold text-[#47705c]"
                    >
                        HD
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-[#53625f]">
                            Harbor Desk
                        </p>
                        <p class="text-[10px] text-[#a0aaa8]">Demo workspace</p>
                    </div>
                    <span
                        class="ml-auto h-1.5 w-1.5 rounded-full bg-[#67a37f]"
                    />
                </div>
            </div>
        </aside>

        <div class="min-h-screen lg:pl-[248px]">
            <header
                class="flex h-[76px] items-center justify-between border-b border-[#e8ebe8] bg-white px-5 sm:px-8 lg:px-10"
            >
                <div class="flex items-center gap-2 text-[12px] text-[#98a29f]">
                    <span class="font-semibold text-[#34433e] lg:hidden"
                        >Tool Plan Desk</span
                    >
                    <span class="hidden lg:inline">Workspace</span>
                    <svg
                        viewBox="0 0 16 16"
                        fill="none"
                        class="hidden h-3 w-3 lg:block"
                        aria-hidden="true"
                    >
                        <path
                            d="m6 3 5 5-5 5"
                            stroke="currentColor"
                            stroke-width="1.4"
                        />
                    </svg>
                    <span class="hidden font-medium text-[#586762] lg:inline"
                        >Planner</span
                    >
                </div>
                <div class="flex items-center gap-3">
                    <div
                        class="hidden items-center gap-2 rounded-full border border-[#e7ebe8] px-3 py-1.5 sm:flex"
                    >
                        <span
                            class="h-1.5 w-1.5 rounded-full"
                            :class="aiEnabled ? 'bg-[#579473]' : 'bg-[#d2a256]'"
                        />
                        <span class="text-[10px] font-medium text-[#74817d]">{{
                            aiEnabled ? 'Gemini refine on' : 'Rules-first mode'
                        }}</span>
                    </div>
                    <button
                        type="button"
                        class="flex h-8 w-8 items-center justify-center rounded-full border border-[#e9ecea] text-[#75827e] transition hover:bg-[#f6f8f6]"
                        aria-label="Help"
                    >
                        <svg
                            viewBox="0 0 20 20"
                            fill="none"
                            class="h-4 w-4"
                            aria-hidden="true"
                        >
                            <circle
                                cx="10"
                                cy="10"
                                r="7"
                                stroke="currentColor"
                                stroke-width="1.4"
                            />
                            <path
                                d="M8.2 7.6a1.9 1.9 0 1 1 3.1 1.5c-.8.6-1.3 1-1.3 2.1m0 2.1h.01"
                                stroke="currentColor"
                                stroke-width="1.4"
                                stroke-linecap="round"
                            />
                        </svg>
                    </button>
                </div>
            </header>

            <main
                class="mx-auto max-w-[1190px] px-5 pt-9 pb-12 sm:px-8 sm:pt-11 lg:px-10 lg:pt-12"
            >
                <div
                    class="mb-8 flex flex-wrap items-end justify-between gap-4"
                >
                    <div>
                        <div class="mb-3 flex items-center gap-2">
                            <span class="h-px w-5 bg-[#78a78c]" />
                            <p
                                class="text-[10px] font-semibold tracking-[0.18em] text-[#688c7b] uppercase"
                            >
                                Harbor Desk · Support workflow
                            </p>
                        </div>
                        <h1
                            class="text-[27px] font-semibold tracking-[-0.045em] text-[#24332f] sm:text-[32px]"
                        >
                            Plan the right next step.
                        </h1>
                        <p
                            class="mt-2 max-w-[520px] text-[13px] leading-6 text-[#899490]"
                        >
                            Turn a customer question into a clear, ordered tool
                            plan. Nothing runs until your team decides.
                        </p>
                    </div>
                    <div
                        class="flex items-center gap-2 rounded-lg border border-[#e5eae6] bg-white px-3 py-2 text-[10px] font-medium text-[#7b8984]"
                    >
                        <svg
                            viewBox="0 0 16 16"
                            fill="none"
                            class="h-3.5 w-3.5 text-[#6a9a7a]"
                            aria-hidden="true"
                        >
                            <path
                                d="m3 8.2 3.1 3L13 4.8"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        Read-only planning
                    </div>
                </div>

                <div
                    class="grid items-start gap-5 xl:grid-cols-[minmax(0,1fr)_290px]"
                >
                    <section class="space-y-5">
                        <div
                            class="overflow-hidden rounded-2xl border border-[#e5eae6] bg-white shadow-[0_3px_14px_rgba(35,56,45,0.025)]"
                        >
                            <div
                                class="flex items-center justify-between border-b border-[#eff1ef] px-5 py-4 sm:px-6"
                            >
                                <div class="flex items-center gap-2.5">
                                    <span
                                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#edf5ef] text-[#54806b]"
                                    >
                                        <svg
                                            viewBox="0 0 20 20"
                                            fill="none"
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M4 5.2h12M4 10h8M4 14.8h5"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                            />
                                            <circle
                                                cx="14.8"
                                                cy="14.8"
                                                r="2.2"
                                                stroke="currentColor"
                                                stroke-width="1.4"
                                            />
                                        </svg>
                                    </span>
                                    <div>
                                        <h2
                                            class="text-[12px] font-semibold text-[#35443f]"
                                        >
                                            Customer question
                                        </h2>
                                        <p
                                            class="mt-0.5 text-[10px] text-[#9ba5a1]"
                                        >
                                            Paste the message you want to
                                            investigate
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="rounded-md bg-[#f5f7f5] px-2 py-1 text-[9px] font-medium tracking-[0.08em] text-[#8e9a95] uppercase"
                                    >Input</span
                                >
                            </div>

                            <form class="p-5 sm:p-6" @submit.prevent="submit">
                                <label for="question" class="sr-only"
                                    >Customer support question</label
                                >
                                <textarea
                                    id="question"
                                    v-model="form.question"
                                    rows="4"
                                    maxlength="1000"
                                    placeholder="e.g. My order hasn't arrived yet. Can you check where it is?"
                                    class="min-h-[118px] w-full resize-y rounded-xl border border-[#e6ebe7] bg-[#fbfcfb] px-4 py-3.5 text-[13px] leading-[1.75] text-[#3a4843] transition outline-none placeholder:text-[#b2bbb7] focus:border-[#8eb49b] focus:bg-white focus:ring-4 focus:ring-[#e8f1eb]"
                                    :aria-invalid="
                                        Boolean(form.errors.question)
                                    "
                                />
                                <div
                                    class="mt-2 flex min-h-5 items-center justify-between"
                                >
                                    <p
                                        v-if="form.errors.question"
                                        class="text-[11px] text-[#b34d48]"
                                    >
                                        {{ form.errors.question }}
                                    </p>
                                    <p
                                        v-else
                                        class="text-[10px] text-[#a2aca8]"
                                    >
                                        Fictional demo data only · No real
                                        orders are connected
                                    </p>
                                    <span
                                        class="ml-auto text-[10px] text-[#aab3af] tabular-nums"
                                        >{{ form.question.length }}/1000</span
                                    >
                                </div>
                                <div
                                    class="mt-4 flex flex-wrap items-center justify-between gap-3"
                                >
                                    <p
                                        class="flex items-center gap-1.5 text-[10px] text-[#9aa5a1]"
                                    >
                                        <svg
                                            viewBox="0 0 16 16"
                                            fill="none"
                                            class="h-3 w-3"
                                            aria-hidden="true"
                                        >
                                            <rect
                                                x="3"
                                                y="7"
                                                width="10"
                                                height="7"
                                                rx="1.5"
                                                stroke="currentColor"
                                                stroke-width="1.2"
                                            />
                                            <path
                                                d="M5.5 7V5a2.5 2.5 0 0 1 5 0v2"
                                                stroke="currentColor"
                                                stroke-width="1.2"
                                            />
                                        </svg>
                                        Your question is stored with its plan
                                        for this demo.
                                    </p>
                                    <button
                                        type="submit"
                                        :disabled="
                                            form.processing ||
                                            form.question.trim().length < 8
                                        "
                                        class="inline-flex h-10 items-center gap-2 rounded-lg bg-[#245b4c] px-4 text-[11px] font-semibold text-white shadow-[0_2px_4px_rgba(33,84,67,0.14)] transition hover:bg-[#194c3e] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#579473] disabled:cursor-not-allowed disabled:opacity-45"
                                    >
                                        <svg
                                            v-if="form.processing"
                                            viewBox="0 0 20 20"
                                            fill="none"
                                            class="h-4 w-4 animate-spin"
                                            aria-hidden="true"
                                        >
                                            <circle
                                                cx="10"
                                                cy="10"
                                                r="7"
                                                stroke="currentColor"
                                                stroke-opacity=".3"
                                                stroke-width="2"
                                            />
                                            <path
                                                d="M17 10a7 7 0 0 0-7-7"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            viewBox="0 0 20 20"
                                            fill="none"
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M3.5 10h12m-5-5 5 5-5 5"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                        {{
                                            form.processing
                                                ? 'Building plan…'
                                                : 'Build tool plan'
                                        }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div
                            class="overflow-hidden rounded-2xl border border-[#e5eae6] bg-white shadow-[0_3px_14px_rgba(35,56,45,0.025)]"
                        >
                            <div
                                class="flex flex-wrap items-center justify-between gap-3 border-b border-[#eff1ef] px-5 py-4 sm:px-6"
                            >
                                <div class="flex items-center gap-2.5">
                                    <span
                                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#f2f4ee] text-[#7f8b6e]"
                                    >
                                        <svg
                                            viewBox="0 0 20 20"
                                            fill="none"
                                            class="h-4 w-4"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M5 3.8h7l3 3v9.4H5V3.8Z"
                                                stroke="currentColor"
                                                stroke-width="1.4"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M12 3.8v3h3M7.5 10h5M7.5 12.8h5"
                                                stroke="currentColor"
                                                stroke-width="1.3"
                                                stroke-linecap="round"
                                            />
                                        </svg>
                                    </span>
                                    <div>
                                        <h2
                                            class="text-[12px] font-semibold text-[#35443f]"
                                        >
                                            Recommended plan
                                        </h2>
                                        <p
                                            class="mt-0.5 text-[10px] text-[#9ba5a1]"
                                        >
                                            An ordered checklist for your
                                            support team
                                        </p>
                                    </div>
                                </div>
                                <span
                                    v-if="selectedPlan"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-[#edf5ef] px-2.5 py-1 text-[9px] font-semibold text-[#59806a]"
                                >
                                    <span
                                        class="h-1.5 w-1.5 rounded-full bg-[#72a583]"
                                    />
                                    Plan ready
                                </span>
                            </div>

                            <div v-if="selectedPlan" class="p-5 sm:p-6">
                                <div
                                    class="rounded-xl bg-[#f8faf8] px-4 py-3.5"
                                >
                                    <p
                                        class="text-[9px] font-semibold tracking-[0.13em] text-[#9aa6a1] uppercase"
                                    >
                                        Question
                                    </p>
                                    <p
                                        class="mt-1.5 text-[12px] leading-5 text-[#53615c]"
                                    >
                                        {{ selectedPlan.question }}
                                    </p>
                                </div>

                                <div
                                    v-if="selectedPlan.tools.length"
                                    class="mt-5 space-y-2.5"
                                >
                                    <div
                                        v-for="(
                                            tool, index
                                        ) in selectedPlan.tools"
                                        :key="`${tool}-${index}`"
                                        class="flex items-start gap-3 rounded-xl border border-[#e9eeea] p-3.5"
                                    >
                                        <span
                                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#eef5f0] text-[10px] font-semibold text-[#5a856f] tabular-nums"
                                            >{{
                                                String(index + 1).padStart(
                                                    2,
                                                    '0',
                                                )
                                            }}</span
                                        >
                                        <div class="min-w-0 flex-1 pt-0.5">
                                            <div
                                                class="flex flex-wrap items-center justify-between gap-2"
                                            >
                                                <p
                                                    class="text-[11px] font-semibold text-[#40504a]"
                                                >
                                                    {{
                                                        toolDetails[tool]
                                                            ?.label ?? tool
                                                    }}
                                                </p>
                                                <span
                                                    class="rounded bg-[#f5f7f5] px-1.5 py-0.5 font-mono text-[9px] text-[#8b9892]"
                                                    >{{ tool }}</span
                                                >
                                            </div>
                                            <p
                                                class="mt-1 text-[10px] leading-[1.6] text-[#94a09a]"
                                            >
                                                {{
                                                    toolDetails[tool]
                                                        ?.description
                                                }}
                                            </p>
                                        </div>
                                        <svg
                                            viewBox="0 0 16 16"
                                            fill="none"
                                            class="mt-1 h-3.5 w-3.5 shrink-0 text-[#9bb6a2]"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="m3 8.2 3 3L13 4.8"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <div
                                    v-else
                                    class="mt-5 rounded-xl border border-dashed border-[#e1e7e2] px-4 py-5 text-center"
                                >
                                    <p
                                        class="text-[11px] font-medium text-[#67756e]"
                                    >
                                        No tool match yet
                                    </p>
                                    <p class="mt-1 text-[10px] text-[#9aa59f]">
                                        Ask for an order, refund, or shipment
                                        detail to route a tool.
                                    </p>
                                </div>

                                <div
                                    class="mt-4 flex gap-2.5 rounded-xl border border-[#e9eee9] bg-white p-3.5"
                                >
                                    <svg
                                        viewBox="0 0 18 18"
                                        fill="none"
                                        class="mt-0.5 h-4 w-4 shrink-0 text-[#7c9c85]"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M9 2.5v2m0 9v2M2.5 9h2m9 0h2M4.4 4.4l1.4 1.4m6.4 6.4 1.4 1.4m0-9.2-1.4 1.4m-6.4 6.4-1.4 1.4M9 6.2a2.8 2.8 0 1 0 0 5.6 2.8 2.8 0 0 0 0-5.6Z"
                                            stroke="currentColor"
                                            stroke-width="1.25"
                                            stroke-linecap="round"
                                        />
                                    </svg>
                                    <div>
                                        <p
                                            class="text-[10px] font-semibold text-[#62716a]"
                                        >
                                            Why this plan
                                        </p>
                                        <p
                                            class="mt-1 text-[10px] leading-[1.65] text-[#909c96]"
                                        >
                                            {{
                                                selectedPlan.rationale ||
                                                'The router selected tools based on keywords in the customer question.'
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="px-5 py-8 sm:px-6">
                                <div
                                    class="empty-illustration mx-auto flex h-[74px] w-[74px] items-center justify-center rounded-[22px] bg-[#f1f6f2] text-[#6b967a]"
                                >
                                    <svg
                                        viewBox="0 0 44 44"
                                        fill="none"
                                        class="h-10 w-10"
                                        aria-hidden="true"
                                    >
                                        <rect
                                            x="8"
                                            y="8"
                                            width="24"
                                            height="29"
                                            rx="4"
                                            fill="white"
                                            stroke="#cddfd1"
                                            stroke-width="1.5"
                                        />
                                        <path
                                            d="M14 16h12M14 21h8M14 26h5"
                                            stroke="#8eaf98"
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                        />
                                        <circle
                                            cx="31"
                                            cy="30"
                                            r="8"
                                            fill="#e1efe3"
                                            stroke="white"
                                            stroke-width="2"
                                        />
                                        <path
                                            d="m27.7 30 2.1 2.1 4-4.2"
                                            stroke="#4f8667"
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </div>
                                <p
                                    class="mt-4 text-center text-[12px] font-semibold text-[#4b5a54]"
                                >
                                    Your plan will appear here
                                </p>
                                <p
                                    class="mx-auto mt-1 max-w-[310px] text-center text-[10px] leading-5 text-[#9aa59f]"
                                >
                                    Add a support question above and we’ll map
                                    it to the tools that can help.
                                </p>
                                <div
                                    class="mx-auto mt-5 flex max-w-[400px] flex-wrap justify-center gap-2"
                                >
                                    <span
                                        v-for="tool in toolKeys"
                                        :key="tool"
                                        class="rounded-full border border-[#e8eee9] bg-white px-2.5 py-1.5 font-mono text-[9px] text-[#85938c]"
                                        >{{ tool }}</span
                                    >
                                </div>
                            </div>
                        </div>
                        <p
                            class="flex items-center justify-center gap-1.5 pb-1 text-[9px] text-[#a4ada9]"
                        >
                            <svg
                                viewBox="0 0 16 16"
                                fill="none"
                                class="h-3 w-3"
                                aria-hidden="true"
                            >
                                <path
                                    d="M8 2.5v11M2.5 8h11"
                                    stroke="currentColor"
                                    stroke-width="1.3"
                                    stroke-linecap="round"
                                />
                            </svg>
                            Tool plans are suggestions only — no tools are
                            executed and no real order data is used.
                        </p>
                    </section>

                    <aside class="space-y-4">
                        <div
                            class="rounded-2xl border border-[#e5eae6] bg-white p-5 shadow-[0_3px_14px_rgba(35,56,45,0.025)]"
                        >
                            <div class="flex items-start justify-between">
                                <div>
                                    <p
                                        class="text-[11px] font-semibold text-[#3e4c47]"
                                    >
                                        Available tools
                                    </p>
                                    <p class="mt-1 text-[10px] text-[#9aa49f]">
                                        The planner can only choose these
                                    </p>
                                </div>
                                <span
                                    class="rounded-md bg-[#f3f6f3] px-1.5 py-1 text-[9px] font-semibold text-[#7e8c84]"
                                    >03</span
                                >
                            </div>
                            <div class="mt-4 space-y-3.5">
                                <div
                                    v-for="tool in toolEntries"
                                    :key="tool[0]"
                                    class="flex gap-2.5"
                                >
                                    <span
                                        class="mt-0.5 flex h-[23px] w-[23px] shrink-0 items-center justify-center rounded-md bg-[#f0f5f1] text-[8px] font-semibold text-[#63836e] tabular-nums"
                                        >{{ tool[1].number }}</span
                                    >
                                    <div>
                                        <p
                                            class="text-[10px] font-medium text-[#596761]"
                                        >
                                            {{ tool[1].label }}
                                        </p>
                                        <p
                                            class="mt-0.5 font-mono text-[8px] text-[#a0aaa5]"
                                        >
                                            {{ tool[0] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="rounded-2xl border border-[#e5eae6] bg-white p-5 shadow-[0_3px_14px_rgba(35,56,45,0.025)]"
                        >
                            <p class="text-[11px] font-semibold text-[#3e4c47]">
                                How planning works
                            </p>
                            <div class="mt-4 space-y-0">
                                <div class="flex gap-3 pb-4">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="flex h-6 w-6 items-center justify-center rounded-full bg-[#edf4ef] text-[9px] font-semibold text-[#59806a]"
                                            >1</span
                                        ><span
                                            class="mt-1 h-full w-px bg-[#e5ebe6]"
                                        />
                                    </div>
                                    <div class="pt-1">
                                        <p
                                            class="text-[10px] font-semibold text-[#58665f]"
                                        >
                                            Keyword router
                                        </p>
                                        <p
                                            class="mt-1 text-[9px] leading-[1.55] text-[#9aa49f]"
                                        >
                                            Matches order, refund, and tracking
                                            intent.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-3 pb-4">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="flex h-6 w-6 items-center justify-center rounded-full bg-[#edf4ef] text-[9px] font-semibold text-[#59806a]"
                                            >2</span
                                        ><span
                                            class="mt-1 h-full w-px bg-[#e5ebe6]"
                                        />
                                    </div>
                                    <div class="pt-1">
                                        <p
                                            class="text-[10px] font-semibold text-[#58665f]"
                                        >
                                            Optional AI refine
                                        </p>
                                        <p
                                            class="mt-1 text-[9px] leading-[1.55] text-[#9aa49f]"
                                        >
                                            Gemini can refine the plan and
                                            explain why.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <span
                                        class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#edf4ef] text-[9px] font-semibold text-[#59806a]"
                                        >3</span
                                    >
                                    <div class="pt-1">
                                        <p
                                            class="text-[10px] font-semibold text-[#58665f]"
                                        >
                                            Human review
                                        </p>
                                        <p
                                            class="mt-1 text-[9px] leading-[1.55] text-[#9aa49f]"
                                        >
                                            Your team decides what to do next.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="rounded-xl border border-[#e4ebe5] bg-[#eef4ef] px-4 py-3.5"
                        >
                            <div class="flex gap-2.5">
                                <svg
                                    viewBox="0 0 18 18"
                                    fill="none"
                                    class="mt-0.5 h-4 w-4 shrink-0 text-[#6d9477]"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M9 2.2 15 4.5v4.2c0 3.6-2.5 5.9-6 7.1-3.5-1.2-6-3.5-6-7.1V4.5l6-2.3Z"
                                        stroke="currentColor"
                                        stroke-width="1.3"
                                        stroke-linejoin="round"
                                    />
                                    <path
                                        d="m6.5 8.8 1.7 1.7 3.4-3.6"
                                        stroke="currentColor"
                                        stroke-width="1.3"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <div>
                                    <p
                                        class="text-[9px] font-semibold text-[#55715d]"
                                    >
                                        Safety by design
                                    </p>
                                    <p
                                        class="mt-1 text-[9px] leading-[1.6] text-[#829588]"
                                    >
                                        AI output is constrained to the tool
                                        allow-list. No actions or customer
                                        records.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                <footer
                    class="mt-9 flex flex-wrap items-center justify-between gap-2 border-t border-[#e7ebe7] pt-4 text-[9px] text-[#a1aaa6]"
                >
                    <span
                        >Tool Plan Desk <span class="mx-1.5">·</span> Harbor
                        Desk demo</span
                    >
                    <span
                        >Plans are generated for demonstration and never execute
                        tools.</span
                    >
                </footer>
            </main>
        </div>
    </div>
</template>
