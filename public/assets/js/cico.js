/**
 * Created by poovarasanv on 12/8/16.
 */
$(function () {
    var valueSelected = "";
    var cvalueSelected = "";
    var valueSelectedTitle = "";
    var cvalueSelectedTitle = "";
    $('#checkoutList').html("")

    $('#checkoutBtn').hide();
    $("input.checkoutbox").easyAutocomplete({
        url: "/getCheckout",
        getValue: "artefact_name",
        list: {
            match: {
                enabled: true
            },
            onSelectItemEvent: function () {
                valueSelected = "";
                valueSelectedTitle = "";
                valueSelected = $("input.checkoutbox").getSelectedItemData().id;
                valueSelectedTitle = $("input.checkoutbox").getSelectedItemData().artefact_name;
            }
        }
    });



    $('#checkoutForm').submit(function (e) {

        $('#checkoutList').html("<div class='card card-block'><h4 class='card-title'>" + valueSelectedTitle + "</h4><p class='card-text'><form name='coform' method='post' action='/checkout'><input type='hidden' name='artefactid' value='" + valueSelected + "'><label>Checkout Reason : </label><textarea required name='checkoutreason' class='form-control' rows='4' cols='5'></textarea><p><input type='submit' value='Checkout' class='btn btn-primary pull-right margin'></form></div>")
        e.preventDefault();
    });



})