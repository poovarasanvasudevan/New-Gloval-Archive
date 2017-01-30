/**
 * Created by poovarasanv on 10/8/16.
 */
$(function () {
    // var stickyOffset = $('.sticky').offset().top;
    //
    // $(window).scroll(function(){
    //     var sticky = $('.sticky'),
    //         scroll = $(window).scrollTop();
    //
    //     if (scroll >= stickyOffset) sticky.addClass('fixed');
    //     else sticky.removeClass('fixed');
    // });
    var active = window.location.pathname;
    $(".nav a[href|='" + active + "']").parent().addClass("active");
})