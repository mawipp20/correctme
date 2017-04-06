function teachers_add_team(){
    $('#team_info').show();
    $('#team_div').show();
    $('#team_names').find("input").focus();
}
function teachers_submit(){
    $('#teachers_collected').val(getTeachers().join("#"))
    $("form").attr("action", "teacher_poll_codes");
    $("form").submit();
}

                

function teacherNameOnInput(elem){

    var elem = $(elem);
    var input_is_empty = elem.val() == "";
    
    var length_before = parseInt(elem.attr("data-text-length"));
    var length_now = elem.val().length;
    if((length_now - length_before) > 3
        & $(elem).closest('.teacher').is($('#team_names').children('.teacher').first())
        ){
        var textarea = $("#team_names").find("textarea");
        textarea.show();
        textarea.val(elem.val());
        elem.val("");
        textarea.autoGrow();
        $('#team_names').children('.teacher').each(function(){
            if(!$(this).is($('#team_names').children('.teacher').first())){
                $(this).remove();
            }
        });
        elem.hide();
    }
    $('#countTeachers').html(getTeachers(true));
        
    elem.attr("data-text-length", length_now);

    /** show or hide the submit button */
    //if(!input_is_empty){$('#teachers_btn_submit').closest('.form-group').show();}

    /** if working on the last input element then add one more */
    if(!input_is_empty
        & elem.is($('#team_names').find('input').last())
        & elem.val().indexOf(",")<0
        & elem.val().indexOf(";")<0
    ){
        addTeacher(text = "");
    }
/** yii, active, record, findall, array yii, active, record, findall, array yii, active, record, findall, array yii, active, record, findall, array yii, active, record, findall, array yii, active, record, findall, array yii, active, record, findall, array yii, active, record, findall, array */ 

    
}

function addTeacher(text){
    
    if($('#teacher-name-textarea').val()!=""){
        return;
    }

    var last_elem = $("#team_names").children('.teacher').last();
    var new_elem = last_elem.clone(true);
    var new_input = new_elem.find("input");
    
    new_input.val(text);
    new_input.attr('data-text-length', text.length);
    new_input.attr('placeholder', '');
    
    new_elem.appendTo($('#team_names'));
    getTeachers();
    
    //$('#div_lesson_submit').show();
}

//<>

function getTeachers(getCount){
    if(typeof getCount == "undefined"){
        getCount = false;
    }
    var teachers = {};
    var textarea = $('#teacher-name-textarea').val();
    if(textarea != ""){
        var arr = textarea.split(",");
        if(arr.length == 1){
           arr = textarea.split(";"); 
        }
        for(var i = 0; i < arr.length; i++){
            if(arr[i].trim() != ""){
                teachers[arr[i].trim()] = "";
            }
        }
        $('#teacher-name-textarea').val(Object.keys(teachers).join(", "));
    }else{
        $('#team_names').children('.teacher').each(function(){
            if($(this).find("input").val()!=""){
            //console.log("test");
                teachers[$(this).find("input").val().trim()] = "";
            }
        });
        
    }
    if(getCount){
        return Object.keys(teachers).length;
    }    
    return Object.keys(teachers);
}