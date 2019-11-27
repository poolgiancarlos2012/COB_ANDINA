<?php

class MARIATelefonoDAO {

    public function DeshabilitarTelefono($numero, $codigo_cliente){
        $sql = " UPDATE ca_telefono
                SET 
                is_active = 0 
                WHERE (numero = ?  OR numero_act=?) and codigo_cliente='$codigo_cliente'";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare( $sql );
        $pr->bindParam(1,$numero,PDO::PARAM_STR);
        $pr->bindParam(2,$numero,PDO::PARAM_STR);        
        if( $pr->execute() ) {
            return true;
        }else{
            return false;
        }
    }    
    
    public function updateLineaTelefono ( $idlinea, $numero, $usu_mof ) {

        $sql = " UPDATE ca_telefono
                SET 
                idlinea_telefono = ? ,
                fecha_modificacion = NOW(),
                usuario_modificacion = ? 
                WHERE numero = ?  ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare( $sql );
        $pr->bindParam(1,$idlinea,PDO::PARAM_INT);
        $pr->bindParam(2,$usu_mof,PDO::PARAM_INT);
        $pr->bindParam(3,$numero,PDO::PARAM_STR);
        if( $pr->execute() ) {
            return true;
        }else{
            return false;
        }
        
    }

    public function CruceTelefono ( $tipo, $idcartera, $fecha_inicio, $fecha_fin, $carteras_fl, $usuario_creacion ) {

        $trace_sql = "";
        if( $tipo == 'mejor' ) {
            $trace_sql = " , finser.peso DESC ";
        }else if ( $tipo == 'ultima' ) {
            $trace_sql = " , lla.fecha DESC ";
        }else{
            $trace_sql = " LIMIT 1 ";
        }

        $date = date("Y_m_d_H_i_s");
        
        $sql = " CREATE TEMPORARY TABLE tmp_cr_te_".$date."  AS 
                SELECT *
                FROM (
                SELECT 
                ( SELECT TRIM(numero) FROM ca_telefono WHERE idtelefono = lla.idtelefono ) AS telefono,
                lla.idfinal
                FROM ca_cliente_cartera clicar INNER JOIN ca_llamada lla INNER JOIN ca_final_servicio finser 
                ON finser.idfinal = lla.idfinal AND lla.idcliente_cartera = clicar.idcliente_cartera 
                WHERE lla.estado = 1 AND lla.tipo = 'LL' AND clicar.idcartera IN ( ".$carteras_fl." ) 
                AND DATE(lla.fecha) BETWEEN ? AND ? 
                ORDER BY 1 ".$trace_sql." 
                ) t1 
                WHERE t1.telefono NOT IN ( '0','00','000','0000','00000','000000','00000000','00000000' )
                GROUP BY t1.telefono ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare( $sql );
        $pr->bindParam(1,$fecha_inicio,PDO::PARAM_STR);
        $pr->bindParam(2,$fecha_fin,PDO::PARAM_STR);
        if( $pr->execute() ) {

            $sqlAIn = " ALTER TABLE tmp_cr_te_".$date." ADD INDEX( telefono ) ";

            $prAIn = $connection->prepare( $sqlAIn ); 
            if ( $prAIn->execute() ) {

                $sqlUpdateTelefono = " UPDATE ca_telefono tel INNER JOIN tmp_cr_te_".$date." tmp 
                                    ON tmp.telefono = tel.numero 
                                    SET
                                    tel.idfinal = tmp.idfinal ,
                                    tel.usuario_modificacion = ? , 
                                    tel.fecha_modificacion = NOW() 
                                    WHERE tel.idcartera = ? ";
                
                $prUpdateTelefono = $connection->prepare( $sqlUpdateTelefono );
                $prUpdateTelefono->bindParam( 1, $usuario_creacion, PDO::PARAM_INT );
                $prUpdateTelefono->bindParam( 2, $idcartera, PDO::PARAM_INT );
                if( $prUpdateTelefono->execute() ) {
                    return true;
                }else{
                    return false;
                }

            } else {
                return false;    
            }
            
        }else{
            return false;
        }


    }

