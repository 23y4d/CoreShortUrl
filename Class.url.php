<?php namespace Core\urlShort;
/**
 * This file is part of the Core package.
 * ------------------
 * PHP version >=5.4
 *-------------------
 * @package   23y4d/CoreShortUrl
 * @author    Zeyad Besiso <zeyad.besiso@gmail.com>
 * @license  https://github.com/23y4d/CoreShortUrl/blob/master/LICENSE
 * @link     https://github.com/23y4d/CoreShortUrl
 */

use RuntimeException;

class CoreUrl  {
   	/**
	 * SHORT
	 *
	 * @const integer
	 */
     const SHORT =  "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    /**
     *
     *public string
     *
     */
    public $table =TABLE;
 	/**
	 * Current zero Error, 0 is default no errors
	 *
	 * @var integer
	 * @access private
	 */
    private $zeroError = 0;
    /**
	 * Current p=protocol
	 *
	 * @var integer
	 * @access private
	 */
    private $p = '://';
    /**
	 * Current page Error
	 *
	 * @var integer
	 * @access private
	 */
    private $pageError = 'errors';
    /**
	 * Current len=length
	 *
	 * @var integer
	 * @access private
	 */                // 1 2 3
    private $len = 3; // [t r 2 ] <-len
     /**
	 * Error messages
	 *
	 * @var array
	 * @access private
	 */
    private $errors =[
		0   => "",
		2   => "<div class='text-center alert alert-info' id='message'> You entered an invalid url</div>",
		3   => "<div class='text-center alert alert-info' id='message'>  invalid url</div>"
	  ];
    /**
	 * Short messages
	 *
	 * @var array
	 * @access private
	 */
   private $Short =['bit.ly','is.gd','tiny.cc','adf.ly',
            'ur1.ca','goo.gl','ow.ly','j.mp','t.co'
            ];

	/**
	 * Set Error
	 *
	 * @param integer Error code
	 * @access private
	 */
  	private function setError($code) {
		$this->zeroError = $code;
	}

    /**
	 * Get zero Error
	 *
	 * @access public
	 * @return integer Error code
	 */
	public function getError() {
		return $this->zeroError;
	}

	/**
	 * Get current Error message
	 *
	 * @access public
	 * @return string Current error message if was
	 */
	public function getMessage() {
		if (array_key_exists($this->zeroError,$this->errors)) {
			return $this->errors[$this->zeroError];
		} else {
			return true;
		}
	}

	/**
	 * clean input
	 *
	 * @access public
	 * @return string
	 */
  public function cleanUrl($input){
        $input = htmlentities($input);
        $input = addslashes($input);
        $input = mysql_real_escape_string($input);
   return $input;
    }



  public function  filterUrl($url)  {
  if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
        $this->setError(2);
        header("Refresh:3");
        return false;
        die;
    } else {
        return true;
    }


  }

    /**
	 * Check if url is Short
	 *
	 * @access public
	 * @return boolean true  || false
	 */
  public function  isShort($url){

    foreach ($this->Short as $isShortTrue) {
      if(strstr($url,$this->p.$isShortTrue)) {
      header("location:".$this->pageError,true,302);
      die();
        }
    }
  }

  	/**
	 * Set the process Url
	 *
	 * @param string make url
	 * @access public
	 * @throws RuntimeException in failure
	 * @return static
	 */
    public function processUrl($url) {
		try {
           $this->makeUrl($url);
		} catch (RuntimeException $z) {
			echo $z->getMessage();
		}
		return $this;
	}

    /**
	 * make Url
	 *
	 * @access private
	 * @return static
	 */
  private function  makeUrl($url){
      if($url !="" ){
      $times = date("d-m-Y h:m:s");
      $short = substr(str_shuffle(self::SHORT),0,$this->len);
      $query = "INSERT INTO ".TABLE." (url_link,url_short,url_ip,url_date)
                VALUES ('{$url}','{$short}','{$_SERVER['REMOTE_ADDR']}','{$times}')";
      $insert = mysql_query($query);
      if($insert):
      	throw new RuntimeException( "<a href='http://localhost/url/$short'>
        http://localhost/url/$short</a>");
      endif;
     } else {
        return false;
      }
 }

    /**
	 * get Url
	 *
	 * @access public
	 * @return static
	 */

   public function  getUrl($code){
    if($code !="" ){
        $query = "SELECT * FROM ".TABLE." WHERE url_short='{$code}'";
        $insert = mysql_query($query);
        $row = mysql_fetch_object($insert);

      if(!isset($row->url_link) || strlen($row->url_link) < 1):
        die(header("Location: 404", true, 301));
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

namespace Core\urlShort\info;

class info {
    /**
	 * get ip Url
	 *
	 * @access public
	 * @return static
	 */
    public function hostName($r){

      if ($r!=""){
                $r = explode('/', $r);
                $r = array_filter($r);
                $r = array_merge($r, array());
                $r = preg_replace('/\?.*/', '', $r);
                $endofurl = $r[1];
                $fi = gethostbyname($endofurl);
        echo "$fi<br>";
      } else {
        return false;
        }
    }
        /**
	 * get Dns website
	 *
	 * @access public
	 * @return static
	 */
  public function getDns($u){

  $parse = parse_url($u);
  $domain = isset($parse['host']) ? $parse['host'] : '';

    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i',$domain,$regs)) {
            $ip = gethostbyname($regs['domain']);
            print"Dns: ".gethostbyaddr($ip)."<br><hr>";
          } else{
            return false;
          }
    }


}
