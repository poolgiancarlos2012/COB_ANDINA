<?php

class MARIAAlertaDAO {

    public function queryAlertasRecientes($servicio, $usuario_creacion, $fecha, $hora) {

        $sql = " SELECT alert.idalerta, alert.fecha_alerta, alert.descripcion, alert.idcliente_cartera, 
        cli.idcliente, clicar.codigo_cliente, clicar.idcartera, clicar.idusuario_servicio , DATE_FORMAT( alert.fecha_alerta, '%r' ) AS 'fecha_format',
        TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
        FROM ca_campania cam 
        INNER JOIN ca_cartera car ON car.idcampania = cam.idcampania
        INNER JOIN ca_cliente_cartera clicar ON clicar.idcartera = car.idcartera
        INNER JOIN ca_cliente cli ON cli.idcliente = clicar.idcliente
        INNER JOIN ca_alerta alert ON alert.idcliente_cartera = clicar.idcliente_cartera
        WHERE alert.estado = 1 AND cli.estado = 1 AND cam.idservicio = ?
        AND clicar.estado = 1 AND alert.usuario_creacion = ? AND DATE(alert.fecha_alerta) = ?  
        AND TIME(alert.fecha_alerta) >= ? "; 

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $usuario_creacion, PDO::PARAM_INT);
        $pr->bindParam(3, $fecha, PDO::PARAM_STR);
        $pr->bindParam(4, $hora, PDO::PARAM_STR);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function insertDataCreation(dto_alerta $dto) {
        $sql = " INSERT INTO ca_alerta ( fecha_creacion,fecha_alerta,descripcion,estado,idcliente_cartera,usuario_creacion) 
				VALUES ( NOW(),?,?,1,?,? ) ";

        $fechaAlerta = $dto->getFechaAlerta();
        $descripcion = trim($dto->getDescripcion());
        $idClienteCartera = $dto->getIdClienteCartera();
        $usuarioCreacion = $dto->getUsuarioCreacion();
        $idusuario_servicio = $dto->getIdUsuarioServicio();
        $idservicio = $dto->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $fechaAlerta);
        $pr->bindParam(2, $descripcion);
        $pr->bindParam(3, $idClienteCartera);
        $pr->bindParam(4, $usuarioCreacion);
        if ($pr->execute()) {

            $idalerta = $connection->lastInsertId();

            $sqlUsuarioServicio = " SELECT ususer.idusuario_servicio , CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS 'usuario'
						FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer 
						ON ususer.idusuario = usu.idusuario WHERE ususer.idusuario_servicio = ? ";
            $prUsu = $connection->prepare($sqlUsuarioServicio);
            $prUsu->bindParam(1, $idusuario_servicio, PDO::PARAM_INT);
            $prUsu->execute();
            $dataUsu = $prUsu->fetchAll(PDO::FETCH_ASSOC);

            $sqlCliente = " SELECT cli.idcliente, CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) AS 'cliente',
						cli.numero_documento, cli.tipo_documento 
						FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar 
						ON clicar.codigo_cliente = cli.codigo 
						WHERE cli.idservicio = ? AND clicar.idcliente_cartera = ? ";
            $prCli = $connection->prepare($sqlCliente);
            $prCli->bindParam(1, $idservicio, PDO::PARAM_INT);
            $prCli->bindParam(2, $idClienteCartera, PDO::PARAM_INT);
            $prCli->execute();
            $dataCli = $prCli->fetchAll(PDO::FETCH_ASSOC);

            /* $factoryConnectionSQLITE = FactoryConnection::create('sqlite');
              $connectionSQLITE = $factoryConnectionSQLITE->getConnection(); */

            /* $connectionSQLITE = new PDO("sqlite:../db/cobrast.sqlite","","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_PERSISTENT => true));

              $connectionSQLITE->beginTransaction();

              $sqlite = " INSERT INTO ca_alerta( idalerta, descripcion, fecha_alerta, idusuario_servicio,
              nombre_usuario_servicio, idcliente_cartera, nombre_cliente, numero_documento_cliente, tipo_documento_cliente, estado , idservicio )
              VALUES ( ?,?,?,?,?,?,?,?,?,?,?,1,? ) "; */

            /* $createTable = " CREATE TABLE ca_alerta (
              idalerta INT PRIMARY KEY,
              descripcion TEXT,
              fecha_alerta DATETIME,
              fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
              idusuario_servicio INT,
              nombre_usuario_servicio VARCHAR(100),
              idcliente_cartera INT,
              nombre_cliente VARCHAR(100),
              numero_documento_cliente VARCHAR(50),
              tipo_documento_cliente VARCHAR(50),
              estado INT DEFAULT 1,
              idservicio INT
              ) ";
              $prSqlite = $connectionSQLITE->prepare( $createTable );
              $prSqlite->execute(); */

            /* $prSqlite = $connectionSQLITE->prepare( $sqlite );
              echo var_dump($prSqlite);
              $prSqlite->bindParam(1,$idalerta);
              $prSqlite->bindParam(2,$descripcion);
              $prSqlite->bindParam(3,$fechaAlerta);
              $prSqlite->bindParam(4,$idusuario_servicio);
              $prSqlite->bindParam(5,$dataUsu[0]['usuario']);
              $prSqlite->bindParam(6,$idClienteCartera);
              $prSqlite->bindParam(7,$dataCli[0]['cliente']);
              $prSqlite->bindParam(8,$dataCli[0]['numero_documento']);
              $prSqlite->bindParam(9,$dataCli[0]['tipo_documento']);
              $prSqlite->bindParam(10,$idservicio);
              if( $prSqlite->execute(array($idalerta,$descripcion,$fechaAlerta,$idusuario_servicio,$dataUsu[0]['usuario'],$idClienteCartera,$dataCli[0]['cliente'],$dataCli[0]['numero_documento'],$dataCli[0]['tipo_documento'],$idservicio)) ) {

              $sqliteQuery = " SELECT * FROM ca_alerta ";
              $prSqliteQuery = $connectionSQLITE->prepare($sqliteQuery);
              $prSqliteQuery->execute();
              print_r($prSqliteQuery->fetchAll(PDO::FETCH_ASSOC));
              //$connection->commit();
              $connectionSQLITE->commit();
              return true;
              }else{
              //$connection->rollBack();
              $connectionSQLITE->rollBack();
              return false;
              } */


            /*if ($cnSqlite = new SQLiteDatabase("../db/cobrast.sqlite")) {

                $cnSqlite->queryExec(" CREATE TABLE ca_alerta (
											idalerta INT PRIMARY KEY, 
											descripcion TEXT, 
											fecha_alerta DATETIME, 
											fecha_creacion DATETIME , 
											idusuario_servicio INT, 
											nombre_usuario_servicio VARCHAR(100), 
											idcliente_cartera INT, 
											nombre_cliente VARCHAR(100), 
											numero_documento_cliente VARCHAR(50), 
											tipo_documento_cliente VARCHAR(50), 
											estado INT DEFAULT 1,
											idservicio INT 
											) ");

                $sqlite = " INSERT INTO ca_alerta( idalerta, descripcion, fecha_alerta, fecha_creacion, idusuario_servicio, 
								nombre_usuario_servicio, idcliente_cartera, nombre_cliente, numero_documento_cliente, tipo_documento_cliente, estado , idservicio ) 
								VALUES( " . $idalerta . ", '" . $descripcion . "', '" . $fechaAlerta . "', '" . date("Y-m-d H:i:s") . "' , " . $idusuario_servicio . ",
								'" . $dataUsu[0]['usuario'] . "', " . $idClienteCartera . ", '" . $dataCli[0]['cliente'] . "', '" . $dataCli[0]['numero_documento'] . "', 
								'" . $dataCli[0]['tipo_documento'] . "' , 1, " . $idservicio . " ) ";

                if ($cnSqlite->queryExec($sqlite)) {

                      //$sqliteResult = $cnSqlite->query( " SELECT * FROM ca_alerta WHERE date(fecha_alerta) = date('now')  ", SQLITE_ASSOC );
                      //while( $row = $sqliteResult->fetch()  ) {
                      //print_r($row);
                      //} 

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }*/
            return true;
        } else {

            return false;
        }
    }

    public function delete(dto_alerta $dtoAlerta) {
        $sql = " UPDATE ca_alerta SET estado=0, fecha_modificacion=NOW(), usuario_modificacion=? WHERE idalerta=?  ";

        $UsuarioModificacion = $dtoAlerta->getUsuarioModificacion();
        $alerta = $dtoAlerta->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $UsuarioModificacion);
        $pr->bindParam(2, $alerta);
        if ($pr->execute()) {

            /*if ($cnSqlite = new SQLiteDatabase("../db/cobrast.sqlite")) {

                $sqlite = " UPDATE ca_alerta SET estado = 0 WHERE idalerta = $alerta ";

                if ($cnSqlite->queryExec($sqlite)) {

                    return true;
                } else {

                    return false;
                }
            } else {

                return false;
            }*/

            return true;
        } else {
            
            return false;
        }
    }

    public function updateDataModification(dto_alerta $dto) {
        $sql = " UPDATE ca_alerta SET fecha_alerta=? ,descripcion=? ,fecha_modificacion=NOW(), usuario_modificacion=? 
				WHERE idalerta=? ";

        $id = $dto->getId();
        $fechaAlerta = $dto->getFechaAlerta();
        $descripcion = $dto->getDescripcion();
        $usuarioModificacion = $dto->getUsuarioModificacion();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        ////$connection->beginTransaction();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $fechaAlerta);
        $pr->bindParam(2, $descripcion);
        $pr->bindParam(3, $usuarioModificacion);
        $pr->bindParam(4, $id);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    //public function queryByUsuarioServicioTodayLastCampaign ( dto_usuario_servicio $dtoUsuarioServicio, dto_cartera $dtoCartera, dto_cliente $dtoCliente ) {
    public function queryByUsuarioServicioTodayLastCampaign(dto_usuario_servicio $dtoUsuarioServicio, dto_cliente $dtoCliente, $fecha, $hora) {
//			$sql=" SELECT aler.idalerta,aler.fecha_alerta,aler.descripcion,aler.idcliente_cartera,clicar.idcliente,car.idcampania,clicar.idusuario_servicio,
//			TRIM(( SELECT CONCAT_WS(' ',nombre,paterno,materno) FROM ca_cliente WHERE idcliente=clicar.idcliente)) AS 'cliente'
//			FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_alerta aler 
//			ON aler.idcliente_cartera=clicar.idcliente_cartera and clicar.idcartera=car.idcartera
//			WHERE car.idcampania IN ( SELECT idcampania FROM ca_campania WHERE idservicio=? AND estado=1 AND fecha_inicio<=CURDATE() AND fecha_fin>=CURDATE() )
//			AND clicar.idusuario_servicio=? AND aler.fecha_alerta>=NOW() AND aler.estado=1 " ;
//			$sql=" SELECT alert.idalerta,alert.fecha_alerta,alert.descripcion,alert.idcliente_cartera,clicar.idcliente,clicar.idcartera,clicar.idusuario_servicio,
//				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN  ca_alerta alert 
//				ON alert.idcliente_cartera= clicar.idcliente_cartera AND clicar.idcliente=cli.idcliente 
//				WHERE cli.estado = 1 AND clicar.idusuario_servicio = ? AND alert.estado = 1 AND alert.fecha_alerta >= NOW() 
//				AND clicar.idcartera = ? AND clicar.estado=1 ORDER BY alert.fecha_alerta ";

        /* $sql=" SELECT alert.idalerta,alert.fecha_alerta,alert.descripcion,alert.idcliente_cartera,
          cli.idcliente,clicar.codigo_cliente,clicar.idcartera,clicar.idusuario_servicio,DATE_FORMAT( alert.fecha_alerta, '%r' ) AS 'fecha_format',
          TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
          FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN  ca_alerta alert
          ON alert.idcliente_cartera= clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo
          WHERE cli.estado = 1 AND cli.idservicio = ? AND clicar.idusuario_servicio = ? AND alert.estado = 1
          AND DATE(alert.fecha_alerta) = CURDATE() AND TIME(alert.fecha_alerta) >= CURTIME()
          AND clicar.idcartera = ? AND clicar.estado=1 ORDER BY alert.fecha_alerta "; */

        $sql = " SELECT alert.idalerta,alert.fecha_alerta,alert.descripcion,alert.idcliente_cartera,
				cli.idcliente,clicar.codigo_cliente,clicar.idcartera,clicar.idusuario_servicio,
				DATE_FORMAT( alert.fecha_alerta, '%r' ) AS 'fecha_format',
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania cam  INNER JOIN  ca_alerta alert 
				ON alert.idcliente_cartera= clicar.idcliente_cartera AND cam.idcampania = car.idcampania AND car.idcartera = clicar.idcartera AND clicar.codigo_cliente=cli.codigo 
				WHERE cam.idservicio = ? AND clicar.idusuario_servicio = ? AND alert.estado = 1 AND car.estado = 1
				AND DATE(alert.fecha_alerta) = ? AND TIME(alert.fecha_alerta) >= ? ORDER BY alert.fecha_alerta ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $servicio = $dtoCliente->getIdServicio();
        //$cartera=$dtoCartera->getId();
        //echo $UsuarioServicio.'##'.$campania;
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * * */
        $pr->bindParam(1, $servicio);
        /*         * * */
        $pr->bindParam(2, $UsuarioServicio);
        //$pr->bindParam(2,$UsuarioServicio);
        //$pr->bindParam(3,$cartera);
        /*         * ********** */
        $pr->bindParam(3, $fecha);
        $pr->bindParam(4, $hora);
        /*         * ********** */
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function alertasHoy(dto_usuario_servicio $dtoUsuarioServicio, dto_cartera $dtoCartera, dto_cliente $dtoCliente, $fecha, $hora) {

        /* $sql=" SELECT alert.idalerta,alert.fecha_alerta,alert.descripcion,alert.idcliente_cartera,
          cli.idcliente,clicar.codigo_cliente,clicar.idcartera,clicar.idusuario_servicio, DATE_FORMAT( alert.fecha_alerta, '%r' ) AS 'fecha_format',
          TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
          FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN  ca_alerta alert
          ON alert.idcliente_cartera= clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo
          WHERE cli.estado = 1 AND cli.idservicio = ? AND clicar.idusuario_servicio = ?
          AND alert.estado = 1 AND DATE(alert.fecha_alerta) = CURDATE() AND TIME(alert.fecha_alerta) < CURTIME()
          AND clicar.idcartera = ? AND clicar.estado=1 ORDER BY alert.fecha_alerta DESC "; */

        $sql = " SELECT alert.idalerta,alert.fecha_alerta,alert.descripcion,alert.idcliente_cartera,
				cli.idcliente,clicar.codigo_cliente,clicar.idcartera,clicar.idusuario_servicio, DATE_FORMAT( alert.fecha_alerta, '%r' ) AS 'fecha_format',
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN  ca_alerta alert 
				ON alert.idcliente_cartera= clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo 
				WHERE cli.idservicio = ? AND clicar.idusuario_servicio = ? 
				AND alert.estado = 1 AND DATE(alert.fecha_alerta) = ? AND TIME(alert.fecha_alerta) < ? 
				GROUP BY alert.fecha_alerta,clicar.idcliente_cartera ORDER BY alert.fecha_alerta DESC ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $servicio = $dtoCliente->getIdServicio();
        //$cartera=$dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $UsuarioServicio);
        //$pr->bindParam(3,$cartera);
        /*         * *** */
        $pr->bindParam(3, $fecha);
        $pr->bindParam(4, $hora);
        /*         * *** */
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function alertasHoySQLITE(dto_alerta $dtoAlerta) {

        $idservicio = $dtoAlerta->getIdServicio();

        $sqlite = " SELECT idalerta, descripcion , fecha_alerta , fecha_creacion , idusuario_servicio , 
				nombre_usuario_servicio , idcliente_cartera , nombre_cliente , numero_documento_cliente , 
				tipo_documento_cliente , estado , idservicio  
				FROM ca_alerta WHERE DATE(fecha_alerta) = DATE('now') AND idservicio = $idservicio  ";

        if ($cnSqlite = new SQLiteDatabase("../db/cobrast.sqlite")) {

            $data = array();
            $sqliteResult = $cnSqlite->query($sqlite, SQLITE_ASSOC);
            while ($row = $sqliteResult->fetch()) {
                array_push($data, $row);
            }

            return $data;
        } else {
            return array();
        }
    }

    public function alertasAyer(dto_usuario_servicio $dtoUsuarioServicio, dto_cartera $dtoCartera, dto_cliente $dtoCliente, $fecha) {

        /* $sql=" SELECT alert.idalerta,alert.fecha_alerta,alert.descripcion,alert.idcliente_cartera,
          cli.idcliente,clicar.codigo_cliente,clicar.idcartera,clicar.idusuario_servicio,DATE_FORMAT( alert.fecha_alerta, '%a %r' ) AS 'fecha_format',
          TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
          FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN  ca_alerta alert
          ON alert.idcliente_cartera= clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo
          WHERE cli.estado = 1 AND cli.idservicio = ? AND clicar.idusuario_servicio = ?
          AND alert.estado = 1 AND DATE(alert.fecha_alerta) = ( CURDATE() - 1 )
          AND clicar.idcartera = ? AND clicar.estado=1 ORDER BY alert.fecha_alerta DESC "; */

        $sql = " SELECT alert.idalerta,alert.fecha_alerta,alert.descripcion,alert.idcliente_cartera,
				cli.idcliente,clicar.codigo_cliente,clicar.idcartera,clicar.idusuario_servicio,DATE_FORMAT( alert.fecha_alerta, '%a %r' ) AS 'fecha_format',
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN  ca_alerta alert 
				ON alert.idcliente_cartera= clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo 
				WHERE cli.idservicio = ? AND clicar.idusuario_servicio = ? 
				AND alert.estado = 1 AND DATE(alert.fecha_alerta) = ( DATE(?) - 1 )
				GROUP BY clicar.idcliente_cartera, alert.fecha_alerta ORDER BY alert.fecha_alerta DESC ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $servicio = $dtoCliente->getIdServicio();
        //$cartera=$dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $UsuarioServicio);
        //$pr->bindParam(3,$cartera);
        /*         * ******* */
        $pr->bindParam(3, $fecha);
        /*         * ********* */
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function alertasAyerSQLITE(dto_alerta $dtoAlerta) {

        $idservicio = $dtoAlerta->getIdServicio();

        $sqlite = " SELECT idalerta, descripcion , fecha_alerta , fecha_creacion , idusuario_servicio , 
				nombre_usuario_servicio , idcliente_cartera , nombre_cliente , numero_documento_cliente , 
				tipo_documento_cliente , estado , idservicio  
				FROM ca_alerta WHERE DATE(fecha_alerta) = DATE('now','-1 day') AND idservicio = $idservicio  ";

        if ($cnSqlite = new SQLiteDatabase("../db/cobrast.sqlite")) {

            $data = array();
            $sqliteResult = $cnSqlite->query($sqlite, SQLITE_ASSOC);
            while ($row = $sqliteResult->fetch()) {
                array_push($data, $row);
            }

            return $data;
        } else {
            return array();
        }
    }

    public function alertasAntiguas(dto_usuario_servicio $dtoUsuarioServicio, dto_cartera $dtoCartera, dto_cliente $dtoCliente, $fecha) {

        /* $sql=" SELECT alert.idalerta,alert.fecha_alerta,alert.descripcion,alert.idcliente_cartera,
          cli.idcliente,clicar.codigo_cliente,clicar.idcartera,clicar.idusuario_servicio,alert.fecha_alerta AS 'fecha_format',
          TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
          FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN  ca_alerta alert
          ON alert.idcliente_cartera= clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo
          WHERE cli.estado = 1 AND cli.idservicio = ? AND clicar.idusuario_servicio = ?
          AND alert.estado = 1 AND DATE(alert.fecha_alerta) < ( CURDATE() - 1 )
          AND clicar.idcartera = ? AND clicar.estado=1 ORDER BY alert.fecha_alerta DESC "; */

        $sql = " SELECT alert.idalerta,alert.fecha_alerta,alert.descripcion,alert.idcliente_cartera,
				cli.idcliente,clicar.codigo_cliente,clicar.idcartera,clicar.idusuario_servicio,alert.fecha_alerta AS 'fecha_format',
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) ) AS 'cliente',1 AS 'estado'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN  ca_alerta alert 
				ON alert.idcliente_cartera= clicar.idcliente_cartera AND clicar.codigo_cliente=cli.codigo 
				WHERE cli.idservicio = ? AND clicar.idusuario_servicio = ? 
				AND alert.estado = 1 AND DATE(alert.fecha_alerta) < ( DATE(?) - 1 )
				GROUP BY clicar.idcliente_cartera,alert.fecha_alerta ORDER BY alert.fecha_alerta DESC LIMIT 30 ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $servicio = $dtoCliente->getIdServicio();
        //$cartera=$dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $UsuarioServicio);
        //$pr->bindParam(3,$cartera);
        $pr->bindParam(3, $fecha);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function alertasAntiguasSQLITE(dto_alerta $dtoAlerta) {

        $idservicio = $dtoAlerta->getIdServicio();

        $sqlite = " SELECT idalerta, descripcion , fecha_alerta , fecha_creacion , idusuario_servicio , 
				nombre_usuario_servicio , idcliente_cartera , nombre_cliente , numero_documento_cliente , 
				tipo_documento_cliente , estado , idservicio  
				FROM ca_alerta WHERE DATE(fecha_alerta) < DATE('now','-1 day') AND idservicio = $idservicio LIMIT 20  ";

        if ($cnSqlite = new SQLiteDatabase("../db/cobrast.sqlite")) {

            $data = array();
            $sqliteResult = $cnSqlite->query($sqlite, SQLITE_ASSOC);
            while ($row = $sqliteResult->fetch()) {
                array_push($data, $row);
            }

            return $data;
        } else {
            return array();
        }
    }

    public function queryAllByOperator(dto_usuario_servicio $dtoUsuarioServicio) {
//			$sql=" SELECT distinct alert.idalerta,alert.descripcion,alert.fecha_alerta,
//				CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',cam.nombre AS 'campania'
//				FROM ca_campania cam INNER JOIN ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli INNER JOIN ca_alerta alert 
//				ON alert.idcliente_cartera=clicar.idcliente_cartera AND cli.idcliente=clicar.idcliente AND clicar.idcartera=car.idcartera
//				AND car.idcampania=cam.idcampania
//				WHERE cam.idservicio=? AND clicar.idusuario_servicio=? AND alert.estado=1 ORDER BY alert.fecha_alerta DESC  ";
//			$sql=" SELECT DISTINCT alert.idalerta,alert.descripcion,alert.fecha_alerta,
//				CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',camp.nombre AS 'campania'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania camp INNER JOIN ca_alerta alert
//				ON alert.idcliente_cartera=clicar.idcliente_cartera AND camp.idcampania=car.idcampania AND car.idcartera=clicar.idcartera 
//				AND clicar.idcliente=cli.idcliente
//				WHERE cli.estado=1 AND clicar.idusuario_servicio = ? AND alert.estado=1 AND camp.idservicio = ?
//				ORDER BY alert.fecha_alerta DESC ";	

        $sql = " SELECT DISTINCT alert.idalerta,alert.descripcion,alert.fecha_alerta,
				CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'cliente',camp.nombre AS 'campania'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cartera car INNER JOIN ca_campania camp INNER JOIN ca_alerta alert
				ON alert.idcliente_cartera=clicar.idcliente_cartera AND camp.idcampania=car.idcampania AND car.idcartera=clicar.idcartera 
				AND clicar.codigo_cliente=cli.codigo
				WHERE cli.idservicio=? AND cli.estado=1 AND clicar.idusuario_servicio = ? AND camp.idservicio = ?
				ORDER BY alert.fecha_alerta DESC ";

        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $servicio = $dtoUsuarioServicio->getIdServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        /*         * ****** */
        $pr->bindParam(1, $servicio);
        /*         * ****** */
        $pr->bindParam(2, $UsuarioServicio);
        $pr->bindParam(3, $servicio);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
