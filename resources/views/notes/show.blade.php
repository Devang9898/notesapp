@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-3xl font-bold">{{ $note->title }}</h2>

    {{-- Render rich text HTML content safely --}}
    <div class="text-gray-600 mt-2 whitespace-pre-wrap">{!! $note->content !!}</div>

    <div class="mt-4">
        <p><strong>Tags:</strong> {{ $note->tags ?? 'None' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($note->status) }}</p>
        <p><strong>Favorite:</strong> {{ $note->is_favorite ? 'Yes' : 'No' }}</p>
        <p><strong>Reminder:</strong> {{ $note->reminder_at ?? 'N/A' }}</p>
        <p><strong>Attachments:</strong> {{ $note->attachments ?? 'N/A' }}</p>
    </div>

    <div class="mt-6">
        <a href="{{ route('notes.edit', $note) }}" class="text-blue-500">Edit</a> |
        <a href="{{ route('notes.index') }}" class="text-gray-600">Back to Notes</a>
    </div>
</div>
@endsection
