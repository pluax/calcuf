<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $pages = 50;
        function getMonth($number){
            switch ($number) {
                case '01':
                    return "Январь";
                case '02':
                    return "Февраль";
                case '03':
                    return "Март";
                case '04':
                    return "Апрель";
                case '05':
                    return "Март";
                case '06':
                    return "Июнь";
                case '07':
                    return "Июль";
                case '08':
                    return "Август";
                case '09':
                    return "Сертябрь";
                case '10':
                    return "Октябрь";
                case '11':
                    return "Ноябрь";
                case '12':
                    return "Декабрь";
            }
        }

        function getDays($number, $year){


            switch ($number) {
                case '01':
                    return 31;
                case '02':
                    if (($year % 4) <> 0)  return 28; else return 29;
                case '03':
                    return 31;
                case '04':
                    return 30;
                case '05':
                    return 31;
                case '06':
                    return 30;
                case '07':
                    return 31;
                case '08':
                    return 31;
                case '09':
                    return 30;
                case '10':
                    return 31;
                case '11':
                    return 30;
                case '12':
                    return 31;
            }
        }


        $userId = Auth::id();
        $dates = [];
        $months = [];
        $days = [];
        $years = [];



        $stats = Transaction::where('user_id',$userId)->orderByDesc('date')->get();

        foreach ($stats as $stat) {
           array_push($dates, mb_substr($stat['date'],0,7));

        }
        $dates = array_unique($dates);

        $i = 0;
        foreach ($dates as $stat) {
            $months[$i] = mb_substr($stat,5,2);
            $years[$i] = mb_substr($stat,0,4);
            $days[$i] = getDays($months[$i],$years[$i]);
            $months[$i] = getMonth($months[$i]);

            $i++;
        }

        $graphs = null;
        $graphsPlus = null;
        $get = $request->all();
        if (isset($get['search'])) {
            $search = $get['search'];
            $stats = Transaction::where('user_id',$userId)->where('transaction.comment', 'like', '%'.$search.'%')->orderByDesc('date')
            ->leftJoin('category', 'transaction.category_id', '=', 'category.id')
            ->select('transaction.*', 'category.name' )
            ->paginate($pages);
            $sum = Transaction::where('user_id',$userId)->where('transaction.comment', 'like', '%'.$search.'%')->sum('cost');


           $graphs = Transaction::where('user_id', 1)->where('cost','<', 0)->where('transaction.comment', 'like', '%'.$search.'%')
           ->selectRaw("SUM(cost) as co")
           ->selectRaw("category_id as cat")
           ->groupBy('category_id')->orderBy('co')->get();

           $graphsPlus = Transaction::where('user_id', 1)->where('cost','>', 0)->where('transaction.comment', 'like', '%'.$search.'%')
           ->selectRaw("SUM(cost) as co")
           ->selectRaw("category_id as catPlus")
           ->groupBy('category_id')->orderByDesc('co')->get();

           $subSum = Transaction::where('user_id',$userId)->where('cost','<', 0)->where('transaction.comment', 'like', '%'.$search.'%')->sum('cost');
           $subSumPlus = Transaction::where('user_id',$userId)->where('cost','>', 0)->where('transaction.comment', 'like', '%'.$search.'%')->sum('cost');

           foreach ( $graphs as $graph) {
            $graph['cat'] = Category::select('name','color')->where('id', $graph['cat'])->first();
            $graph['percent'] =  round((($graph->co / $subSum) * 100), 2);
            $graph['co'] =  round($graph['co'],2);
           }

           foreach ( $graphsPlus as $graph) {
            $graph['cat'] = Category::select('name','color')->where('id', $graph['catPlus'])->first();
            $graph['percent'] =  round((($graph->co / $subSumPlus) * 100), 2);
            $graph['co'] =  round($graph['co'],2);

           }

            foreach ($stats as $stat) {
                if (($stat->type)==0) {
                    $stat->type = 'Расход';
                    $stat->date = Carbon::parse($stat->date)->format('d.m.Y');
                    $stat->background = '#ff909026';
                } else {
                    $stat->type = 'Доход';
                    $stat->date = Carbon::parse($stat->date)->format('d.m.Y');
                    $stat->background = '#d0f0c05e';
                }


            }

            $userId = Auth::id();
            $cats = Category::where([['user_from', $userId]])->orderByDesc('id')->get();

            $sum = round($sum,2);



            return view('home', compact('stats','dates','months','days','years','cats','sum','graphs', 'graphsPlus' ));


        }
         else {


        if ((isset($get['datefrom'])) or (isset($get['dateto']))) {
            $from = $get['datefrom'];
            $to = $get['dateto'];
            $stats = Transaction::where('user_id',$userId)->whereBetween('date', [$from, $to])
            ->leftJoin('category', 'transaction.category_id', '=', 'category.id')
            ->select('transaction.*', 'category.name' )
            ->paginate($pages);
            $sum = Transaction::where('user_id',$userId)->whereBetween('date', [$from, $to])->sum('cost');

            $graphs = Transaction::where('user_id', 1)->where('cost','<', 0)->whereBetween('date', [$from, $to])
            ->selectRaw("SUM(cost) as co")
            ->selectRaw("category_id as cat")
            ->groupBy('category_id')->orderBy('co')->get();

            $graphsPlus = Transaction::where('user_id', 1)->where('cost','>', 0)->whereBetween('date', [$from, $to])
            ->selectRaw("SUM(cost) as co")
            ->selectRaw("category_id as catPlus")
            ->groupBy('category_id')->orderByDesc('co')->get();

            $subSum = Transaction::where('user_id',$userId)->where('cost','<', 0)->whereBetween('date', [$from, $to])->sum('cost');
            $subSumPlus = Transaction::where('user_id',$userId)->where('cost','>', 0)->whereBetween('date', [$from, $to])->sum('cost');

            foreach ( $graphs as $graph) {
             $graph['cat'] = Category::select('name','color')->where('id', $graph['cat'])->first();
             $graph['percent'] =  round((($graph->co / $subSum) * 100), 2);
             $graph['co'] =  round($graph['co'],2);
            }

            foreach ( $graphsPlus as $graph) {
             $graph['cat'] = Category::select('name','color')->where('id', $graph['catPlus'])->first();
             $graph['percent'] =  round((($graph->co / $subSumPlus) * 100), 2);
             $graph['co'] =  round($graph['co'],2);

            }

            if (isset($get['category']))  {
                $id = $get['category'];
                $stats = Transaction::where('user_id',$userId)->where('category_id',$id)->whereBetween('date', [$from, $to])->orderByDesc('date')
                ->leftJoin('category', 'transaction.category_id', '=', 'category.id')
                  ->select('transaction.*', 'category.name' )->paginate($pages);
                $sum = Transaction::where('user_id',$userId)->where('category_id',$id)->whereBetween('date', [$from, $to])->sum('cost');
                $graphs = null;
                $graphsPlus = null;

            }

        } elseif (isset($get['category'])) {
            $id = $get['category'];
            $stats = Transaction::where('user_id',$userId)->where('category_id',$id)->orderByDesc('date')
            ->leftJoin('category', 'transaction.category_id', '=', 'category.id')->select('transaction.*', 'category.name' )
            ->paginate($pages);
            $sum = Transaction::where('user_id',$userId)->where('category_id',$id)->sum('cost');

        } else {
            $stats = Transaction::where('user_id',$userId)->orderByDesc('date')
            ->leftJoin('category', 'transaction.category_id', '=', 'category.id')
            ->select('transaction.*', 'category.name' )
            ->paginate($pages);
            $sum = Transaction::where('user_id',$userId)->sum('cost');


           $graphs = Transaction::where('user_id', 1)->where('cost','<', 0)
           ->selectRaw("SUM(cost) as co")
           ->selectRaw("category_id as cat")
           ->groupBy('category_id')->orderBy('co')->get();

           $graphsPlus = Transaction::where('user_id', 1)->where('cost','>', 0)
           ->selectRaw("SUM(cost) as co")
           ->selectRaw("category_id as catPlus")
           ->groupBy('category_id')->orderByDesc('co')->get();

           $subSum = Transaction::where('user_id',$userId)->where('cost','<', 0)->sum('cost');
           $subSumPlus = Transaction::where('user_id',$userId)->where('cost','>', 0)->sum('cost');

           foreach ( $graphs as $graph) {
            $graph['cat'] = Category::select('name','color')->where('id', $graph['cat'])->first();
            $graph['percent'] =  round((($graph->co / $subSum) * 100), 2);
            $graph['co'] =  round($graph['co'],2);
           }

           foreach ( $graphsPlus as $graph) {
            $graph['cat'] = Category::select('name','color')->where('id', $graph['catPlus'])->first();
            $graph['percent'] =  round((($graph->co / $subSumPlus) * 100), 2);
            $graph['co'] =  round($graph['co'],2);

           }
        }

        foreach ($stats as $stat) {
            if (($stat->type)==0) {
                $stat->type = 'Расход';
                $stat->date = Carbon::parse($stat->date)->format('d.m.Y');
                $stat->background = '#ff909026';
            } else {
                $stat->type = 'Доход';
                $stat->date = Carbon::parse($stat->date)->format('d.m.Y');
                $stat->background = '#d0f0c05e';
            }


        }

        $userId = Auth::id();
        $cats = Category::where([['user_from', $userId]])->orderByDesc('id')->get();
       // $sum = Transaction::where('user_id',$userId)->sum('price');
        $sum = round($sum,2);



        return view('home', compact('stats','dates','months','days','years','cats','sum','graphs', 'graphsPlus' ));
    } }
}
