/**

- Was ist bei nur einer Aufgabe mit der Navigation?
- Alles fertig Button in Betrieb nehmen
- Aufgaben eingeben / hochladen



*/

var lesson = {
    "startKey":""
    ,"lesson.numTasks":""
    ,"type":""
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
    ,"teacherName":"" /** name of the teacher in a poll - retrieved by the rest service */
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
    this.num = "1";
    this.type = "";
    this.task_text = "";
}
var task = new taskO();

var answer_all = {};
function answerO(){
    this.startKey = "";
    this.taskId = "";
    this.studentId = "";
    this.answer_text = "";
    this.status = "empty"
}
var answer = new answerO();

var state = {
    "error":"" 
    ,"eventPageX":0 /** position where a nav button was clicked to place the waiting for answer symbol nearby */
    ,"program_version":"" /** can be set to "dev" for debugging */
    ,"goto_taskNum":1 /** number of the requested task */
    ,"numNavButtons":5 /** is adapted to window width */
    ,"rest_status":"" /** empty string means display tasks for the first time; 0 = not in connection; 1 waiting for rest answer */
    ,"restInterval_seconds":5000 /** interval millisecondes when the the rest service is called */
    ,"howOftenNextTaskDelay":500 /** interval milliseconds when the the rest service is called */
    ,"hasChanges":0
    ,"lastSaved":0
    ,"pollStart":true /** at the start of a poll the title of the poll and the name of the teacher is shown with a start-now-button */
    ,"millisecondsServerMinusClient":false /** difference in time between server and client, used for the minutes countdown */ 
    ,"messageHelpButtonShown":false /** has the info message about the call for help already been shown */ 
    };

const BLANK = "&nbsp;";
var restInterval;

$(document).ready(function() {
    lesson["startKey"] = $("[name=startKey]").val();
    student["studentKey"] = $("[name=studentKey]").val();
    state["program_version"] = "dev";
    rest_service();
    restInterval = setInterval(function() {
        saveAnswer();
        rest_service();
        //if (/* stop */) clearInterval(restInterval)       
    }, state["restInterval_seconds"]);
});

function getTask(){
        
    /** taskNav: spinning loading sign next to position of last click */
    
    $("#taskNav_first").empty();
    $("#taskNav_second").empty();
    
    var taskNavWait = '<div id="taskNavWait"><i class="fa fa-circle-o-notch fa-spin"></i> ';
    if(task["taskId"]!=0){
        taskNavWait += state["goto_taskNum"];
    }
    taskNavWait += '</div>';
    $('#taskNav_first').append($(taskNavWait));
    if(state["eventPageX"]!=0){
        $('#taskNavWait').css('padding-left', state["eventPageX"] - $('#taskNavWait').position().left - 40);
    }


    /** save */
    saveAnswer();

    /** moving on to the next required task */
    if(typeof task_all[state["goto_taskNum"]] != "undefined"){
        task = task_all[state["goto_taskNum"]];
    }else{
        task = new taskO();
    }
    
    /** task text transformation */
    if(lesson.type == "poll"){
        var this_text = task.task_text;
        this_text = this_text.replace("#pollStart#", _L["student_think_poll_start_info"]);
        this_text = this_text.replace("#pollTitle#", lesson.title);
        if(student.teacherName != ""){
            this_text = this_text.replace("#teacherName#", student.teacherName);
            this_text = this_text.replace("#teacherNameDativ#", student.teacherName.replace("Herr ", "Herrn "));
        }else{
            this_text = this_text.replace("#teacherName#", _L["student_think_poll_default_teacher_name"]);
            this_text = this_text.replace("#teacherNameDativ#", _L["student_think_poll_default_teacher_name"]);
        }
        task.task_text = this_text;
    }
  

    if(typeof answer_all[state["goto_taskNum"]] != "undefined"){
        answer = answer_all[state["goto_taskNum"]];
    }else{
        answer = new answerO();
    }
    
    displayTasks();
    state["error"] = "";
    state["debug"] = ""; 
}

