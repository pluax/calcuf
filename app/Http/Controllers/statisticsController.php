<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class statisticsController extends Controller
{
    //
    function editOneTransaction($id){
        $userId = Auth::id();

        $transId = Transaction::where('user_id',$userId)->where('id',$id)->get();

        if (($transId->count())<>0) {
            $transId = Transaction::where('user_id',$userId)->where('id',$id)->get();
            $cats = Category::where([['user_from', $userId]])->orderByDesc('id')->get();
            return view('oneTransaction', compact('transId', 'cats'));

        } else {
             return view('nodata');

        }

    }

    function deleteTransaction(Request $request){
        $userId = Auth::id();
        $input = $request->all();
        $id = $input['id'];

        Transaction::where('user_id', $userId)->where('id', $id)->delete();

        return redirect('/');
    }

    function updateTransaction(Request $request){
        $userId = Auth::id();
        $input = $request->all();

        if ($input['cost'] > 0) {
            $type = true;
        } else {
            $type = false;
        }

        Transaction::where('id', $input['id'])->
                    where('user_id', $userId)->update([
            'cost' => $input['cost'],
            'category_id' =>  $input['cat'],
            'date' =>  $input['date'],
            'type' => $type,
            'comment' => $input['comment'],
          ]);
          return redirect('/');
    }

    //     function searchComment(Request $request){
    //         $userId = Auth::id();
    //         $pages = 50;
    //         $stats = Transaction::where('user_id',$userId)->orderByDesc('date')
    //         ->leftJoin('category', 'transaction.category_id', '=', 'category.id')
    //         ->select('transaction.*', 'category.name' )
    //         ->paginate($pages);
    //         $sum = Transaction::where('user_id',$userId)->sum('cost');


    //        $graphs = Transaction::where('user_id', 1)->where('cost','<', 0)
    //        ->selectRaw("SUM(cost) as co")
    //        ->selectRaw("category_id as cat")
    //        ->groupBy('category_id')->orderBy('co')->get();

    //        $graphsPlus = Transaction::where('user_id', 1)->where('cost','>', 0)
    //        ->selectRaw("SUM(cost) as co")
    //        ->selectRaw("category_id as catPlus")
    //        ->groupBy('category_id')->orderByDesc('co')->get();

    //        $subSum = Transaction::where('user_id',$userId)->where('cost','<', 0)->sum('cost');
    //        $subSumPlus = Transaction::where('user_id',$userId)->where('cost','>', 0)->sum('cost');

    //        foreach ( $graphs as $graph) {
    //         $graph['cat'] = Category::select('name','color')->where('id', $graph['cat'])->first();
    //         $graph['percent'] =  round((($graph->co / $subSum) * 100), 2);
    //         $graph['co'] =  round($graph['co'],2);
    //        }

    //        foreach ( $graphsPlus as $graph) {
    //         $graph['cat'] = Category::select('name','color')->where('id', $graph['catPlus'])->first();
    //         $graph['percent'] =  round((($graph->co / $subSumPlus) * 100), 2);
    //         $graph['co'] =  round($graph['co'],2);
    //         return view('home', compact('stats','dates','months','days','years','cats','sum','graphs', 'graphsPlus' ));
    //     }
    // }
}
