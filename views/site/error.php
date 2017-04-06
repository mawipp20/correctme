<?php

use app\assets\AppAsset;
AppAsset::register($this);

$this->title = "error";

echo '<div class="alert alert-danger">Error: '.$msg.'</div>';

?>