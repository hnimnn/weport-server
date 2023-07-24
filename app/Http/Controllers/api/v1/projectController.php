<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class projectController extends Controller
{
    public function index(){
        return Project::all();
    }

    public function show(Project $project){
        return $project;
    }
    public function store(StoreProjectRequest $request){
        Project::create($request->validated());
        return response()->json("Project Created");
    }
    public function update(StoreProjectRequest $request, Project $project){
        $project->update($request->validated());
        return response()->json("Project Updated");
    }
     public function destroy( Project $project){
        $project->delete();
        return response()->json("Project Deleted");
    }
}
