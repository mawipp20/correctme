<?php

namespace app\components;

use Yii;		

class MyHelpers
{
    static function cmConfigJsValue($v){
        $v = Yii::$app->params[$v];
        if($v === true){return "true";}
        elseif($v === false){return "false";}
        else{return "'".$v."'";}
    }
}

?>