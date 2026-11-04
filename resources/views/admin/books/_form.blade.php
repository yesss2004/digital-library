@if ($errors->any())
    <div class="admin-alert admin-alert-error" role="alert">
        <strong>Periksa kembali data buku:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form class="admin-form admin-book-form" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if (! empty($method) && $method !== 'POST')
        @method($method)
    @endif

    <label class="admin-field-wide">
        Judul buku
        <input type="text" name="title" value="{{ old('title', $book?->title) }}" maxlength="255" required autofocus>
    </label>
    <label>
        Penulis / instansi
        <input type="text" name="author" value="{{ old('author', $book?->author) }}" maxlength="255" required>
    </label>
    <label>
        Penerbit
        <input type="text" name="publisher" value="{{ old('publisher', $book?->publisher) }}" maxlength="255" required>
    </label>
    <label>
        Tahun terbit
        <input type="number" name="year" value="{{ old('year', $book?->year ?? now()->year) }}" min="1900" max="{{ now()->year + 1 }}" required>
    </label>
    <label>
        Jumlah halaman <span class="admin-optional">(opsional)</span>
        <input type="number" name="pages" value="{{ old('pages', $book?->pages) }}" min="1" max="65535">
    </label>
    <label class="admin-field-wide">
        Kategori
        <select name="category" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category['slug'] }}" @selected(old('category', $book?->category) === $category['slug'])>{{ $category['name'] }}</option>
            @endforeach
        </select>
    </label>
    <label class="admin-field-wide">
        Deskripsi
        <textarea name="description" rows="5" maxlength="5000" required>{{ old('description', $book?->description) }}</textarea>
    </label>
    <label class="admin-field-wide">
        Tautan isi buku / flipbook <span class="admin-optional">(opsional, URL HTTP/HTTPS)</span>
        <input type="url" name="reader_url" value="{{ old('reader_url', $book?->reader_url) }}" maxlength="2048" placeholder="https://...">
    </label>
    <label class="admin-field-wide">
        Gambar sampul
        <span class="admin-optional">{{ $book ? '(biarkan kosong jika tidak diganti, JPG/PNG/WEBP maksimal 2 MB)' : '(opsional, JPG/PNG/WEBP maksimal 2 MB)' }}</span>
        <input type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        @if ($book && $book->cover_image)
            <span class="admin-current-file">
                Sampul saat ini:
                <a href="{{ Storage::disk('public')->url($book->cover_image) }}" target="_blank" rel="noopener noreferrer">{{ basename($book->cover_image) }}</a>
            </span>
        @endif
    </label>
    <label class="admin-field-wide">
        Berkas PDF <span class="admin-optional">{{ $book ? '(biarkan kosong jika tidak diganti, maksimal 20 MB)' : '(opsional, maksimal 20 MB)' }}</span>
        <input type="file" name="pdf_file" accept=".pdf,application/pdf">
        @if ($book && $book->pdf_file)
            <span class="admin-current-file">
                PDF saat ini:
                <a href="{{ route('books.download', $book->slug) }}">{{ basename($book->pdf_file) }}</a>
            </span>
        @endif
    </label>
    <div class="admin-field-wide admin-form-footer">
        <button class="btn" type="submit">{{ $submitLabel }}</button>
    </div>
</form>
