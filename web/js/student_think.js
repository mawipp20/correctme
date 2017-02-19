var lesson = {
    "startKey":""
    ,"numTasks":""
    ,"numTeamsize":""
    ,"thinkingMinutes":""
    ,"typeTasks":""
    ,"typeMixing":""
    ,"earlyPairing":""

/**
not yet used
 * @property string $teacherKey
 * @property string $name
 * @property string $stats
 * @property string $status
 * @property string $lastchange
 * @property string $insert_timestamp
 */
};
var student = {
    "id":""
    ,"name":""
    ,"studentKey":""
/**
not yet used
 * @property string $startKey
 * @property integer $position
 * @property string $stats
 * @property string $status
*/    
};
var task_all = {};
function taskO(){
    this.taskId = "";
    this.num = "";
    this.type = "";
    this.answer_text = "";
}
var task = new taskO();

var answer_all = {};
function answerO(){
    this.startKey = "";
    this.taskId = "";
    this.studentId = "";
    this.answer_text = "";
    this.answer_text = "";
    this.status = "empty"
}
var answer = new answerO();

var state = {
    "error":""
    ,"eventPageX":0
    ,"program_version":""
    ,"goto_taskNum":1
    ,"numNavButtons":5 /** is adapted to window width */
    ,"do_save":1
    ,"do_get_data_all":1
    ,"do_server_retrieve":0
    };

const BLANK = "&nbsp;";

$(document).ready(function() {
    lesson["startKey"] = $("[name=startKey]").val();
    student["studentKey"] = $("[name=studentKey]").val();
    state["program_version"] = "dev";
    getTask();
});

function getTask(){
    
    /** taskNav: spinning loading sign next to position of last click */
    
    $("#taskNav").empty();
    var taskNavWait = '<div id="taskNavWait"><i class="fa fa-circle-o-notch fa-spin"></i> ';
    if(task["taskId"]!=0){
        taskNavWait += state["goto_taskNum"];
    }
    taskNavWait += '</div>';
    $('#taskNav').append($(taskNavWait));
    if(state["eventPageX"]!=0){
        $('#taskNavWait').css('padding-left', state["eventPageX"] - $('#taskNavWait').position().left - 40);
    }

    /** saving locally and moving to the next required task */ 


    /* getting and preparing the answer_text and the answer_status */
    var this_answer_text = "";
    var this_answer_status = "empty";
    if(typeof answer["status"] == "undefined"){
        this_answer_status = answer["status"];
    }    
    if($("#answer_text").length > 0){
        this_answer_text = $("#answer_text").val();
        /** changing answer status from empty to working when there is an answer_text */
        if(   answer["status"] == "empty"
            & this_answer_text!=""
            ){                
                this_answer_status = "working";
        }
    }
    

    if( !state["do_server_retrieve"]
        & typeof task_all[task.num] != "undefined"
        & typeof task_all[state["goto_taskNum"]] != "undefined"
        ){
        
        /** saving */
        var this_task = task_all[task.num];
        answer.startKey = lesson.startKey;
        answer.studentId = student.studentId;
        answer.taskId = this_task.taskId;
        answer.answer_text = this_answer_text;
        answer.status = this_answer_status;
        
        answer_all[task.num] = answer;
        
        /** moving on */
        task = task_all[state["goto_taskNum"]];
        if(typeof answer_all[state["goto_taskNum"]] != "undefined"){
            answer = answer_all[state["goto_taskNum"]];
        }else{
            answer = new taskO();
        }
        
        displayTasks();
        return;
        
    }

    /** connecting to server using restcorrectme REST service to save and get fresh data */
    
    query = {};
    query["startKey"] = lesson["startKey"];
    query["studentKey"] = student["studentKey"];
    query["taskId"] = task["taskId"];
    query["goto_taskNum"] = state["goto_taskNum"];
    query["answer_text"] = this_answer_text;
    query["answer_status"] = this_answer_status;
    query["do_save"] = state["do_save"];
    query["do_get_data_all"] = state["do_get_data_all"];
    
    console.log(query);
    
    $.ajax({
        url: 'http://localhost/restcorrectme/web/student/think',
        type: 'POST',
        data: query,
        success: function(data) {
            
            lesson = data["lesson"];
            student = data["student"];
            task_all = data["task_all"];
            answer_all = data["answer_all"];
            task = data["task"];
            answer = data["answer"];
            
            state["error"] = data["error"];
            state["debug"] = data["debug"];
            
            displayTasks();
        },

        error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            if(state["program_version"]=="dev"){
                state["error"] = 'server error';
            }else{
                state["error"] = err.Message;
            }
            displayTasks();
        }
    });
}

