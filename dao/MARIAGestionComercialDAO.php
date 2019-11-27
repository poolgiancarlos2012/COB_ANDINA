<?php

	class MARIAGestionComercialDAO {
            public function queryGestionComercial ( $sidx,$sord,$start,$limit ) {
			$sql="select vis.idvisita AS idvisita,cu.numero_cuenta AS numerocuenta,cli.codigo AS codigocliente, cli.nombre AS nombre,cu.moneda AS moneda, cu.total_deuda AS totaldeuda, cu.dato9 AS territorio, cu.dato11 AS oficina, cli.numero_documento AS ruc,dir.direccion AS direccion,
                                vis.fecha_visita AS fechavisita, vis.hora_visita AS horavisita, gine.nombre AS gironegocio, vis.detalle_giro_extra_negocio AS detallegironegocio, moatne.nombre AS motivoatrasonegocio, vis.detalle_motivo_atraso_negocio AS detallemotivoatrasonegocio,
                                afpane.nombre AS afrotnarpagonegocio, vis.detalle_afrontar_pago_negocio AS detalleafrontarpagonegocio, cucone.nombre AS cuestionacobranza, obesne.nombre AS observacionespecialistanegocio,
                                vis.caracteristica_negocio_tieneexistencias AS tieneexistencias, vis.caracteristica_negocio_laborartesanal AS laborartesanal, vis.caracteristica_negocio_localpropio AS localpropio, vis.caracteristica_negocio_ofiadministra AS oficinaadministrativa, 
                                vis.caracteristica_negocio_menorigualdiezpersonas AS menorigualdiezpersonas, vis.caracteristica_negocio_mayordiezpersonas AS mayordiezpersonas, vis.caracteristica_negocio_plantaindustrial AS plantaindustrial, vis.caracteristica_negocio_casanegocio AS casanegocio,
                                vis.caracteristica_negocio_puertaacalle AS puertaacalle, vis.caracteristica_negocio_actividad_adicional AS actividadadiconal, vis.nueva_direccion AS nuevadireccion, vis.numero_visita AS numerovisita, vis.nuevo_telefono AS nuevotelefono, cafi.nombre AS tipocontacto, vis.direccion_visita_2 AS direccionvisita2

                                FROM ca_cliente_cartera clicar
                                inner join ca_cliente cli on clicar.idcliente=cli.idcliente
                                inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera
                                inner join ca_direccion dir on dir.idcliente_cartera = clicar.idcliente_cartera
                                inner join ca_visita vis on vis.idcliente_cartera=clicar.idcliente_cartera 
                                inner join ca_giro_negocio gine on gine.idgiro_negocio=vis.idgiro_negocio 
                                inner join ca_motivo_atraso_negocio moatne on moatne.idmotivo_atraso_negocio=vis.idmotivo_atraso_negocio
                                inner join ca_afrontar_pago_negocio afpane on afpane.idafrontar_pago_negocio = vis.idafrontar_pago_negocio
                                inner join ca_cuestiona_cobranza_negocio cucone on cucone.idcuestiona_cobranza_negocio = vis.idcuestiona_cobranza_negocio
                                inner join ca_observacion_especialista_negocio obesne on obesne.idobservacion_especialista_negocio=vis.idobservacion_especialista_negocio
                                inner join ca_carga_final cafi on  cafi.idcarga_final=vis.idcarga_final
                          where vis.iddireccion=dir.iddireccion ORDER BY $sidx $sord LIMIT $start , $limit  ";
                        
                        
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
                
             public function queryGestionComercial_ ( ) {
			$sql="select vis.idvisita AS idvisita,concat('=\"',cu.numero_cuenta,'\"') AS numerocuenta,cli.codigo AS codigocliente, cli.nombre AS nombre,cu.moneda AS moneda, cu.total_deuda AS totaldeuda, cu.dato9 AS territorio, cu.dato11 AS oficina, concat('=\"',cli.numero_documento,'\"') AS ruc,dir.direccion AS direccion,
                                vis.fecha_visita AS fechavisita, vis.hora_visita AS horavisita, gine.nombre AS gironegocio, vis.detalle_giro_extra_negocio AS detallegironegocio, moatne.nombre AS motivoatrasonegocio, vis.detalle_motivo_atraso_negocio AS detallemotivoatrasonegocio,
                                afpane.nombre AS afrotnarpagonegocio, vis.detalle_afrontar_pago_negocio AS detalleafrontarpagonegocio, cucone.nombre AS cuestionacobranza, obesne.nombre AS observacionespecialistanegocio,
                                vis.caracteristica_negocio_tieneexistencias AS tieneexistencias, vis.caracteristica_negocio_laborartesanal AS laborartesanal, vis.caracteristica_negocio_localpropio AS localpropio, vis.caracteristica_negocio_ofiadministra AS oficinaadministrativa, 
                                vis.caracteristica_negocio_menorigualdiezpersonas AS menorigualdiezpersonas, vis.caracteristica_negocio_mayordiezpersonas AS mayordiezpersonas, vis.caracteristica_negocio_plantaindustrial AS plantaindustrial, vis.caracteristica_negocio_casanegocio AS casanegocio,
                                vis.caracteristica_negocio_puertaacalle AS puertaacalle, vis.caracteristica_negocio_actividad_adicional AS actividadadiconal, vis.nueva_direccion AS nuevadireccion, vis.numero_visita AS numerovisita, vis.nuevo_telefono AS nuevotelefono, cafi.nombre AS tipocontacto, vis.direccion_visita_2 AS direccionvisita2

                                FROM ca_cliente_cartera clicar
                                inner join ca_cliente cli on clicar.idcliente=cli.idcliente
                                inner join ca_cuenta cu on cu.idcliente_cartera=clicar.idcliente_cartera
                                inner join ca_direccion dir on dir.idcliente_cartera = clicar.idcliente_cartera
                                inner join ca_visita vis on vis.idcliente_cartera=clicar.idcliente_cartera 
                                inner join ca_giro_negocio gine on gine.idgiro_negocio=vis.idgiro_negocio 
                                inner join ca_motivo_atraso_negocio moatne on moatne.idmotivo_atraso_negocio=vis.idmotivo_atraso_negocio
                                inner join ca_afrontar_pago_negocio afpane on afpane.idafrontar_pago_negocio = vis.idafrontar_pago_negocio
                                inner join ca_cuestiona_cobranza_negocio cucone on cucone.idcuestiona_cobranza_negocio = vis.idcuestiona_cobranza_negocio
                                inner join ca_observacion_especialista_negocio obesne on obesne.idobservacion_especialista_negocio=vis.idobservacion_especialista_negocio
                                inner join ca_carga_final cafi on  cafi.idcarga_final=vis.idcarga_final
                          where vis.iddireccion=dir.iddireccion  ";
                        
                        
			$factoryConnection= FactoryConnection::create('mysql');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
                
            public function COUNT ( ) {
			$sql=" SELECT COUNT(*) AS 'COUNT' FROM ca_visita WHERE tipo='VISCOM' ";
			$factoryConnection= FactoryConnection::create('mysql');
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);	
		}
	}

?>