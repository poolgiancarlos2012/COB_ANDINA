<?php

class configRP3 {

    private $host;
    private $user;
    private $password;
    private $db;
    private $dns;
    //private $option;
    public function  __construct() {
        $this->host='192.168.0.46';
        $this->user='kennedy';
        $this->password='k3nn3dy';
        $this->db='wsHdec';
		$this->port='3306';
        $this->dns='mysql:dbname='.($this->db).';port='.($this->port).';host='.($this->host);
        /*$this->host='localhost';
        $this->user='root';
        $this->password='';
        $this->db='testCobrast';
        $this->dns='mysql:dbname=testCobrast;host=localhost';*/
        //$this->option=array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_PERSISTENT => true);
    }
    public function setHost ( $host ) {
        $this->host=$host;
    }
    public function getHost () {
        return $this->host;
    }

    public function setUser ( $user ) {
        $this->user=$user;
    }
    public function getUser ( ) {
        return $this->user;
    }

    public function setPassword ( $password ) {
        $this->password=$password;
    }
    public function getPassword () {
        return $this->password;
    }

    public function setDb ( $db ) {
        $this->db=$db;
    }
    public function getDb ( ) {
        return $this->db;
    }

    public function setDns ( $dns ) {
        $this->dns=$dns;
    }
    public function getDns ( ) {
        return $this->dns;
    }

    /*public function setOption ( $option ) {
			$this->option=$option;
		}
		public function getOption ( ) {
			return $this->option;
		}*/
}


?>