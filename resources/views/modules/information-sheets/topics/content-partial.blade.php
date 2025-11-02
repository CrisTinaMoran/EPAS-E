<link rel="stylesheet" href="{{ asset('css/pages/content-partial.css') }}">
<div class="topic-content">
    <div class="topic-header mb-4">
        <h2 class="topic-title">{{ $topic->title }}</h2>
        <div class="topic-meta d-flex align-items-center gap-3 text-muted">
            <span class="topic-number">Topic {{ $topic->topic_number }}</span>
            <span class="topic-order">Order: {{ $topic->order }}</span>
        </div>
    </div>
    
    <div class="content-body basic-formatting">
        {!! $topic->content !!}
    </div>
</div>

