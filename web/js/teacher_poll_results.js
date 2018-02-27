function result_tabs_click(elem, div_id){
    $(elem).parent("li").siblings().removeClass("active");
    $(elem).parent("li").addClass("active");
    $('.results').hide();
    $('#' + div_id).show();
}
function delete_foul_text_answer(elem_a){

    var query = {};
    query["_csrf"] = $('[name="_csrf"]').val();
    query["startKey"] = $('[name="startKey"]').val();
    query["studentId"] = $(elem_a).attr("data-student-id");
    query["taskId"] = $(elem_a).attr("data-task-id");
    
    var elem_li = $(elem_a).closest("li");
    if(!confirm(elem_li.text())){return false;}
    elem_li.hide();
    
    
    var restUrl = "../../../" + restcorrectmePath + "web/site/delete_text_answer";
    
    console.log(query);
    
    $.ajax({

        url: restUrl,
        type: 'POST',
        data: query,
        success: function(data) {
            if(data["error"]!=""){
                alert(data["error"]);
                elem_li.show();
            }else{
                elem_li.remove();
            }          
        },
        error: function(xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            alert(err.Message);
            //alert(xhr.responseText);        
        }
    });
        
}
