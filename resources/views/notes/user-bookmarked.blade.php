@extends('layouts.app')

@section('content')
    <h2 class="text-xl font-bold mb-4">My Bookmarked Notes</h2>

    @forelse ($bookmarkedNotes as $note)
        <div class="border p-3 rounded mb-2 bg-white shadow-sm">
            <h3 class="text-lg font-semibold">
                <a href="{{ route('notes.show', $note->id) }}" class="hover:underline">{{ $note->title }}</a>
            </h3>
            <p class="text-gray-600 mt-1 whitespace-pre-line">
                {{ \Illuminate\Support\Str::limit($note->content, 100) }}
            </p>
        </div>
    @empty
        <p class="text-gray-500">You haven't bookmarked any notes yet.</p>
    @endforelse

    {{ $bookmarkedNotes->links() }}
@endsection
