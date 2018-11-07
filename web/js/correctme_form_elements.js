function isObject(obj) {
  return obj === Object(obj);
}
function cm_spinner(timeout_millis){
    if(typeof timeout_millis == "undefined"){
        timeout_millis = 1;
    }
    if($("#modal_spinner").length != 0){
        setTimeout(function(){
            $("#modal_spinner").click();
        }, timeout_millis);
    }
        
}