function displayTasks(){
    
    /** adapt the number of direct navigation buttons according to numTask and window size */
    state["numNavButtons"] = lesson["numTasks"];
    var maxNumNavButtons = Math.floor(($(window).width() / 100));
    if(maxNumNavButtons % 2 == 0){maxNumNavButtons += 1;}
    if(state["numNavButtons"] > maxNumNavButtons){state["numNavButtons"] = maxNumNavButtons;}
    

    /** clear container */
    $("#displayTasks").html("");
    
    /** in case there is an error */
    if(state["error"] != "" ){
        var msg = $('<div class="alert alert-danger"></div>');
        msg.html(state["error"]);
        $("#displayTasks").append(msg);
    }
    
    /** for debugging by developers */
    if(state["debug"] != "" ){
        var msg = $('<div class="alert alert-warning"></div>');
        msg.html(state["debug"]);
        $("#displayTasks").append(msg);
    }
    
    
    /** Answer input */
    var taskDisplay = $('<div data-id="' + task["taskId"] + '" class="form-group"></div>');
    
    var label_str = '<label for="answer_text" class="task_label"><span class="taskId">';
    label_str += task["num"] + '.</span><span class="task_text">' + task["task_text"] + '</span></label>';
    taskDisplay.append($(label_str));
    
    var texarea_rows = 1;
    if(lesson['typeTasks'] == "textarea"){
        texarea_rows = 5;
    }
    var textarea_str = '<textarea class="form-control text-area" rows="' + texarea_rows + '" id="answer_text">';
    textarea_str += answer["answer_text"] + '</textarea>';
    
    taskDisplay.append($(textarea_str));
    $("#displayTasks").append(taskDisplay);
    
    $(".text-area").autoGrow();
    
    $("#answer_text").focus();
       
    
    displayTaskNavigation();
        
}

function displayTaskNavigation(){
    
    /** task navigation buttons */
    
    $("#taskNav").empty();
    
    var curNum = task["num"];
    var numTasks = lesson["numTasks"];
    var numButtonsNextToCurNum = (state["numNavButtons"] - 1)/2;
    var numButtons = state["numNavButtons"];
        
    var taskNav = $('#taskNav');
    
    /** save button 

    taskNav.append($('<button type="button" id="btn_save" class="btn btn-success btn_task">speichern</button>'));
    taskNav.children("button").last().on("click", "", function( event ) {
        state["eventPageX"] = event.pageX;
        state["goto_taskNum"] = task["num"];
        getTask();
    });
    taskNav.children("button").last().wrap($('<div id="btn_save_wrap"></div>'));
*/    
    
    taskNav.append($('<div id="countdown_server_save">' + _L["student_think_countdown_server_save"] + '</div>'));
    
    /** back to 1 button */
    
    if(lesson["numTasks"] > state["numNavButtons"]){
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
            state["goto_taskNum"] = task["num"] - 1;
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
            getTask();
        });
        
    }
    
    /** foward button */
    
    btn_class = 'btn btn_task btn_task_move';
    taskNav.append($('<button type="button" id="btn_task_move_forward" class="btn btn_task btn_task_move"><i class="fa fa-caret-right" aria-hidden="true"></i></button>'));
    if(curNum == numTasks){
        taskNav.children("button").last().addClass('btn_task_inactive');
    }else{
        taskNav.children("button").last().on("click", "", function( event ) {
            state["eventPageX"] = event.pageX;
            state["goto_taskNum"] = task["num"] + 1;
            getTask();
        });
    }

    /** foward to end button */
    
    if(lesson["numTasks"] > state["numNavButtons"] & stopAt < lesson["numTasks"]){
        btn_class = 'btn btn_task btn_task_move';
        taskNav.append($('<button type="button" class="btn btn_task btn_task_move"><i class="fa fa-step-forward" style="font-size:smaller;" aria-hidden="true"></i><b>' + blanks(2) + lesson["numTasks"] + '</b></button>'));
        if(curNum == numTasks){
            taskNav.children("button").last().addClass('btn_task_inactive');
        }else{
            taskNav.children("button").last().on("click", "", function( event ) {
                state["eventPageX"] = event.pageX;
                state["goto_taskNum"] = lesson["numTasks"];
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