function saveAnswer(){

    /** save the answer locally  */ 

    /* get text input */
    if($("#answer_text").length > 0){
        answer.answer_text = $("#answer_text").val();
    }
    
    /** changing answer status from empty to working when there is an answer_text */
    if(   answer.status == "empty"
        & answer.answer_text!=""
        ){                
            answer.status = "working";
    }
    
    if( typeof task_all[task.num] != "undefined" ){
        
        var this_task = task_all[task.num];
        answer.startKey = lesson.startKey;
        answer.studentId = student.id;
        answer.taskId = this_task.taskId;
        
        answer_all[task.num] = answer;        
    }    
}

function rest_service(redirectTo){

    /** called by interval every 10 seconds (or more depending on server work load)  */

    saveAnswer();
    
    if(state['millisecondsServerMinusClient']!==false){
        displayLessonInfo();
    }


    /* stop if the last query (rest_status) is still going on or when there are no changes */
    if( typeof redirectTo == "undefined" &
        (state["rest_status"] == 1
        | state["hasChanges"] === false
        )
    ){
        console.log("no changes:" + state["rest_status"]);
        return;
    }


    /** connecting to server using restcorrectme REST service to save and get fresh data */
    
    query = {};
    query["startKey"] = lesson["startKey"];
    query["studentKey"] = student["studentKey"];
    query["answer_all"] = answer_all;
    
    console.log(query);
    
    var this_do_getTasks_first_time = false;
    if(state["rest_status"] === ""){this_do_getTasks_first_time = true;}
    
    state["rest_status"] = 1;
    
    var restUrl = "../../../" + cmConfig.restcorrectmePath + "web/student/think";
    
    
    $.ajax({

        url: restUrl,
        type: 'POST',
        data: query,
        success: function(data) {
            
            state["rest_status"] = 0;
            
            lesson = data["lesson"];
            
            student = data["student"];
            task_all = data["task_all"];
            answer_all = data["answer_all"];
            
            state["error"] = data["error"];
            state["debug"] = data["debug"];
            state["lastSaved"] = data["lastSaved"];
            
            if(typeof redirectTo != "undefined"){window.location.href = redirectTo; return;}

            
            state["hasChanges"] = false;
            
            if(this_do_getTasks_first_time){
                getTask();
            }
            if(state['millisecondsServerMinusClient']===false){
                var this_now_client = new Date();
                var this_now_server = Date.parse(state["lastSaved"]);
                state['millisecondsServerMinusClient'] = this_now_server - this_now_client.getTime();
            }
            
        },

        error: function(xhr, status, error) {
            state["rest_status"] = 0;
            var err = eval("(" + xhr.responseText + ")");
            if(state["program_version"]=="dev"){
                state["error"] = 'server error';
            }else{
                state["error"] = err.Message;
            }
        }
    });
    
}

