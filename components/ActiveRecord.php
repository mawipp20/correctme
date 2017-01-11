<?php

	namespace app\components;

    use Yii;		

	class ActiveRecord extends \yii\db\ActiveRecord {

        public function generateUniqueRandomString($attribute, $length = 32, $toLower = true) {
        			
        	$randomString = Yii::$app->getSecurity()->generateRandomString($length);
            if($toLower){
                $randomString = strtolower($randomString);
                $randomString = str_replace("l", "k", $randomString);
                $randomString = str_replace("o", "u", $randomString);
                $randomString = str_replace("0", "2", $randomString);
            }
        			
        	if(!$this->findOne([$attribute => $randomString]))
        		return $randomString;
        	else
        		return $this->generateUniqueRandomString($attribute, $length);
        			
        }
        	
	}
	
?>