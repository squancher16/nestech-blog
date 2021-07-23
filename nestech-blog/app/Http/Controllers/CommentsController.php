<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\Comment;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        CommentsController::my_validate($request);
        $new_comment = new Comment();
        $new_comment->post_id = $request->post_id;
        $new_comment->user_id = Auth::id();
        $new_comment->content = $request->content;
        $new_comment->save();
        return response()->json(['message' => 'Created','body' => $new_comment], 201);
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
            $my_comment = $my_post->comments()->get();
            return response()->json(['message' => 'Done','body' => $my_comment], 200);          
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
        $my_comment = Comment::Find($id);
        if(!$my_comment)
        {
            return response()->json(['message' => 'Not Found','body' => null], 404);
        }
        $user_id = Auth::id();
        if($my_comment->user_id == $user_id)
        {
            CommentsController::my_validate($request);
            $my_comment->content = $request->content;
            $my_comment->save();
            return response()->json(['message' => 'Comment Updated','body' => $my_comment], 201);
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
        $my_comment = Comment::Find($id);
        if(!$my_comment)
        {
            return response()->json(['message' => 'Not Found','body' => null], 404);
        }
        $user_id = Auth::id();
        if($my_comment->user_id == $user_id)
        {
            $my_comment->delete();
            return response()->json(['message' => 'Comment Deleted','body' => null], 204);
        }
        else
        {
            return response()->json(['message' => 'Unauthorized','body' => null], 401);
        }
    }

    public static function my_validate($request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required',
        ]);
    }
}
