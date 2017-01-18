function teacherToggleJoinRejoin(){
    var rejoin = $(".cm_input_group_join").first().is(":visible");
    $( ".cm_input_group_rejoin" ).each(function() {
        if($( this ).find("input").length > 0){$( this ).find("input").val('');}
        if(rejoin){$(this).show();}else{$(this).hide();}
    });
    $( ".cm_input_group_join" ).each(function() {
        if(rejoin){$(this).hide();}else{$(this).show();}
        //if($( this ).find("input").length > 0){$( this ).find("input").prop( "disabled", rejoin );}
    });
}