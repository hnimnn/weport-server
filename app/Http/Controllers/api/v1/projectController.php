<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        try {

        $projects = Project::all();
        $projectsFinal = array();
        foreach ($projects as $project) {
            $projectsFinal = $project::with(['users_liked'])->get();
        }
        return $projectsFinal;
        } catch (\Throwable $th) {
        //throw $th;
        dd($th);
        }
    }

    public function show(Project $project)
    {
        return $project;
    }
    public function store(StoreProjectRequest $request)
    {
        Project::create($request->validated());
        return response()->json("Project Created");
    }
    public function update(StoreProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return $project;
    }
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json("Project Deleted");
    }
    public function like($id, Request $request){
    try {
        $project = Project::find($id);
        if ($project) {
            $userLikedProject = $project->users_liked()->where('user_id', $request->user_id)->exists();

            if ($userLikedProject) {
                $project->users_liked()->detach($request->user_id);
            } else {
                $project->users_liked()->attach($request->user_id);
            }
        }
        return response()->json("");
    } catch (\Throwable $th) {
        //throw $th;
       dd($th);
    }}

    public function view($id){
    try {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $project = Project::find($id);
        Project::where('id', $id)->update(array('view' => $project->view + 1));

        return response()->json("");
    } catch (\Throwable $th) {
        //throw $th;
       dd($th);
    }}
    public function getProjectByUserId(){
    try {

        return Project::all();
    } catch (\Throwable $th) {
        //throw $th;
       dd($th);
    }}

}
