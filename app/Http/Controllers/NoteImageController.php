<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\NoteImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteImageController extends Controller
{
    public function store(Request $request, Note $note): JsonResponse
    {
        abort_unless($note->user_id === Auth::id(), 403);

        $request->validate([
            'images' => ['required', 'array', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($note->images()->count() + count($request->file('images')) > 10) {
            return response()->json([
                'message' => 'Mỗi ghi chú chỉ được đính kèm tối đa 10 ảnh.',
            ], 422);
        }

        $uploaded = [];

        foreach ($request->file('images') as $file) {
            $path = $file->store('note-images', 'public');
            $uploaded[] = $note->images()->create(['path' => $path])->append('url');
        }

        return response()->json([
            'message' => 'Đã đính kèm ảnh.',
            'images' => $uploaded,
        ], 201);
    }

    public function destroy(Note $note, NoteImage $image): JsonResponse
    {
        abort_unless($note->user_id === Auth::id(), 403);
        abort_unless($image->note_id === $note->id, 404);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json(['message' => 'Đã xóa ảnh.']);
    }
}
