<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function download(Note $note)
    {
        $attendanceNames = [];
        if ($note->attendance && is_array($note->attendance)) {
            $attendanceNames = User::with('member')
                ->whereIn('id', $note->attendance)
                ->get()
                ->map(fn($user) => $user->name)
                ->toArray();
        }

        return view('notes.pdf', [
            'note' => $note,
            'attendanceNames' => $attendanceNames,
        ]);
    }
}
