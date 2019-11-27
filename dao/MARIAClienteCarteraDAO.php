<?php

class MARIAClienteCarteraDAO {

    public function listarSituacionLaboral($xidservicio){

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sql="SELECT idsituacion_laboral , nombre  FROM ca_situacion_laboral WHERE estado=1  AND idservicio = $xidservicio";
        $pr=$connection->prepare($sql);

        if( $pr->execute() ){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{

        }
    }

    public function listarDisposicionRefinanciamiento($xidservicio){

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        $sql="SELECT iddisposicion_refinanciar , nombre  FROM ca_disposicion_refinanciar WHERE estado=1  AND idservicio = $xidservicio";
        $pr=$connection->prepare($sql);

        if( $pr->execute() ){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{

        }

    }

    public function listarEstadoCliente($xidservicio){

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        
        $sql="SELECT idestado_cliente , nombre  FROM ca_estado_cliente WHERE estado=1  AND idservicio = $xidservicio";
        $pr=$connection->prepare($sql);

        if( $pr->execute() ){
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        }else{

        }

    }



    public function getIdTelefonoCliente($_post){
        $telefono=$_post['telefono'];
        $idClicar=$_post['idClicar'];

        $codCli=$_post['codCli'];
        $idCartera=$_post['idCartera'];

        $sql = " SELECT t1.idtelefono, t1.numero, t1.anexo, t1.is_new, t1.is_campo,t1.is_carga, t1.referencia , t1.estado , t1.prefijos, t1.peso FROM
                (SELECT idtelefono, IFNULL(numero_act,numero) as numero, IFNULL(anexo,'') AS 'anexo', IFNULL(m_peso,0) AS peso ,
                is_new, is_campo,is_carga ,IFNULL(referencia,'') AS referencia ,
                IFNULL(( SELECT nombre FROM ca_final WHERE idfinal = tel.idfinal ),'') AS estado ,
                IFNULL(( SELECT CONCAT_WS(':',nombre,CONCAT_WS('-',lb_prefijo,lb_prefijo2,lb_prefijo3)) FROM ca_linea_telefono WHERE idlinea_telefono = tel.idlinea_telefono ),':') AS prefijos 
                FROM ca_telefono tel 
                WHERE idcliente_cartera='$idClicar' and (numero='$telefono' or numero_act='$telefono') limit 1
                ) t1";
        //echo($sql);exit;
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            $data= $pr->fetchAll(PDO::FETCH_ASSOC);
            if(count($data)>0){
                $pref = explode(":",$data[0]['prefijos']);
                $data[0]["linea"]=$pref[0];
                $data[0]["prefijos"]=$pref[1];
                return array('rst'=>true,'msg'=>'Ok telefono','data'=>$data);
            }else{
                return array('rst'=>false,'msg'=>'<b>Telefono no existe, ingreselo Manualmente!!</b>');    
            }
        } else {
            return array('rst'=>false,'msg'=>'Error en query');
        }        
    }
	
	public function guardarRefinanciamiento ( dto_cliente_cartera $dtoCliCar ) {
		
		$idcli_car = $dtoCliCar->getId();
		$deuda = $dtoCliCar->getDeuda();
		$descuento = $dtoCliCar->getDescuentoRef();
		$interes = $dtoCliCar->getInteresRef();
		$comision = $dtoCliCar->getComisionRef();
		$mora = $dtoCliCar->getMoraRef();
		$gastos_cobranza = $dtoCliCar->getGastosCobranzaRef();
		$n_cuotas = $dtoCliCar->getNumeroCuotasRef();
		$tipo_pago = $dtoCliCar->getTipoPagoRef();
		$fecha_pri_pago = $dtoCliCar->getFechaPrimerPagoRef();
		$obs = $dtoCliCar->getObservacionRef();
		
		$factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
		
		$sql = " UPDATE ca_cliente_cartera
				SET
				is_ref = 1,
				deuda = ?,
				fecha_ref = NOW(),
				descuento_ref = ?,
				interes_ref = ?,
				comision_ref = ?,
				mora_ref = ?,
				gastos_cobranza_ref = ?,
				n_cuotas_ref = ?,
				tipo_pago_ref = ?,
				fecha_primer_pago_ref = ?,
				observacion_ref = ?
				WHERE idcliente_cartera = ? ";
		
		$pr = $connection->prepare( $sql );
		$pr->bindParam(1,$deuda);
		$pr->bindParam(2,$descuento);
		$pr->bindParam(3,$interes);
		$pr->bindParam(4,$comision);
		$pr->bindParam(5,$mora);
		$pr->bindParam(6,$gastos_cobranza);
		$pr->bindParam(7,$n_cuotas,PDO::PARAM_INT);
		$pr->bindParam(8,$tipo_pago,PDO::PARAM_STR);
		$pr->bindParam(9,$fecha_pri_pago,PDO::PARAM_STR);
		$pr->bindParam(10,$obs,PDO::PARAM_STR);
		$pr->bindParam(11,$idcli_car,PDO::PARAM_INT);
		if( $pr->execute() ) {
			return true;
		}else{
			return false;
		}
		
		
	}

    public function generarDistribucionPagos(dto_cartera $dtoCartera, $operadores, $dataPagos, $modo) {

        $cartera = $dtoCartera->getId();
        
        $field = array();
        for( $i=0;$i<count($dataPagos);$i++ ) {
            if( $dataPagos[$i] == 'sin_pago' ) {
                array_push( $field, " cu.monto_pagado<=0 " );
            }else if( $dataPagos[$i] == 'amortizado' ){
                array_push( $field, " ( IFNULL(cu.monto_pagado,0)>0 AND IFNULL(cu.monto_pagado,0)< IFNULL(cu.total_deuda,0) ) " );
            }else if( $dataPagos[$i] == 'cancelado' ) {
                array_push( $field, " ( IFNULL(cu.total_deuda,0) - IFNULL(cu.monto_pagado,0) )<=0 " );
            }
        }

        $s_field = "";
        if( count($dataPagos)>0 ) {
            $s_field = " AND ( ".implode("OR",$field)." ) ";
        }

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        function MapIdClienteCarteraDistribucionPagos($n) {
            return  $n['idcliente_cartera'] ;
        }

        $dataCodigoCliente = array();

        $sqlCodigoCliente = " SELECT cu.idcliente_cartera, SUM( cu.total_deuda ) AS 'deuda' 
            FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clicar 
            ON clicar.idcliente_cartera = cu.idcliente_cartera 
            WHERE cu.idcartera = ? AND clicar.idcartera = ?
            AND clicar.idusuario_servicio = 0 ".$s_field."
            GROUP BY cu.idcliente_cartera ORDER BY 2 DESC ";

        $prCliente = $connection->prepare($sqlCodigoCliente);
        $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
        $prCliente->bindParam(2, $cartera, PDO::PARAM_INT);
        $prCliente->execute();
        $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);
        
        $MapDataCodigoCliente = array_map("MapIdClienteCarteraDistribucionPagos", $dataCodigoCliente);

        for ($i = 0; $i < count($operadores); $i++) {
            $codigo_operador = $operadores[$i]['operador'];
            $clientes = array();
            for ($j = $i; $j < ceil(count($dataCodigoCliente) / 2); $j = $j + count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            for ($j = (count($dataCodigoCliente) - ($i + 1)); $j >= round(count($dataCodigoCliente) / 2); $j = $j - count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            if (count($clientes) > 0) {

                $sqlUpdateClienteCartera = " UPDATE ca_cliente_cartera 
                SET idusuario_servicio = ? 
                WHERE idcartera = ? 
                AND idcliente_cartera IN ( " . implode(",", $clientes) . " ) ";

                $prUpdate = $connection->prepare($sqlUpdateClienteCartera);
                $prUpdate->bindParam(1, $codigo_operador, PDO::PARAM_INT);
                $prUpdate->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prUpdate->execute()) {

                } else {
        
                    return false;
                    exit();
                    break;
                }
            }
        }

        
        return true;
    }

    public function CantidadClientesSinAsignarDistrPagos ( dto_cartera $dtoCartera, $dataPagos ) {
    
        $idcartera = $dtoCartera->getId();
        
        $field = array();
        for( $i=0;$i<count($dataPagos);$i++ ) {
            if( $dataPagos[$i] == 'sin_pago' ) {
                array_push( $field, " cu.monto_pagado<=0 " );
            }else if( $dataPagos[$i] == 'amortizado' ){
                array_push( $field, " ( IFNULL(cu.monto_pagado,0)>0 AND IFNULL(cu.monto_pagado,0)< IFNULL(cu.total_deuda,0) ) " );
            }else if( $dataPagos[$i] == 'cancelado' ) {
                array_push( $field, " ( IFNULL(cu.total_deuda,0) - IFNULL(cu.monto_pagado,0) )<=0 " );
            }
        }
        
        $s_field = "";
        if( count($dataPagos)>0 ) {
            $s_field = " AND ( ".implode(" OR ",$field)." ) ";
        }
        
        $sql = " SELECT 
                COUNT( DISTINCT cu.idcliente_cartera ) AS 'COUNT'
                FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clicar 
                ON clicar.idcliente_cartera = cu.idcliente_cartera
                WHERE cu.idcartera = ? AND clicar.idcartera = ? AND clicar.idusuario_servicio = 0 ".$s_field." ";
        
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        $pr->bindParam(2, $idcartera, PDO::PARAM_INT);
        if ($pr->execute()) {

            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {

            return array(array('COUNT' => 0));
        }
        
    }

    public function CantidadClientesSinAsignarSinGestion(dto_cartera $dtoCartera) {

        $idcartera = $dtoCartera->getId();

        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_cliente_cartera 
                WHERE idcartera = ? AND idusuario_servicio = 0 AND id_ultima_llamada = 0 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        if ($pr->execute()) {
        
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
        
            return array(array('COUNT' => 0));
        }
    }

    public function queryListarClusterByServicio($servicio) {
    
        $sql = " SELECT idcluster,nombre FROM ca_cluster_usuario WHERE idservicio = ? and estado=1";
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1,$servicio,PDO::PARAM_INT);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public function generarDistribucionSinGestion(dto_cartera $dtoCartera, $operadores) {

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        function MapCodigoClienteDistribucionMontosIguales($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        $dataCodigoCliente = array();


        $sqlCodigoCliente = " SELECT cu.codigo_cliente, SUM( cu.total_deuda ) AS 'deuda' FROM ca_cuenta cu 
					WHERE cu.idcartera = ? 
					AND cu.codigo_cliente IN ( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0 AND id_ultima_llamada = 0 )
					GROUP BY cu.codigo_cliente ORDER BY 2 DESC ";

        $prCliente = $connection->prepare($sqlCodigoCliente);
        $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
        $prCliente->bindParam(2, $cartera, PDO::PARAM_INT);
        $prCliente->execute();
        $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);



        $MapDataCodigoCliente = array_map("MapCodigoClienteDistribucionMontosIguales", $dataCodigoCliente);

        for ($i = 0; $i < count($operadores); $i++) {
            $codigo_operador = $operadores[$i]['operador'];
            $clientes = array();
            for ($j = $i; $j < ceil(count($dataCodigoCliente) / 2); $j = $j + count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            for ($j = (count($dataCodigoCliente) - ($i + 1)); $j >= round(count($dataCodigoCliente) / 2); $j = $j - count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            if (count($clientes) > 0) {

                $sqlUpdateClienteCartera = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? 
					WHERE idcartera = ? AND codigo_cliente IN ( " . implode(",", $clientes) . " ) ";

                $prUpdate = $connection->prepare($sqlUpdateClienteCartera);
                $prUpdate->bindParam(1, $codigo_operador, PDO::PARAM_INT);
                $prUpdate->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prUpdate->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function RetirarTodoClienteAsignadosUsuario(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = 0, fecha_modificacion = NOW() WHERE idcartera = ? AND idusuario_servicio = ? ";

        $idcartera = $dtoClienteCartera->getIdCartera();
        $idusuario_servicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        $pr->bindParam(2, $idusuario_servicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function CantidadClientesSinAsignarDConstante(dto_cartera $dtoCartera) {

        $idcartera = $dtoCartera->getId();

        $sql = " SELECT COUNT(*) AS 'COUNT' FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $idcartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function DistribucionConstante(dto_cartera $dtoCartera, $idcartera_referencia, $operadores) {

        $idcartera = $dtoCartera->getId();

        function MapCodigoCliente($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

;

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        for ($i = 0; $i < count($operadores); $i++) {

            $sql = " SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = ? ";

            $pr = $connection->prepare($sql);
            $pr->bindParam(1, $idcartera_referencia, PDO::PARAM_INT);
            $pr->bindParam(2, $operadores[$i]['operador'], PDO::PARAM_INT);
            $pr->execute();
            $dataCodigoCliente = $pr->fetchAll();
            $map_codigo_cliente = array_map("MapCodigoCliente", $dataCodigoCliente);

            if (count($map_codigo_cliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ?
						WHERE idcartera = ? AND idusuario_servicio = 0 AND codigo_cliente IN ( " . implode(",", $map_codigo_cliente) . " ) ";

                $prU = $connection->prepare($sql);
                $prU->bindParam(1, $operadores[$i]['operador'], PDO::PARAM_INT);
                $prU->bindParam(2, $idcartera, PDO::PARAM_INT);
                if ($prU->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function CantidadClientesSinAsignarZona(dto_cartera $dtoCartera, $zona) {

        /* 			$sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
          FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar
          ON clicar.idcliente = dir.idcliente
          WHERE clicar.idcartera = ? AND dir.idcartera = ? AND dir.idtipo_referencia  = 3
          AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ? "; */
          
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
          
        $sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
				FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar 
				ON clicar.idcliente_cartera = dir.idcliente_cartera
				WHERE clicar.idcartera = ?  AND dir.idcartera = ? 
				AND clicar.idusuario_servicio = 0 AND TRIM(dir.grupo) = ? ";

        

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $zona, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function CantidadClientesSinAsignarCartera(dto_cartera $dtoCartera) {

        /* 			$sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
          FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar
          ON clicar.idcliente = dir.idcliente
          WHERE clicar.idcartera = ? AND dir.idcartera = ? AND dir.idtipo_referencia  = 3
          AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ? "; */

        $sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
				FROM ca_cliente_cartera clicar 
				WHERE clicar.idcartera = ? 
				AND clicar.idusuario_servicio = 0 and clicar.estado=1";

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function CantidadCuentasPorCartera(dto_cartera $dtoCartera) {

        /* 			$sql = " SELECT COUNT( DISTINCT clicar.idcliente_cartera ) AS 'COUNT'
          FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar
          ON clicar.idcliente = dir.idcliente
          WHERE clicar.idcartera = ? AND dir.idcartera = ? AND dir.idtipo_referencia  = 3
          AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ? "; */

        $sql = " SELECT COUNT(idcuenta) as COUNT FROM ca_cuenta WHERE idcartera=? and estado=1";

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array(array('COUNT' => 0));
        }
    }

    public function consultaNextHorarioAtencion(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $h_i, $h_f, $item, $filtroEstado) {

        $sql = " SELECT clicar.idcliente_cartera,clicar.idcartera,cli.idcliente,cli.codigo,cli.idservicio,clicar.estado, clicar.retiro, clicar.motivo_retiro, 
					TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
					IFNULL(cli.numero_documento,'') AS 'numero_documento',
					IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
					IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
					FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.idcliente=cli.idcliente  
					WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera = ? AND clicar.idusuario_servicio = ?  
					AND clicar.idcliente_cartera = ( 
					
						SELECT t2.idcliente_cartera 
						FROM
						(
						SELECT @rownum:=@rownum+1 AS 'item', t1.idcliente_cartera
						FROM (
						SELECT clicar2.idcliente_cartera
						FROM 
						( SELECT DISTINCT idcliente FROM ca_horario_atencion WHERE hora BETWEEN ? AND ? ) ha INNER JOIN 
						( SELECT idcliente, codigo  FROM ca_cliente WHERE idservicio = ?  ) cli2 INNER JOIN 
						( SELECT idcliente_cartera, codigo_cliente, idcliente  FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = ? $filtroEstado ) clicar2
						ON clicar2.idcliente = cli2.idcliente AND cli2.idcliente = ha.idcliente
						) t1, ( SELECT @rownum:=0 ) r
						) t2 WHERE t2.item = ? 
						
					) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $h_i, PDO::PARAM_STR);
        $pr->bindParam(5, $h_f, PDO::PARAM_STR);
        $pr->bindParam(6, $servicio, PDO::PARAM_INT);
        $pr->bindParam(7, $cartera, PDO::PARAM_INT);
        $pr->bindParam(8, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(9, $item, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function save_cliente_especial(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio_especial = ? WHERE idcliente_cartera = ? ";

        $idcliente_cartera = $dtoClienteCartera->getId();
        $usuario_servicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $idcliente_cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function generarDistribucionMontosIguales(dto_cartera $dtoCartera, $operadores, $zona) {

        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        function MapCodigoClienteDistribucionMontosIguales($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        $dataCodigoCliente = array();

        if ($zona == '0') {

            $sqlCodigoCliente = " SELECT cu.codigo_cliente, SUM( IF(cu.moneda='USD',2.8*cu.total_deuda,IF(cu.moneda='VAC',8*cu.total_deuda,cu.total_deuda)) ) AS 'deuda' FROM ca_cuenta cu 
					WHERE cu.idcartera = ? and cu.estado=1
					AND cu.codigo_cliente IN ( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0 and estado=1 )
					GROUP BY cu.codigo_cliente ORDER BY 2 DESC ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $cartera, PDO::PARAM_INT);
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);
        } else {

            /* 				$sqlCodigoCliente  = " SELECT cu.codigo_cliente, SUM(cu.total_deuda) AS 'deuda'
              FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
              ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente = dir.idcliente
              WHERE clicar.idcartera = ? AND cu.idcartera = ? AND dir.idcartera = ? AND dir.idtipo_referencia  = 3
              AND clicar.idusuario_servicio = 0 AND TRIM(dir.zona) = ?
              GROUP BY cu.codigo_cliente ORDER BY 2 DESC "; */

            $sqlCodigoCliente = " SELECT cu.codigo_cliente,SUM( IF(cu.moneda='USD',2.8*cu.total_deuda,IF(cu.moneda='VAC',8*cu.total_deuda,cu.total_deuda)) ) AS 'deuda'
					FROM ca_direccion dir INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cuenta cu
					ON cu.idcliente_cartera = clicar.idcliente_cartera AND clicar.idcliente_cartera = dir.idcliente_cartera
					WHERE clicar.idcartera = ? AND cu.idcartera = ? and cu.estado=1 and clicar.estado=1
					AND clicar.idusuario_servicio = 0 AND TRIM(dir.grupo) = ?
					GROUP BY cu.codigo_cliente ORDER BY 2 DESC ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $cartera, PDO::PARAM_INT);
            /* $prCliente->bindParam(3,$cartera,PDO::PARAM_INT); */
            $prCliente->bindParam(3, $zona, PDO::PARAM_STR);
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);
        }

        $MapDataCodigoCliente = array_map("MapCodigoClienteDistribucionMontosIguales", $dataCodigoCliente);

        for ($i = 0; $i < count($operadores); $i++) {
            $codigo_operador = $operadores[$i]['operador'];
            $clientes = array();
            for ($j = $i; $j < ceil(count($dataCodigoCliente) / 2); $j = $j + count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            for ($j = (count($dataCodigoCliente) - ($i + 1)); $j >= round(count($dataCodigoCliente) / 2); $j = $j - count($operadores)) {
                array_push($clientes, $MapDataCodigoCliente[$j]);
            }

            if (count($clientes) > 0) {

                $sqlUpdateClienteCartera = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? 
					WHERE idcartera = ? AND codigo_cliente IN ( " . implode(",", $clientes) . " ) ";

                $prUpdate = $connection->prepare($sqlUpdateClienteCartera);
                $prUpdate->bindParam(1, $codigo_operador, PDO::PARAM_INT);
                $prUpdate->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prUpdate->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionPorDepartamento(dto_direccion_ER2 $dtoDireccion, $operadores, $clientes_por_operador) {

        $cartera = $dtoDireccion->getIdCartera();
        $departamento = $dtoDireccion->getDepartamento();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        function MapCodigoClienteDistribucionDepartamento($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        $inicio = 0;

        for ($i = 0; $i < count($operadores); $i++) {

            $codigo_operador = $operadores[$i]['operador'];

            $dataCodigoCliente = array();

//				$sqlCodigoCliente = " SELECT DISTINCT codigo_cliente 
//					FROM ca_direccion WHERE idcartera = ? AND TRIM(departamento) = ? LIMIT ?, ? ";

            $sqlCodigoCliente = " SELECT DISTINCT codigo_cliente
					FROM ca_direccion WHERE idcartera = ? AND TRIM(departamento) = ? 
					AND codigo_cliente IN 
					( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0  ) 
					LIMIT $inicio, $clientes_por_operador ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $departamento, PDO::PARAM_STR);
            /*             * ***** */
            $prCliente->bindParam(3, $cartera, PDO::PARAM_INT);
            /*             * ***** */
            /* $prCliente->bindParam(4,$inicio,PDO::PARAM_INT);
              $prCliente->bindParam(5,$clientes_por_operador,PDO::PARAM_INT); */
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);

            $MapDataCodigoCliente = array_map("MapCodigoClienteDistribucionDepartamento", $dataCodigoCliente);

            if (count($dataCodigoCliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? 
					WHERE idcartera = ? AND idusuario_servicio = 0 AND codigo_cliente IN ( " . implode(",", $MapDataCodigoCliente) . " ) ";

                $prDisDepartamento = $connection->prepare($sql);
                $prDisDepartamento->bindParam(1, $codigo_operador, PDO::PARAM_INT);
                $prDisDepartamento->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prDisDepartamento->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }

            //$inicio += $clientes_por_operador; 
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionPorTramoModoSeguimiento(dto_detalle_cuenta $dtoDetalleCuenta, $operadores) {

        $cartera = $dtoDetalleCuenta->getIdCartera();
        $tramo = $dtoDetalleCuenta->getTramo();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $sqlCantidadClientes = " SELECT COUNT( DISTINCT codigo_cliente ) AS 'COUNT'
				FROM ca_detalle_cuenta WHERE idcartera = ? AND tramo = ? 
				AND codigo_cliente ";

        $pr = $connection->prepare($sqlCantidadClientes);
        $pr->bindParam(1, $cartera, PDO::PARAM_INT);
        $pr->bindParam(2, $tramo, PDO::PARAM_STR);
        $pr->execute();
        $dataCantidadCliente = $pr->fetchAll(PDO::FETCH_ASSOC);
        $clientes_disponibles = (int) $dataCantidadCliente[0]['COUNT'];
        $clientes_por_operador = ceil($clientes_disponibles / count($operadores));

        $inicio = 0;

        function MapCodigoClienteDistribucionTramo($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        for ($i = 0; $i < count($operadores); $i++) {

            $dataCodigoCliente = array();

            $sqlCodigoCliente = " SELECT DISTINCT codigo_cliente 
					FROM ca_detalle_cuenta WHERE idcartera = ? AND tramo = ? 
					AND codigo_cliente 
					LIMIT $inicio, $clientes_por_operador ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $tramo, PDO::PARAM_STR);
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);

            $MapdataCodigoCliente = array_map("MapCodigoClienteDistribucionTramo", $dataCodigoCliente);

            if (count($dataCodigoCliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio_especial = ? 
					WHERE idcartera = ? AND codigo_cliente IN ( " . implode(",", $MapdataCodigoCliente) . " ) ";

                $prDisTramo = $connection->prepare($sql);
                $prDisTramo->bindParam(1, $operadores[$i]['operador'], PDO::PARAM_INT);
                $prDisTramo->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prDisTramo->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }
            $inicio += $clientes_por_operador;
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionPorTramoModoSeguimientoEspecial(dto_detalle_cuenta $dtoDetalleCuenta, $operadores, $clientes_por_operador) {

        $cartera = $dtoDetalleCuenta->getIdCartera();
        $tramo = $dtoDetalleCuenta->getTramo();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $inicio = 0;

        function MapCodigoClienteDistribucionTramo($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        for ($i = 0; $i < count($operadores); $i++) {

            $dataCodigoCliente = array();

            $sqlCodigoCliente = " SELECT DISTINCT A.codigo_cliente FROM (
                                SELECT codigo_cliente,max(dias_mora) as cantidad
                                FROM ca_detalle_cuenta 
                                WHERE idcartera=? GROUP BY codigo_cliente
                                )A WHERE A.cantidad BETWEEN ".$tramo." AND A.codigo_cliente IN
                                ( SELECT codigo_cliente FROM ca_cliente_cartera WHERE estado=1 AND idcartera = ? AND idusuario_servicio_especial = 0  ) 
                                LIMIT $inicio, $clientes_por_operador ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $cartera, PDO::PARAM_INT);
            /* $prCliente->bindParam(4,$inicio,PDO::PARAM_INT);
              $prCliente->bindParam(5,$clientes_por_operador,PDO::PARAM_INT); */
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);

            $MapdataCodigoCliente = array_map("MapCodigoClienteDistribucionTramo", $dataCodigoCliente);

            if (count($dataCodigoCliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio_especial = ? 
                    WHERE idcartera = ? AND idusuario_servicio_especial = 0 AND codigo_cliente IN ( " . implode(",", $MapdataCodigoCliente) . " ) ";

                $prDisTramo = $connection->prepare($sql);
                $prDisTramo->bindParam(1, $operadores[$i]['operador'], PDO::PARAM_INT);
                $prDisTramo->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prDisTramo->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }

            //$inicio += $clientes_por_operador; 
        }

        ////$connection->commit();
        return true;
    }        

    public function generarDistribucionPorTramo(dto_detalle_cuenta $dtoDetalleCuenta, $operadores, $clientes_por_operador) {

        $cartera = $dtoDetalleCuenta->getIdCartera();
        $tramo = $dtoDetalleCuenta->getTramo();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $inicio = 0;

        function MapCodigoClienteDistribucionTramo($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        for ($i = 0; $i < count($operadores); $i++) {

            $dataCodigoCliente = array();

            $sqlCodigoCliente = " SELECT DISTINCT codigo_cliente 
					FROM ca_detalle_cuenta WHERE idcartera = ? AND tramo = ? 
					AND codigo_cliente IN 
					( SELECT codigo_cliente FROM ca_cliente_cartera WHERE idcartera = ? AND idusuario_servicio = 0  ) 
					LIMIT $inicio, $clientes_por_operador ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $tramo, PDO::PARAM_STR);
            $prCliente->bindParam(3, $cartera, PDO::PARAM_INT);
            /* $prCliente->bindParam(4,$inicio,PDO::PARAM_INT);
              $prCliente->bindParam(5,$clientes_por_operador,PDO::PARAM_INT); */
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);

            $MapdataCodigoCliente = array_map("MapCodigoClienteDistribucionTramo", $dataCodigoCliente);

            if (count($dataCodigoCliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? 
					WHERE idcartera = ? AND idusuario_servicio = 0 AND codigo_cliente IN ( " . implode(",", $MapdataCodigoCliente) . " ) ";

                $prDisTramo = $connection->prepare($sql);
                $prDisTramo->bindParam(1, $operadores[$i]['operador'], PDO::PARAM_INT);
                $prDisTramo->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prDisTramo->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }

            //$inicio += $clientes_por_operador; 
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionPorTramoEspecial(dto_detalle_cuenta $dtoDetalleCuenta, $operadores, $clientes_por_operador) {

        $cartera = $dtoDetalleCuenta->getIdCartera();
        $tramo = $dtoDetalleCuenta->getTramo();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $inicio = 0;

        function MapCodigoClienteDistribucionTramo($n) {
            return "'" . $n['codigo_cliente'] . "'";
        }

        for ($i = 0; $i < count($operadores); $i++) {

            $dataCodigoCliente = array();

            $sqlCodigoCliente = " SELECT DISTINCT A.codigo_cliente FROM (
                                SELECT codigo_cliente,max(dias_mora) as cantidad
                                FROM ca_detalle_cuenta 
                                WHERE idcartera=? GROUP BY codigo_cliente
                                )A WHERE A.cantidad BETWEEN ".$tramo." AND A.codigo_cliente IN
                                ( SELECT codigo_cliente FROM ca_cliente_cartera WHERE estado=1 AND idcartera = ? AND idusuario_servicio = 0  ) 
                                LIMIT $inicio, $clientes_por_operador ";

            $prCliente = $connection->prepare($sqlCodigoCliente);
            $prCliente->bindParam(1, $cartera, PDO::PARAM_INT);
            $prCliente->bindParam(2, $cartera, PDO::PARAM_INT);
            /* $prCliente->bindParam(4,$inicio,PDO::PARAM_INT);
              $prCliente->bindParam(5,$clientes_por_operador,PDO::PARAM_INT); */
            $prCliente->execute();
            $dataCodigoCliente = $prCliente->fetchAll(PDO::FETCH_ASSOC);

            $MapdataCodigoCliente = array_map("MapCodigoClienteDistribucionTramo", $dataCodigoCliente);

            if (count($dataCodigoCliente) > 0) {

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? 
                    WHERE estado=1 AND idcartera = ? AND idusuario_servicio = 0 AND codigo_cliente IN ( " . implode(",", $MapdataCodigoCliente) . " ) ";

                $prDisTramo = $connection->prepare($sql);
                $prDisTramo->bindParam(1, $operadores[$i]['operador'], PDO::PARAM_INT);
                $prDisTramo->bindParam(2, $cartera, PDO::PARAM_INT);
                if ($prDisTramo->execute()) {
                    
                } else {
                    ////$connection->rollBack();
                    return false;
                    exit();
                    break;
                }
            }

            //$inicio += $clientes_por_operador; 
        }

        ////$connection->commit();
        return true;
    }    

    public function queryDistribucionAutomatica(dto_servicio $dtoServicio, dto_cartera $dtoCartera) {
//			$sql=" SELECT IFNULL(SUM(IF(idusuario_servicio=0,1,0)),0) AS 'clientes_sin_asignar',
//				IFNULL(SUM(IF(idusuario_servicio<>0,1,0)),0) AS 'clientes_asignados',(
//				SELECT COUNT(*) FROM ca_usuario_servicio WHERE idservicio=? AND idtipo_usuario IN (1,2) ) AS 'cantidad_operadores'
//				FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera 
//				WHERE car.idcampania=? ";

        $sql = " SELECT IFNULL(SUM(IF(idusuario_servicio=0,1,0)),0) AS 'clientes_sin_asignar',
				IFNULL(SUM(IF(idusuario_servicio<>0,1,0)),0) AS 'clientes_asignados',(
				SELECT COUNT(*) FROM ca_usuario_servicio WHERE idservicio=? AND idtipo_usuario IN (2,3) AND estado=1 ) AS 'cantidad_operadores' 
				FROM ca_cliente_cartera WHERE idcartera=? AND estado=1 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $servicio = $dtoServicio->getId();
        $cartera = $dtoCartera->getid();

        $pr->bindParam(1, $servicio);
        $pr->bindParam(2, $cartera);
        /*         * */
        //$pr->bindParam(3,$servicio);
        /*         * * */
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryDataDistribucionPorOperador(dto_cliente_cartera $dtoClienteCartera) {
        $cartera = $dtoClienteCartera->getIdCartera();
        $sql = "    SELECT 
                    IFNULL( COUNT(*),0 ) AS 'cliente_asignados',
    				-- IFNULL(SUM( IF( id_ultima_llamada =0 AND estado=1,1,0 ) ),0) AS 'clientes_sin_gestionar',
                    -- IFNULL(SUM(IF(CONCAT(YEAR(NOW()), MONTH(NOW())) > CONCAT(YEAR(lla.fecha), MONTH(lla.fecha)),1,0)),0) AS 'clientes_sin_gestionar',
    				-- IFNULL(SUM( IF( id_ultima_llamada <>0,1,0 ) ),0) AS 'clientes_gestionados'
                    -- IFNULL(SUM(IF(CONCAT(YEAR(NOW()), MONTH(NOW())) = CONCAT(YEAR(lla.fecha), MONTH(lla.fecha)),1,0)),0) AS 'clientes_gestionados'
    				IFNULL(SUM(IF(CONCAT(YEAR(NOW()), MONTH(NOW())) > IFNULL(CONCAT(YEAR(lla.fecha),MONTH(lla.fecha)),'0'),1,0)),0) AS 'clientes_sin_gestionar',
                    IFNULL(SUM(IF(CONCAT(YEAR(NOW()), MONTH(NOW())) = IFNULL(CONCAT(YEAR(lla.fecha),MONTH(lla.fecha)),'0'),1,0)),0) AS 'clientes_gestionados'
                    FROM 
                    ca_cliente_cartera clicar
                    LEFT JOIN ca_llamada lla ON lla.idllamada=clicar.id_ultima_llamada
    				WHERE clicar.estado=1 AND clicar.idusuario_servicio = ? AND clicar.idcartera IN (" . $cartera . ")
                    ";
// echo $sql;
// exit();
        $usuario_servicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_servicio, PDO::PARAM_INT);
        //$pr->bindParam(2,$cartera,PDO::PARAM_INT);
        if ($pr->execute()) {
            //////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            //////$connection->rollBack();
            return array();
        }
    }

    public function queryClientesByOperador(dto_cartera $dtoCartera, dto_servicio $dtoServicio) {
        //$sql=" SELECT usu.idusuario,ususer.idusuario_servicio,CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) AS operador,
//				IFNULL( (SELECT SUM(IF(clicar.id_ultima_llamada=0 AND clicar.id_ultima_visita=0,1,0)) FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera WHERE car.idcampania=? AND clicar.idusuario_servicio=ususer.idusuario_servicio ),0) AS 'clientes_sin_gestionar',
//				IFNULL( (SELECT SUM(IF(clicar.id_ultima_llamada<>0 OR clicar.id_ultima_visita<>0,1,0)) FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera WHERE car.idcampania=? AND clicar.idusuario_servicio=ususer.idusuario_servicio ),0) AS 'clientes_gestionados',
//				( SELECT COUNT(*) FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car ON car.idcartera=clicar.idcartera WHERE car.idcampania=? AND clicar.idusuario_servicio=ususer.idusuario_servicio) AS 'clientes_asignados'
//				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario
//				WHERE ususer.idservicio=? AND ususer.idtipo_usuario IN (2,3) ";
//			$sql=" SELECT usu.idusuario,ususer.idusuario_servicio,UPPER(CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno)) AS operador,
//				IFNULL( (SELECT SUM(IF(id_ultima_llamada=0 AND id_ultima_visita=0,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1   ),0) AS 'clientes_sin_gestionar',
//				IFNULL( (SELECT SUM(IF(id_ultima_llamada<>0 OR id_ultima_visita<>0,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1  ),0) AS 'clientes_gestionados',
//				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1 ) AS 'clientes_asignados'
//				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario
//				WHERE ususer.idservicio=? AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 ";

        $sql = " SELECT usu.idusuario,ususer.idusuario_servicio,UPPER(CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre)) AS operador,
				IFNULL( (SELECT SUM(IF(id_ultima_llamada=0 ,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1   ),0) AS 'clientes_sin_gestionar',
				IFNULL( (SELECT SUM(IF(id_ultima_llamada<>0 ,1,0)) FROM ca_cliente_cartera  WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1  ),0) AS 'clientes_gestionados',
				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera=? AND idusuario_servicio=ususer.idusuario_servicio AND estado=1 ) AS 'clientes_asignados'
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario
				WHERE ususer.idservicio=? AND ususer.idtipo_usuario IN (2) AND ususer.estado=1 AND usu.estado = 1 ORDER BY 3 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $cartera = $dtoCartera->getId();
        $servicio = $dtoServicio->getId();

        $pr->bindParam(1, $cartera);
        $pr->bindParam(2, $cartera);
        $pr->bindParam(3, $cartera);
        $pr->bindParam(4, $servicio);

        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryClientesByOperadorPorCluster(dto_cartera $dtoCartera, dto_servicio $dtoServicio, $idcluster) {
        $cartera = $dtoCartera->getId();
        $servicio = $dtoServicio->getId();

        if ($idcluster == 0) {
            $sqlfill = "WHERE ususer.idservicio=" . $servicio . " AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1  ORDER BY 3";
        } else {
            $sqlfill = "inner join ca_usuario_servicio_cluster ususerclu on ususer.idusuario_servicio=ususerclu.idusuario_servicio
				WHERE ususer.idservicio=" . $servicio . " AND ususer.idtipo_usuario IN (2,3) AND ususer.estado=1 and ususerclu.idcluster=" . $idcluster . " and ususerclu.estado=1 ORDER BY 3";
        }

        $sql = " SELECT usu.idusuario,ususer.idusuario_servicio,UPPER(CONCAT_WS(' ',usu.paterno,usu.materno,usu.nombre)) AS operador,
				IFNULL( (SELECT SUM(IF(id_ultima_llamada=0 ,1,0)) FROM ca_cliente_cartera  WHERE idcartera=" . $cartera . " AND idusuario_servicio=ususer.idusuario_servicio AND estado=1   ),0) AS 'clientes_sin_gestionar',
				IFNULL( (SELECT SUM(IF(id_ultima_llamada<>0 ,1,0)) FROM ca_cliente_cartera  WHERE idcartera=" . $cartera . " AND idusuario_servicio=ususer.idusuario_servicio AND estado=1  ),0) AS 'clientes_gestionados',
				( SELECT COUNT(*) FROM ca_cliente_cartera WHERE idcartera=" . $cartera . " AND idusuario_servicio=ususer.idusuario_servicio AND estado=1 ) AS 'clientes_asignados'
				FROM ca_usuario usu INNER JOIN ca_usuario_servicio ususer ON ususer.idusuario=usu.idusuario " . $sqlfill;

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryClientesSinAsignar(dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();
        //$servicio=$dtoCampania->getIdServicio();
        //$sql=" SELECT IFNULL(SUM(IF(clicar.idusuario_servicio=0,1,0)),0) AS 'clientes_sin_asignar' FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car 
//				ON car.idcartera=clicar.idcartera WHERE car.idcampania=:campania ";

        $sql = " SELECT IFNULL(SUM(IF(idusuario_servicio=0,1,0)),0) AS 'clientes_sin_asignar' 
				FROM ca_cliente_cartera 
				WHERE idcartera=:cartera AND estado=1 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        if ($pr->execute(array(':cartera' => $cartera))) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryClientesSinPago(dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();
        $sql = " select count(distinct(data.idcliente_cartera)) as 'clientes_sin_pago' from
(SELECT 	t1.idcliente_cartera, t1.idcartera, TRUNCATE( t1.total_deuda,2) AS 'EXIGIBLE', TRUNCATE( t1.monto_pagado,2) AS 'PAGO', TRUNCATE( ( t1.total_deuda + IFNULL(t1.total_comision,0) - IFNULL(t1.monto_pagado,0) ),2 ) AS 'SALDO' ,
	IF( IFNULL(t1.monto_pagado,0)=0, 'SIN PAGO', IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  <= t1.monto_pagado , 'ACANCELADO', 
		IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  != t1.monto_pagado , 'AMORTIZADO', '' )  )   ) AS 'status' 
FROM ca_cuenta t1 
	where idcartera=:cartera and retirado=0
order by 1 desc, 6 desc ) data
where data.status in ('SIN PAGO') ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        if ($pr->execute(array(':cartera' => $cartera))) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryClientesAmortizado(dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();
        $sql = " select count(distinct(data.idcliente_cartera)) as 'clientes_amortizado' from
(SELECT 	t1.idcliente_cartera, t1.idcartera, TRUNCATE( t1.total_deuda,2) AS 'EXIGIBLE', TRUNCATE( t1.monto_pagado,2) AS 'PAGO', TRUNCATE( ( t1.total_deuda + IFNULL(t1.total_comision,0) - IFNULL(t1.monto_pagado,0) ),2 ) AS 'SALDO' ,
	IF( IFNULL(t1.monto_pagado,0)=0, 'SIN PAGO', IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  <= t1.monto_pagado , 'ACANCELADO', 
		IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  != t1.monto_pagado , 'AMORTIZADO', '' )  )   ) AS 'status' 
FROM ca_cuenta t1 
	where idcartera=:cartera and retirado=0
order by 1 desc, 6 desc ) data
where data.status in ('AMORTIZADO') ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        if ($pr->execute(array(':cartera' => $cartera))) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function queryNumeroCliCar(dto_cartera $dtoCartera) {

        $cartera = $dtoCartera->getId();
        $sql = " select count(*) as clientes from ca_cliente_cartera where idcartera=:cartera ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        //////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        if ($pr->execute(array(':cartera' => $cartera))) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function generarDistribucionAutomatica(dto_servicio $dtoServicio, dto_cartera $dtoCartera) {

        $factoryConnection = FactoryConnection::create('mysqli');
        $connection = $factoryConnection->getConnection();

        $dao = DAOFactory::getDAOUsuarioServicio('maria');

        $count = 0;

        $data = $this->queryDistribucionAutomatica($dtoServicio, $dtoCartera);

        $clientesXOperador = ceil($data[0]['clientes_sin_asignar'] / $data[0]['cantidad_operadores']);
        $operadores = $dao->queryIdOperadorXServicio($dtoServicio);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        //$connection->autocommit(false);

        for ($i = 0; $i < count($operadores); $i++):

            //$arrayId = $this->queryReturnIdClienteCartera($dtoCartera, $count, $clientesXOperador);
            $arrayId = $this->queryReturnIdClienteCartera($dtoCartera, 0, $clientesXOperador);

            if (count($arrayId) > 0) :

                $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $arrayId));

                //$count+=$clientesXOperador;

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio=? WHERE idcliente_cartera IN ( $ids ) ";

                $usuario_servicio = $operadores[$i]['idusuario_servicio'];

                $pr = $connection->prepare($sql);
                $pr->bind_param('i', $usuario_servicio);
                $rst = $pr->execute();
                //$rst=$pr->execute(array(':usuario'=>$usuario));
                if (!$rst):
                    ////$connection->rollBack();
                    return false;
                    exit();
                endif;

            endif;

        endfor;

        ////$connection->commit();
        return true;
    }

    public function queryReturnIdClienteCartera(dto_cartera $dto, $inicio, $cantidad) {
        //$sql=" SELECT clicar.idcliente_cartera 
//				FROM ca_cliente_cartera clicar INNER JOIN ca_cartera car 
//				ON car.idcartera=clicar.idcartera 
//				WHERE car.idcampania=? AND clicar.idusuario_servicio=0 
//				ORDER BY clicar.idcliente_cartera 
//				LIMIT $inicio, $cantidad ";

        $sql = " SELECT idcliente_cartera FROM ca_cliente_cartera 
				WHERE idcartera=? AND idusuario_servicio=0 AND estado=1 
				ORDER BY idcliente_cartera LIMIT $inicio, $cantidad ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $cartera = $dto->getId();
        //$servicio=$dto->getIdServicio();

        $pr->bindParam(1, $cartera);
        //$pr->bindParam(2,$servicio);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryReturnIdClienteCarteraSinPago(dto_cartera $dto, $inicio, $cantidad) {
        $sql = " select distinct(data.idcliente_cartera) from
(SELECT 	t1.idcliente_cartera, t1.idcartera, TRUNCATE( t1.total_deuda,2) AS 'EXIGIBLE', TRUNCATE( t1.monto_pagado,2) AS 'PAGO', TRUNCATE( ( t1.total_deuda + IFNULL(t1.total_comision,0) - IFNULL(t1.monto_pagado,0) ),2 ) AS 'SALDO' ,
	IF( IFNULL(t1.monto_pagado,0)=0, 'SIN PAGO', IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  <= t1.monto_pagado , 'ACANCELADO', 
		IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  != t1.monto_pagado , 'AMORTIZADO', '' )  )   ) AS 'status' 
FROM ca_cuenta t1 
	where idcartera=? and retirado=0
order by 1 desc, 6 desc ) data
where data.status in ('SIN PAGO') limit $inicio,$cantidad ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $cartera = $dto->getId();
        //$servicio=$dto->getIdServicio();

        $pr->bindParam(1, $cartera);
        //$pr->bindParam(2,$servicio);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryReturnIdClienteCarteraAmortizado(dto_cartera $dto, $inicio, $cantidad) {
        $sql = " select distinct(data.idcliente_cartera) from
(SELECT 	t1.idcliente_cartera, t1.idcartera, TRUNCATE( t1.total_deuda,2) AS 'EXIGIBLE', TRUNCATE( t1.monto_pagado,2) AS 'PAGO', TRUNCATE( ( t1.total_deuda + IFNULL(t1.total_comision,0) - IFNULL(t1.monto_pagado,0) ),2 ) AS 'SALDO' ,
	IF( IFNULL(t1.monto_pagado,0)=0, 'SIN PAGO', IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  <= t1.monto_pagado , 'ACANCELADO', 
		IF( IFNULL(t1.monto_pagado,0)!=0 AND ( IFNULL(t1.total_deuda,0) + IFNULL(t1.total_comision,0) )  != t1.monto_pagado , 'AMORTIZADO', '' )  )   ) AS 'status' 
FROM ca_cuenta t1 
	where idcartera=? and retirado=0
order by 1 desc, 6 desc ) data
where data.status in ('AMORTIZADO') limit $inicio,$cantidad ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);

        $cartera = $dto->getId();
        //$servicio=$dto->getIdServicio();

        $pr->bindParam(1, $cartera);
        //$pr->bindParam(2,$servicio);

        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAllClienteSinGestionarXUsuario(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {
        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio=0 WHERE id_ultima_llamada=0 AND id_ultima_visita=0 
				AND idusuario_servicio=? AND idcartera = ? AND estado=1 ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);

        $cartera = $dtoCartera->getId();
        $usuario_servicio = $dtoUsuarioServicio->getId();

        $pr->bindParam(1, $usuario_servicio);
        $pr->bindParam(2, $cartera);

        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function generarDistribucionManual(dto_cartera $dtoCartera, $arrayData) {

        $factoryConnection = FactoryConnection::create('mysqli');
        $connection = $factoryConnection->getConnection();
        $inicio = 0;

        //$connection->autocommit(false);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        for ($i = 0; $i < count($arrayData); $i++) {
            $cantidad = $arrayData[$i]['clientes'];
            $arrayId = $this->queryReturnIdClienteCartera($dtoCartera, $inicio, $cantidad);
            if (count($arrayId) > 0) {
                $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $arrayId));

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? WHERE idcliente_cartera IN ( $ids ) ";

                $usuario = $arrayData[$i]['usuario_servicio'];

                //$inicio+=$cantidad;

                $pr = $connection->prepare($sql);
                $pr->bind_param('i', $usuario);
                $rst = $pr->execute();

                if (!$rst) {
                    ////$connection->rollBack();
                    return false;
                    exit();
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionSinPago(dto_cartera $dtoCartera, $arrayData) {

        $factoryConnection = FactoryConnection::create('mysqli');
        $connection = $factoryConnection->getConnection();
        $inicio = 0;

        $connection->autocommit(false);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        for ($i = 0; $i < count($arrayData); $i++) {
            $cantidad = $arrayData[$i]['clientes'];
            $arrayId = $this->queryReturnIdClienteCarteraSinPago($dtoCartera, $inicio, $cantidad);
            if (count($arrayId) > 0) {
                $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $arrayId));

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ?, fecha_modificacion = NOW() WHERE idcliente_cartera IN ( $ids ) ";

                $usuario = $arrayData[$i]['usuario_servicio'];

                $inicio+=$cantidad;

                $pr = $connection->prepare($sql);
                $pr->bind_param('i', $usuario);
                $rst = $pr->execute();

                if (!$rst) {
                    ////$connection->rollBack();
                    return false;
                    exit();
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function generarDistribucionAmortizado(dto_cartera $dtoCartera, $arrayData) {

        $factoryConnection = FactoryConnection::create('mysqli');
        $connection = $factoryConnection->getConnection();
        $inicio = 0;

        $connection->autocommit(false);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        for ($i = 0; $i < count($arrayData); $i++) {
            $cantidad = $arrayData[$i]['clientes'];
            $arrayId = $this->queryReturnIdClienteCarteraAmortizado($dtoCartera, $inicio, $cantidad);
            if (count($arrayId) > 0) {
                $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $arrayId));

                $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ?, fecha_modificacion = NOW() WHERE idcliente_cartera IN ( $ids ) ";

                $usuario = $arrayData[$i]['usuario_servicio'];

                $inicio+=$cantidad;

                $pr = $connection->prepare($sql);
                $pr->bind_param('i', $usuario);
                $rst = $pr->execute();

                if (!$rst) {
                    ////$connection->rollBack();
                    return false;
                    exit();
                }
            }
        }

        ////$connection->commit();
        return true;
    }

    public function generarTraspasoCartera($idusuario_servicio_DE, $idusuario_servicio_PARA, $idcartera, $filtros) {

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $idcliente_cartera = '';
        $amortizado = 0;
        $cancelado = 0;
        $sin_pago = 0;
        
        for( $i=0;$i<count($filtros);$i++ ) {
                if( $filtros[$i] == "amortizados" ) {
                        $amortizado = 1;
                }else if( $filtros[$i] == "sin_pago" ) {
                        $sin_pago = 0;
                }else if( $filtros[$i] == "cancelados" ) {
                        $cancelado = 1;
                }
        }
        
        function MapArray($n) {
                return "'" . $n['codigo_cliente'] . "'";
        }
        
        $sql_general = " SELECT codigo_cliente 
                        FROM ca_cliente_cartera 
                        WHERE idcartera = ? AND idusuario_servicio = ? ";
                        
        $prG = $connection->prepare($sql_general);
        $prG->bindParam(1,$idcartera,PDO::PARAM_INT);
        $prG->bindParam(2,$idusuario_servicio_DE,PDO::PARAM_INT);
        $prG->execute();
        $dataG = array_map("MapArray", $prG->fetchAll(PDO::FETCH_ASSOC) );
        
        if( $amortizado == 1 ) {
                $sql_amortizados = " SELECT codigo_cliente 
                        FROM ca_cuenta 
                        WHERE idcartera = ? AND ( total_deuda - monto_pagado ) >0 AND ( total_deuda - monto_pagado ) < total_deuda ";
                
                $prA = $connection->prepare($sql_amortizados);
                $prA->bindParam(1,$idcartera,PDO::PARAM_INT);
                $prA->execute();
                $dataA = array_map("MapArray", $prA->fetchAll(PDO::FETCH_ASSOC) );
                
                $dataG = array_intersect ( $dataG, $dataA );
        }
        
        if( $sin_pago == 1 ) {
                $sql_sinpago = " SELECT codigo_cliente 
                                FROM ca_cuenta 
                                WHERE idcartera = ?  AND monto_pagado<= 0  ";
                $prSP = $connection->prepare($sql_sinpago);
                $prSP->bindParam(1,$idcartera,PDO::PARAM_INT);
                $prSP->execute();
                $dataSP = array_map("MapArray", $prSP->fetchAll(PDO::FETCH_ASSOC) );
                
                $dataG = array_intersect ( $dataG, $dataSP );
        }
        
        if( $cancelado == 1 ) {
                $sql_cancelados = " SELECT codigo_cliente 
                                FROM ca_cuenta 
                                WHERE idcartera = ? AND ( total_deuda - monto_pagado ) = 0  ";
                
                $prC = $connection->prepare($sql_cancelados);
                $prC->bindParam(1,$idcartera,PDO::PARAM_INT);
                $prC->execute();
                $dataC = array_map("MapArray", $prC->fetchAll(PDO::FETCH_ASSOC) );

                $dataG = array_intersect ( $dataG, $dataC );
        }
        
        if( count($dataG)>0 ) {
                
                $sqlUpdate = " UPDATE ca_cliente_cartera 
                             SET idusuario_servicio = ?,
                             fecha_modificacion = NOW()
                             WHERE idcartera = ? AND codigo_cliente IN ( ".implode(",",$dataG)." ) ";
                             
                $prU = $connection->prepare($sqlUpdate);
                $prU->bindParam(1,$idusuario_servicio_PARA,PDO::PARAM_INT);
                $prU->bindParam(2,$idcartera,PDO::PARAM_INT);
                if( $prU->execute() ){
                        return true;
                }else{
                        return false;
                }
                
        }else{
                return true;
        }

        /*$sql_idclicar = " select concat(idcliente_cartera,',') as 'idcliente_cartera' 
                        from ca_cliente_cartera 
                        where idcartera=" . $idcartera . " and idusuario_servicio=" . $idusuario_servicio_DE . " ";
        
        $pr_idclicar = $connection->prepare($sql_idclicar);
        if ($pr_idclicar->execute()) {
            while ($row = $pr_idclicar->fetch(PDO::FETCH_ASSOC)) {
                foreach ($row as $index => $value) {
                    $idcliente_cartera.=$value;
                }
            }
            $idcliente_cartera = substr($idcliente_cartera, 0, (strlen($idcliente_cartera) - 1));

            $sqlUpdClicar = "update ca_cliente_cartera set idusuario_servicio=" . $idusuario_servicio_PARA . ", fecha_modificacion = NOW() where idcliente_cartera in (" . $idcliente_cartera . ")";
            $prUpdClicar = $connection->prepare($sqlUpdClicar);
            if ($prUpdClicar->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }*/
    }

    public function deleteClientesIngresadosSinGestionar(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, $cantidad) {
        $sql = " SELECT clicar.idcliente_cartera 
				FROM ca_cliente_cartera clicar 
				WHERE clicar.idcartera=? AND clicar.idusuario_servicio=? AND clicar.id_ultima_llamada=0 AND clicar.id_ultima_visita=0 AND clicar.estado=1 
				ORDER BY clicar.idcliente_cartera LIMIT 0 , $cantidad ";

        $usuario = $dtoUsuarioServicio->getId();
        $cartera = $dtoCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $cartera);
        $pr->bindParam(2, $usuario);
        $pr->execute();
        $dataIds = $pr->fetchAll(PDO::FETCH_ASSOC);

        function array_map_queryReturnIdClienteCartera($n) {
            return $n['idcliente_cartera'];
        }

        $ids = implode(",", array_map("array_map_queryReturnIdClienteCartera", $dataIds));

        ////$connection->beginTransaction();

        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio=0 WHERE idcliente_cartera IN ( $ids ) ";

        $pr = $connection->prepare($sql);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function consultaNext(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $item, $filtro_estado) {

//			$sql=" SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
//				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
//				IFNULL(cli.numero_documento,'') AS 'numero_documento',
//				IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
//				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo 
//				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
//				AND clicar.idcliente_cartera=( 
//				SELECT MIN(idcliente_cartera) FROM ca_cliente_cartera
//				WHERE idcartera=? AND idusuario_servicio=? AND estado=1 AND idcliente_cartera > ? 
//				) ";

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio, clicar.idcartera, cli.codigo,clicar.estado, clicar.retiro, clicar.motivo_retiro, 
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(cli.numero_documento,'') AS 'numero_documento',
				IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.idusuario_servicio=?  $filtro_estado 
				AND clicar.idcliente_cartera=( 
					
					SELECT t1.idcliente_cartera
					FROM
					(
					SELECT @rownum:=@rownum+1 AS 'item', clicar.idcliente_cartera
					FROM ca_cliente_cartera clicar,( SELECT @rownum:=0 ) r
					WHERE idcartera = ? AND idusuario_servicio = ? $filtro_estado 
					) t1
					WHERE t1.item = ? LIMIT 1
					
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * *** */
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        /*         * *** */
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(6, $item, PDO::PARAM_INT);
        //$pr->bindParam(6,$ClienteCartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaNextTramo(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $item, $filtro_estado) {

//			$sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
//				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
//				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
//				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
//				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
//				AND clicar.idcliente_cartera=( 
//				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
//				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
//				WHERE clicar2.idcartera=? AND clicar2.idusuario_servicio=?  AND clicar2.estado=1 AND clicar2.idcliente_cartera > ? AND detcun.tramo = ? 
//				) ";

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio,cli.codigo, clicar.idcartera, clicar.estado, clicar.retiro, clicar.motivo_retiro,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
					
					SELECT t2.idcliente_cartera 
					FROM
					(
					SELECT @rownum:=@rownum+1 AS 'item', t1.idcliente_cartera
					FROM
					(
					SELECT DISTINCT clicar.idcliente_cartera
					FROM ca_cliente_cartera clicar INNER JOIN ca_detalle_cuenta detcun 
					ON detcun.codigo_cliente = clicar.codigo_cliente AND detcun.idcartera = clicar.idcartera
					WHERE clicar.idcartera = ? AND clicar.idusuario_servicio = ?  
					AND clicar.estado = 1  AND detcun.tramo = ? $filtro_estado 
					) t1 , ( SELECT @rownum:=0 ) r
					) t2 WHERE t2.item = ? LIMIT 1
					
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $UsuarioServicio, PDO::PARAM_INT);
        //$pr->bindParam(6,$ClienteCartera,PDO::PARAM_INT);
        $pr->bindParam(6, $tramo, PDO::PARAM_STR);
        $pr->bindParam(7, $item, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaNextMonto(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $sord, $item, $filtro_estado) {

//			$sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
//				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
//				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
//				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
//				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
//				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
//				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
//				AND clicar.idcliente_cartera=( 
//					SELECT clic.idcliente_cartera
//					FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clic ON clic.codigo_cliente = cu.codigo_cliente
//					WHERE cu.idcartera = ? AND clic.idcartera = ? AND clic.idusuario_servicio = ? AND clic.idcliente_cartera > ?
//					GROUP BY cu.codigo_cliente ORDER BY cu.total_deuda $sord LIMIT 1 
//				) ";

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.idservicio, clicar.idcartera, cli.codigo, clicar.estado, clicar.retiro, clicar.motivo_retiro, 
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.idusuario_servicio=? $filtro_estado
				AND clicar.idcliente_cartera=( 
				
					SELECT t2.idcliente_cartera 
					FROM
					(
					SELECT @rownum:=@rownum+1 AS 'item', t1.idcliente_cartera
					FROM
					(
					SELECT DISTINCT clicar.idcliente_cartera 
					FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente = cu.codigo_cliente
					WHERE cu.idcartera = ? AND clicar.idcartera = ? AND clicar.idusuario_servicio = ?  $filtro_estado 
					GROUP BY cu.codigo_cliente ORDER BY cu.total_deuda $sord 
					) t1 , ( SELECT @rownum:=0 ) r
					) t2 WHERE t2.item = ? LIMIT 1
					
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $cartera, PDO::PARAM_INT);
        $pr->bindParam(6, $UsuarioServicio, PDO::PARAM_INT);
        //$pr->bindParam(7,$ClienteCartera,PDO::PARAM_INT);
        $pr->bindParam(7, $item, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaNextGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(cli.numero_documento,'') AS 'numero_documento',
				IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo 
				WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(idcliente_cartera) FROM ca_cliente_cartera
				WHERE idcartera=? AND estado=1 AND idcliente_cartera > ? 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $ClienteCartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaNextTramoGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.estado=1 AND clicar2.idcliente_cartera > ? AND detcun.tramo = ? 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $ClienteCartera, PDO::PARAM_INT);
        $pr->bindParam(5, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBack(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(cli.numero_documento,'') AS 'numero_documento',
				IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo
				WHERE cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
				SELECT MAX(idcliente_cartera) FROM ca_cliente_cartera WHERE idcartera=? 
				AND idusuario_servicio=? AND estado=1 AND idcliente_cartera < ?
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        /*         * ***** */
        $pr->bindParam(1, $servicio);
        /*         * ***** */
        $pr->bindParam(2, $cartera);
        $pr->bindParam(3, $UsuarioServicio);
        $pr->bindParam(4, $cartera);
        $pr->bindParam(5, $UsuarioServicio);
        $pr->bindParam(6, $ClienteCartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBackTramo(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.idusuario_servicio=?  AND clicar2.estado=1 AND clicar2.idcliente_cartera < ? AND detcun.tramo = ? 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(6, $ClienteCartera, PDO::PARAM_INT);
        $pr->bindParam(7, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBackMonto(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera, $sord) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
					SELECT clic.idcliente_cartera
					FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clic ON clic.codigo_cliente = cu.codigo_cliente
					WHERE cu.idcartera = ? AND clic.idcartera = ? AND clic.idusuario_servicio = ? AND clic.idcliente_cartera < ?
					GROUP BY cu.codigo_cliente ORDER BY cu.total_deuda $sord LIMIT 1 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $cartera, PDO::PARAM_INT);
        $pr->bindParam(6, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(7, $ClienteCartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBackGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(cli.numero_documento,'') AS 'numero_documento',
				IFNULL(cli.tipo_documento,'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar ON clicar.codigo_cliente=cli.codigo
				WHERE cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 
				AND clicar.idcliente_cartera=( 
				SELECT MAX(idcliente_cartera) FROM ca_cliente_cartera WHERE idcartera=? AND estado=1 AND idcliente_cartera < ?
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $ClienteCartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function consultaBackTramoGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor'
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.estado=1 AND clicar2.idcliente_cartera < ? AND detcun.tramo = ? 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $tramo = $dtoCartera->getTramo();
        $UsuarioServicio = $dtoUsuarioServicio->getId();
        $ClienteCartera = $dtoClienteCartera->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $ClienteCartera, PDO::PARAM_INT);
        $pr->bindParam(5, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestion(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(idcliente_cartera) FROM ca_cliente_cartera
				WHERE idcartera=? AND idusuario_servicio=?  AND estado=1 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        //$pr->bindParam(1,$servicio);
        /*         * ***** */
        $pr->bindParam(1, $servicio);
        /*         * **** */
        $pr->bindParam(2, $cartera);
        $pr->bindParam(3, $UsuarioServicio);
        $pr->bindParam(4, $cartera);
        $pr->bindParam(5, $UsuarioServicio);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestionTramo(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.idusuario_servicio=?  AND clicar2.estado=1 AND detcun.tramo = ?
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $tramo = $dtoCartera->getTramo();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(6, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestionMonto(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio, $sord) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 AND clicar.idusuario_servicio=? 
				AND clicar.idcliente_cartera=( 
					SELECT clic.idcliente_cartera
					FROM ca_cuenta cu INNER JOIN ca_cliente_cartera clic ON clic.codigo_cliente = cu.codigo_cliente
					WHERE cu.idcartera = ? AND clic.idcartera = ? AND clic.idusuario_servicio = ? GROUP BY cu.codigo_cliente ORDER BY cu.total_deuda $sord LIMIT 1
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $tramo = $dtoCartera->getTramo();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $UsuarioServicio, PDO::PARAM_INT);
        $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        $pr->bindParam(5, $cartera, PDO::PARAM_INT);
        $pr->bindParam(6, $UsuarioServicio, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestionGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera= ? AND clicar.estado=1  
				AND clicar.idcliente_cartera=( 
				SELECT MIN(idcliente_cartera) FROM ca_cliente_cartera
				WHERE idcartera = ? AND estado=1 
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function InitDefaultGestionTramoGlobalCartera(dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo,
				TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) as 'nombre',
				IFNULL(TRIM(cli.numero_documento),'') AS 'numero_documento',
				IFNULL(TRIM(cli.tipo_documento),'') AS 'tipo_documento',
				IF( clicar.idusuario_servicio = 0, 'SIN GESTOR', ( SELECT CONCAT_WS(' ',usu.nombre,usu.paterno,usu.materno) FROM ca_usuario_servicio ususer INNER JOIN ca_usuario usu ON usu.idusuario=ususer.idusuario WHERE ususer.idusuario_servicio = clicar.idusuario_servicio ) )  AS 'gestor' 
				FROM ca_cliente cli INNER JOIN ca_cliente_cartera clicar
				ON clicar.codigo_cliente=cli.codigo WHERE cli.estado=1 AND cli.idservicio = ? AND clicar.idcartera=? AND clicar.estado=1 
				AND clicar.idcliente_cartera=( 
				SELECT MIN(clicar2.idcliente_cartera) FROM ca_cliente_cartera clicar2 INNER JOIN ca_detalle_cuenta detcun 
				ON detcun.codigo_cliente = clicar2.codigo_cliente AND detcun.idcartera = clicar2.idcartera
				WHERE clicar2.idcartera=? AND clicar2.estado=1 AND detcun.tramo = ?
				) ";

        $servicio = $dtoUsuarioServicio->getIdServicio();
        $tramo = $dtoCartera->getTramo();
        $cartera = $dtoCartera->getId();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        $pr->bindParam(4, $tramo, PDO::PARAM_STR);
        if ($pr->execute()) {
            ////$connection->commit();
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            ////$connection->rollBack();
            return array();
        }
    }

    public function SearchClientByDni(dto_cliente $dtoCliente, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {
    
        $NumeroDocumento = $dtoCliente->getNumeroDocumento();
        $cartera = $dtoCartera->getId();
        $servicio = $dtoCliente->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo, clicar.idcartera,
				CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',IFNULL(cli.numero_documento,'') AS 'numero_documento',
				IFNULL(cli.tipo_documento,'') AS 'tipo_documento' 
                                FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli INNER JOIN ca_campania cam
				ON cli.codigo=clicar.codigo_cliente AND clicar.idcartera = car.idcartera AND cam.idcampania=car.idcampania 
				WHERE car.status='ACTIVO' AND cam.status='ACTIVO' AND cli.idservicio = ? AND TRIM(cli.numero_documento)=? AND cam.idservicio=$servicio
                                ".( ($cartera=='' || $cartera=='0')?" ": " AND clicar.idcartera = ? " )."
                                AND car.estado=1 AND clicar.estado=1 
                                ORDER BY clicar.idcartera DESC limit 1";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $NumeroDocumento, PDO::PARAM_STR);
        if( $cartera != '' && $cartera!='0' ) {
                $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        }
        
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function SearchClientByCode(dto_cliente $dtoCliente, dto_cartera $dtoCartera, dto_usuario_servicio $dtoUsuarioServicio) {
    
        $codigo = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();
        $servicio = $dtoCliente->getIdServicio();
        $UsuarioServicio = $dtoUsuarioServicio->getId();

        $sql = "    SELECT 
                    clicar.idcliente_cartera,
                    cli.idcliente,cli.codigo, 
                    clicar.idcartera,
				    -- CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',
                    IF(TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno)) IS NULL OR TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno))='',cli.razon_social,TRIM(CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno))) AS 'nombre',
                    IFNULL(cli.numero_documento,'') AS 'numero_documento',
				    IFNULL(cli.tipo_documento,'') AS 'tipo_documento' 
                    FROM ca_cartera car INNER JOIN ca_cliente_cartera clicar INNER JOIN ca_cliente cli INNER JOIN ca_campania cam
    				ON cli.codigo=clicar.codigo_cliente  AND clicar.idcartera = car.idcartera AND cam.idcampania=car.idcampania
    				WHERE car.status='ACTIVO' AND cam.status='ACTIVO' AND cli.idservicio = ? AND TRIM(cli.codigo) = ? AND cam.idservicio=$servicio
                                    ".( ( $cartera=='' || $cartera=='0' )?" ":" AND clicar.idcartera = ? " )." 
                                    AND clicar.estado in(1,0) AND car.estado = 1 
                                    ORDER BY clicar.idcartera DESC limit 1";



        

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        
        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $codigo, PDO::PARAM_STR);
        if( $cartera != '' && $cartera!='0' ) {
                $pr->bindParam(3, $cartera, PDO::PARAM_INT);
        }
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }
    
    public function SearchClientByAccountNumber(dto_cliente $dtoCliente, dto_cartera $dtoCartera, dto_cuenta $dtoCuenta) {
        
        $codigo = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();
        $servicio = $dtoCliente->getIdServicio();
        $numero_cuenta = $dtoCuenta->getNumeroCuenta();
        
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
    
        $sql = " SELECT clicar.idcliente_cartera,cli.idcliente,cli.codigo, clicar.idcartera,
                CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',IFNULL(cli.numero_documento,'') AS 'numero_documento',
                IFNULL(cli.tipo_documento,'') AS 'tipo_documento' 
                FROM ca_cliente_cartera clicar INNER JOIN ca_cliente cli INNER JOIN ca_cuenta cu INNER JOIN ca_cartera car INNER JOIN ca_campania cam
                ON car.idcartera = clicar.idcartera AND cu.idcliente_cartera = clicar.idcliente_cartera AND cli.codigo=clicar.codigo_cliente  AND cam.idcampania=car.idcampania
                WHERE car.status='ACTIVO' AND cam.status='ACTIVO' AND cli.idservicio = ? AND cu.numero_cuenta = ? AND cam.idservicio=$servicio
                AND clicar.estado=1 AND car.estado = 1 ".( ($cartera=='' || $cartera=='0')?"":" AND clicar.idcartera = ? AND cu.idcartera = ? " )."
                GROUP BY clicar.idcliente_cartera 
                ORDER BY clicar.idcartera DESC  ";
        
        
        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $numero_cuenta, PDO::PARAM_STR);
        if( $cartera!='' && $cartera!='0' ) {
                $pr->bindParam(3, $cartera, PDO::PARAM_INT);
                $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        }
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }
    
    public function SearchClientByPhone(dto_cliente $dtoCliente, dto_cartera $dtoCartera, dto_telefono $dtoTelefono) {

        $codigo = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();
        $servicio = $dtoCliente->getIdServicio();
        $telefono = $dtoTelefono->getNumero();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $sql = "    SELECT 
                    clicar.idcliente_cartera,
                    cli.idcliente,
                    cli.codigo, 
                    clicar.idcartera,
                    CONCAT_WS(' ',cli.nombre,cli.paterno,cli.materno) AS 'nombre',
                    IFNULL(cli.numero_documento,'') AS 'numero_documento',
                    IFNULL(cli.tipo_documento,'') AS 'tipo_documento' 
                    FROM 
                    ca_cliente_cartera clicar 
                    INNER JOIN ca_cliente cli 
                    -- INNER JOIN ca_cuenta cu 
                    INNER JOIN ca_telefono telf
                    INNER JOIN ca_cartera car 
                    INNER JOIN ca_campania cam
                    ON 
                    car.idcartera = clicar.idcartera AND 
                    -- cu.idcliente_cartera = clicar.idcliente_cartera AND 
                    telf.idcliente_cartera=clicar.idcliente_cartera AND
                    cli.codigo=clicar.codigo_cliente  AND 
                    cam.idcampania=car.idcampania
                    WHERE 
                    car.status='ACTIVO' AND 
                    cam.status='ACTIVO' AND 
                    cli.idservicio = ? AND 
                    -- cu.telefono = ?  AND 
                    telf.numero='$telefono' AND
                    cam.idservicio=$servicio AND 
                    clicar.estado=1 AND 
                    car.estado = 1 ".( ($cartera=='' || $cartera=='0')?"":" AND clicar.idcartera = ? AND cu.idcartera = ? " )."
                    GROUP BY clicar.idcliente_cartera 
                    ORDER BY clicar.idcartera DESC limit 1";


        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $servicio, PDO::PARAM_INT);
        $pr->bindParam(2, $telefono, PDO::PARAM_STR);
        if( $cartera!='' && $cartera!='0' ) {
                $pr->bindParam(3, $cartera, PDO::PARAM_INT);
                $pr->bindParam(4, $cartera, PDO::PARAM_INT);
        }
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function updateUltimaVisita(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET id_ultima_visita = ?, usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";

        $ClienteCartera = $dtoClienteCartera->getId();
        $UsuarioModificacion = $dtoClienteCartera->getUsuarioModificacion();
        $IdUltimaVisita = $dtoClienteCartera->getIdUltimaVisita();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $IdUltimaVisita);
        $pr->bindParam(2, $UsuarioModificacion);
        $pr->bindParam(3, $ClienteCartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function updateUltimaLlamada(dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET id_ultima_llamada = ?,usuario_modificacion = ?, fecha_modificacion = NOW() WHERE idcliente_cartera = ?  ";

        $ClienteCartera = $dtoClienteCartera->getId();
        $UsuarioModificacion = $dtoClienteCartera->getUsuarioModificacion();
        $IdUltimaLlamada = $dtoClienteCartera->getIdUltimaLlamada();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $IdUltimaLlamada);
        $pr->bindParam(2, $UsuarioModificacion);
        $pr->bindParam(3, $ClienteCartera);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function updateMultiId($ids, dto_cliente_cartera $dtoClienteCartera) {

        $sql = " UPDATE ca_cliente_cartera SET idusuario_servicio = ? WHERE idcliente_cartera IN ( $ids ) ";

        $usuario_servicio = $dtoClienteCartera->getIdUsuarioServicio();

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        ////$connection->beginTransaction();

        $pr = $connection->prepare($sql);
        $pr->bindParam(1, $usuario_servicio);
        if ($pr->execute()) {
            ////$connection->commit();
            return true;
        } else {
            ////$connection->rollBack();
            return false;
        }
    }

    public function executeSelectString($sql) {
        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();
        $pr = $connection->prepare($sql);
        $pr->execute();
        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

//~ Vic
	public function setShowDataCliente($idcliente, $idservicio)
	{
		$sql = "SELECT c.nombre
				FROM ca_cliente_cartera cc 
					INNER JOIN ca_cliente c ON cc.idcliente=c.idcliente
				WHERE cc.idcliente_cartera='".$idcliente."' AND c.idservicio=".$idservicio." LIMIT 1";

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$pr = $connection->prepare($sql);
		if ($pr->execute()) {
			return array('rst'=>true,'msg' => $pr->fetchAll(PDO::FETCH_ASSOC));
		} else {
			return array('rst'=>false,'msg'=>'Error de Select');
		}
	}
        public function SearchClientByCode2(dto_cliente $dtoCliente, dto_cartera $dtoCartera) {  //piro
    
        $codigo = $dtoCliente->getCodigo();
        $cartera = $dtoCartera->getId();
        

        $sql = " SELECT 
                    cu.dato9 As 'territorio',
                    cu.dato11 as 'oficina',
                    (SELECT nombre FROM ca_cliente where idcliente=clicar.idcliente) AS 'cliente',
                    (SELECT numero_documento FROM ca_cliente where idcliente=clicar.idcliente) AS 'ruc',
                    '' AS 'estado',
                    SUM(cu.total_deuda) as 'total_deuda',
                    '' as 'clasificacion',
                    IFNULL(clicar.deuda,'') as 'provision',
                    '' as 'sistema_financiero',
                    '' as 'tipo_credito',
                    '' as 'tramo',
                    '' as 'contacto1',
                    '' as 'contacto2',
                    '' as 'nivel_riesgo'
                    from ca_cuenta cu
                    inner join ca_detalle_cuenta detcu on cu.idcuenta=detcu.idcuenta
                    inner join ca_cliente_cartera clicar on clicar.idcliente_cartera =cu.idcliente_cartera
                    where clicar.codigo_cliente=? and clicar.idcartera =? and cu.producto = 'COMERCIAL' 
                   ";
        

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);
        
        $pr->bindParam(1, $codigo, PDO::PARAM_STR);
        $pr->bindParam(2, $cartera, PDO::PARAM_INT);
        
        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function ListDomicilio(dto_cliente_cartera $dtoClienteCartera) {  //piro

        $codigoCliente = $dtoClienteCartera->getCodigoCliente();

        $sql = " select iddireccion ,direccion AS direccion
                from ca_direccion
                where codigo_cliente= ?  and direccion is not NULL
                group by direccion
                   ";

        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $codigoCliente, PDO::PARAM_STR);

        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }
    public function searchClienteCartera( dto_cartera $dtoCartera, dto_cliente_cartera $dtoClienteCartera ) { //piro

        $codigoCliente = $dtoClienteCartera->getId();
        $codigoCartera= $dtoCartera->getId();


        $sql = " select idcliente_cartera from ca_cliente_cartera where codigo_cliente=?  and idcartera=?";


        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $codigoCliente, PDO::PARAM_STR);
        $pr->bindParam(2, $codigoCartera, PDO::PARAM_STR);


        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function FillIdCuentaByCode( dto_cartera $dtoCartera, dto_cliente_cartera $dtoClienteCartera ) { //piro

        $codigoCliente = $dtoClienteCartera->getId();
        $codigoCartera= $dtoCartera->getId();


        $sql = "select idcuenta,numero_cuenta,moneda,total_deuda
                from ca_cuenta
                where codigo_cliente=? and idcartera=? and producto='COMERCIAL' and estado=1 ";


        $factoryConnection = FactoryConnection::create('mysql');
        $connection = $factoryConnection->getConnection();

        $pr = $connection->prepare($sql);

        $pr->bindParam(1, $codigoCliente, PDO::PARAM_STR);
        $pr->bindParam(2, $codigoCartera, PDO::PARAM_STR);


        if ($pr->execute()) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }/**/

}


?>
