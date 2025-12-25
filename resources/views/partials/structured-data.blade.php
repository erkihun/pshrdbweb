@php
    $currentUrl = url()->current();
    $siteName = config('app.name');
    $logoUrl = asset('images/logo.png');
    
    // Ensure variables exist
    $latestNews = $latestNews ?? collect();
    $type = $type ?? 'news';
@endphp

{{-- Organization Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "GovernmentOrganization",
    "name": "{{ $siteName }}",
    "description": "Official digital public service platform",
    "url": "{{ $currentUrl }}",
    "logo": "{{ $logoUrl }}"
}
</script>

{{-- Website Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ $siteName }}",
    "url": "{{ $currentUrl }}"
}
</script>

{{-- News Schema (only if news exists) --}}
@if($latestNews && $latestNews->count() > 0)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "itemListElement": [
        @foreach($latestNews as $index => $post)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "item": {
                "@type": "NewsArticle",
                "headline": "{{ addslashes($post->display_title ?? '') }}",
                "url": "{{ $type === 'news' ? route('news.show', $post->slug) : route('announcements.show', $post->slug) }}"
            }
        }
    ]
}
</script>
@endif