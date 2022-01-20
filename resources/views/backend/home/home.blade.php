@extends('backend.layouts.index')

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="card card--28">
            <div class="card-body">
                Selamat Datang {{ Auth::user()->name }}
            </div>
        </div>
    </div>
</div>
@endsection