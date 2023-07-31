<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        try {

            $projects = Project::all();
            $projectsFinal = array();
            foreach ($projects as $project) {
                $projectsFinal = $project::with(['user'])->with(['users_liked'])->with('users_saved')->get();
            }

            return $projectsFinal;
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    public function show($id)
    {
        try {
            return Project::where('id', $id)->with('users_liked')->with('users_saved')->first();
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function store(StoreProjectRequest $request)
    {
        try {
            if ($request->thumbnail) {
                $fileURL = Cloudinary::upload($request->file('thumbnail')->getRealPath())->getSecurePath();
                $request->request->add(['thumbnail' => $fileURL]);
            }
            $project_id = Project::create($request->validated())->id;
            $project = Project::find($project_id);
            $project->thumbnail = $fileURL;
            $project->save();
            return response()->json("Project Created");
        } catch (\Throwable $th) {
            dd($th);
        }
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
    public function like($id)
    {
        try {
            $project = Project::find($id);
            $currentUser = Auth::user();
            if ($project) {
                $userLikedProject = $project->users_liked()->where('user_id', $currentUser->id)->exists();

                if ($userLikedProject) {
                    $project->users_liked()->detach($currentUser->id);
                } else {
                    $project->users_liked()->attach($currentUser->id);
                }
            }
            return response()->json("");
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
    public function save($id)
    {
        try {
            $project = Project::find($id);
            $currentUser = Auth::user();

            if ($project) {
                $userSavedProject = $project->users_saved()->where('user_id', $currentUser->id)->exists();

                if ($userSavedProject) {
                    $project->users_saved()->detach($currentUser->id);
                } else {
                    $project->users_saved()->attach($currentUser->id);
                }
            }
            return response()->json("");
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    public function buy($id)
    {
        try {
            $project = Project::find($id);
            $currentUser = Auth::user();
            $userBuy = User::find($currentUser->id);
            $userSell = User::find($project->user_id);
            echo ($userSell);
            if (is_null($project->user_bought) &&  $project->user_id !== $currentUser->id) {
                if ($currentUser->cash >= $project->price) {
                    $project->user_bought = $currentUser->id;
                    $project->save();
                    $userBuy->cash = $userBuy->cash - $project->price;
                    $userBuy->save();
                    $userSell->cash = $userSell->cash + $project->price * 85 / 100;
                    $userSell->save();

                    return response()->json("Bought");
                } else return response()->json("Not enough cash to pay", 401);
            }
            return response()->json("Error", 401);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }


    public function view($id)
    {
        try {
            $out = new \Symfony\Component\Console\Output\ConsoleOutput();
            $project = Project::find($id);
            Project::where('id', $id)->update(array('view' => $project->view + 1));

            return response()->json("");
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
}
