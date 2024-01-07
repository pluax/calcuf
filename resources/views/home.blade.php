@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">

    <div class="col-md-3 months">
        <ul>
        <li>
            <a href="/">Все
            </a>
            </li>
          @foreach ($dates as $date)
            <li>
            <a href="/?datefrom={{  $date  }}-01&dateto={{  $date  }}-{{ $days[$loop->index] }}">
            {{ $months[$loop->index] }} {{ $years[$loop->index] }}
            </a>
            </li>
           @endforeach
        </ul>
    </div>

    <div class="col-md-9">
          <form action="/" method="get">
            <div class=row>
            <div class="col-md-4">
                Категория
                <select id=catrgory-select class=form-control name="category">
                    <option value='0'> Не выбрано ...  </option>
                @foreach ($cats as $cat)
                    <option value='{{ $cat["id"] }}' <?php if(isset($_GET['category'])) :
                        if ($cat["id"]==$_GET['category']) :
                            echo "selected";
                            endif;
                    endif;
                    ?> >
                 {{   $cat['name']   }}
                </option>

                  @endforeach
            </select>
            </div>
            <div class="col-md-3">
                От:
                <input type=date class=form-control name="datefrom" value="<?php if(isset($_GET['datefrom'])) echo $_GET['datefrom'];  ?>"/>
            </div>
            <div class="col-md-3">
                До:
                <input type=date class=form-control name="dateto" value="<?php if(isset($_GET['dateto'])) echo $_GET['dateto'];  ?>"/>
            </div>
            <div class="col-md-2">
                <br>
            <button type="submit" class="btn btn-primary">Поиск</button>
                </div>
            </form>
            </div>

            <div class=balance style='font-size:40px; float:left;'>Баланс: <strong> {{ $sum }} </strong></div>
            <form action=/ method=get class="comment-search" style="">

                <input class=form-control placeholder="Поиск по комментариям...(для поиска нажать Enter)" name=search  value="<?php if(isset($_GET['search'])) echo $_GET['search'];  ?>">
            </form>
            </div>
            <div class="col-md-3">
                <hr>
                <div class="all_stats">
                <span class=title-stats>Статистика расходов: </span><br>
                @if(isset($graphs))
                    @foreach ($graphs as $graph)
                    {{ $graph->cat->name }}  - {{ $graph->percent }}% ({{ $graph->co * (-1) }})<br>
                    <div class="bar" style='width:{{ $graph->percent }}%; background: {{ $graph->cat->color }} '>
                        </div><br>
                    @endforeach
                @endif

                </div>
                <hr>
                <div class="all_stats">
                <span class=title-stats>Статистика доходов: </span><br>
                @if(isset($graphsPlus))
                    @foreach ($graphsPlus as $graph)
                    {{ $graph->cat->name }}  - {{ $graph->percent }}% ({{ $graph->co }})<br>
                    <div class="bar" style='width:{{ $graph->percent }}%; background: {{ $graph->cat->color }} '>
                        </div><br>
                    @endforeach
                @endif
                </div>
                </div>
        <div class="col-md-9">
        <table class="table table-bordered menu">
            <thead>
            <tr>
                <th scope="col">Цена</th>
                <th scope="col">Категория</th>
                <th scope="col">Дата</th>
                <th scope="col">Коммент </th>
            </tr>
            </thead>
            <tbody>

            @foreach ($stats as $stat)

                <tr>
                <td style="background-color:{{  $stat->background; }}">
                    <a href="/transaction/{{ $stat->id  }}">
                        {{  $stat->cost }}
                    </a>
                </td>
                <td style="background-color:{{  $stat->background; }}">
                <a href="/?category={{ $stat->category_id  }}">
                {{  $stat->name }}
                </a>
                </td>
                <td style="background-color:{{  $stat->background; }}">
                {{  $stat->date }}
                </td>
                <td style="background-color:{{  $stat->background; }}">
                {{  $stat->comment }}
                </td>
                </tr>

                @endforeach

            </tbody>
        </table>
        <div class=pagination>
            {{ $stats->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
