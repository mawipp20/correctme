function taskO(){
    this.num = "1";
    this.type = "textarea";
    this.answer_text = "";
}

$(document).ready(function() {
    $('#lesson_form').on('beforeValidate', function (e) {
        return lesson_exact_validate();
    });    
});

function dropdown_task_type(elem){
    var task_type = $(elem).closest('.task_type');
    task_type.attr("data-task-type", $(elem).attr("data-task-type"));
    task_type.children("button").first().html($(elem).text() + '<span class="caret"></span>');
    $(elem).closest('.task').find(".task_input").focus();
}
function taskOnInput(elem){

    var elem = $(elem);
    var input_is_empty = elem.val() == "";

    /** show or hide the submit button */    
    if(!input_is_empty){$('#lesson_btn_submit').closest('.form-group').show();}

    /** if working on the last input element then add one more */
    var this_task = $(elem).closest('.task');
    if(!input_is_empty & this_task.is($('#tasks').children('.task').last())){
        var this_newTask = this_task.clone();
        this_newTask.find(".task_input").val('');
        $('#tasks').append(this_newTask);
    }
    
}
function lesson_exact_validate(){
    var numTasks = 0;
    

    $('#tasks').children('.task').each(function() {
        var this_text = $(this).find(".task_input").val();
        if(this_text != ''){
            numTasks++;
            var this_task = new taskO();
            this_task.type = "";
            
        }
    });
    
    id="lesson-numtasks";
    return false;
}