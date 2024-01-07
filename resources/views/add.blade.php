@extends('layouts.app')

@section('content')
<div class="container">
<label>
Добваить платёж
</label>
   <form method=post action=add/transaction>
<div class=row>
    @csrf
     <div class="col-md-3">
            Сумма:
          <input class=form-control name="cost" required/>
        </div>
        <div class="col-md-3">
            Дата:
           <input type=date class=form-control name="date" required/>
        </div>
        <div class="col-md-3">
            + / -
           <select type=date class=form-control name="plus" required>
           <option value="minus">
                    Расход
                </option>
           <option value="plus">
                Доход
                </option>

            </select>
        </div>
        <div class="col-md-3">
            Категория
        <select id=catrgory-select class=form-control name="cat">
        @foreach ($cats as $cat)
           <option value='{{ $cat["id"] }}'>
           {{   $cat['name']   }}
                </option>

             @endforeach
            </select>
        </div>
        <div class="col-md-12">
            Комментарий:
        <textarea class='form-control' name=comment></textarea>
        </div>
</div>
<button class="btn btn-primary">Добавить</button>
</form>
</div>

@endsection