function displayTasks(){

   
    /** adapt the number of direct navigation buttons according to numTask and window size */
    state["numNavButtons"] = lesson.numTasks;
    var maxNumNavButtons = Math.floor(($(window).width() / 80));
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
    
    var taskDisplay = $('<div data-id="' + task["taskId"] + '" class="form-group"></div>').appendTo($("#displayTasks"));
    
    var label_str = '';
    label_str += '<div class="well row task_label">';
    
    
    var this_cols = { 'num': {'xs':2, 'sm':1, 'md':1}
                    , 'text': {'xs':8, 'sm':9, 'md':10}
                    , 'help': {'xs':2, 'sm':2, 'md':1}};
    
    if(!cmConfig.displayTaskLabelNum){
        this_cols.text.xs += this_cols.num.xs;
        this_cols.text.sm += this_cols.num.sm;
        this_cols.text.md += this_cols.num.md;
    }
    if(!cmConfig.displayBtnHelp){
        this_cols.text.xs += this_cols.help.xs;
        this_cols.text.sm += this_cols.help.sm;
        this_cols.text.md += this_cols.help.md;
    }
    
    if(cmConfig.displayTaskLabelNum){
        var this_col_class = 'col-xs-' + this_cols["num"]["xs"];
        this_col_class += ' col-sm-' + this_cols["num"]["sm"];
        this_col_class += ' col-md-' + this_cols["num"]["md"]
        label_str += '<div class="' + this_col_class + '">';
        label_str += '<span class="taskId">' + task["num"] + '.</span>';
        label_str += '</div>';
    }
    
    var this_col_class = 'col-xs-' + this_cols["text"]["xs"];
    this_col_class += ' col-sm-' + this_cols["text"]["sm"];
    this_col_class += ' col-md-' + this_cols["text"]["md"];
    
    label_str += '<div class="' + this_col_class + ' no_padding_right no_padding_left">';
    label_str += '<span class="task_text">' + task["task_text"] + '</span>';
    label_str += '</div>';
    
    if(cmConfig.displayBtnHelp){
        var this_col_class = 'col-xs-' + this_cols["help"]["xs"];
        this_col_class += ' col-sm-' + this_cols["help"]["sm"];
        this_col_class += ' col-md-' + this_cols["help"]["md"]
        label_str += '<div id="displayBtnHelp" style="text-align:right;" class="' + this_col_class + ' no_padding_left"></div>';
    }
    
    label_str += '</div>';
    taskDisplay.append($(label_str));
    
    if(task.type == "info" | task.type == "sysinfo"){
        $(".task_label").addClass("task_label_info");
    }

    if(task.type == "text"){
        var str = '<textarea class="form-control text-area" rows="1"';
        str += ' id="answer_text" oninput="textarea_oninput(this);">';
        str += answer["answer_text"] + '</textarea>';
        taskDisplay.append($(str));
        $(".text-area").autoGrow();
        $("#answer_text").focus();
    }
    
    if(task.type == "how-true" | task.type == "how-often"){
        
        $('#student_think_btn_task_finished').css('visibility', 'hidden');
        
        var str = "";

/**
        pie_percentage = ["", "0", "25", "75", "100", ""];
        for(var i = 1; i <= 5; i++){
            str += get_taskDisplay_how_often_true_button(task.type, pie_percentage[i], i);
        }
*/        
        for(var i in task_types[task.type]["values"]){
            str += get_taskDisplay_how_often_true_button(task.type, i);
        }
        
        
        taskDisplay.append($(str));
        $(".pie").progressPie({color:$(".task_label").css("background-color"), valueData:"val", size:30, strokeWidth: 1});
    }   
    
    displayTaskNavigation();
    displayLessonInfo();
        
}

function get_taskDisplay_how_often_true_button(type, key){
        var scale = task_types[type];
        var data_val = scale["values"][key];
        var pie_percentage = scale["pie_percentages"][key];
        
        var this_btn_css_class = 'btn btn-block btn-primary task_how_true_often_button';
        if(answer["answer_text"] == data_val){
            this_btn_css_class += ' task_how_true_often_button_selected';
        }
        else if(pie_percentage === ''){
            this_btn_css_class += ' task_how_true_often_button_dont_know';
        }
        var btn_start = '<button onclick="task_how_often_true_button_click(this);" data-val="' + data_val + '" type="button" class="' + this_btn_css_class + '">';
        var this_class = 'pie';
        if(pie_percentage === ''){
            this_class += ' invisible';
        }
        var ret = btn_start + '<span class="' + this_class + '" data-val="' + pie_percentage + '"></span>';
        ret += '<span class="task_how_true_often_label">' + _L['scale_' + type + '-' + key] + '</span></button>';
        return ret;
}

