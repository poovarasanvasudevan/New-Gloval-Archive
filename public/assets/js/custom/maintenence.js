/**
 * Created by Poovarasan on 8/14/2016.
 */
$(function () {
    $('#schedulePanel').hide();
    $('#artefactTypes').on('change', function () {
        $('#schedulePanel').hide();
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
            }
        });
    });
})