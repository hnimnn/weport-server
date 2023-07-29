<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

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
}
