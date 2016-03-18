<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Post;
use Session;

class PostController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //create a varible and store all the posts in it from the database

        $posts = Post::orderBy('id','desc')->paginate(5);

        //return a view and pass in the above variable
        return view('posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() { 
      
      return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
       
        //validate the data
        $this->validate($request, array(
            'title' => 'required|max:255',
            'body'  => 'required'
            ));
        
        //store in the database
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;

        $post->save();
        Session::flash('success','The blog was successfully save');

        return redirect()->route('posts.show',$post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        $post = Post::findOrFail($id);
        return view('posts.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) { 
        //find the post in the database and save as a variable
        $post = Post::find($id);


        //return the view and pass in the variable we previosly created

        return view('posts.edit',compact('post'));
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
        //Validate the data
         $this->validate($request, array(
            'title' => 'required|max:255',
            'body'  => 'required'
            ));

        //Save the data to the database
         $post = Post::find($id);
         $post->title = $request->input('title');
         $post->body = $request->input('body');

         $post->save();

        //set flash data with success message
         Session::flash('success','This post was successfully saved.');

        //redirect with flash data to posts.show
        return redirect()->route('posts.show',$post->id);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) { 

        $post = Post::find($id);

        $post->delete();

        Session::flash('success','The post was successfuly deleted');

        return redirect()->route('posts.index');
    
    }
}
