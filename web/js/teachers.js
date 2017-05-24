function teacher_join_poll_save_name(){

    /**  */
       
    query = {};
    query["activationkey"] = $("#activationkey").val();
    query["teacher-name"] = $("#teacher-name").val();
    
    $.ajax({
        url: cmConfig.restcorrectmeBaseUrl + "web/lesson/poll_save_teacher_name",
        type: 'POST',
        data: query,
        success: function(data) {
            
            if(data["error"] == ""){
                $("#div_save_name").hide();
                $("#div_save_name_error").hide();
                $("#div_save_name_success").html(_L['teacher_join_poll_save_name_success']);
                $("#div_save_name_success").show();
            }else{
                if(data["error"] == "name-too-short"){
                    $("#div_save_name_error").html(_L['teacher_join_poll_save_name_error_name_too_short']);
                    $("#div_save_name_error").show();
                }else{
                    $("#div_save_name_error").html(_L['teacher_join_poll_save_name_error']);
                    $("#div_save_name_error").show();
                }
            }
            
        },
        error: function(xhr, status, error) {
            state["rest_status"] = 0;
            var err = eval("(" + xhr.responseText + ")");
            if(state["program_version"]=="dev"){
                $("#div_save_name_error").html("server error");
                $("#div_save_name_error").show();
            }else{
                $("#div_save_name_error").html("server error");
                $("#div_save_name_error").show();
            }
        }
    });
    
}


function teachers_add_names(){
    $('#div_team_without_names').hide();
    $('#team_div').show();
    $('#team_names').find("input").focus();
    return false
}
function teachers_submit_names(){
    $('#lesson-poll_type').val('names');
    $('#teachers_collected').val(getTeachers().join("#"));
    $("form").submit();
}
function teachers_submit_single(){
    $('#lesson-poll_type').val('single');
    $("form").submit();
}          
function teachers_submit_team(){
    $('#lesson-poll_type').val("team");
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
        var ret = Object.keys(teachers).length;
        if(typeof teachers[$('#teacher-name').val()] == "undefined"){ret++;}
        return ret;
    }    
    return Object.keys(teachers);
}