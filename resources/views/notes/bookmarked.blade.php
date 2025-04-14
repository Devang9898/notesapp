@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold">⭐ Bookmarked Notes</h2>

        <a href="{{ route('notes.index') }}" class="text-blue-500 hover:underline"><button>← Back to All Notes</button></a>
    </div>

    @if($bookmarkedNotes->count())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($bookmarkedNotes as $note)
                <div class="bg-white p-6 rounded shadow">
                    <h3 class="text-xl font-bold flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            {{ $note->title }}
                            <span class="text-yellow-500">&#9733;</span>
                        </span>
                        <!-- Actions -->
                        <span class="flex gap-3 text-lg">
                            <!-- View -->
                            <a href="{{ route('notes.show', $note->id) }}" class="text-blue-500 hover:text-blue-700" title="View">🔍</a>

                            <!-- Edit -->
                            <a href="{{ route('notes.edit', $note->id) }}" class="text-green-500 hover:text-green-700" title="Edit">✏️</a>

                            <!-- Delete -->
                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST"
                                onsubmit="return confirm('Delete this note?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">🗑️</button>
                            </form>

                            <!-- Unbookmark -->
                            <form action="{{ route('notes.toggleFavorite', $note->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" title="Unbookmark"
                                    class="text-yellow-500 hover:text-yellow-600">🔖</button>
                            </form>
                        </span>
                    </h3>

                    
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $bookmarkedNotes->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="bg-gray-100 p-6 rounded text-center shadow text-gray-500">
            No bookmarked notes found.
        </div>
    @endif
</div>
@endsection
