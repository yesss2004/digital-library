<a class="book-card" href="{{ $book['url'] }}" title="{{ $book['title'] }}">
    <div class="book-cover anyflip-book-cover">
        <img class="cover-photo" src="{{ $book['cover'] }}" alt="Sampul {{ $book['title'] }}" loading="lazy">
    </div>
    <div class="book-caption">
        <strong>{{ $book['title'] }}</strong>
        <span class="book-card-action">
            Buka flipbook di AnyFlip
            <svg viewBox="0 0 16 16" fill="none" aria-hidden="true">
                <path d="M3 8h9m-4-4 4 4-4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
    </div>
</a>
