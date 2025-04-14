@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    

    <!-- 🔍 Live Search -->
    <div class="relative mb-8">
        <input type="text" id="noteSearch" placeholder="Search notes..."
            class="w-full border border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200" />
        
        <ul id="autocomplete-results"
            class="absolute bg-white border border-gray-300 mt-1 rounded-md shadow-lg max-h-60 overflow-y-auto hidden w-full z-50">
        </ul>
    </div>

    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-3xl font-bold text-gray-800">📝 My Notes</h2>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('notes.index') }}" class="flex items-center gap-2">

                <select name="sort"
                    class="border border-gray-300 px-3 py-1.5 rounded-md shadow-sm focus:ring-2 focus:ring-blue-300">
                    <option value="">Sort by Latest</option>
                    <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Title A-Z</option>
                </select>

                <button type="submit"
                    class="bg-blue-500 text-white px-3 py-1.5 rounded-md hover:bg-blue-600 transition">Filter</button>

                    <a href="{{ route('notes.index') }}">
    <button class="bg-gray-200 text-gray-800 px-3 py-1.5 rounded-md hover:bg-gray-300 transition">Reset</button>
</a>
            </form>

            <a href="{{ route('notes.create') }}"
                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">+ New Note</a>

                

<a href="{{ route('notes.bookmarked') }}">
    <button class="bg-yellow-100 text-yellow-800 px-3 py-1.5 rounded-md hover:bg-yellow-200 transition">⭐ Bookmarked</button>
</a>
        </div>
    </div>

    <!-- Notes List -->
    <div class="space-y-4">
        @forelse ($notes as $note)
            <div class="bg-white border border-gray-200 p-5 rounded-lg shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $note->title }}</h3>
                        @if($note->is_favorite)
                            <span class="text-yellow-400">&#9733;</span>
                        @else
                            <span class="text-gray-300">&#9734;</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 text-xl">
                        <a href="{{ route('notes.show', $note) }}" class="text-blue-500 hover:text-blue-700" title="View">🔍</a>
                        <a href="{{ route('notes.edit', $note) }}" class="text-green-500 hover:text-green-700" title="Edit">✏️</a>

                        <form action="{{ route('notes.destroy', $note) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this note?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">🗑️</button>
                        </form>

                        <form action="{{ route('notes.toggleFavorite', $note) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="{{ $note->is_favorite ? 'text-yellow-400' : 'text-gray-400' }} hover:text-yellow-500" title="{{ $note->is_favorite ? 'Unbookmark' : 'Bookmark' }}">
                                {{ $note->is_favorite ? '🔖' : '📌' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
        <div class="text-center text-gray-500 text-lg mt-12">
    No notes found. Try adding a new one!
    <a href="{{ route('notes.create') }}"
       class="inline-block ml-2 bg-blue-500 text-white px-4 py-2 rounded-full shadow hover:bg-blue-600 transition duration-200">
        ✨ 
    </a>
</div>


        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $notes->appends(request()->query())->links() }}
    </div>
</div>

<!-- Live Search Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('noteSearch');
        const resultsList = document.getElementById('autocomplete-results');
        let results = [];
        let selectedIndex = -1;

        searchInput.addEventListener('input', function () {
            const query = this.value.trim();
            selectedIndex = -1;

            if (query.length < 2) {
                resultsList.classList.add('hidden');
                resultsList.innerHTML = '';
                return;
            }

            fetch(`{{ route('notes.search') }}?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    results = data;
                    resultsList.innerHTML = '';

                    if (results.length === 0) {
                        resultsList.innerHTML = '<li class="px-4 py-2 text-gray-500">No matching notes found</li>';
                    } else {
                        results.forEach((note, index) => {
                            const li = document.createElement('li');
                            li.textContent = note.title;
                            li.className = 'px-4 py-2 hover:bg-blue-100 cursor-pointer';
                            li.dataset.index = index;

                            li.addEventListener('click', () => {
                                window.location.href = `/notes/${note.id}`;
                            });

                            resultsList.appendChild(li);
                        });
                    }

                    resultsList.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Live search failed:', err);
                    resultsList.classList.add('hidden');
                });
        });

        searchInput.addEventListener('keydown', function (e) {
            const items = resultsList.querySelectorAll('li');

            if (results.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % results.length;
                updateHighlight(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + results.length) % results.length;
                updateHighlight(items);
            } else if (e.key === 'Enter' && selectedIndex > -1) {
                e.preventDefault();
                window.location.href = `/notes/${results[selectedIndex].id}`;
            }
        });

        function updateHighlight(items) {
            items.forEach((item, idx) => {
                if (parseInt(item.dataset.index) === selectedIndex) {
                    item.classList.add('bg-blue-100');
                } else {
                    item.classList.remove('bg-blue-100');
                }
            });
        }

        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !resultsList.contains(e.target)) {
                resultsList.classList.add('hidden');
            }
        });
    });
</script>



@endsection
