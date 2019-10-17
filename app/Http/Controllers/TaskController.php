<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use App\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create(Request $request, Project $project)
    {
        $user_id = (auth()->user())->id;
        $project_user_id = $project->user_id;
        if ($user_id !== $project_user_id) {
            return response([
                'data' => 'project not found',
                'status' => 'fail',
            ], 203);
        } else {
            $request->validate([
                'name' => 'required|max:255|min:3'
            ]);
            $name = $request->input('name');
            $task = new Task();
            $task->name = $name;
            $task->project_id = $project->id;
            $task->user_id = $user_id;
            $task->save();
            return response($task, 201);
        }
    }

    public function get(Project $project)
    {

        $user_id = (auth()->user())->id;
        $project_user_id = $project->user_id;
        if ($user_id !== $project_user_id) {
            return response([
                'data' => 'task not found',
                'status' => 'fail',
            ], 203);
        } else {
            $tasks = $project->tasks()->orderByRaw('(created_at) ')->get();
            return response($tasks, 200);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $user_id = (auth()->user())->id;
        $task_user_id = $task->user_id;
        if ($user_id !== $task_user_id) {
            return response([
                'data' => 'task not found',
                'status' => 'fail',
            ], 201);
        } else {
            $request->validate([
                'name' => 'required|max:255|min:3'
            ]);
            $task->name = $request->input('name');
            $task->priority = $request->input('priority');
            $task->date = $request->input('date');
            $task->project_id = $request->input('project_id');
            $task->status = $request->input('status');
            $task->save();

            return response($task, 200);
        }

    }


    public function destroy(Task $task)
    {
        $user_id = (auth()->user())->id;
        $task_user_id = $task->user_id;
        if ($user_id !== $task_user_id) {
            return response([
                'data' => 'task not found',
                'status' => 'fail',
            ], 203);
        } else {
            $task->delete();
            return response($task, 202);
        }
    }
}
