<?php

class PGSQL_PDOAyudaGestionDAO {

    public function queryByServicio(dto_servicio $dto) {
//			$sql=" SELECT ayuda.ruta,tipayuda.nombre FROM ca_ayuda_gestion ayuda INNER JOIN ca_tipo_ayuda_gestion tipayuda 
//				ON tipayuda.idtipo_ayuda_gestion=ayuda.idtipo_ayuda_gestion
//				WHERE ayuda.idservicio=? AND ayuda.estado=1 ";

        $sql = " SELECT ayuda.ruta,tipayuda.nombre FROM ca_ayuda_gestion ayuda INNER JOIN ca_tipo_ayuda_gestion tipayuda 
				ON tipayuda.idtipo_ayuda_gestion=ayuda.idtipo_ayuda_gestion
				WHERE ayuda.idservicio=? AND ayuda.estado=1 AND ayuda.is_text=0 ";

        $servicio = $dto->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryPorServicioTextoNoTexto(dto_servicio $dtoServicio) {

        $sql = " SELECT ag.idayuda_gestion,ag.nombre,ag.is_text,
			( SELECT nombre FROM ca_tipo_ayuda_gestion WHERE idtipo_ayuda_gestion=ag.idtipo_ayuda_gestion LIMIT 1 ) AS 'tipo'
			FROM ca_ayuda_gestion ag WHERE ag.estado=1 AND ag.idservicio = ? ";

        $servicio = $dtoServicio->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryAllByService(dto_ayuda_gestion $dtoAyudaGestion) {
//			$sql=" SELECT ayuges.idayuda_gestion,ayuges.ruta,DATE(ayuges.fecha_creacion) AS 'fecha_creacion',tipayuges.nombre AS 'tipo_ayuda_gestion'
//				FROM ca_ayuda_gestion ayuges INNER JOIN ca_tipo_ayuda_gestion tipayuges ON
//				tipayuges.idtipo_ayuda_gestion=ayuges.idtipo_ayuda_gestion
//				WHERE ayuges.estado=1 AND ayuges.idservicio=? ORDER BY ayuges.fecha_creacion DESC ";

        $sql = " SELECT ayuges.idayuda_gestion,ayuges.ruta,DATE(ayuges.fecha_creacion) AS 'fecha_creacion',tipayuges.nombre AS 'tipo_ayuda_gestion'
				FROM ca_ayuda_gestion ayuges INNER JOIN ca_tipo_ayuda_gestion tipayuges ON
				tipayuges.idtipo_ayuda_gestion=ayuges.idtipo_ayuda_gestion
				WHERE ayuges.estado=1 AND ayuges.idservicio=? AND ayuges.is_text=0 ORDER BY ayuges.fecha_creacion DESC ";

        $servicio = $dtoAyudaGestion->getIdServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryAllIsTextById(dto_ayuda_gestion $dtoAyudaGestion) {
        $sql = " SELECT idayuda_gestion,nombre,idtipo_ayuda_gestion,texto 
				FROM ca_ayuda_gestion 
				WHERE is_text=1 AND idayuda_gestion = ? ";

        $id = $dtoAyudaGestion->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function queryAllByServicieIsText(dto_ayuda_gestion $dtoAyudaGestion) {
        $sql = " SELECT ayuges.idayuda_gestion,ayuges.nombre,DATE(ayuges.fecha_creacion) AS 'fecha_creacion',
				tipayuges.nombre AS 'tipo_ayuda_gestion',tipayuges.idtipo_ayuda_gestion 
				FROM ca_ayuda_gestion ayuges INNER JOIN ca_tipo_ayuda_gestion tipayuges ON
				tipayuges.idtipo_ayuda_gestion=ayuges.idtipo_ayuda_gestion
				WHERE ayuges.estado=1 AND ayuges.idservicio=? AND ayuges.is_text=1 ORDER BY ayuges.fecha_creacion DESC LIMIT 2 ";

        $servicio = $dtoAyudaGestion->getIdServicio();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function insertDataCreation(dto_ayuda_gestion $dtoAyudaGestion, $_post, $_files) {

        $ruta = '../speech/' . $_post['NombreServicio'] . '/' . $_files['fileSpeech']['name'];

        if ($_files['fileSpeech']['type'] == 'text/plain') {
            $sql = " INSERT INTO ca_ayuda_gestion( idservicio, nombre, ruta, estado, idtipo_ayuda_gestion, fecha_creacion, usuario_creacion) 
				VALUES (?,?,?,1,?,NOW(),?) ";

            $servicio = $dtoAyudaGestion->getIdServicio();
            //$ruta=$dtoAyudaGestion->getRuta();
            $nombre = $dtoAyudaGestion->getNombre();
            $tipo = $dtoAyudaGestion->getIdTipoAyudaGestion();
            $UsuarioCreacion = $dtoAyudaGestion->getUsuarioCreacion();

            $factoryConnection = FactoryConnection::create('postgres_pdo');
            $connection = $factoryConnection->getConnection();

            //$connection->beginTransaction();

            $pr = $connection->prepare($sql);

            $pr->bindParam(1, $servicio);
            $pr->bindParam(2, $nombre);
            $pr->bindParam(3, $ruta);
            $pr->bindParam(4, $tipo);
            $pr->bindParam(5, $UsuarioCreacion);

            if (@$pr->execute()) {

                if (@opendir('../speech/' . $_post['NombreServicio'])) {
                    if (@move_uploaded_file($_files['fileSpeech']['tmp_name'], '../speech/' . $_post['NombreServicio'] . '/' . $_files['fileSpeech']['name'])) {
                        //$connection->commit();
                        echo json_encode(array('rst' => true, 'msg' => 'Archivo guardado correctamente'));
                    } else {
                        //$connection->rollBack();
                        echo json_encode(array('rst' => true, 'msg' => 'Error al subir archivo al servidor'));
                    }
                } else {

                    if (@mkdir('../speech/' . $_post['NombreServicio'])) {
                        if (@move_uploaded_file($_files['fileSpeech']['tmp_name'], '../speech/' . $_post['NombreServicio'] . '/' . $_files['fileSpeech']['name'])) {
                            //$connection->commit();
                            echo json_encode(array('rst' => true, 'msg' => 'Archivo guardado correctamente'));
                        } else {
                            //$connection->rollBack();
                            echo json_encode(array('rst' => true, 'msg' => 'Error al subir archivo al servidor'));
                        }
                    } else {
                        //$connection->rollBack();
                        echo json_encode(array('rst' => false, 'msg' => 'Error al crear directorio'));
                    }
                }
            } else {
                //$connection->rollback();
            }
        } else {
            echo json_encode(array('rst' => false, 'msg' => 'Formato de archivo incorrecto'));
        }
    }

    public function insertModoTexto(dto_ayuda_gestion $dtoAyudaGestion) {
        $servicio = $dtoAyudaGestion->getIdServicio();
        $TipoAyudaGestion = $dtoAyudaGestion->getIdTipoAyudaGestion();
        $nombre = $dtoAyudaGestion->getNombre();
        $texto = $dtoAyudaGestion->getTexto();
        $IsText = $dtoAyudaGestion->getIsText();
        $UsuarioCreacion = $dtoAyudaGestion->getUsuarioCreacion();

        $sql = " INSERT INTO ca_ayuda_gestion ( idservicio, idtipo_ayuda_gestion, nombre, texto, is_text, fecha_creacion, usuario_creacion ) 
			VALUES ( ?,?,?,?,?,NOW(),? ) ";

        //echo $servicio.'#'.$TipoAyudaGestion.'#'.$nombre.'#'.$texto.'#'.$IsText.'#'.$UsuarioCreacion; 
        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $TipoAyudaGestion);
        $pr->bindParam(3, $nombre);
        $pr->bindParam(4, $texto);
        $pr->bindParam(5, $IsText);
        $pr->bindParam(6, $UsuarioCreacion);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function updateModoTexto(dto_ayuda_gestion $dtoAyudaGestion) {
        $sql = " UPDATE ca_ayuda_gestion SET idtipo_ayuda_gestion = ? , nombre = ? , texto = ? , 
			fecha_modificacion = NOW(), usuario_modificacion = ? WHERE idayuda_gestion = ? ";

        $id = $dtoAyudaGestion->getId();
        $TipoAyudaGestion = $dtoAyudaGestion->getIdTipoAyudaGestion();
        $nombre = $dtoAyudaGestion->getNombre();
        $texto = $dtoAyudaGestion->getTexto();
        $UsuarioModificacion = $dtoAyudaGestion->getUsuarioModificacion();


        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $TipoAyudaGestion);
        $pr->bindParam(2, $nombre);
        $pr->bindParam(3, $texto);
        $pr->bindParam(4, $UsuarioModificacion);
        $pr->bindParam(5, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function ReadText(dto_ayuda_gestion $dtoAyudaGestion) {

        $sql = " SELECT texto FROM ca_ayuda_gestion WHERE idayuda_gestion = ? AND is_text = 1 ";

        $id = $dtoAyudaGestion->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ReadFile(dto_ayuda_gestion $dtoAyudaGestion) {

        $sql = " SELECT ruta FROM ca_ayuda_gestion WHERE idayuda_gestion = ? AND is_text=0 ";

        $id = $dtoAyudaGestion->getId();

        $factoryConnection = FactoryConnection::create('postgres_pdo');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $id);
        $pr->execute();
        $ruta = $pr->fetchAll(PDO::FETCH_ASSOC);

        if (file_exists($ruta[0]['ruta'])) {

            $gestor = fopen($ruta[0]['ruta'], "r");
            if ($gestor) {
                $data = array();
                while (!feof($gestor)) {
                    $line = fgets($gestor);
                    array_push($data, array('line' => $line));
                }
                return array('rst' => true, 'msg' => $data);
            } else {
                return array('rst' => false, 'msg' => 'Archvio seleccionado no se puede leer');
            }
        } else {
            return array('rst' => false, 'msg' => 'Problemas al encontrar archivo en el servidor');
        }
    }

}

?>