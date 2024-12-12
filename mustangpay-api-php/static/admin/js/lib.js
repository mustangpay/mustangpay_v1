$(document).ready(function() {
    $('.list_lk_d1 dt').click(function(event) {
        $(this).parents('li').toggleClass('show').find('dd').stop().slideToggle();
        return false;
    });
});