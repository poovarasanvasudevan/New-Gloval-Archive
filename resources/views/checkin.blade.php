@extends('layout.layout2')
@section('content')
    <div class="full-bg">
        @include("layout.navbar")
        <style>
            .easy-autocomplete-container {
                margin-top: 55px !important;
            }
        </style>

        <div class="col-md-6 card card-block col-md-offset-3" style="margin-top: 70px !important;">

            @if (session()->has('flash_notification.message'))
                <div class="alert alert-{{ session('flash_notification.level') }}">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    {!! session('flash_notification.message') !!}
                </div>
            @endif
            <ul class="nav nav-pills center-block">
                <li><a  href="/cico">Check Out</a></li>
                <li class="active"><a href="/cin">Check In</a></li>
            </ul>

            <hr/>
            <div class="tab-content">
                <div id="checkib" class="tab-pane fade in active" style="height: 55% !important;">
                    <h2>
                        <center>Checkout</center>
                    </h2>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">

                            <form name="checkinForm" id="checkinForm">
                                <div class="form-group has-success">
                                    <label class="control-label" for="inputcheckin">Search Artefact</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-lg checkinbox" id="inputcheckin">
                                        <span class="input-group-btn">
                                      <input type="submit" class="btn btn-primary btn-lg" value="ADD">
                                    </span>
                                    </div>
                                </div>
                            </form>
                            <div id="checkinList">

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {

            $("input.checkinbox").easyAutocomplete({
                url: "/checkInAutocomplete",
                getValue: "artefact_name",
                list: {
                    match: {
                        enabled: true
                    },
                    onSelectItemEvent: function () {
                        cvalueSelected = "";
                        cvalueSelectedTitle = "";
                        cvalueSelected = $("input.checkinbox").getSelectedItemData().id;
                        cvalueSelectedTitle = $("input.checkinbox").getSelectedItemData().artefact_name;
                    }
                }
            });

            $('#checkinForm').submit(function (e) {

                $('#checkinList').html("<div class='card card-block'><h4 class='card-title'>" + cvalueSelectedTitle + "</h4><p class='card-text'><form name='coform' method='post' action='/checkin'><input type='hidden' name='artefactid' value='" + cvalueSelected + "'><label>Checkin Reason : </label><textarea required name='checkinreason' class='form-control' rows='4' cols='5'></textarea><p><input type='submit' value='Checkin' class='btn btn-primary pull-right margin'></form></div>")
                e.preventDefault();
            });
        })

    </script>
@endsection