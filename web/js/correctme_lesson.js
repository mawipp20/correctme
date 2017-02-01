
/** file deprecated *

$(document).ready(function() {
    $( ".cm_input_group_rejoin" ).each(function() {
        $(this).hide();
    });
    $(".cm_input_group_join").first().find("input").focus();
});



function teacherToggleJoinRejoin(){
    var rejoin = $(".cm_input_group_join").first().is(":visible");
    $( ".cm_input_group_rejoin" ).each(function() {
        if($( this ).find("input").length > 0){$( this ).find("input").val('');}
        if(rejoin){$(this).show();}else{$(this).hide();}
    });
    $( ".cm_input_group_join" ).each(function() {
        if(rejoin){$(this).hide();}else{$(this).show();}
    });
    if(rejoin){
        $(".cm_input_group_rejoin").first().find("input").focus();
        $("#lesson_btn_submit").text(_L_lesson["lesson_btn_submit_toggle"]);
        $("#lesson_btn_rejoin_session").text(_L_lesson["lesson_btn_rejoin_session_toggle"]);
    }else{
        $(".cm_input_group_join").first().find("input").focus();
        $("#lesson_btn_submit").text(_L_lesson["lesson_btn_submit"]);
        $("#lesson_btn_rejoin_session").text(_L_lesson["lesson_btn_rejoin_session"]);
    }
}

*/