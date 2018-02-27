var textarea_autogrow_reduce_height_by = 0; /** autoGrow plugin adapted so that the textarea-row-line is the same as the type-button */ 
var lesson_type = "";
var init_title_validation = false;

function taskO(){
    this.num = "1";
    this.type = "textarea";
    this.task_text = "";
}

$(document).ready(function() {

    lesson_type = $('#lesson-type').val();

    if(typeof uploadedTasks != "undefined"){
            
        /** reverse order of task as they are inserted on top */    
        var tempArr = [];
        //for (var key in uploadedTasks){tempArr.push(key);}
        for (var key in uploadedTasks){tempArr.unshift(key);}
        for(var i = 0; i < tempArr.length; i++) {
            var this_question = tempArr[i];
            addTask(this_question, type = uploadedTasks[this_question], "top");
        }
        $('#lesson_btn_submit').closest('.form-group').show();
        $("#poll_tasks_input_mode_link").css("visibility", "visible");
    }
    
    if($('#tasks').length > 0){
        var this_input = $('.task_input');
        var this_btn_type = $('.task_type').children("button").first();                
        textarea_autogrow_reduce_height_by = parseInt(this_input.css('height')) - parseInt(this_btn_type.css('height'));
        console.log(this_input.css('height') + "/" + this_btn_type.css('height'));
        this_input.autoGrow();
        this_input.keydown(function( event ) {
            if (event.ctrlKey && event.which == 86) {
                //display_btn_task_text_analyse(this);
            }
            if (event.which == 9) {
                console.log("tab");
                var this_task = $(this).closest(".task");
                if(!this_task.is($("#tasks").children(".task").last())){
                    this_task.next().find(".task_type").children("button").first().focus();
                }
            }
        });
    }
    if($('#lessonupload-lessonfile').length > 0){
        $('#lessonupload-lessonfile').val('');
        $('#lessonFile-info').html('&nbsp;');
    }
    
    $("#lesson_form").on("afterValidateAttribute", function(event, attribute, messages) {
             if(messages != ""){$(window).scrollTop(0);}
             return true;
    });

    $('body').mousemove(function( event ) {
        if(!init_title_validation){
            $('#lesson_form').yiiActiveForm('validateAttribute', 'lesson-title');
            init_title_validation = true;
        }
    });
    $(window).scroll(function( event ) {
        if(!init_title_validation){
            $('#lesson_form').yiiActiveForm('validateAttribute', 'lesson-title');
            init_title_validation = true;
        }
    });

});

function dropdown_task_type(elem){
    var task_type = $(elem).closest('.task_type');
    task_type.attr("data-task-type", $(elem).attr("data-task-type"));
    task_type.children("button").first().html($(elem).text() + '<span class="caret"></span>');
    var this_input = $(elem).closest('.task').find(".task_input");
    this_input.focus();
    
    for(var this_class in this_input.prop("classList")){
        var prop = this_input.prop("classList")[this_class];
        if(typeof prop == "string"){
            if(prop.substr(0, 10)=="task_type_"){
                this_input.removeClass(prop);
            }
        }
    }
    this_input.addClass("task_type_" + $(elem).attr("data-task-type"));
    console.log(this_input.prop("classList"));
}

function taskOnInput(elem){

    var elem = $(elem);

    var input_is_empty = elem.val() == "";
    
    $("#poll_tasks_input_mode_link").css("visibility", "visible");
    
    var length_before = parseInt(elem.attr("data-text-length"));
    var length_now = elem.val().length;
    if((length_now - length_before) > 3 && elem.val().match(/\n/g) != null){
        display_btn_task_text_analyse(elem);
    }
    //console.log(length_now - length_before);
    elem.attr("data-text-length", length_now);

    /** show or hide the submit button */    
    if(!input_is_empty){$('#lesson_btn_submit').closest('.form-group').show();}

    /** if working on the last input element then add one more */
    if(!input_is_empty & $(elem).closest('.task').is($('#tasks').children('.task').last())){
        addTask(text = "", type = "clone", "bottom");
    }
    
    
}

function addTask(text, type, where){
    /** type can be "clone" so that the type of the last task is copied  */
    var last_task = $("#tasks").children('.task').last();
    var this_newTask = last_task.clone(true);
    this_newTask.find(".task_input").val(text);
    this_newTask.find(".task_input").attr('data-text-length', text.length);
    if(type != "clone"){
        this_newTask.find(".task_type").attr("data-task-type", type);
        var this_html = _L_lesson['lesson_tasks_type_' + type];
        if(lesson_type == "poll"){
            this_html = _L_poll['poll_tasks_type_' + type];
        }
        this_newTask.find(".task_type").find("button").html(this_html + ' <span class="caret"></span>');
        this_newTask.find(".form-control").addClass("task_type_" + type)
    }
    this_newTask.find(".task_input").attr('placeholder', '');
    this_newTask.find(".btn_analyse_task_text").remove();
    if(where == "bottom"){
        this_newTask.appendTo($('#tasks'));
    }else if(where == "top"){
        var first_task = $("#tasks").children('.task').first();
        first_task.before(this_newTask);
    }else if(isObject(where)){
        this_newTask.insertBefore(where);
    }   
    //this_newTask.find(".task_input").autoGrow();
    $('#div_lesson_submit').show();
}

