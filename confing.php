<?php 

/**
 * A very simple  Connect to MySqL
 *
 * PHP version 5.4+
 *
 * @package   Connect to MySqL
 * @author  Zeyad Besiso <zeyad.besiso@gmail.com>
 * @Copyright  © 2013
 */

error_reporting(0);

 if (version_compare(phpversion(),'5.4.7', "<")){
    exit('This class works fine in PHP 5.4.7 or newer!');
  }

define('HOST','localhost');
define('USER','root');
define('PASSWORD','root');
define('DATABASE','shotz');
define('TABLE','url');

class ConMySql {

    private $dbhost,
            $dbuser,
            $dbpass;

    public function __construct ($host,$user,$pass) {
    $this->dbhost = $host;
    $this->dbuser = $user;
    $this->dbpass = $pass;
    }

    public function con() {
            @mysql_connect($this->dbhost,$this->dbuser,$this->dbpass)
    or die("<center><h1>Not Connect</h1> <a href='setup.php'>Setup</a></center>");
    }

    public function CreateDb($data) {
            @mysql_query("CREATE DATABASE IF NOT EXISTS `$data`")
    or die ("<center><h1>Not data base</h1></center>");
    }

    public function SelectDb ($data) {
            @mysql_select_db($data)
    or die ("<center><h1>Not Selected data base </h1></center>");
    }

    public function CreateTB($data) {
    @mysql_query("CREATE TABLE IF NOT EXISTS `$data` (
              `url_id` int(11) NOT NULL auto_increment,
              `url_link` varchar(255) default NULL,
              `url_short` varchar(6) default NULL,
              `url_date` varchar(255) default NULL,
              `url_ip` varchar(255) default NULL,
              `url_hits` int(11) default '0',
               PRIMARY KEY  (`url_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;")
    or die ("<center>Not data </center>");
    }



}

$con = new ConMySql(HOST,USER,PASSWORD);
$con->con();
$con->CreateDb(DATABASE);
$con->SelectDb(DATABASE);
$con->CreateTB(TABLE);

$wsn = "Core 1.0.2";

?>