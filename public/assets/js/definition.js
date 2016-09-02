/**
 * Created by poovarasanv on 10/8/16.
 */
$(function () {

    $('#card-block').hide();
    $('#newArtefact').hide();
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




    $('#newArtefact').click(function () {
        swal({
            title: "Artefact!",
            text: "Enter Artefact Name:",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Artefact Name",
            showLoaderOnConfirm: true,
        }, function (inputValue) {
            if (inputValue === false) return false;
            if (inputValue === "") {
                swal.showInputError("You need to write artefact name!");
                return false
            }
            $.ajax({
                url: '/addArtefact/' + artefactSelected + '/' + 0 + '/' + inputValue,
                success: function (data) {
                    if (data.status == 200) {
                        swal("Artefact Inserted Succesfully!");
                        var tree = $("#tree").fancytree("getTree");
                        tree.load();
                    }
                }
            });
        });

    });

    $('#artefactTypes').on('change', function () {

        artefactSelected = $(this).val();
        if(artefactSelected == '0' || artefactSelected == 0){
            $('#newArtefact').hide();
            $('#card-block').hide();
            return false;
        }

        $('#newArtefact').show();
        $('#card-block').hide();
        $('#artefacttitle').html('');

        $('#detailPanel').html('<center style="margin-top: 23% !important;"> <img src="/image/logo.png" height="150" width="150"> </center>');

        var tree = $("#tree").fancytree("getTree");
        if (tree) {
            $(":ui-fancytree").fancytree("destroy")
        }

        var clipboard = "", parentartefact = "";

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

                $('#status').html("");
                var node = data.node;


                $('#artefacttitle').html(node.title);
                if (node.isFolder()) {
                    return false;
                }
                $('#crlink').prop('href', '/crview/' + node.key);
                $('#printa').prop('href', '/artefactprint/' + node.key);
                $('#detailPanel').html('<center style="margin-top: 23% !important;"> <img src="/image/logo.png" width="150" height="150"> </center>');
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
            },
            contextMenu: {
                menu: {
                    'edit': {'name': 'Add Sub Item', 'icon': 'edit'},
                    'cut': {'name': 'Cut', 'icon': 'cut'},
                    'paste': {'name': 'Paste', 'icon': 'paste'},
                    'rename': {'name': 'Rename', 'icon': 'rename'},
                    'delete': {'name': 'Delete', 'icon': 'cut'}

                },
                actions: function (node, action, options) {
                    switch (action) {
                        case 'delete': {
                            swal({
                                title: "Are you sure?",
                                text: "You will not be able to recover this artefact!",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: "Yes, delete it!",
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true,
                            }, function () {
                                $.ajax({
                                    url: '/deleteArtefact/' + node.key,
                                    success: function (data) {
                                        if (data.status == 200) {
                                            swal("Your artefact has been deleted.");
                                            node.remove();
                                            node.load(true);
                                        }
                                    }
                                });
                            });
                            break;
                        }
                        case 'edit' : {

                            var nodeID = node.key;
                            swal({
                                title: "Artefact!",
                                text: "Enter Sub Artefact Name:",
                                type: "input",
                                showCancelButton: true,
                                closeOnConfirm: false,
                                animation: "slide-from-top",
                                inputPlaceholder: "Artefact Name",
                                showLoaderOnConfirm: true,
                            }, function (inputValue) {
                                if (inputValue === false) return false;
                                if (inputValue === "") {
                                    swal.showInputError("You need to write artefact name!");
                                    return false
                                }
                                $.ajax({
                                    url: '/addArtefact/' + artefactSelected + '/' + node.key + '/' + inputValue,
                                    success: function (data) {
                                        if (data.status == 200) {
                                            swal("Artefact Inserted Succesfully!");
                                            node.load(true);
                                        }
                                    }
                                });
                            });
                            break;
                        }
                        case 'cut' : {
                            if(node.isFolder()){
                                swal("Error", "Your can't move Parent :)", "error");
                                return false;
                            }
                            clipboard = node.key;
                            node.remove();
                            swal("Cut!", "Now you can Paste the artefact?")
                            break;
                        }
                        case 'paste' : {
                            if (clipboard == "") {
                                swal("Paste", "No Item in the clipboard :)", "error");
                            } else {
                                $.ajax({
                                    url: '/moveArtefact/' + clipboard + '/' + node.key,
                                    success: function (data) {
                                        if (data.status == 200) {
                                            swal("Moved!", "Your artefact  has been moved.", "success");
                                            node.load(true);
                                        }
                                    }
                                });

                            }

                            clipboard = "";
                            break;
                        }
                        case 'rename' : {
                            swal({
                                title: "Rename!",
                                text: "Enter New Artefact Name for "+node.title,
                                type: "input",
                                showCancelButton: true,
                                closeOnConfirm: false,
                                animation: "slide-from-top",
                                inputPlaceholder: "New Artefact Name",
                                showLoaderOnConfirm: true,
                            }, function (inputValue) {
                                if (inputValue === false) return false;
                                if (inputValue === "") {
                                    swal.showInputError("You need to write artefact name!");
                                    return false
                                }
                                $.ajax({
                                    url: '/renameArtefact/' + node.key + '/' + inputValue,
                                    success: function (data) {
                                        if (data.status == 200) {
                                            swal("Artefact Renamed Succesfully!");
                                            node.setTitle(inputValue)
                                            node.load(true);
                                        }
                                    }
                                });
                            });
                            break;
                        }

                    }
                }
            }
        });

        // $.contextMenu({
        //     selector: '.fancytree-title',
        //     callback: function(key, options) {
        //         var m = "clicked: " + key;
        //         alert($(this).val());
        //     },
        //     items: {
        //         "cut": {name: "Cut"},
        //         "paste": {name: "Paste"},
        //         "delete": {name: "Delete"},
        //         "sep1": "---------",
        //         "rename": {name: "Rename"},
        //     }
        // });
        //
        // $('.context-menu-one').on('click', function(e){
        //    // alert($(this).val());
        //     alert('clicked', this);
        // });

    })
})