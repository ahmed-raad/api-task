<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index() {
        $posts = Post::paginate(5);
        return $posts;
    }

    public function user_post(User $user, Request $request) {
        $logging_user = User::where('api_token', $request->api_token)->first();
        $user_posts = $user->posts()->paginate(5);
        return $user_posts;
    }

    public function store(Request $request){
        $logging_user = User::where('api_token', $request->api_token)->first();
        if($logging_user)
        {
            $validation = Validator::make($request->all(),
            [
                'title' => ['required', 'max:30', 'min:5'],
                'body' => ['required', 'max:500', 'min:20']
            ]);

            if ($validation->fails()) {
                return response()->json(['error' => $validation->errors()]);
            }
            else {
                $new_post = $logging_user->posts()->create([
                    'title' => $request->title,
                    'body' => $request->body
                ]);
                $new_post->save();

                return $new_post;
            }
        }
        return response()->json([
            'error' => 'There is something wrong, please try again!',
            'code' => 401
        ], status: 401);
    }


    public function update(Post $post, Request $request)
    {
        $logging_user = User::where('api_token', $request->api_token)->where('id', $post->user_id)->first();
        if($logging_user) {
            $validation = Validator::make($request->all(),
            [
                'title' => ['required', 'max:30', 'min:5'],
                'body' => ['required', 'max:500', 'min:20']
            ]);

            if ($validation->fails()) {
                return response()->json(['error' => $validation->errors()]);
            }
            else {
                $updated_post = $logging_user->posts()->where('id', $post->id)->update([
                    'title' => $request->title,
                    'body' => $request->body
                ]);
                return 'The post has been updated successfully!';
            }
        }
        return response()->json([
            'error' => 'There is something wrong, please try again!',
            'code' => 401
        ], status: 401);
    }


    public function destroy(Post $post, Request $request) {
        $logging_user = User::where('api_token', $request->api_token)->where('id', $post->user_id)->first();
        if($logging_user)
        {
            $post->delete();
            return 'The post has been deleted from.';
        }
        return response()->json([
            'error' => 'There is something wrong, please try again!',
            'code' => 401
        ], status: 401);
    }
}
