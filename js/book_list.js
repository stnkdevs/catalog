var hide_filter=function(){
    $('.book_filter').css('display', 'block');
    var offset=$('.results').offset();
    if(offset.top>100)
        $('.book_filter').css('display', 'none');
    else $('.book_filter').css('dispaly', 'block');
};
$(window).resize(hide_filter);
$(document).ready(hide_filter);
