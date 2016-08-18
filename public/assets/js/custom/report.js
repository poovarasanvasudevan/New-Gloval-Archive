/**
 * Created by poovarasanv on 17/8/16.
 */


$(function () {
    $('.date').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'dd-mm-yyyy'
    });
    $('#cicoPrint').hide();

    $('#cicoForm').submit(function (e) {


        var serData = $(this).serialize();
        $.ajax({
            url: "/getCicoWithDates",
            method: "POST",
            data: serData,
            success: function (res) {

                if (res.length > 0) {
                    $('#cicoPrint').show();

                    html = "<table class='table table-bordered table-responsive'>";
                    html += "<thead>";
                    html += "<tr>";
                    html += "<td>Artefact Name</td>"
                    html += "<td>Check Out Time</td>"
                    html += "<td>Checkout Desctiption</td>"
                    html += "<td>Check In Time</td>"
                    html += "<td>Check In Desctiption</td>"
                    html += "<td>Remarks</td>"
                    html += "<td>Is Checked In</td>"
                    html += "<td>Done By</td>"
                    html += "</tr>";
                    html += "</thead>";
                    for (i = 0; i < res.length; i++) {
                        isAvailable = "Yes";
                        if (res[i].check_out_status == true) {
                            isAvailable = "No"
                        }
                        desc = "";
                        if (res[i].check_in_description != null) {
                            desc = res[i].check_in_description;
                        }
                        html += "<tr>";
                        html += "<td>" + res[i].artefact.artefact_name + "</td>"
                        html += "<td>" + res[i].created_at + "</td>"
                        html += "<td>" + res[i].check_out_description + "</td>"
                        html += "<td>" + res[i].updated_at + "</td>"
                        html += "<td>" + desc + "</td>"
                        html += "<td>" + res[i].remarks + "</td>"
                        html += "<td>" + isAvailable + "</td>"
                        html += "<td>" + res[i].user.fname + "</td>"
                        html += "</tr>";
                    }
                    html += "</table>";

                    $('.cicogrid').html(html);

                    $('#cicoPrint').click(function () {
                        data = $('#cicoForm').serializeArray();

                        window.open('/cicoReportPrint/' + encodeURI(data[0].value) + "/" + encodeURI(data[1].value));
                        //window.location = '/cicoReportPrint/' + encodeURI(data[0].value) + "/" + encodeURI(data[1].value);
                    })
                } else {
                    $('.cicogrid').html("<center><h4>No Reports Found</h4></center>");
                }
            }
        });
        e.preventDefault();
    });

    $('#crForm').submit(function (e) {
        var serData = $(this).serialize();
        $.ajax({
            url: "/getCRWithDates",
            method: "POST",
            data: serData,
            success: function (res) {
                if (res.length > 0) {
                    html = "<table class='table table-bordered table-responsive'>";
                    html += "<thead>";
                    html += "<tr>";
                    html += "<td>Artefact Name</td>"
                    html += "<td>Maintenence Date</td>"
                    html += "<td>Done By</td>"
                    html += "<td>Report Done Time</td>"
                    html += "<td>Action</td>"
                    html += "</tr>"
                    html += "</thead>"
                    for (i = 0; i < res.length; i++) {
                        if(res[i].is_completed == true) {
                            html += "<tr>";
                            html += "<td>" + res[i].scheduled_maintenence.artefact_id.artefact_name + "</td>"
                            html += "<td>" + res[i].maintenence_date + "</td>"
                            html += "<td>" + res[i].users.fname + "</td>"
                            html += "<td>" + res[i].updated_at + "</td>"

                            url = "/crReportPrint/" + res[i].id;
                            html += "<td>" + "<a href='" + url + "' target='_blank' class='btn btn-danger'>Print</a> " + "</td>"
                            html += "</tr>"
                        }
                    }
                    html += "</table>";

                    $('#crGrid').html(html);
                } else {
                    $('#crGrid').html("<center><h4>No Reports Found</h4></center>");
                }
            }
        });

        e.preventDefault();
    })
})