    public function UpdateStatus ( dto_telefono_ER2 $dtoTelefono ) {
        
        $usuario_modificacion = $dtoTelefono->getUsuarioModificacion();
        $numero = $dtoTelefono->getNumero();
        $status = $dtoTelefono->getStatus();
        $codigo_cliente = $dtoTelefono->getCodigoCliente();
        
        $sql = " UPDATE ca_telefono 
                SET
                status = ?,
                usuario_modificacion = ?,
                fecha_modificacion = NOW()
                WHERE codigo_cliente = ? AND numero = ? ";
                
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        $pr = $connection->prepare( $sql );
        $pr->bindParam(1,$status,PDO::PARAM_STR);
        $pr->bindParam(2,$usuario_modificacion,PDO::PARAM_INT);
        $pr->bindParam(3,$codigo_cliente,PDO::PARAM_STR);
        $pr->bindParam(4,$numero,PDO::PARAM_STR);
        if( $pr->execute() ) {
                return true;
        }else{
                return false;
        }
        
    }

    public function inactive(dto_telefono_ER2 $dtoTelefono) {

        $idTelefono = $dtoTelefono->getId();

        $sql = " UPDATE ca_telefono SET estado = 0 WHERE idtelefono = ? ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idTelefono, PDO::PARAM_INT);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function insert(dto_telefono_ER2 $dtoTelefono, dto_cliente $dtoCliente, $cuentas ) {
        
        $codigo_cliente = $dtoCliente->getCodigo();
        
        $cliente = $dtoTelefono->getIdCliente();
        $idcliente_cartera = $dtoTelefono->getIdClienteCartera();
        $origen = $dtoTelefono->getIdOrigen();
        $cartera = $dtoTelefono->getIdCartera();
        $TipoReferencia = $dtoTelefono->getIdTipoReferencia();
        $LineaTelefono = $dtoTelefono->getIdLineaTelefono();
        $TipoTelefono = $dtoTelefono->getIdTipoTelefono();
        $numero = $dtoTelefono->getNumero();
        $anexo = $dtoTelefono->getAnexo();
        $observacion = $dtoTelefono->getObservacion();
        $UsuarioCreacion = $dtoTelefono->getUsuarioCreacion();
        $is_campo = $dtoTelefono->getIsCampo();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        for( $i=0;$i<count($cuentas);$i++ ) {
                $sql = " INSERT INTO ca_telefono ( idcliente_cartera, idcuenta, codigo_cliente, idorigen, idcartera, idtipo_referencia, idtipo_telefono, idlinea_telefono, numero, anexo, observacion, usuario_creacion, fecha_creacion, is_new, is_campo ) 
                        VALUES( ?,?,?,?,?,?,?,?,?,?,?,?,NOW(),1,? ) ";
                
                $pr = $connection->prepare($sql);
                
                $pr->bindParam(1, $idcliente_cartera);
                $pr->bindParam(2, $cuentas[$i]['cuenta']);
                $pr->bindParam(3, $codigo_cliente);
                $pr->bindParam(4, $origen);
                $pr->bindParam(5, $cartera);
                $pr->bindParam(6, $TipoReferencia);
                $pr->bindParam(7, $TipoTelefono);
                $pr->bindParam(8, $LineaTelefono);
                $pr->bindParam(9, $numero);
                $pr->bindParam(10, $anexo);
                $pr->bindParam(11, $observacion);
                $pr->bindParam(12, $UsuarioCreacion);
                $pr->bindParam(13, $is_campo);
                
                if ($pr->execute()) {
                    
                } else {
                    //return false;
                    return array("rst"=>false,"msg"=>"Error al grabar telefono");
                    exit();
                }
        
        }

        //return true;
        $id = $connection->lastInsertId();
        return array("rst"=>true,"msg"=>"Telefono grabado correctamente","id"=>$id,"idcuenta"=>$cuentas[0]['cuenta']);
        
    }

