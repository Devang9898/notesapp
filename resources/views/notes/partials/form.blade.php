@php
    $isEdit = isset($note);
@endphp

{{-- Include Trix Editor Assets --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>

<input type="text" name="title" placeholder="Title" value="{{ old('title', $note->title ?? '') }}"
    class="w-full p-3 mb-3 border rounded focus:ring-2 focus:ring-green-400" required>

{{-- Visible Rich Text Editor for Note Content --}}
<label class="block text-gray-700 font-semibold mb-1">Your Note</label>
<input id="content" type="hidden" name="content" value="{{ old('content', $note->content ?? '') }}">
<trix-editor input="content"
    class="trix-content w-full p-3 mb-3 border rounded focus:ring-2 focus:ring-green-400 bg-white"></trix-editor>

<input type="text" name="tags" placeholder="Tags (comma separated)" value="{{ old('tags', $note->tags ?? '') }}"
    class="w-full p-3 mb-3 border rounded focus:ring-2 focus:ring-green-400">

<select name="status" class="w-full p-3 mb-3 border rounded focus:ring-2 focus:ring-green-400" required>
    <option value="active" {{ old('status', $note->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
    <option value="archived" {{ old('status', $note->status ?? '') == 'archived' ? 'selected' : '' }}>Archived</option>
</select>

<label class="flex items-center mb-3">
    <input type="checkbox" name="is_favorite" value="1" 
        {{ old('is_favorite', $note->is_favorite ?? false) ? 'checked' : '' }}
        class="mr-2">
    <span>Mark as favorite</span>
</label>




<input type="datetime-local" name="reminder_at" value="{{ old('reminder_at', $note->reminder_at ?? '') }}"
    class="w-full p-3 mb-3 border rounded focus:ring-2 focus:ring-green-400">

<input type="text" name="attachments" placeholder="Attachment (URL or text)" value="{{ old('attachments', $note->attachments ?? '') }}"
    class="w-full p-3 mb-3 border rounded focus:ring-2 focus:ring-green-400">



    <div class="flex justify-between items-center mt-4">
    <a href="{{ route('notes.index') }}"
        class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
        Back
    </a>

    <button type="submit"
        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
        {{ $isEdit ? 'Update Note' : 'Save Note' }}
    </button>
</div>

