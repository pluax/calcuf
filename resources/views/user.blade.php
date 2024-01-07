@extends('layouts.app')

@section('content')

<div class="container">
    <div class=row>
    <div class="col-md-6">
        <form action=/user/update method=post>
             @csrf
          Email
         <input type="email" class=form-control name=email value="{{ $user->email }}">
         Имя
         <input type="text" class=form-control name=name value="{{ $user->name }}">
         Фамилия
         <input type="surname" class=form-control name=surname value="{{ $user->surname }}">

         <button value="{{ $user->id }}" class='btn btn-secondary' name=id>Сохранить</button>
    </div>
</div>

    @endsection
