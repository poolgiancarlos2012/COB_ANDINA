<?php
class dbc {
 
    public $mysqli = null;
 
    public function __construct() {
 
        include_once 'config.php';
        $this->mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT);
 
        if ($this->mysqli->connect_errno) {
            echo "Error MySQLi: ("&nbsp. $this->mysqli->connect_errno.") " . $this->mysqli->connect_error;
            exit();
        }
        $this->mysqli->set_charset("utf8");
    }
 
    public function __destruct() {
        $this->CloseDB();
    }
 
    public function runQuery($qry) {
        $result = $this->mysqli->query($qry);
        return $result;
    }
 
    public function CloseDB() {
        $this->mysqli->close();
    }
 
    public function clearText($text) {
        $text = trim($text);
        return $this->mysqli->real_escape_string($text);
    }
 
    public function seleccionar_cliente($codigo)
    {
        $q = "SELECT negocio,sum(cuota_mensual+seguros+otros) as suma  FROM ca_cuenta WHERE estado=1 and codigo_cliente='$codigo' group by negocio";
 
        $result = $this->mysqli->query($q);
        //Array asociativo que contendrá los datos
        $valores = array();
                //Si no hay resultados
                //Se avisa al usuario y se redirige al index de la aplicación
       
            while($row = mysqli_fetch_assoc($result))
            {
                //Se agrega cada valor en el array
                array_push($valores, $row);
            }
          
        //Regresa array asociativo
        return $valores;
    }
}
?>