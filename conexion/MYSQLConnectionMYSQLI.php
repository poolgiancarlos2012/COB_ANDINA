<?php

class MYSQLConnectionMYSQLI {
    public static $instance=NULL;
    public static $connection = NULL;
    private function  __construct() {}
    private function  __clone() {}
    public static function getInstance ( ) {
        if(self::$instance==NULL){
            self::$instance=new self();
        }
        return self::$instance;
    }
    
    public function getConnection ( $user = "", $password = "", $new_config = NULL ) {
        $cn=NULL;
        try {
            if (self::$connection == NULL) {
                $cf = new config();
                
                if( $user!='' ){ $cf->setUser($user); }
                if( $password!='' ){ $cf->setPassword($password); }
                if( $new_config!=NULL ){ $cf = $new_config; }
                
                self::$connection = new mysqli($cf->getHost(),$cf->getUser(),$cf->getPassword(),$cf->getDb());
            }
            //@$cf=new config();
            @$cn=new mysqli($cf->getHost(),$cf->getUser(),$cf->getPassword(),$cf->getDb());
        } catch (Exception $exc) {
            echo json_encode(array('rst'=>false,'msg'=>'Error KNCHAE000000012340007 : COBRAST not found'));
			exit();
        }
        return self::$connection;
    }
    
}
?>
