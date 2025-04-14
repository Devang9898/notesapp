@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Create Note</h2>

    <form method="POST" action="{{ route('notes.store') }}">
        @csrf

        @include('notes.partials.form', ['note' => null])

       {{-- <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save Note</button>--}}
    </form>
</div>
@endsection
