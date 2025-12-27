@props(['class' => ''])

<div aria-live="polite" {{ $attributes->merge(['class' => 'prose prose-slate max-w-none prose-headings:tracking-tight prose-a:underline prose-a:underline-offset-4 prose-table:w-full prose-th:whitespace-nowrap prose-img:rounded-xl ' . $class]) }}>
    <div class="overflow-x-auto">
        {!! $slot !!}
    </div>
</div>