    public function UpdateNumero(dto_telefono_ER2 $dtoTelefono) {
        $sql = " UPDATE ca_telefono SET numero = ? WHERE idtelefono = ? ";

        $id = $dtoTelefono->getId();
        $numero = $dtoTelefono->getNumero();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $numero);
        $pr->bindParam(2, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function update(dto_telefono_ER2 $dtoTelefono) {

        $id = $dtoTelefono->getId();
        $TipoReferencia = $dtoTelefono->getIdTipoReferencia();
        $TipoTelefono = $dtoTelefono->getIdTipoTelefono();
        $LineaTelefono = $dtoTelefono->getIdLineaTelefono();
        $numero = $dtoTelefono->getNumero();
        $anexo = $dtoTelefono->getAnexo();
        $observacion = $dtoTelefono->getObservacion();
        $UsuarioModificacion = $dtoTelefono->getUsuarioModificacion();

        $sql = " UPDATE ca_telefono SET numero = ?, anexo = ?, idtipo_telefono = ? , idtipo_referencia = ? ,
			idlinea_telefono = ? , observacion = ? , fecha_modificacion = NOW() , usuario_modificacion = ? 
			WHERE idtelefono = ? ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $numero);
        $pr->bindParam(2, $anexo);
        $pr->bindParam(3, $TipoTelefono);
        $pr->bindParam(4, $TipoReferencia);
        $pr->bindParam(5, $LineaTelefono);
        $pr->bindParam(6, $observacion);
        $pr->bindParam(7, $UsuarioModificacion);
        $pr->bindParam(8, $id);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

    public function queryById(dto_telefono_ER2 $dtoTelefono) {

        $sql = " SELECT idtelefono, numero, IFNULL(anexo,'') AS 'anexo', IFNULL(idtipo_telefono,0) AS 'idtipo_telefono', 
			IFNULL(idorigen,'') AS 'idorigen', IFNULL(idtipo_referencia,0) AS 'idtipo_referencia', 
			IFNULL(idlinea_telefono,0) AS 'idlinea_telefono',IFNULL(observacion,'') AS 'observacion' 
			FROM ca_telefono WHERE idtelefono = ? ";

        $id = $dtoTelefono->getId();

        $factoryConnection = FactoryConnection::create('mysql');
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

    public function queryTelefonosPorNombreCliente(dto_cliente $dtoCliente) {

        $nombre = $dtoCliente->getNombre();

//			$sql=" SELECT DISTINCT lla.idtelefono,cli.codigo,TRIM(CONCAT_WS('',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//				( SELECT nombre FROM ca_servicio WHERE idservicio=cli.idservicio LIMIT 1 ) AS 'servicio',
//				tel.numero,tel.anexo,
//				( SELECT nombre FROM ca_origen where idorigen=tel.idorigen LIMIT 1 )  AS 'origen',
//				( SELECT nombre FROM ca_tipo_telefono WHERE idtipo_telefono=tel.idtipo_telefono LIMIT 1 ) AS 'tipo_telefono',
//				( SELECT nombre FROM ca_tipo_referencia WHERE idtipo_referencia=tel.idtipo_referencia LIMIT 1 ) AS 'tipo_referencia',
//				IFNULL(( SELECT nombre FROM ca_linea_telefono WHERE idlinea_telefono=tel.idlinea_telefono LIMIT 1 ),'') AS 'linea_telefono'
//				FROM ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_telefono tel INNER JOIN ca_cliente cli 
//				ON cli.idcliente=tel.idcliente AND tel.idtelefono=lla.idtelefono AND lla.idtransaccion=tran.idtransaccion 
//				WHERE CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) LIKE '%$nombre%' ";
//			$sql=" SELECT DISTINCT(tel.numero) AS 'numero',tel.idtelefono,tel.anexo,
//				( SELECT nombre FROM ca_origen WHERE idorigen=tel.idorigen LIMIT 1 ) AS 'origen',
//				( SELECT nombre FROM ca_tipo_telefono WHERE idtipo_telefono=tel.idtipo_telefono LIMIT 1 ) AS 'tipo_telefono',
//				( SELECT nombre FROM ca_tipo_referencia WHERE idtipo_referencia=tel.idtipo_referencia LIMIT 1 ) AS 'tipo_referencia',
//				cli.idcliente,cli.codigo,TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
//				(SELECT nombre FROM ca_servicio WHERE idservicio=cli.idservicio LIMIT 1 ) AS 'servicio',
//				IFNULL(( SELECT carfin.nombre FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_telefono tel2 
//				ON tel2.idtelefono=lla.idtelefono AND lla.idtransaccion=tran.idtransaccion AND tran.idfinal=fin.idfinal AND fin.idcarga_final=carfin.idcarga_final 
//				WHERE TRIM(tel2.numero)=TRIM(tel.numero) ORDER BY tran.idtransaccion DESC LIMIT 1  ),'') AS 'carga_final'
//				FROM ca_cliente cli INNER JOIN ca_telefono tel ON tel.idcliente=cli.idcliente
//				WHERE CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) LIKE '%$nombre%'  GROUP BY TRIM(tel.numero); ";

        $sql = " SELECT DISTINCT(tel.numero) AS 'numero',tel.idtelefono,tel.anexo,
				( SELECT nombre FROM ca_origen WHERE idorigen=tel.idorigen LIMIT 1 ) AS 'origen',
				( SELECT nombre FROM ca_tipo_telefono WHERE idtipo_telefono=tel.idtipo_telefono LIMIT 1 ) AS 'tipo_telefono',
				( SELECT nombre FROM ca_tipo_referencia WHERE idtipo_referencia=tel.idtipo_referencia LIMIT 1 ) AS 'tipo_referencia',
				cli.idcliente,cli.codigo,TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) AS 'cliente',
				(SELECT nombre FROM ca_servicio WHERE idservicio=cli.idservicio LIMIT 1 ) AS 'servicio',
				IFNULL(( SELECT carfin.nombre FROM ca_carga_final carfin INNER JOIN ca_final fin INNER JOIN ca_transaccion tran INNER JOIN ca_llamada lla INNER JOIN ca_telefono tel2 
				ON tel2.idtelefono=lla.idtelefono AND lla.idtransaccion=tran.idtransaccion AND tran.idfinal=fin.idfinal AND fin.idcarga_final=carfin.idcarga_final 
				WHERE TRIM(tel2.numero)=TRIM(tel.numero) ORDER BY tran.idtransaccion DESC LIMIT 1  ),'') AS 'carga_final'
				FROM ca_cliente cli INNER JOIN ca_telefono tel ON tel.idcliente=cli.idcliente 
				WHERE CONCAT_WS(' ',cli.paterno,cli.materno,cli.nombre) LIKE '%$nombre%' GROUP BY TRIM(tel.numero); ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            //$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //$connection->rollBack();
            return array();
        }
    }

