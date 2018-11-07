/**
$(document).ready(function() {
   $("a").click(function(){
        alert("click"); 
   });
});
*/
function about_goto(link_elem, target_elem_id){
    $('html, body').animate({
        scrollTop: $("#" + target_elem_id).offset().top - 65
    }, 300);
}
function info_span_expand(e, do_toggle){
    if(typeof do_toggle == "undefined"){do_toggle = false;}
    var this_span = $(e).next("span");
    if(!do_toggle){
        this_span.show();
    }else{
        if(this_span.is(':visible')){
            this_span.hide();
            $(e).text("..." + _L["gen_in_detail"]);
        }else{
            this_span.show();
            $(e).text("..." + _L["gen_less"]);
        }
    }
}
