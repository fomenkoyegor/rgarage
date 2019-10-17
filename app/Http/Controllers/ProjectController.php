<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth:api',
//            ['except' => []]
//        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
//        $projects = Project::orderBy('created_at', 'desc')->get();
        $projects = $user->projects()->latest()->get();
        return response($projects, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = (auth()->user())->id;
        $request->validate([
            'name' => 'required|max:255|min:3'
        ]);
        $name = $request->input('name');
        $project = new Project();
        $project->name = $name;
        $project->user_id = $user_id;
        $project->save();
        return response($project, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $user_id = (auth()->user())->id;
        $project_user_id = $project->user_id;
        if ($user_id !== $project_user_id) {
            return response([
                'data' => 'project not found',
                'status' => 'fail',
            ], 201);
        } else {
            $request->validate([
                'name' => 'required|max:255|min:3'
            ]);
            $name = $request->input('name');
            $project->name = $name;
            $project->save();
            return response($project, 202);
        }
    }


    public function destroy(Project $project)
    {
        $user_id = (auth()->user())->id;
        $project_user_id = $project->user_id;
        if ($user_id !== $project_user_id) {
            return response([
                'data' => 'project not found',
                'status' => 'fail',
            ], 203);
        } else {
            $project->delete();
            return response($project, 202);
        }
    }
}
