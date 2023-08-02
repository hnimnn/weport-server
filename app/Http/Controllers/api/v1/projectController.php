<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'view']]);
    }
    public function index()
    {
        try {

            $projects = Project::all();
            $projectsFinal = array();
            foreach ($projects as $project) {
                $projectsFinal = $project::select('id', 'name', 'description', 'user_id','thumbnail', 'view', 'tags', 'user_bought', 'price', 'status', 'created_at', 'updated_at')->with(['user'])->with(['users_liked'])->with('users_saved')->where('status', 'published')->get();
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
            return Project::select('id', 'name', 'description', 'user_id','thumbnail', 'view', 'tags', 'user_bought', 'price', 'status', 'created_at', 'updated_at')->where('id', $id)->with('users_liked')->with('users_saved')->first();
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
     public function dataUpdateProject($id)
    {
        try {
            return Project::where('id', $id)->with('users_liked')->with('users_saved')->first();
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function update(StoreProjectRequest $request, Project $project)
    {
        if ($request->thumbnail) {
            $fileURL = Cloudinary::upload($request->file('thumbnail')->getRealPath())->getSecurePath();
            $urlParts = explode("/", $project->thumbnail);
            $imageNameWithExtension = end($urlParts);

            $imageParts = explode(".", $imageNameWithExtension);

            $imageNameWithoutExtension = $imageParts[0];
            if ($imageNameWithoutExtension) {
                Cloudinary::destroy($imageNameWithoutExtension);
            }
            $project->update($request->validated());

            $request->request->add(['thumbnail' => $fileURL]);
            $project->thumbnail = $fileURL;
            $project->save();
        }

        return $project;
    }
    public function destroy(Project $project)
    {

        $urlParts = explode("/", $project->thumbnail);
        $imageNameWithExtension = end($urlParts);

        $imageParts = explode(".", $imageNameWithExtension);

        $imageNameWithoutExtension = $imageParts[0];
        if ($imageNameWithoutExtension) {
            Cloudinary::destroy($imageNameWithoutExtension);
        }
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

    public function savedProject()
    {

        try {
            $currentUser = Auth::user();
            $user = User::with('users_saved')->where('id', $currentUser->id)->first();
            $projectsFinal = array();
            foreach ($user->users_saved as $project) {
                $projectData = Project::with(['user', 'users_liked', 'users_saved'])
                    ->where('id', $project->id)
                    ->where('status', 'published')
                    ->first();

                if ($projectData) {
                    $projectsFinal[] = $projectData; // Add the project data to the projectsFinal array
                }
            }
            return $projectsFinal;
        } catch (\Throwable $th) {
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
            if (is_null($project->user_bought) &&  $project->user_id !== $currentUser->id) {
                if ($currentUser->cash >= $project->price) {
                    $project->user_bought = $currentUser->id;
                    $project->save();
                    $userBuy->cash = $userBuy->cash - $project->price;
                    $userBuy->save();
                    $userSell->cash = $userSell->cash + $project->price * 85 / 100;
                    $userSell->save();
                    $data = array(
                        'name' => $userBuy->name,
                        'email' => $userBuy->email,
                        'project_name' => $project->name,
                        'price' => $project->price,
                        'source' => $project->source,

                    );
                    Mail::send(['html' => 'mail'], $data, function ($message) use ($userBuy) {
                        $message->to($userBuy->email, $userBuy->name)->subject('Here your source');
                        $message->from('WEport@gmail.com', 'WEport');
                    });
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

    //Admin --------------------
    public function allProjects()
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

    public function approve($id, Request $request)
    {
        try {
            // $project = Project::find($id);
            Project::where('id', $id)->update(array('status' => $request->status));

            return response()->json("Finish");
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
}
