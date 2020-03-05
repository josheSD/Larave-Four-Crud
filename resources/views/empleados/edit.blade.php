@extends('layouts.app')
@section('content')
    <div class="container">

        <form action="{{ url('/empleados/' . $empleado->id) }}" method="post" enctype="multipart/form-data">

            {{ csrf_field() }}
            {{ method_field('PUT') }}

            @include('empleados.form',['Modo' => 'editar'])

        </form>

    </div>
@endsection
