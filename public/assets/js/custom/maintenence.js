/**
 * Created by Poovarasan on 8/14/2016.
 */
$(function () {
    $('.weekdays').hide();
    $('.monthdays').hide();
    $('#content').html("");
    $('a#sperodicOpener').magnificPopup({
        type: 'inline',
        closeOnBgClick: false,
        midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
    });

    $('a#periodicOpener').magnificPopup({
        type: 'inline',
        closeOnBgClick: false,
        midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
    });

    $('.scheduleDate').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd/mm/yyyy'
    })
        .on("changeDate", function (e) {
            // `e` here contains the extra attributes
            $('#maintenenceDesc').val("Maintenence has been scheduled on : " + e.format());
        });

    $('.occurtype').change(function () {
        if ($(this).val() == 'week') {
            $('.weekdays').show();
            $('.monthdays').hide();
        } else {
            $('.weekdays').hide();
            $('.monthdays').show();
        }
    })

    $('#newPerodicMaintenance').submit(function () {
        if ($('.occurtype').val() == 'week') {
            if ($('#weekdays').val() == 0) {
                alert("Please select the day fom week to alert schedule");
                return false;
            }
        } else {
            month = parseInt($('#month_day').val())
            if (month < 0 || month > 31 || month == "" || month == 'undefined') {
                alert("Please enter date that wou want to make alert schedule");
                return false;
            }
        }

        return true;
    })
    $('#schedulePanel').hide();
    $('#artefactTypes').on('change', function () {
        $('#schedulePanel').hide();
        $('#content').html("");
        artefactSelected = $(this).val();
        $('#artefact_name').val("");
        $('#parent').fancytree({
            autoActivate: true,
            autoScroll: true,
            clickFolderMode: 3,
            keyboard: true,
            extensions: ['filter', 'contextMenu', 'dnd'],
            filter: {
                autoApply: true,
                counter: true,
                hideExpandedCounter: true,
                mode: "hide"
            },
            source: {
                url: "/loadTree/" + artefactSelected + "/0",
                cache: false
            },
            lazyLoad: function (event, data) {
                var node = data.node;
                data.result = {
                    url: "/loadTree/" + artefactSelected + "/" + node.key,
                    cache: false
                };
            },
            activate: function (event, data) {
                var node = data.node;
                $('#schedulePanel').show();
                $('#artefact_name').html(node.title);
                $('.artefact_id').val(node.key);


                $('#content').html("");
                $.ajax({
                    url: "/getSchedule/" + node.key,
                    method: 'GET',
                    success: function (data) {
                        html = "<div>";

                        for (i = 0; i < data.length; i++) {
                            html += "<div class='card card-block col-md-3'>";
                            html += "<ul>";
                            html += "<li><label>Type : </label>&nbsp;&nbsp;" + data[i].maintenence_type + "</li>";
                            html += "<li><label>Created Date : </label>&nbsp;&nbsp;" + data[i].created_at + "</li>";
                            html += "</ul>";
                            html += "</div>";
                        }
                        html += "<div>";
                        $('#content').html(html);
                    }
                })
            }
        });
    });
})