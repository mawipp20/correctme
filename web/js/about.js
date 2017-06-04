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
