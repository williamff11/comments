<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentFormRequest;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function index(Comment $comment, Request $request)
    {
        return $comment->getResults($request->all());
    }

    public function store(Comment $comment, StoreCommentFormRequest $request)
    {
        $data = $request->all();

        $data['user_id'] = auth()->user()->id;

        $comment = $comment->create($data);

        return response()->json($comment, 201);
    }

    public function show($id)
    {
        return Comment::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $comment->update($request->all());

        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        $comment->delete();

        return response()->json(['success => true'], 204);
    }
}
