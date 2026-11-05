<a class="book-card" href="{{ $book['url'] }}" title="{{ $book['title'] }}">
    <div class="book-cover anyflip-book-cover">
        <img class="cover-photo" src="{{ $book['cover'] }}" alt="Sampul {{ $book['title'] }}" loading="lazy">
    </div>
    <div class="book-caption">
        <strong>{{ $book['title'] }}</strong>
        <span>Buka flipbook di AnyFlip</span>
    </div>
</a>
