/**
 * Created by Poovarasan on 8/13/2016.
 */
$(function () {
    $('form#newUser').submit(function (e) {

      


        checkboxSelectedLength = $('div.checkbox label :checkbox:checked').length;
        if (length > 0) {
            alert('Please select any one artefact to access...');
            return false;
        } else {
            return true;
        }

    })
})