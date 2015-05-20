<?php
require'confing.php';
require'class.url.php';


if(empty($_GET['url']) || $_GET['url'] > 3 ) {
header("location: errors".$this->page);
die;
} else {
    $r  = new Core\urlShort\CoreUrl();
    $r->getUrl($_GET['url']);
}
?>
