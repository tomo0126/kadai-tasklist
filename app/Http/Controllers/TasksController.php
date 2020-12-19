<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\User;
class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $user = \Auth::user(); 
        if(\Auth::check()){
            //$tasks = Task::all();
            $tasks = \Auth::user()->tasks()->get();
            return view('tasks.index',[
            'tasks' => $tasks,
            'user' => $user,
          ]);
        }else{
            return view('layouts.app');
        }
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        
        return view('tasks.create',[
            'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
            
            ]);
    if (\Auth::check()){
        $task = new Task;
        $user = new User;
        $task->content = $request->content;
        $task->status = $request->status;
       $task->user_id = $request->user()->id;
        $task->save();
    }  
        return redirect('/');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $task = Task::findOrFail($id);

        return view('tasks.show',[
            'task' => $task,
            ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        return view('tasks.edit',[
            'task' => $task,
            ]);
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
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを更新
         if (\Auth::id() === $task->user_id) {
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
         }
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを削除
        if (\Auth::id() === $task->user_id) {
            $micropost->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
