$(document).ready(function() {
    getTasks();
});

function getTasks(){
    
    $("#displayTasks").html("");
    
    var data = {};
    data["startKey"] = $("[name=startKey]").val();
    data["studentKey"] = $("[name=studentKey]").val();
    
    $.ajax({
        url: 'http://localhost/restcorrectme/web/student/think',
        type: 'POST',
        data: data,
        success: function(data) {
            displayTasks(data);
        }
    });
}

function displayTasks(data){
    
    alert(JSON.stringify(data));
    
    if(data["error"] != "" ){
        var msg = $('<div class="alert alert-danger"></div>');
        msg.html(data["error"]);
        $("#displayTasks").append(msg);
        return;
    }
    if(data["debug"] != "" ){
        var msg = $('<div class="alert alert-warning"></div>');
        msg.html(data["debug"]);
        $("#displayTasks").append(msg);
        return;
    }
    
    var templateTaskRow = '<div data-id="' + data["taskId"] + '" class="panel panel-default task"></div>';
    var task = $(templateTaskRow);
    task.append($("<div class='panel-heading'><div class='taskId'>" + data["taskId"] + "</div><div class='task_text'>" + data["task_text"] + "</div></div>"));
    task.append($("<div id='answer_text' class='panel-body'>" + data["answer_text"] + "</div>"));
    $("#displayTasks").append(task);
        
        /**

<div class="panel panel-default">
  <div class="panel-heading">Panel Heading</div>
  <div class="panel-body">Panel Content</div>
</div>        
        
        */
        
        
    //} 
}