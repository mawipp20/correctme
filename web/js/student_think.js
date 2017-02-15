var state = {
    "startKey":""
    ,"studentKey":""
    ,"numTasks":0
    ,"taskNum":1
    ,"taskId":0
    ,"goto_taskNum":1
    ,"numNavButtons":5 /** is adapted to window width */
    ,"eventPageX":0
    ,};

const BLANK = "&nbsp;";

$(document).ready(function() {
    state["startKey"] = $("[name=startKey]").val();
    state["studentKey"] = $("[name=studentKey]").val();
    getTask();
});

function getTask(){
    
    /** taskNav: spinning loading sign next to position of last click */
    
    $("#taskNav").empty();
    var taskNavWait = '<div id="taskNavWait"><i class="fa fa-circle-o-notch fa-spin"></i> ';
    if(state["taskId"]!=0){
        taskNavWait += state["goto_taskNum"];
    }
    taskNavWait += '</div>';
    $('#taskNav').append($(taskNavWait));
    if(state["eventPageX"]!=0){
        $('#taskNavWait').css('padding-left', state["eventPageX"] - $('#taskNavWait').position().left - 40);
    }

    /** getting new data */

    var data = state;
    data["answer_text"] = $("#answer_text").val();
    
    console.log(data);
    
    $.ajax({
        url: 'http://localhost/restcorrectme/web/student/think',
        type: 'POST',
        data: data,
        success: function(data) {
            if(data["error"] != ""){alert(data["error"] + data["debug"]);}
            state["taskNum"] = data["task"]["num"];
            state["taskId"] = data["task"]["taskId"];
            state["taskNum"] = data["task"]["num"];
            state["numTasks"] = data["lesson"]["numTasks"];
            state["numNavButtons"] = data["lesson"]["numTasks"];
            var maxNumNavButtons = Math.floor(($(window).width() / 100));
            if(maxNumNavButtons % 2 == 0){maxNumNavButtons += 1;}
            if(state["numNavButtons"] > maxNumNavButtons){state["numNavButtons"] = maxNumNavButtons;}
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
    
    
    /** Answer input */

    var templateTaskRow = '<div data-id="' + data["task"]["taskId"] + '" class="form-group"></div>';
    var task = $(templateTaskRow);
    
    var label_str = '<label for="answer_text" class="task_label"><span class="taskId">';
    label_str += data["task"]["taskId"] + '.</span><span class="task_text">' + data["task"]["task_text"] + '</span></label>';
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
    
    displayTaskNavigation();
        
}

function displayTaskNavigation(){
    
    /** task navigation buttons */
    
    $("#taskNav").empty();
    
    var curNum = state["taskNum"];
    var numTasks = state["numTasks"];
    var numButtonsNextToCurNum = (state["numNavButtons"] - 1)/2;
    var numButtons = state["numNavButtons"];
        
    var taskNav = $('#taskNav');
    
    /** back to 1 button */
    
    if(state["numTasks"] > state["numNavButtons"]){
        btn_class = 'btn btn_task btn_task_move';
        taskNav.append($('<button type="button" class="btn btn_task btn_task_move"><i class="fa fa-step-backward" aria-hidden="true" style="font-size:smaller;"></i></button>'));
        if(curNum == 1){
            taskNav.children("button").last().addClass('btn_task_inactive');
        }else{
            taskNav.children("button").last().on("click", "", function( event ) {
                state["eventPageX"] = event.pageX;
                state["goto_taskNum"] = 1;
                getTask();
            });
        }
    }
    

    /** back button */
      
    taskNav.append($('<button type="button" class="btn btn_task btn_task_move"><i class="fa fa-caret-left" aria-hidden="true"></i></button>'));
    if(curNum == 1){
        taskNav.children("button").last().addClass('btn_task_inactive');
    }else{
        taskNav.children("button").last().on("click", "", function( event ) {
            state["eventPageX"] = event.pageX;
            state["goto_taskNum"] = state["taskNum"] - 1;
            getTask();
        });
    }    

    /** add numbered buttons */
    
    var startAt = curNum - numButtonsNextToCurNum;
    if(startAt > (numTasks - state["numNavButtons"] + 1)){startAt = numTasks - state["numNavButtons"] + 1;}
    if(startAt < 1){startAt = 1;}
    
    var stopAt = curNum + numButtonsNextToCurNum;
    if(stopAt < numButtons){
        stopAt = numButtons;
    }
    if(stopAt > numTasks){
        stopAt = numTasks;
    }
    
    for(var i = startAt; i <= stopAt; i++){
        
        if(i > numTasks){break;}
           
        var btn_class = "";
        if(i == curNum){
            btn_class = 'btn-primary';
        }
        var btn_text = i;
        if(i == numTasks){
            btn_text += '.';
        }
        
        var btn = $('<button type="button" data-num="' + i + '" class="btn ' + btn_class + ' btn_task">' + btn_text + '</button>');
        taskNav.append(btn);
        taskNav.children("button").last().on("click", "", function( event ) {
            state["eventPageX"] = event.pageX;
            state["goto_taskNum"] = $(this).attr("data-num");
            //alert(state["goto_taskNum"]);
            getTask();
        });
        
    }
    
    /** foward button */
    
    btn_class = 'btn btn_task btn_task_move';
    taskNav.append($('<button type="button" class="btn btn_task btn_task_move"><i class="fa fa-caret-right" aria-hidden="true"></i></button>'));
    if(curNum == numTasks){
        taskNav.children("button").last().addClass('btn_task_inactive');
    }else{
        taskNav.children("button").last().on("click", "", function( event ) {
            state["eventPageX"] = event.pageX;
            state["goto_taskNum"] = state["taskNum"] + 1;
            getTask();
        });
    }

    /** foward to end button */
    
    if(state["numTasks"] > state["numNavButtons"] & stopAt < state["numTasks"]){
        btn_class = 'btn btn_task btn_task_move';
        taskNav.append($('<button type="button" class="btn btn_task btn_task_move"><i class="fa fa-step-forward" style="font-size:smaller;" aria-hidden="true"></i><b>' + blanks(2) + state["numTasks"] + '</b></button>'));
        if(curNum == numTasks){
            taskNav.children("button").last().addClass('btn_task_inactive');
        }else{
            taskNav.children("button").last().on("click", "", function( event ) {
                state["eventPageX"] = event.pageX;
                state["goto_taskNum"] = state["numTasks"];
                getTask();
            });
        }
    }

}

function blanks(numBlanks){
    var ret = "";
    for(i=0;i<numBlanks;i++){ret += BLANK;}
    return ret;
}