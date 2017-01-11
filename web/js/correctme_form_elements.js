function btnGroupToggle(btn, save_with_field, save_val){
    if($("#"+save_with_field).length==0){alert("save_with_field not found");return;}
    $("#"+save_with_field).val(save_val);
    //alert($("#"+save_with_field).val());
    $(btn).parent(".btn-group").children(".btn").each(function(){
        //if(!$(this).is($(btn))){$(this).addClass("btn-default")}
        if($(this).is(btn)){
            $(this).removeClass("btn-default");
            $(this).addClass("btn-success");
        }else{
            $(this).removeClass("btn-success");
            $(this).addClass("btn-default");
        }
    });
}