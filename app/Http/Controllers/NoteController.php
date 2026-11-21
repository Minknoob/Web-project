<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
        ]);
        $search = trim($validated['search'] ?? '');

        $notes = Auth::user()
            ->notes()
            ->with('images')
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('pinned_at')
            ->orderByDesc('updated_at')
            ->get();

        if ($request->wantsJson()) {
            return response()->json(['notes' => $notes]);
        }

        return view('home', compact('notes'));
    }

    public function show(Note $note): JsonResponse
    {
        $this->authorizeNote($note);
        $note->load('images');

        return response()->json(['note' => $note]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        $title = trim((string) ($validated['title'] ?? ''));
        $content = trim((string) ($validated['content'] ?? ''));

        if ($title === '' && $content === '') {
            return response()->json([
                'message' => 'Vui lòng nhập tiêu đề hoặc nội dung ghi chú.',
            ], 422);
        }

        $note = Auth::user()->notes()->create([
            'title' => $title !== '' ? $title : 'Ghi chú mới',
            'content' => $content,
        ]);

        return response()->json([
            'message' => 'Đã lưu ghi chú.',
            'note' => $note,
        ], 201);
    }

    public function togglePin(Note $note): JsonResponse
    {
        $this->authorizeNote($note);

        $isPinned = ! $note->is_pinned;
        $note->update([
            'is_pinned' => $isPinned,
            'pinned_at' => $isPinned ? now() : null,
        ]);

        return response()->json([
            'message' => $isPinned ? 'Đã ghim ghi chú.' : 'Đã bỏ ghim ghi chú.',
            'pinned' => $isPinned,
        ]);
    }

    public function update(Request $request, Note $note): JsonResponse
    {
        $this->authorizeNote($note);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        $title = trim((string) ($validated['title'] ?? ''));
        $content = trim((string) ($validated['content'] ?? ''));

        if ($title === '' && $content === '') {
            return response()->json([
                'message' => 'Vui lòng nhập tiêu đề hoặc nội dung ghi chú.',
            ], 422);
        }

        $note->update([
            'title' => $title !== '' ? $title : 'Ghi chú mới',
            'content' => $content,
        ]);

        return response()->json([
            'message' => 'Đã cập nhật ghi chú.',
            'note' => $note,
        ]);
    }

    public function destroy(Note $note): JsonResponse
    {
        $this->authorizeNote($note);

        foreach ($note->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $note->delete();

        return response()->json(['message' => 'Đã xóa ghi chú.']);
    }

    private function authorizeNote(Note $note): void
    {
        abort_unless($note->user_id === Auth::id(), 403);
    }
}
