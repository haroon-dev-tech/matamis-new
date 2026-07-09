<div id="ai-generate-error" class="hidden rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-800 dark:bg-amber-950 dark:text-amber-200"></div>

<div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-dashed border-brand-200 bg-brand-50/50 px-4 py-3 dark:border-brand-800 dark:bg-brand-950/30">
    <div>
        <p class="text-sm font-medium text-slate-700 dark:text-slate-200">AI Assistant</p>
        <p class="text-xs text-slate-500">Generate risk and recommendations using Groq API (free cloud, no install).</p>
    </div>
    <button type="button" id="ai-generate-btn" class="btn-secondary inline-flex items-center gap-2 text-sm">
        <span data-label class="inline-flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
            Generate with AI
        </span>
        <span data-spinner class="hidden inline-flex items-center gap-2">
            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            Generating…
        </span>
    </button>
</div>