function display_btn_task_text_analyse(elem){
   
    
    var elem = $(elem);

    elem.closest(".task").find(".btn_analyse_task_text").remove();
    
    var attach_to_elem = elem.closest(".task").find('.task_action_buttons_label');
    var btn_str = '<button class="btn btn-success btn_analyse_task_text"';
    btn_str += ' onclick="split_task_text(this);return false;"';
    btn_str += '>' + _L_lesson["lesson_tasks_btn_analyse_task_text"];
    btn_str += '</button>';
    attach_to_elem.append(btn_str);
}

function task_remove(elem){
    if($(elem).hasClass("task")){
        var this_task = $(elem);
    }else{
        var this_task = $(elem).closest('.task');
    }
    if( $("#tasks").children(".task").length > 1
    ){
        if(this_task.is($("#tasks").children(".task").last())){
            if(this_task.prev().find(".task_input").val()!=""){
                this_task.find(".task_input").val("");
                return;
            }  
        }
        this_task.remove();
    }
}
function task_start_sort(elem){

    if(($('.btn_task_sort_place_here').length > 0)){
        return;
    }                

    $(elem).addClass("btn-success");
    if($('.btn_task_sort_place_here').length == 0){
        $('#tasks').children('.task').each(function() {
            var btn_str = '<br/><button class="btn btn_task_sort_place_here"';
            btn_str += ' onclick="task_sort_place_here(this);return false;"';
            btn_str += '>' + '<i class="fa fa-arrow-left" aria-hidden="true"></i>';
            btn_str += '</button>';
            var new_elem = $(this).find('.task_action_buttons_label').append(btn_str);
        });
    }
}

function split_task_text(elem){
    var elem = $(elem);
    var this_task = $(elem).closest('.task');
    var this_input = this_task.find(".task_input");
    var arr = this_input.val().split(/\n/);
    for(i = 0; i < arr.length; i++){
        var t = arr[i];
        if(t != ""){
            
            /** numbered tasks texts with dots or bracket are cleaned of these numbers */
            t = t.replace(/^[0-9]{1,}[.)]{0,1}([ ]|[\t])*/g, '');
            
            var type_use = this_task.find(".task_type").attr("data-task-type");
            
            /** check if a task-type is given at the start of the line */
            var line_arr = t.split("=");
            if(line_arr.length > 1){
                if(typeof controller_lesson["taskTypes"][line_arr[0].trim()] != "undefined"){
                    type_use = line_arr[0].trim();
                }
                line_arr.shift();
            }
            
            addTask(line_arr.join("="), type_use, this_task);

        }
    }
    this_task.remove();
}

function lesson_exact_validate_tasks(){
    
    /** in case of quick lesson without specific task texts: skip tasks validation */
    if($('#tasks').length == 0){return true;}
    
    var this_num = 0;
    var ret = false;
    var new_tasks = {};
    
    /** for polls an start info with the title and the teacher will be automatically inserted */
    if($("#lesson-type").val()=="poll"){
        this_num++;
        var this_task = new taskO();
        this_task.type = "sysinfo";
        this_task.task_text = "#pollStart#";
        this_task.num = this_num;
        new_tasks[this_num] = this_task;
    }    
    
    $('#tasks').children('.task').each(function() {
        var this_text = $(this).find(".task_input").val();
        if(this_text != ''){
            this_num++;
            ret = true;
            var this_task = new taskO();
            this_task.type = $(this).find(".task_type").attr("data-task-type");
            this_task.task_text = this_text;
            this_task.num = this_num;
            new_tasks[this_num] = this_task;
        }
    });
    $('#new_tasks').val(JSON.stringify(new_tasks));
    $('#lesson-numtasks').val(this_num);
    if(ret === true){
        return new_tasks;
    }
    return ret;
}
function lesson_file_onchange(e){
    $('#lessonFile-info').css("color", "black");
    $('#lessonFile-info').html($(e).val());
}
function lesson_upload_on_submit(e){
    if($('#lessonupload-lessonfile').val()!=""){
        e.form.submit();
    }else{
        $('#lessonFile-info').html(_L_lesson["lesson_upload_no_file_picked_warning"]);
        $('#lessonFile-info').css("color", "red");
    }
}
function toggle_text_mode(){
    var elem = $("#poll_tasks_input_mode_link");
    var target_mode = "text";
    if(elem.html() == poll_tasks_input_mode["text"]){
        elem.html(poll_tasks_input_mode["input"]);
    }else{
        elem.html(poll_tasks_input_mode["text"]);
        target_mode = "input";
        
    }
    
    if(target_mode == "text"){
        var str = "";
        var tasks = lesson_exact_validate_tasks();
        for(var i in tasks){
            if(tasks[i].type == "sysinfo"){continue;}
            str += tasks[i].type + " = " + tasks[i].task_text + "\n";
        }
        $('#tasks').children('.task').each(function() {
            task_remove($(this));
        });
        $("#tasks").hide();
        $("#tasks_edit").show();
        $("#tasks_edit_textarea").val(str);
        $("#tasks_edit_textarea").autoGrow();
        
    }
    
    
}