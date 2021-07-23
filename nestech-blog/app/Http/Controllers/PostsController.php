<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;

class PostsController extends Controller
{
    public function get_others_posts()
    {
        $user_id = Auth::id();
        $posts = Post::where('user_id', '!=', $user_id)->get();
        return response()->json(['message' => 'Done','body' => $posts], 200);
    }

    public function get_my_posts()
    {
        $user_id = Auth::id();
        $posts = Post::where('user_id', '=', $user_id)->get();
        return response()->json(['message' => 'Done','body' => $posts], 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::All();
        return response()->json(['message' => 'Done','body' => $posts], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        PostsController::my_validate($request);
        $new_post = new Post();
        $new_post->user_id = Auth::id();
        $new_post->title = $request->title;
        $new_post->content = $request->content;
        $new_post->save();
        return response()->json(['message' => 'Created','body' => $new_post], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $my_post = Post::Find($id);
        if($my_post)
        {
            return response()->json(['message' => 'Done','body' => $my_post], 200);
        }
        else
        {
            return response()->json(['message' => 'Not Found','body' => null], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $my_post = Post::Find($id);
        if(!$my_post)
        {
            return response()->json(['message' => 'Not Found','body' => null], 404);
        }
        $user_id = Auth::id();
        if($my_post->user_id == $user_id)
        {
            PostsController::my_validate($request);
            $my_post->title = $request->title;
            $my_post->content = $request->content;
            $my_post->save();
            return response()->json(['message' => 'Post Updated','body' => $my_post], 201);
        }
        else
        {
            return response()->json(['message' => 'Unauthorized','body' => null], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $my_post = Post::Find($id);
        if(!$my_post)
        {
            return response()->json(['message' => 'Not Found','body' => null], 404);
        }
        $user_id = Auth::id();
        if($my_post->user_id == $user_id)
        {
            $my_post->delete();
            return response()->json(['message' => 'Post Deleted','body' => null], 204);
        }
        else
        {
            return response()->json(['message' => 'Unauthorized','body' => null], 401);
        }
    }
    
    public static function my_validate($request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);
    }
    
}
