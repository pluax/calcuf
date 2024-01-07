@extends('layouts.app')

@section('content')

<div class="container">
    <div class=row>
    <table class="table table-bordered menu">
        <form method=post action=add/category>
        @csrf
                  <tr>
                     <td>
                        <input class=form-control name='name' required>
                    </td>
                    <td>
                       <input class=form-control name='comment'>
                    </td>
                    <td style='display:flex;'>
                       <input class=form-control type=color name='color' style="height: 35px;">
                       <button class='btn btn-secondary'>Добавить</button>
                    </td>
                </tr>
        </form>
            <thead>

            <tr>
                <th scope="col">Категория</th>
                <th scope="col">Комментарий</th>
                <th scope="col">Цвет</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($mainTable as $item)

                <tr>
                <td>
                <a href="/category/edit/{{  $item['id'] }}">
                {{  $item['name'] }}
                </a>
                </td>
                <td>
                {{  $item['comment'] }}
                </td>
                <td>
                {{  $item['color'] }}
                <div style="background:   {{  $item['color'] }} ;
                height: 20px;
                float: right;
                width: 50%;">
                    </div>
                </td>
                </tr>

                @endforeach


            </tbody>
        </div>
</div>
@endsection
