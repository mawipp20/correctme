function result_tabs_click(elem, div_id){
    $(elem).parent("li").siblings().removeClass("active");
    $(elem).parent("li").addClass("active");
    $('.results').hide();
    $('#' + div_id).show();
}
