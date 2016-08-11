@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-10 col-md-offset-1" style="margin-top: 70px !important;">
            {!! $grid !!}
        </div>
    </div>
@endsection