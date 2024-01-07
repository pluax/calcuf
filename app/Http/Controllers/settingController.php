<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class settingController extends Controller
{

    function setting() {
            $userId = Auth::id();

            $mainTable = Category::where('user_from',$userId)->orderBy('id')->get();
            return view('setting', compact('mainTable'));
    }


    function editOne($id) {
        $userId = Auth::id();

        $catId = Category::where('user_from',$userId)->where('id',$id)->get();

        if (($catId->count())<>0) {
            $catId = Category::where('user_from',$userId)->where('id',$id)->get();
            return view('settingOnecategory', compact('catId'));

        } else {
             return view('nodata');

        }
    }


    function updateOne(Request $request){
        $userId = Auth::id();
        $input = $request->all();
        $id = $input['id'];

        Category::where('id', $id)->update([
            'name' => $input['name'],
            'comment' =>  $input['comment'],
            'color' =>  $input['color'],
            'user_from' => $userId,
          ]);
          return redirect('/setting');
    }

    function deleteOne(Request $request){
        $userId = Auth::id();
        $input = $request->all();
        $id = $input['id'];

        $count = Category::where('user_from', $userId)->where('id', $id)->delete();

        if ($count>0) {
            Transaction::where('category_id', $id)->delete();
        }
        return redirect('/setting');

    }

}
