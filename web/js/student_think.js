var state = {
    "taskId":""
    ,"startKey":""
    ,"studentKey":""
    ,};

$(document).ready(function() {
    state["startKey"] = $("[name=startKey]").val();
    state["studentKey"] = $("[name=studentKey]").val();
    state["taskId"] = 1;
    getTask();
});

function getTask(goto_taskId){
    
    if(typeof goto_taskId == "undefined"){
        goto_taskId = 1;
    }
    
    var data = state;
    data["goto_taskId"] = goto_taskId;
    data["answer_text"] = $("#answer_text").val();
        
    $.ajax({
        url: 'http://localhost/restcorrectme/web/student/think',
        type: 'POST',
        data: data,
        success: function(data) {
            state["taskId"] = data["task"]["taskId"];
            displayTasks(data);
        }
    });
}

function displayTasks(data){
    
    /**
    
    data is with example
    
    Object ( [error] => "errorMessage in case there is an error"
             [debug] => "if not empty this string is displayed in an bootstrap warning alert"
    
            [task] => Object (  [taskId] => 1 
                                [startKey] => y6dgse
                                [num] => 1
                                [type] => textarea
                                [task_text] => Erste Aufgabe
                        
                                [answer_text] => ... of this student, derived from table "answer" and put into the task object 
                        
                                )
                                
            [lesson] => Object ( [startKey] => y6dgse
                                [teacherKey] => ci4hxe
                                [teacherId] => 0 
                                [numTasks] => 3 
                                [numStudents] => 21 
                                [numTeamsize] => 3 
                                [thinkingMinutes] => 15 
                                [typeTasks] => textarea 
                                [earlyPairing] => 0 
                                [typeMixing] => random 
                                [namedPairing] => 1 
                                [insert_timestamp] => 2017-02-08 11:03:21 )
                                
            [student] => Object ([id] => 47 
                                [startKey] => y6dgse 
                                [name] => Streetview B.5 
                                [studentKey] => s-fpu8wc 
                                [position] => 1 
                                [stats] => 
                                [status] => empty 
                                [lastchange] => 2017-02-09 10:24:54 
                                [insert_timestamp] => 2017-02-09 10:24:54
                                 ) ) 
    */    
    

    $("#displayTasks").html("");
    
    /** in case there is an error */
    if(data["error"] != "" ){
        var msg = $('<div class="alert alert-danger"></div>');
        msg.html(data["error"]);
        $("#displayTasks").append(msg);
        return;
    }
    
    /** for debugging by developers */
    if(data["debug"] != "" ){
        var msg = $('<div class="alert alert-warning"></div>');
        msg.html(data["debug"]);
        $("#displayTasks").append(msg);
    }
    
    
    /**  */

    var templateTaskRow = '<div data-id="' + data["task"]["taskId"] + '" class="form-group"></div>';
    var task = $(templateTaskRow);
    
    var label_str = '<label for="answer_text" class="task_label"><span class="taskId">';
    label_str += data["task"]["taskId"] + '</span><span class="task_text">' + data["task"]["task_text"] + '</span></label>';
    task.append($(label_str));
    
    var texarea_rows = 1;
    if(data['lesson']['typeTasks'] == "textarea"){
        texarea_rows = 5;
    }
    var textarea_str = '<textarea class="form-control text-area" rows="' + texarea_rows + '" id="answer_text">';
    textarea_str += data["task"]["answer_text"] + '</textarea>';
    
    task.append($(textarea_str));
    $("#displayTasks").append(task);
    $(".text-area").autoGrow();
    
    for(var i = 1; i <= data["lesson"]["numTasks"];i++){
        var btn_class = "";
        var btn_click = 'getTask(\'' + i + '\')';
        if(i == data["task"]["taskId"]){
            btn_class = 'btn-primary';
        }
        var btn = '<button type="button" class="btn ' + btn_class + ' btn_task" onclick="' + btn_click + '">' + i + '</button>';
        $("#displayTasks").append(btn);
    }
        
}