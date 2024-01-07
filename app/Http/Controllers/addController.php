<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class addController extends Controller
{
    //
    public function add(){
    $userId = Auth::id();
    $cats = Category::where([['user_from', $userId]])->orderByDesc('id')->get();

    return view('add', compact('cats'));
    }

    public function addCategory(Request $request){
     $userId = Auth::id();
     $input = $request->all();

     $name = $input['name'];
     $comment = $input['comment'];
     $color = $input['color'];

     Category::create([
         'name' => $name,
         'comment' => $comment,
         'user_from' => $userId,
         'color' => $color,
     ]);

     return back()->withInput();
    }


    public function addTransaction(Request $request){
        $userId = Auth::id();
        $input = $request->all();

        $cost = $input['cost'];

        if ($input['plus']=='minus') {
            $type = false;
            $cost = $cost*(-1);
        } else {
            $type = true;
        }


        Transaction::create([
            'cost' => $cost,
            'category_id' => $input['cat'],
            'date' => $input['date'],
            'type' => $type,
            'comment' => $input['comment'],
            'user_id' => $userId,

        ]);

        return back()->withInput();

    }
}
