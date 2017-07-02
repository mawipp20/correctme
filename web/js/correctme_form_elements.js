function isObject(obj) {
  return obj === Object(obj);
}
/**
function btnGroupToggle(btn, save_with_field, save_val){
    if($("#"+save_with_field).length==0){alert("save_with_field not found");return;}
    $("#"+save_with_field).val(save_val);
    $(btn).parent(".btn-group").children(".btn").each(function(){
        if($(this).is(btn)){
            $(this).removeClass("btn-default");
            $(this).addClass("btn-success");
        }else{
            $(this).removeClass("btn-success");
            $(this).addClass("btn-default");
        }
    });
}
function teacherKey_to_ASCII_codes(){
    var teacherKey = $("#lesson-teacherkey").val();
    var ret = new Array();
    for(var i = 0; i < teacherKey.length; i++){
        ret[ret.length] = teacherKey.charCodeAt(i);
    }
    $("#lesson-teacherkey").val((ret.join('.'));
}

*/