function displayTaskNavigation(){
    
    var taskNav_first = $('#taskNav_first');
    var taskNav_second = $('#taskNav_second');
    
    taskNav_first.empty();
    taskNav_second.empty();
    
    /**
    var this_col_class = 'col-xs-' + this_cols["num"]["xs"];
    this_col_class += ' col-sm-' + this_cols["num"]["sm"];
    this_col_class += ' col-md-' + this_cols["num"]["md"]
    */
    
    taskNav_first.append($('<div id="taskNav_first_left" class="col-xs-10 col-sm-10 col-md-10 vcenter"></div><div id="taskNav_first_right" class="col-xs-2 col-sm-2 col-md-2 vcenter"></div>'));
    var taskNav_first_left = $('#taskNav_first_left');
    var taskNav_first_right = $('#taskNav_first_right');   
    
    
    if(!cmConfig.displayTaskNavSecond){taskNav_second.css("display", "none");}
    
    if(lesson.numTasks == 1){return;}
    
    /** horizontal align */
    if(task.type == 'text'){
        taskNav_first_left.addClass('text-center');
    }else{
        taskNav_first_left.removeClass('text-center');
    }
        
    /** task navigation buttons */
    
    var numButtonsNextToCurNum = (state["numNavButtons"] - 1)/2;

    /** back button */
    
    // _L['student_think_btn_back']
      
    taskNav_first_left.append($('<button type="button" class="btn btn-lg btn_task btn_task_move1">' + '<i class="fa fa-caret-left" aria-hidden="true"></i>' + '</button>'));
    if(task.num == 1
        |   ((task.type == 'how-true' | task.type == 'how-often')
            & task.answer_text == '')
        ){
        taskNav_first_left.children("button").last().addClass('btn_task_inactive');
        taskNav_first_left.children("button").last().addClass('display_none');
    }else{
        taskNav_first_left.children("button").last().on("click", "", function( event ) {
            state["eventPageX"] = event.pageX;
            state["goto_taskNum"] = task["num"] - 1;
            getTask();
        });
    }
        
    /** finished button */
    if(task.type == 'text'){
        show_btn_finished();
    }


    /** foward button */
    
    var btn_task_move_forward_caption = '<i class="fa fa-caret-right" aria-hidden="true"></i>';
    if(task.type == "sysinfo" & task.num == 1){btn_task_move_forward_caption = _L["student_think_btn_start_poll"];}
    taskNav_first_left.append($('<button type="button" id="btn_task_move_forward" class="btn btn-lg btn_task btn_task_move1">' + btn_task_move_forward_caption + '</button>'));
    
    
    /** position info */
    if(!cmConfig.displayTaskNavSecond){
        taskNav_first_right.append($('<span class="taskOfTaskPositionInfo">' + task.num + '/' + lesson.numTasks + '</span>'));
        if(task.type == 'how-true' | task.type == 'how-often'){
            $('#taskNav_first').css("max-width", $('.task_how_true_often_button').css("max-width"));}
    }

    
    if(task.num == lesson.numTasks
        |   ((task.type == 'how-true' | task.type == 'how-often')
            & answer.answer_text == '')
    ){
        taskNav_first_left.children("button").last().addClass('btn_task_inactive');
    }else{
        taskNav_first_left.children("button").last().on("click", "", function( event ) {
            state["eventPageX"] = event.pageX;
            state["goto_taskNum"] = task["num"] + 1;
            getTask();
        });
    }

    /** back to start button */
    
    if(lesson.numTasks > state["numNavButtons"]){
        btn_class = 'btn btn_task btn_task_move';
        taskNav_second.append($('<button type="button" class="btn btn_task"><i class="fa fa-step-backward" aria-hidden="true" style="font-size:smaller;"></i></button>'));
        if(task.num == 1){
            taskNav_second.children("button").last().addClass('btn_task_inactive');
        }else{
            taskNav_second.children("button").last().on("click", "", function( event ) {
                state["eventPageX"] = event.pageX;
                state["goto_taskNum"] = 1;
                getTask();
            });
        }
    }
       
    /** add numbered buttons */
    
    var startAt = task.num - numButtonsNextToCurNum;
    if(startAt > (lesson.numTasks - state["numNavButtons"] + 1)){startAt = lesson.numTasks - state["numNavButtons"] + 1;}
    if(startAt < 1){startAt = 1;}
    
    var stopAt = task.num + numButtonsNextToCurNum;
    if(stopAt < state["numNavButtons"]){
        stopAt = state["numNavButtons"];
    }
    if(stopAt > lesson.numTasks){
        stopAt = lesson.numTasks;
    }
    
    for(var i = startAt; i <= stopAt; i++){
        
        if(i > lesson.numTasks){break;}
           
        var btn_class = "";
        if(i == task.num){
            btn_class = 'btn-primary btn_task_current';
        }else if (typeof answer_all[i] != 'undefined'){
            if(answer_all[i]['status'] == 'finished'){
                btn_class = 'btn_task_finished';
            }
        }
        var btn_text = i;
        if(i == lesson.numTasks){
            btn_text += '.';
        }
        
        var btn = $('<button type="button" data-num="' + i + '" class="btn ' + btn_class + ' btn_task">' + btn_text + '</button>');
        taskNav_second.append(btn);
        if(i != task.num){
            taskNav_second.children("button").last().on("click", "", function( event ) {
                state["eventPageX"] = event.pageX;
                state["goto_taskNum"] = $(this).attr("data-num");
                getTask();
            });
        }
        
    }
    
    /** foward to end button */
    
    if(lesson.numTasks > state["numNavButtons"] & stopAt < lesson.numTasks){
        btn_class = 'btn btn_task btn_task_move1';
        taskNav_second.append($('<button type="button" class="btn btn_task btn_task_move1"><i class="fa fa-step-forward" style="font-size:smaller;" aria-hidden="true"></i><b>' + blanks(2) + lesson.numTasks + '</b></button>'));
        if(task.num == lesson.numTasks){
            taskNav_second.children("button").last().addClass('btn_task_inactive');
        }else{
            taskNav_second.children("button").last().on("click", "", function( event ) {
                state["eventPageX"] = event.pageX;
                state["goto_taskNum"] = lesson.numTasks;
                getTask();
            });
        }
    }

    /** append "Help with this task" button in case of taskType = text */
    
    var displayBtnHelp = $('#displayBtnHelp').empty();
    if(task.type == 'text'){
        var this_symbol = 'fa-life-ring';
        var this_style = 'color:red;border:1px solid blue;background:white;'
        if(answer["status"] == "help"){
            this_symbol = 'fa-user-o';
            this_style += 'color:black;border:1px solid black;background:yellow;';        
        }
        var str = '<button type="button" style="' + this_style + '" id="student_think_btn_task_help" class="btn btn_task">';
        str += '<i class="fa ' + this_symbol + '" aria-hidden="true"></i>';
        str += '</button>';
        displayBtnHelp.append($(str));
        displayBtnHelp.children("button").last().on("click", "", function( event ) {
            if(answer["status"] == "help"){
                if(answer["status"]["answer_text"]==''){
                    answer["status"] = "empty";
                }else{
                    answer["status"] = "working";
                }
            }else{
                answer["status"] = "help";
            }
            state["hasChanges"] = true;
            saveAnswer();
            state["eventPageX"] = event.pageX;
            state["goto_taskNum"] = task["num"];
            getTask();
            if(!state['messageHelpButtonShown']){
                $('#student_think_help_message_btn_toggle').click();
                state['messageHelpButtonShown'] = true;
            }
        });
    }
}

