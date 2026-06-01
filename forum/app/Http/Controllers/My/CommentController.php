<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $comments = Comment::where('user_id', auth()->id())
            ->with('post')
            ->latest()
            ->paginate(15);

        return view('my.comments', compact('comments'));
    }
}
