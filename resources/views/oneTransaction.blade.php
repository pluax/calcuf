@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-6">
        <form action=/update/transaction method=post>
             @csrf
            <span style='font-size: 20px'> Транзакция # {{ $transId[0]->id }}</span><br>

            Стоимость:
            <input type="number" class=form-control name=cost value="{{ $transId[0]->cost }}" required>
            Категория:
            <select  class=form-control name="cat" required>
            @foreach ($cats as $cat)
            <option value='{{ $cat["id"] }}' <?php if ($cat["id"]==$transId[0]->category_id) echo 'selected'; ?>>
             {{   $cat['name']   }}
                </option>

             @endforeach
            </select>
            Дата:
            <input type="date" class=form-control name=date value="{{ $transId[0]->date }}" required>
            Комментарий:
            <textarea type="color" class=form-control name=comment> {{ $transId[0]->comment }} </textarea>
            <button value="{{ $transId[0]->id }}" class='btn btn-secondary' name=id>Сохранить</button>

        </form>

            </div>

        <div class="col-md-6">
        <form action=/delete/transaction method=post>
                @csrf
                <button id='delete-category'
                class="btn btn-danger"
                style='float: right;'
                value="{{ $transId[0]->id }}"
                name=id
                onclick="if(!confirm('Внимание! Данная транзакция будет удалена!')) return false"
                > Удалить транзакцию </button>
                </form>
            </div>
    </div>
</div>
@endsection
