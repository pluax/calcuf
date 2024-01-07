<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class userController extends Controller
{
    //
    function editUser(){
        $userId = Auth::id();

        $user = User::where('id',$userId)->first();
        return view('user', compact('user'));
    }

    function updateUser(Request $request){
        $userId = Auth::id();
        $input = $request->all();

        User::where('id', $input['id'])->
        update([
        'name' => $input['name'],
        'email' =>  $input['email'],
        'surname' =>  $input['surname'],
        ]);

        return redirect('/user');
    }
}
