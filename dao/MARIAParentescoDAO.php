<?php

class MARIAParentescoDAO {

	public function queryByService(dto_servicio $dtoServicio) {

		$sql = " SELECT idparentesco,nombre,
				IFNULL(codigo,'') AS codigo, IFNULL(descripcion,'') AS descripcion  
		FROM ca_parentesco WHERE idservicio = ? AND estado = 1 ";

		$idservicio = $dtoServicio->getId();

		$factoryConnection = FactoryConnection::create('mysql');
		$connection = $factoryConnection->getConnection();

		$pr = $connection->prepare($sql);
		$pr->bindParam(1, $idservicio, PDO::PARAM_INT);
		if ($pr->execute()) {
			
			$data = array();
			$r_full = $pr->fetchAll(PDO::FETCH_ASSOC);
			for( $i=0;$i<count($r_full);$i++ ) {
				$row = array();
				$row['idparentesco'] = $r_full[$i]['idparentesco'];
				$row['nombre'] = utf8_encode($r_full[$i]['nombre']);
				$row['codigo'] = $r_full[$i]['codigo'];
				$row['descripcion'] = utf8_encode($r_full[$i]['descripcion']);
				
				array_push( $data, $row );
			}
			
			return $data;
			
		} else {
		
			return array();
		}
	}

}

?>