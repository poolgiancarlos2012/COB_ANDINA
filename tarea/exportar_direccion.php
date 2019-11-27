<?php
session_start();

require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/config.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/conexion/MYSQLConnectionPDO.php';
require_once 'C:/xampp/htdocs/COB_ANDINA/factory/FactoryConnection.php';

 function limpiarCartera4($name) {
	$factoryConnection = FactoryConnection::create('mysql');
	$connection = $factoryConnection->getConnection();
    $path = "C:/xampp/htdocs/COB_ANDINA/documents/loaddireccion/".$name;
    if (!file_exists($path)) {
        echo "no existe o fue removida ".$name." ".date("Y-m-d H:m:s")."\n";
        exit();
    }    
    $tmp_file = "TMP" . session_id() . rand(1, 1000) . $name;
    $tmpArchivo = @fopen("C:/xampp/htdocs/COB_ANDINA/documents/loaddireccion/".$tmp_file, 'a+');
    if (!$tmpArchivo) {
        echo json_encode(array('rst' => false, 'msg' => 'Problemas al crear archivo temporal'));
        exit();
    }
    $carteraArchivo = @fopen($path, 'r+');
    if ($carteraArchivo) {
        $count = 0;
        while (!feof($carteraArchivo)) {
            $linea = trim(fgets($carteraArchivo));
            if(!empty($linea)){
                $buscar = array('"', "'", "#", "&", "?", "Â¿", "!", "Â¡", "", "Â¥");
                $cambia = array('' , "" , "" , "" , "" , "" , ""  , ""  , "", "N" );
                $line_c = str_replace($buscar, $cambia, trim($linea));
                fwrite($tmpArchivo, $line_c . "\r\n");
                $count++;
            }
        }                
        fclose($carteraArchivo);
        fclose($tmpArchivo);
        $insertfileload=" INSERT INTO loaddireccion(nombrecarga,nombretrans,pendiente) VALUES('$name','$tmp_file',1);";
		$prinsertfileload = $connection->prepare($insertfileload);
		$prinsertfileload->execute();        
        echo "Exportar direccion ".$name."\n";
    } else {
        fclose($carteraArchivo);
        fclose($tmpArchivo);
        echo "Error"."\n";
    }
}

$nombrecarga=$argv[1];
limpiarCartera4($nombrecarga);
?>