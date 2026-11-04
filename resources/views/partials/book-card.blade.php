<a class="book-card" href="{{ route('books.show', $book['slug']) }}" title="{{ $book['title'] }}">
    <div class="book-cover" style="--cover-bg: {{ $book['cover'] }};">
        @if (!empty($book['cover_image']))
            <img class="cover-photo" src="{{ $book['cover_image'] }}" alt="Sampul {{ $book['title'] }}">
        @endif
        <span class="cover-brand">JOGJA TANGGUH BENCANA</span>
        <span class="book-year">{{ $book['year'] }}</span>
        <strong class="cover-title">{{ str_replace('<br>', "\n", $book['cover_title']) }}</strong>
        <span class="cover-subtitle">{{ $book['cover_author'] }}</span>
        <span class="cover-ornament" aria-hidden="true">◉</span>
    </div>
    <div class="book-caption">
        <strong>{{ $book['title'] }}</strong>
        <span>{{ $book['year'] }} <span aria-hidden="true">·</span> {{ $book['publisher'] }}</span>
    </div>
</a>
