<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index() {
        $comments = Comment::paginate(10);
        return $comments;
    }

    public function store(Post $post, Request $request)
    {
        $logging_user = User::where('api_token', $request->api_token)->first();
        if ($logging_user) {
            $validation = Validator::make($request->all(), ['comment' => ['required', 'max:500', 'min:20']]);
            if ($validation->fails()) {
                return response()->json(['error' => $validation->errors()]);
            }
            else {
                $post->comments()->create([
                    'user_id' => $logging_user->id,
                    'comment' => $request->comment
                ]);
                $post->save();
                return $post;
            }
        }
        return response()->json([
            'error' => 'There is something wrong, please try again!',
            'code' => 401
        ], status: 401);
    }


    public function update(Post $post, Comment $comment, Request $request)
    {
        $logging_user = User::where([
            'id'=> $comment->user_id,
            'api_token' => $request->api_token
            ])->first();
        if($logging_user) {
            $validation = Validator::make($request->all(), ['comment' => ['required', 'max:500', 'min:20']]);
            if ($validation->fails()) {
                return response()->json(['error' => $validation->errors()]);
            }
            else {
                $post->comments()->where([
                    'user_id' => $logging_user->id,
                    'id' => $comment->id
                    ])->update(['comment' => $request->comment]);
                return 'The comment has been updated successfully!';
            }
        }
        return response()->json([
            'error' => 'There is something wrong, please try again!',
            'code' => 401
        ], status: 401);
    }


    public function destroy(Comment $comment, Request $request) {
        $logging_user = User::where([
            'api_token' => $request->api_token,
            'id' => $comment->user_id
            ])->first();
        if($logging_user)
        {
            $comment->delete();
            return 'The comment has been deleted successfully.';
        }
        return response()->json([
            'error' => 'There is something wrong, please try again!',
            'code' => 401
        ], status: 401);

    }
}
