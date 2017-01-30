@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")

        <div class="col-md-6 card card-block col-md-offset-3" style="margin-top: 70px !important;">

            @if (session()->has('flash_notification.message'))
                <div class="alert alert-{{ session('flash_notification.level') }}">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    {!! session('flash_notification.message') !!}
                </div>
            @endif
            <ul class="nav nav-pills center-block">
                <li class="active"><a  href="/cico">Check Out</a></li>
                <li><a href="/cin">Check In</a></li>
            </ul>

            <hr/>
            <div class="tab-content">
                <div id="checkout" class="tab-pane fade in active" style="height: 55% !important;">
                    <h2>
                        <center>Checkout</center>
                    </h2>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">

                            <form name="checkoutForm" id="checkoutForm">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess">Search Artefact</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg checkoutbox" id="inputcheckout">
                                        <span class="input-group-btn">
                                      <input type="submit" class="btn btn-primary btn-lg" value="ADD">
                                    </span>
                                    </div>
                                </div>
                            </form>
                            <div id="checkoutList">

                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="/assets/js/cico.js"></script>
@endsection