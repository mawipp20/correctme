<?php

function setSessionLanguageToDefault() {
    
    
    /**
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
    
    $country = "de";
    
    $_SESSION["_LANGUAGE"]=$country;
}

if (!isset($_SESSION["_LANGUAGE"])) {
    setSessionLanguageToDefault();
}

if (file_exists('../language/'.$_SESSION["_LANGUAGE"].'.php')) {
    include('../language/'.$_SESSION["_LANGUAGE"].'.php');
} else {
    include('../language/de.php');
}

?>