function displayLessonInfo(){

    if(!cmConfig.displayThinkingMinutes){return;}

    var info = _L["student_think_working_time"];
    
    var insertDate = Date.parse(lesson['insert_timestamp']);    
    var server_now = new Date();
    server_now = server_now.getTime() + state["millisecondsServerMinusClient"];

    var minutesLeft = parseInt(lesson['thinkingMinutes']) - Math.floor((server_now - insertDate) / 60000);
    
      
    var is_overtime = false;
    if(parseInt(minutesLeft) >= 0){
        info = info.replace('#', '<b>' + minutesLeft + '</b>');
    }else{
        is_overtime = true;
        info = _L["student_think_working_overtime"].replace('#', '<b>' + minutesLeft * (-1) + '</b>');
    } 
    
    var lessonInfo = $('.lessonInfo');
    lessonInfo.html(info);
    if(is_overtime){
        lessonInfo.addClass('lessonInfo_warning');
    }else{
        lessonInfo.removeClass('lessonInfo_warning');
    }

}

function blanks(numBlanks){
    var ret = "";
    for(i=0;i<numBlanks;i++){ret += BLANK;}
    return ret;
}

function textarea_oninput(elem){
    state["hasChanges"] = true;
    var this_answer_status_before = answer["status"];
    if($(elem).val()!=''){
        answer["status"] = 'working';
    }else{
        answer["status"] = 'empty';
    }
    if(this_answer_status_before != answer["status"]){
        saveAnswer();
        displayTaskNavigation();
    }
}

