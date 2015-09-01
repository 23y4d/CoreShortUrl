<?php

include_once 'header.php';

$Short = new Core\urlShort\CoreUrl();
$ip    =  new Core\urlShort\info\info(); 

if(isset($_POST['shorten'])){

   $url = $_POST['url'];

   $Short->cleanUrl($url);

   $Short->filterUrl($url);

   $Short->isShort($url);

}
echo <<<EOF
<div class="container">
EOF;
echo $Short->getMessage();
echo <<<EOF
<div class="centerfull">
<img width="100" height="100" alt="star" src="data:image/png;base64,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" />
        <form  class="form-horizontal"  method="POST" action=""  role="form">
        <input type="text" class="form-control floating-label" placeholder="URL"  name="url"
        data-hint="You should add http://"  autocomplete="off"  />
        <button  type="submit"  class="btn btn-flat btn-primary"  name="shorten" >Shorten!</button>
        </form>
EOF;
$ip->hostname($url); 
$Short->processUrl($url);
$Short->getUrl($url);
echo '</div>';
include_once 'footer.php';
?>






