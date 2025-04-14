@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Edit Note</h2>

    <form method="POST" action="{{ route('notes.update', $note) }}">
        @csrf
        @method('PUT')

        @include('notes.partials.form', ['note' => $note])

        {{--<button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Note</button>--}}
    </form>
</div>
@endsection
