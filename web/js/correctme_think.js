
    function getThinkingStudents(){
           
        var data = {"test":"this is an ajax test"};
        
        $.ajax({
            url: 'http://localhost/restcorrectme/web/student',
            type: 'GET',
            data: data,
            success: function(data) {
                //$("#ajaxDisplay").text(data[0]['name']);
                var i;
                var key;
                var text = "";
                for(i = 0; i < data.length; i++){
                    for(key in data[i]){
                        text += key + ":" + data[i][key] + "<br>";
                    }
                }
                $("#ajaxDisplay").html(text);
            }
        });
    }