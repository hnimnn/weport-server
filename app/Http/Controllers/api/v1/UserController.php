<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['profile']]);
    }

    public function myProject(Request $request)
    {
        try {
            $currentUser = Auth::user();
            $projects = Project::with('users_liked')->where('user_id', $currentUser->id)->get();
            return $projects;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function profile($id)
    {
        try {
            $currentUser = User::find($id);
            $projects = Project::select('id', 'name', 'description', 'user_id','thumbnail', 'view', 'tags', 'user_bought', 'price', 'status', 'created_at', 'updated_at')
            ->with(['user'])
            ->with('users_liked')
            ->where('user_id', $currentUser->id)
            ->where('status', 'published')->get();
            $data = [
                'currentUser' => $currentUser,
                'projects' => $projects,
            ];
            return $data;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
