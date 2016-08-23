/**
 * Created by poovarasanv on 18/8/16.
 */
$(function () {
    var pages = 0;
    $('#topBlock').hide();
    $("input.autocomplete").easyAutocomplete({
        url: "/attrs",
        getValue: "value",
        list: {
            match: {
                enabled: true
            }
        }
    });
    $('.date').datepicker();

    $('#artefactTypes').change(function () {
        if ($(this).val() != 0 || $(this).val() != '0') {
            window.location = "/search/" + $(this).val();
        }
    })

    $('#search').click(function () {
        //alert("hi");
        pages = 0;
        requestData(pages);
        pages++;
        $('#topBlock').show();
    })

    $('#next').click(function () {
        requestData(pages);
        pages++;
    });

    $('#prev').click(function () {
        pages--;
        requestData(pages);
    });

    function requestData(page) {
        setDat = $('#searchAttr').serialize();
        $.ajax({
            url: '/searchTable/' + page,
            method: 'POST',
            data: setDat,
            success: function (res) {
                html = "";
                if (res.length > 0) {
                    for (i = 0; i < res.length; i++) {
                        html += "<div class='col-md-3'>";
                        html += "<div class='card card-block'>";
                        html += "<h4 class='card-title'>" + res[i].artefact_name + "</h4>"
                        html += "<p class='card-text'>" + res[i].parent.artefact_name + "</p>"

                        url = '/artefactview/' + res[i].id;
                        html += "<a class='btn btn-success pull-right' target='_blank' href='" + url + "'>View Artefact</a>"
                        html += "</div>";
                        html += "</div>";
                    }
                } else {

                }

                $('#searchTable').html(html);
            }
        })
    }
});