function show_btn_finished(){
    
    taskNav_first_left = $('#taskNav_first_left');
    
    /** check the status of the current task */   
    this_answer_is_finished = false;
    if(typeof answer_all[task.num] != 'undefined'){
        if(answer_all[task.num]["status"] == 'finished'){
            this_answer_is_finished = true;
        }
    }
        
    /** finish and save button */
    btn_class = 'btn btn-lg btn_task';
    if(this_answer_is_finished){
        btn_class += ' btn-success';
    }
    
    var this_step = 0;
    if(cmConfig.taskFinishedButtonMoveOn){
        this_step = 1;
    }
    var str = '<button type="button" id="student_think_btn_task_finished" class="' + btn_class + '">';
    
    str += '<i class="fa fa-check';
    if(!this_answer_is_finished){str += ' fa-check-cm';}
    str += '" aria-hidden="true"></i>';
    str += '</button>';
    taskNav_first_left.append($(str));
    taskNav_first_left.children("button").last().on("click", "", function( event ) {
        if(answer["status"] == "finished"){
            if(answer.answer_text == ""){
                answer["status"] = "empty";
            }else{
                answer["status"] = "working";
            }
        }else{
            answer["status"] = "finished";
        }
        state["hasChanges"] = true;
        saveAnswer();
        state["eventPageX"] = event.pageX;
        if(whenPollFinished()){return;}
        state["goto_taskNum"] = task["num"] + this_step;
        getTask();
    });    
}
function whenPollFinished(){
    if(state["goto_taskNum"] == lesson.numTasks){
        if(cmConfig.studentRedirectAfterLastAnswer){
            rest_service('poll_finished');
            $('#taskNav_first').html('<div id="taskNavWait"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
            /**
                <div id="displayTasks">
        <div id="taskNavWait"><i class="fa fa-circle-o-notch fa-spin"></i></div>
    </div>
    <div id="taskNav_first" class="row"></div>
    <div class="lessonInfo" id="lessonInfo"></div>
    <div id="taskNav_second"></div>

            */
            return true;
        }
    }
    return false;
}

function task_how_often_true_button_click(elem){

    answer.answer_text = $(elem).attr("data-val");
    answer.status = "finished";
    state["hasChanges"] = true;
    saveAnswer();
    
    if(whenPollFinished()){return;}
    
    $('.task_label').css('background-color', 'white');
    $('.task_how_true_often_button').each(function() {
        if(!$(this).is(elem)){
            $(this).addClass("invisible");
        }
    });
    
    setTimeout(function() {
        state["goto_taskNum"] = task["num"] + 1;
        getTask();
    }, state["howOftenNextTaskDelay"])
}