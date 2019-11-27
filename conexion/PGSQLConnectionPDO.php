<?php

class PGSQLConnectionPDO {

    public static $instance = NULL;
    public static $connecion = NULL;

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    public static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        $cn = NULL;
        try {
            if (self::$connecion == NULL) {
                $cf = new config();
                self::$connecion = new PDO($cf->getDns(), $cf->getUser(), $cf->getPassword(), array(PDO::ATTR_PERSISTENT => true));
                self::$connecion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $exc) {
            echo $exc->getMessage();
            //echo json_encode(array('rst' => false, 'msg' => 'Error KNCHE0000000160007x16 : COBRAST not found'));
            exit();
        }
		return self::$connecion;
    }

}

?>
