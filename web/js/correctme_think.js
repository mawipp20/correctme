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
    for(var i = 0; i < data.length; i++){
        var templateStudentRow = '<div data-id="' + data[i]["id"] + '" class="row studentRow studentRow' + data[i]["status"] + '"></div>';
        var newStudentRow = $(templateStudentRow);
        newStudentRow.append($("<div class='col-xs-6 col-sm-3 col-md-2 studentName'>" + data[i]["name"] + "</div>"));
        newStudentRow.append($("<div class='col-xs-6 col-sm-9 col-md-10 answers_completed'>" + data[i]["id"] + "</div>"));
        
        $("#studentRows").append(newStudentRow);
        //newStudentRow.html(JSON.stringify(data[i]));
    } 
}