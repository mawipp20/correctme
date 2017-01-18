$(document).ready(function() {
    getThinkingStudents();
});

function getThinkingStudents(){
       
    var data = {};
    data["startKey"] = $("[name=startKey]").val();
    data["teacherKey"] = $("[name=teacherKey]").val();
    
    $.ajax({
        url: 'http://localhost/restcorrectme/web/lesson/think',
        type: 'POST',
        data: data,
        success: function(data) {
            displayThinkingStudents(data);
        }
    });
}

function displayThinkingStudents(data){
            for(var key in data){               
                //text += key + ":" + data[key] + "<br>";
            }
            $("#ajaxDisplay").html(JSON.stringify(data));
}