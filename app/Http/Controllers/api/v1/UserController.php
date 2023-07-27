<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //

    public function myProject(Request $request){
        try {
        $projects = Project::with('users_liked')->where('user_id', $request->header('user_id'))->get();
        return $projects;
        } catch (\Throwable $th) {
            dd($th);
        }

   }
}