    public function importTelefonos($ids, dto_telefono_ER2 $dtoTelefono) {

        $UsuarioCreacion = $dtoTelefono->getUsuarioCreacion();
        $cliente = $dtoTelefono->getIdCliente();
        $cartera = $dtoTelefono->getIdCartera();

//			$sql=" INSERT INTO ca_telefono( numero,is_import,anexo,observacion,idorigen,idtipo_referencia,idtipo_telefono,idlinea_telefono,idcartera,idcliente,fecha_creacion,usuario_creacion )
//			SELECT numero,1,anexo,observacion,idorigen,idtipo_referencia,idtipo_telefono,idlinea_telefono,$cartera,$cliente,NOW(),$UsuarioCreacion 
//			FROM ca_telefono WHERE idtelefono IN ( $ids ) ";

        $sql = " INSERT INTO ca_telefono( numero,is_import,anexo,observacion,idorigen,idtipo_referencia,idtipo_telefono,idlinea_telefono,idcartera,codigo_cliente,fecha_creacion,usuario_creacion )
			SELECT numero,1,anexo,observacion,idorigen,idtipo_referencia,idtipo_telefono,idlinea_telefono,$cartera,
			( SELECT codigo FROM ca_cliente WHERE idcliente = ? LIMIT 1 ),
			NOW(),$UsuarioCreacion 
			FROM ca_telefono WHERE idtelefono IN ( $ids ) ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cliente);
        if ($pr->execute()) {
            //$connection->commit();
            return true;
        } else {
            //$connection->rollBack();
            return false;
        }
    }

}

?>