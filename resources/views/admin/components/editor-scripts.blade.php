@once
    @push('tinymce-scripts')
        <script defer src="/vendor/tinymce/tinymce.min.js"></script>
        <script defer src="{{ asset('js/admin-tinymce.js') }}"></script>
    @endpush
@endonce
