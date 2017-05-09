<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

            /** try to detect the country and language
            $ip=$_SERVER['REMOTE_ADDR'];
            $url='http://api.hostip.info/get_html.php?ip='.$ip;
            $data=file_get_contents($url);
            $s=explode (':',$data);
            $s2=explode('(',$s[1]);
            $country=str_replace(')','',substr($s2[1], 0, 3));
            if ($country=='us') {
                $country='en';
            }
            $country=strtolower(ereg_replace("[^A-Za-z0-9]", "", $country ));
            */


class Language extends Component {
 
    public $L_sections = array();
    public $L = array();
    public $country = "en";
    
    public function get_country(){
        return $this->country;
    }

    public function init()
    {
        parent::init();
        
        $supportedLanguages = ['en', 'de'];
        $preferredLanguage = Yii::$app->request->getPreferredLanguage($supportedLanguages);
        Yii::$app->language = $preferredLanguage;

        if (!isset($_SESSION["_LANGUAGE"])) {
            $this->country = $preferredLanguage;
            $_SESSION["_LANGUAGE"] = $this->country;
        }else{
            $this->country = $_SESSION["_LANGUAGE"];
        }
        $pathSeparator = "/";
        if($_SERVER['HTTP_HOST'] == 'localhost'){$pathSeparator = "\\";}


/** hard coded de */
$this->country = "de";    

        
        
        $this_language_file = \Yii::$app->basePath.$pathSeparator.'language'.$pathSeparator.$this->country.'.ini';
        if (file_exists($this_language_file)) {
            $this->L_sections = parse_ini_file($this_language_file, true, INI_SCANNER_RAW);
            foreach($this->L_sections as $section => $arr){
                $this->L = array_merge($this->L, $arr);
            }
        }else{
            die('! missing language file: '.$_SESSION["_LANGUAGE"].'.ini '.$this_language_file.print_r($_SERVER, true));
        }
    }
    
    public function get($phrase){
        if(isset($this->L_sections[$phrase])){return $this->L_sections[$phrase];}
        if(array_key_exists($phrase, $this->L_sections)){return $this->L_sections;}       
        return (!array_key_exists($phrase,$this->L)) ? $phrase : $this->L[$phrase];
    }    
}
?>