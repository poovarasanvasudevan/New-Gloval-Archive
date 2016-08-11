/**
 * Created by poovarasanv on 10/8/16.
 */
$(function () {

    $('#card-block').hide();
    $("#artefactForm").submit(function (e) {

        var url = "/saveArtefact"; // the script where you handle the form input.

        $.ajax({
            type: "POST",
            url: url,
            data: $("#artefactForm").serialize(), // serializes the form's elements.
            success: function (data) {
                if (data.status == 200) {
                    $('#status').html("<label class='text-primary'> Successfully Updated...</label>")
                }
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $('#artefactTypes').on('change', function () {

        $('#detailPanel').html('<center style="margin-top: 23% !important;"> <img src="/image/logo.png"> </center>');

        var tree = $("#tree").fancytree("getTree");
        if (tree) {
            $(":ui-fancytree").fancytree("destroy")
        }

        artefactSelected = $(this).val();
        $('#parent').fancytree({
            autoActivate: true,
            autoScroll: true,
            clickFolderMode: 3,
            keyboard: true,
            extensions: ['filter', 'dnd'],
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

                if (node.isFolder()) {
                    return false;
                }
                $('#detailPanel').html('<center style="margin-top: 23% !important;"> <img src="/image/logo.png"> </center>');
                $.ajax({
                    url: '/getArtefact/' + artefactSelected + '/' + node.key,
                    success: function (data) {
                        $('#detailPanel').html(data);
                        $('#card-block').show();
                        $("input.autocomplete").easyAutocomplete({
                            url: "/attrs",
                            getValue: "value",
                            list: {
                                match: {
                                    enabled: true
                                }
                            }
                        });

                        $('.date').datepicker({
                            startDate: '-3y'
                        });
                    }
                })
            }
        })


    })


})