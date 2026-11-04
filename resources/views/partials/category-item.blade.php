<a class="category-item" href="{{ route('categories.show', $category['slug']) }}">
    <span class="category-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
    <div class="category-name">{{ $category['name'] }}</div>
    <p class="category-description">{{ $category['description'] }}</p>
    <span class="category-collection">{{ $category['collection'] }}</span>
    @isset($category['total_books'])
        <span class="category-total">{{ $category['total_books'] }} koleksi</span>
    @endisset
    <span class="category-arrow" aria-hidden="true">↗</span>
</a>
