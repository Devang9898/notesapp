<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Note::where('user_id', Auth::id());

            // Apply filters
            $query = $this->applyFilters($request, $query);

            $notes = $query->paginate(5);
            return view('notes.index', compact('notes'));

        } catch (Exception $e) {
            Log::error('Error fetching notes: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while fetching notes.');
        }
    }
    
    private function applyFilters(Request $request, $query)
    {
        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sorting
        switch ($request->input('sort')) {
            case 'name':
                $query->orderBy('title');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        return $query;
    }
    
    public function create()
    {
        return view('notes.create');
    }
    public function liveSearch(Request $request)
    {
        try {
            $search = $request->input('query');

            $notes = Note::where('user_id', Auth::id())
                ->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'title']); // return minimal data for autocomplete

            return response()->json($notes);

        } catch (Exception $e) {
            Log::error('Live search error: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed'], 500);
        }
    }
 

    public function search(Request $request)
    {
        $query = $request->get('query');

        $notes = \App\Models\Note::where('user_id', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'title']);

        return response()->json($notes);
    }
    public function uploadAttachment(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ]);

        $path = $request->file('file')->store('attachments', 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $path)
        ]);
    }



    // public function search(Request $request)
    // {
    //     $query = $request->get('query');

    //     $notes = \App\Models\Note::where('user_id', Auth::id())
    //         ->where(function($q) use ($query) {
    //             $q->where('title', 'like', "%{$query}%")
    //             ->orWhere('content', 'like', "%{$query}%");
    //         })
    //         ->limit(10)
    //         ->get(['id', 'title']);

    //     return response()->json($notes);
    // }
    public function store(StoreNoteRequest $request)
    {
        try {
            Note::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'content' => $request->content,
                'tags' => $request->tags,
                'is_favorite' => $request->has('is_favorite'),
                'status' => $request->status,
                'reminder_at' => $request->reminder_at,
                'attachments' => $request->attachments,
            ]);

            return redirect()->route('notes.index')->with('success', 'Note created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating note: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create the note.');
        }
    }

    public function show(Note $note)
    {
        try {
            $this->authorizeNote($note);
            return view('notes.show', compact('note'));
        } catch (Exception $e) {
            Log::error('Error showing note: ' . $e->getMessage());
            return redirect()->route('notes.index')->with('error', 'Unable to display the note.');
        }
    }

    public function edit(Note $note)
    {
        try {
            $this->authorizeNote($note);
            return view('notes.edit', compact('note'));
        } catch (Exception $e) {
            Log::error('Error editing note: ' . $e->getMessage());
            return redirect()->route('notes.index')->with('error', 'Unable to edit the note.');
        }
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        try {
            $this->authorizeNote($note);

            $note->update([
                'title' => $request->title,
                'content' => $request->content,
                'tags' => $request->tags,
                'is_favorite' => $request->has('is_favorite'),
                'status' => $request->status,
                'reminder_at' => $request->reminder_at,
                'attachments' => $request->attachments,
            ]);

            return redirect()->route('notes.index')->with('success', 'Note updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating note: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update the note.');
        }
    }

    public function destroy(Note $note)
    {
        try {
            $this->authorizeNote($note);
            $note->delete();

            return redirect()->route('notes.index')->with('success', 'Note deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting note: ' . $e->getMessage());
            return redirect()->route('notes.index')->with('error', 'Failed to delete the note.');
        }
    }

    public function bookmarked()
    {
        $user = Auth::user();
    
        // Assuming you have a "bookmarked" or "is_favorite" column
        $bookmarkedNotes = $user->notes()->where('is_favorite', true)->paginate(5);

    
        return view('notes.bookmarked', compact('bookmarkedNotes'));
    }

    public function toggleFavorite(Note $note)
    {
        try {
            $this->authorizeNote($note);

            $note->is_favorite = !$note->is_favorite;
            $note->save();

            return redirect()->route('notes.index')->with('success', 'Bookmark status updated.');
        } catch (Exception $e) {
            Log::error('Error toggling favorite: ' . $e->getMessage());
            return redirect()->route('notes.index')->with('error', 'Failed to update bookmark status.');
        }
    }

    private function authorizeNote(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }
    }

    // public function togglePin(Note $note)
    // {
    //     try {
    //         $this->authorizeNote($note);

    //         $pinnedNotes = session()->get('pinned_notes', []);

    //         if (in_array($note->id, $pinnedNotes)) {
    //             $pinnedNotes = array_diff($pinnedNotes, [$note->id]);
    //         } else {
    //             $pinnedNotes[] = $note->id;
    //         }

    //         session()->put('pinned_notes', $pinnedNotes);

    //         return redirect()->route('notes.index')->with('success', 'Pin status updated.');
    //     } catch (Exception $e) {
    //         Log::error('Error toggling pin: ' . $e->getMessage());
    //         return redirect()->route('notes.index')->with('error', 'Failed to update pin status.');
    //     }
    // }
}
