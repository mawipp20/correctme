/**

- Was ist bei nur einer Aufgabe mit der Navigation?
- Alles fertig Button in Betrieb nehmen
- Aufgaben eingeben / hochladen



*/

var lesson = {
    "startKey":""
    ,"lesson.numTasks":""
    ,"title":""
    ,"description":""
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
    ,"nextTaskDelay":500 /** interval milliseconds when the the rest service is called */
    ,"hasChanges":0
    ,"lastSaved":0
    ,"answer_text":""
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
    
    if(task.num > lesson.numTasks){
        state["goto_taskNum"] = lesson.numTasks;
    }
        
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
        this_text = this_text.replace("#pollDescription#", lesson.description.replace(new RegExp("\\r\\n", 'g'), '<br/>'));
        if(student.teacherName != ""){
            this_text = this_text.replace("#teacherName#", student.teacherName);
            this_text = this_text.replace("#teacherNameDativ#", student.teacherName.replace("Herr ", "Herrn "));
        }else{
            this_text = this_text.replace("<br/><b>#teacherName#</b><br/>", "");
            this_text = this_text.replace("#teacherName#", _L["student_think_poll_default_teacher_name"]);
            this_text = this_text.replace("#teacherNameDativ#", _L["student_think_poll_default_teacher_name"]);
        }
        task.task_text = this_text;
    }
    
    // #(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'".,<>?������]))
    
    var link_matches = task.task_text.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
    if(link_matches != null){
        for(var i = 0; i < link_matches.length; i++){
            task.task_text.replace(link_matches[i], "<a href='" + link_matches[i] + "' target='_blank'>" + link_matches[i] + "</a>");
        }
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

    if(typeof redirectTo == "undefined"){ redirectTo = "";}

    saveAnswer();
    
    if(state['millisecondsServerMinusClient']!==false){
        displayLessonInfo();
    }


    /* stop if the last query (rest_status) is still going on or when there are no changes */
    if( redirectTo == ""
        & state["hasChanges"] === false
    ){
        console.log("no changes");
        return;
    }
    
    
    /** display warning when the rest service failed too often */
    
    if(state["rest_status"] >= 3){
        state_error_alert_display(_L["student_think_rest_service_warning"], "danger");
    }

    /** connecting to server using restcorrectme REST service to save and get fresh data */
    
    query = {};
    query["startKey"] = lesson["startKey"];
    query["studentKey"] = student["studentKey"];
    query["answer_all"] = answer_all;
    query["student.status"] = student.status;
    
    
    var this_warning = _L["student_think_rest_service_warning"];
    if(Object.keys(task_all).length == 0){this_warning = _L["student_think_rest_service_warning_start"];}

    console.log(query);
       
    state["rest_status"]++;
    
    var restUrl = "../../../" + cmConfig.restcorrectmePath + "web/student/think";
    
    
    $.ajax({

        url: restUrl,
        type: 'POST',
        data: query,
        success: function(data) {

        console.log(data);
    
            
            if(typeof data["error"] == "undefined"){
                data["error"] = "";
            }
            
            if(data["error"] != ""){
                
                /** nachsichtiges Verhalten: Wenn ein Fehler gemeldet wird,
                dann wird nach 3 Speicherversuchen eine Meldung einblendet */
                
                state_error_alert_display(this_warning, "danger");
                
                state["error"] = data["error"];
                                   
            }else{
                
console.log("my__data:" + redirectTo);
                
                if(redirectTo != ""){window.location.href = redirectTo; return;}
                
                state["hasChanges"] = false;
                state["rest_status"] = 0;
                
                /** if there was a rest service warning before, display a revoke-success for some sconds */
                
                if(state["error"] != ""){
                    state_error_alert_display(_L["student_think_rest_service_warning_revoked"], "success", 6000);
                }

                var this_do_getTasks_first_time = false;
                if(Object.keys(task_all).length == 0){this_do_getTasks_first_time = true;}

                lesson = data["lesson"];
                student = data["student"];
                task_all = data["task_all"];
                answer_all = data["answer_all"];
                state["debug"] = data["debug"];
                state["lastSaved"] = data["lastSaved"];
                if(this_do_getTasks_first_time){
                    getTask();
                }
                if(state['millisecondsServerMinusClient']===false){
                    var this_now_client = new Date();
                    var this_now_server = Date.parse(state["lastSaved"]);
                    state['millisecondsServerMinusClient'] = this_now_server - this_now_client.getTime();
                }
                
            }
            
            
        },

        error: function(xhr, status, error) {

            /** nachsichtiges Verhalten: Wenn ein Fehler gemeldet wird,
            dann wird immer weiter versucht. Nach 5 Versuchen im 4-Sekundenabstand wir eine Meldung einblendet */
            
            state_error_alert_display(this_warning, "danger");

            if(redirectTo != ""){setTimeout(rest_service(redirectTo), 4000);}
            
            if(state["program_version"]=="dev"){
                state["error"] = "ajax-error: " + xhr.responseText;
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
    
    if(state["error"] == ""){
        $("#state_error_alert").remove();
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
        
        var this_class = "form-control text-area";
        if(answer.status == "finished"){this_class += " student_think_textarea_finished"}
        var str = '<textarea class="' + this_class + '" rows="1"';
        str += ' id="answer_text" oninput="textarea_oninput(this);">';
        str += answer["answer_text"] + '</textarea>';
        taskDisplay.append($(str));
        $(".text-area").autoGrow();
        if(answer.status != "finished"){
            $("#answer_text").focus();
        }
    }

    if(task_types[task.type]["type"] == "scale"){
        
        $('#student_think_btn_task_finished').css('visibility', 'hidden');
        
        var str = "";

        var keys = task_types[task.type]["values_string"].split("#|#");
        for(var i = 0; i < keys.length; i++){
            var key = keys[i];
            str += get_taskDisplay_how_often_true_button(task.type, key);
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
        else if(data_val === 'x'){
            this_btn_css_class += ' task_how_true_often_button_dont_know';
        }
        var btn_start = '<button onclick="task_how_often_true_button_click(this);" data-val="' + data_val + '" type="button" class="' + this_btn_css_class + '">';
        var this_class = 'pie';
        if(pie_percentage === ''){
            this_class += ' invisible';
        }
        var ret = btn_start + '<span class="' + this_class + '" data-val="' + pie_percentage + '"></span>';
        ret += '<span class="task_how_true_often_label';
        $this_caption = _L['scale_' + type + '-' + key];
        //if($this_caption.length <= 4){ret += ' large';}
        ret += '">' + $this_caption + '</span></button>';
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
    if(task.type == 'text' | task.type == 'info' | task.type == 'sysinfo'){
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
        //taskNav_first_left.children("button").last().addClass('display_none');
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

    
    if( task.num == lesson.numTasks
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
    
    if(task.type == "info" & task.num == lesson.numTasks){
        show_btn_finished();
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
    /** protect against copy-paste text of more than 25 characters into answers **/
    if($("#answer_text").val().length > 25 + state.answer_text.length){
        $("#answer_text").val(state.answer_text);
        return;
    }else{
        state.answer_text = $("#answer_text").val();        
    }
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
        
   /** finish or edit-again button */
    var btn_class = 'btn btn-lg btn_task';
    if(task.type == "info"){btn_class += " display_none"}
    var finished_edit_symbol = '<i class="fa fa-check fa-check-cm aria-hidden="true"></i>';
    if(answer["status"] == "finished" & lesson.type == "lesson"){
        btn_class += ' student_think_btn_edit';
        finished_edit_symbol = '<i class="fa fa-edit aria-hidden="true"></i>';
    }

    
    var this_step = 0;
    if(cmConfig.taskFinishedButtonMoveOn){
        this_step = 1;
    }

    var str = '<button type="button" id="student_think_btn_task_finished" class="' + btn_class + '">';    
    str += finished_edit_symbol + '</button>';
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
        if(answer["status"] == "finished"
         ){
            $("#answer_text").addClass("student_think_textarea_finished");
            if(task.num < lesson.numTasks){
                setTimeout(function(){
                    state["goto_taskNum"] = task["num"] + this_step;
                    getTask();
                }, state["nextTaskDelay"]);
            }else{
                    getTask();
            }
        }else{
            getTask();
        }
    });

    if(task.num == lesson.numTasks & (task.type == "info" | whenAllFinished())){
        if(lesson.type == "lesson"){
            commit_dialog(true);
        }else if(lesson.type == "poll" & task.type == "info"){
            var this_btn = "<button class='btn btn-success' onclick='";
            this_btn += "saveAnswer();rest_service(\"poll_finished\");'>";
            this_btn += _L["student_think_finished_button"] + "</button>";
            $('#taskNav_first_left').append(this_btn);
        }
    }


}

function commit_dialog(show_btn){
    
        var this_answer_status = get_answer_status();
        if(this_answer_status.unfinished == 0){
            $(".student-think-commit-dialog-modal-header-span").html(_L["student_think_commit_header_ready"]);
            $(".student-think-commit-dialog-modal-content-span").html(_L["student_think_commit_content_ready"]);
        }else{
            var this_text = _L["student_think_commit_header_warning"].replace("#unfinished#", this_answer_status.unfinished);
            $(".student-think-commit-dialog-modal-header-span").html(this_text);
            $(".student-think-commit-dialog-modal-content-span").html(_L["student_think_commit_content_warning"]);
        }
        if(show_btn){
            if($('#taskNav_first #student_think_btn_commit_toggle').length == 0){
                var this_btn = $("#student_think_btn_commit_toggle").clone();
                $('#taskNav_first_left').append(this_btn);
                this_btn.show();
            }
        }else{
            $("#student_think_btn_commit_toggle").click();
        }
        $("#student_think_btn_commit_confirmed").on("click", "", function( event ) {
            cm_spinner();
            if(lesson.type == "lesson"){
                window.location.href = 'commit_single';
            }else{
                window.location.href = 'poll_finished';
            }
        });
    
}

function whenAllFinished(){
    var this_answer_status = get_answer_status();
    if(this_answer_status["unfinished"] == 0 & task.type != "info"){
        if(cmConfig.studentRedirectAfterLastAnswer){
            if(lesson.type == "poll"){
                rest_service('poll_finished');
            }else{
                rest_service('commit_single');
            }
        }
        return true;
    }else{
        return false;
    }
}

function task_how_often_true_button_click(elem){

    answer.answer_text = $(elem).attr("data-val");
    answer.status = "finished";
    state["hasChanges"] = true;
    saveAnswer();
    
    if(task.num == lesson.numTasks){
        if(lesson.type == "lesson"){
            /** display commit button */
            getTask();
            commit_dialog(true);
        }else{
            whenAllFinished();
            return;
        }
    }else{
        $('.task_label').css('background-color', 'white');
        $('.task_how_true_often_button').each(function() {
            if(!$(this).is(elem)){
                $(this).addClass("invisible");
            }
        });
        setTimeout(function() {
            state["goto_taskNum"] = task["num"] + 1;
            getTask();
        }, state["nextTaskDelay"])
    }
    
}


function state_error_alert_display(text, alert_type, timeout){

    state["error"] = text;
    
    if(typeof timeout == "undefined"){
        timeout = 0;
    }

    var new_alert = true;
    if($("#state_error_alert").length > 0){
        $("#state_error_alert").remove();
        new_alert = false;
    }
    
    if(state["error"]==""){return;}
    
    var msg = $('<div id="state_error_alert" class="alert alert-' + alert_type +'"></div>');
    msg.html(text);
    
    if(new_alert){msg.hide();}
    
    $("#displayTasks").append(msg);
        
    if(new_alert){$( "#state_error_alert" ).fadeIn( "normal", function() {});}   
    
    if(timeout > 0){
        setTimeout(function(){
            state["error"] = "";
            $("#state_error_alert").fadeOut("normal", function() {
                    $(this).remove();
            });
        }, timeout);
    }
}

function get_answer_status(){
        //var empty_answers_count = 0;
        var working_answers_count = 0;
        var finished_answers_count = 0;
        var task_count = 0;

        for(key in task_all){
            if(task_all[key]["type"] != "info"
               & task_all[key]["type"] != "sysinfo"){
                    task_count++;}
        }

        
        for(key in answer_all){
            //if(task_all[key]["type"] == "info"){console.log("info!");continue;}
            if(answer_all[key]["status"] == "working"){working_answers_count++;}
            if(answer_all[key]["status"] == "finished"){finished_answers_count++;}
        }
        
console.log(task_count + "//" + finished_answers_count);        
        
    return {"all": task_count,
            "working": working_answers_count,
            "finished": finished_answers_count,
            "unfinished": task_count - finished_answers_count,
            "empty": lesson.numTasks - finished_answers_count - working_answers_count, 
            }
}