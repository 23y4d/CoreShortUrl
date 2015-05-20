<?php

/**
 * A very simple class for Short Url
 *
 * PHP version 5.4+
 *
 * @package   Jasny/DB-MySQL
 * @author  Zeyad Besiso <zeyad.besiso@gmail.com>
 * @license
 * @link
 */


//require 'confing.php';

define("SHORT","0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");

class CoreUrl {


  /**
   *
   *public string
   *
   */
   public $table =TABLE;

   public $page = '.php';

  /**
   *
   *private string
   *
   */
   private $zeroError = 0;

   private $p = '://';

   private $pageError = 'errors';
                        //                     1 2 3  <-$len
   private $len = 3;   //  url www.exmple.com/[T r 6 ]


   private $errors =[
		0   => "",
		1   => "The uploaded file exceeds",
		2   => "<div class='text-center alert alert-info' id='message'> You entered an invalid url</div>",
		3   => "<div class='text-center alert alert-info' id='message'>  invalid url</div>"
	  ];

   private $Short =['bit.ly','is.gd','tiny.cc','adf.ly',
            'ur1.ca','goo.gl','ow.ly','j.mp','t.co'
            ];







  	private function setError($code) {
		$this->zeroError = $code;
	}

	public function getError() {
		return $this->zeroError;
	}



	public function getMessage() {
		if (array_key_exists($this->zeroError,$this->errors)) {
			return $this->errors[$this->zeroError];
		} else {
			return true;
		}
	}


  public function cleanUrl($input){
        $input = htmlentities($input);
        $input = addslashes($input);
        $input = mysql_real_escape_string($input);
   return $input;
    }


  public function  filterUrl($url)  {
  if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
    $this->setError(2);
    echo '<meta http-equiv="refresh" content="1; url=index"> ';
    return false;
    die;
    } else {
       return ture;

    }


  }

  public function  isShort($url){

    foreach ($this->Short as $isShortTrue) {
      if(strstr($url,$this->p.$isShortTrue)) {
      header("location:".$this->pageError/*.$this->page*/,true,302);
      die();
        }
    }
  }



    public function processUrl($url){
		try {
           $this->makeUrl($url);
		} catch (RuntimeException $z) {
			echo $z->getMessage();
		}
		return $this;
	}



  private function  makeUrl($url){
      if($url !="" ){
      $time = date("d-m-Y h:m:s");
      $short = substr(str_shuffle(SHORT),0,$this->len);
      $query = "INSERT INTO ".TABLE." (url_link,url_short,url_ip,url_date)
                VALUES ('{$url}','{$short}','{$_SERVER['REMOTE_ADDR']}','{$time}')";
      $insert = mysql_query($query);
      if($insert):
      	throw new RuntimeException( "<a class=\"tooltip-toggle\" data-toggle=\"tooltip\"
        href='http://localhost/url/$short' >
        http://localhost/url/$short</a>");
      endif;
     } else {
        return false;
      }
 }



   public function  getUrl($code){
    if($code !="" ){
        $query = "SELECT * FROM ".TABLE." WHERE url_short='{$code}'";
        $insert = mysql_query($query);
        $row = mysql_fetch_object($insert);

      if(!isset($row->url_link) || strlen($row->url_link) < 1):
        header("Location: 404", true, 302);
        die();
      endif;

	  header("Location: {$row->url_link}", true, 301);
      $oldclicks = $row->url_hits;
      $newclicks = $oldclicks + 1;
      $query2 = "UPDATE ".TABLE." SET url_hits='{$newclicks}' WHERE url_short='{$code}'";
      $insert2 = mysql_query($query2);
    } else {
        return false;
      }
 }




}

?>