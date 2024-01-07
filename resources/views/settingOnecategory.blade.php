@extends('layouts.app')

@section('content')

<div class="container">
    <div class=row>
    <div class="col-md-6">
    <form action=/update/category method=post>
    @csrf
        <span style='font-size: 20px'> Редактирование : {{ $catId[0]->name }}</span><br>

        Название:
        <input type="text" class=form-control name=name value="{{ $catId[0]->name }}" required>
        Комментарий:
        <input type="text" class=form-control name=comment value="{{ $catId[0]->comment }}">
        Цвет:
        <input type="color" class=form-control name=color value="{{ $catId[0]->color }}">
        <button value="{{ $catId[0]->id }}" class='btn btn-secondary' name=id>Сохранить</button>

        </form>
    </div>
        <div class="col-md-6">
        <form action=/delete/category method=post>
        @csrf
        <button id='delete-category'
        class="btn btn-danger"
        style='float: right;'
        value="{{ $catId[0]->id }}"
        name=id
        onclick="if(!confirm('Внимание! Данная категория будет удалена, а также все транзакции, которые с ней связаны!')) return false"
        > Удалить категорию </button>
        </form>
            </div>
    </div>
</div>
@endsection
