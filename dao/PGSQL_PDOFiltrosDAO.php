<?php

	class PGSQL_PDOFiltrosDAO {
		
		public function queryTablaByService ( dto_filtros $dtoFiltro ) {
			$sql=" SELECT DISTINCT tabla_mostrar,tabla FROM ca_filtros WHERE idtipo_filtro=? AND idservicio=? ";
			
			$TipoFiltro=$dtoFiltro->getIdTipoFiltro();
			$servicio=$dtoFiltro->getIdServicio();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$TipoFiltro);
			$pr->bindParam(2,$servicio);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public function queryCampoByTabla ( dto_filtros $dtoFiltro ) {
			$sql=" SELECT campo,tipo_campo FROM ca_filtros WHERE idtipo_filtro=? AND idservicio=? AND tabla=? ";
			
			$TipoFiltro=$dtoFiltro->getIdTipoFiltro();
			$servicio=$dtoFiltro->getIdServicio();
			$tabla=$dtoFiltro->getTabla();
			
			$factoryConnection= FactoryConnection::create('postgres_pdo');	
			$connection = $factoryConnection->getConnection();
			$pr=$connection->prepare($sql);
			$pr->bindParam(1,$TipoFiltro);
			$pr->bindParam(2,$servicio);
			$pr->bindParam(3,$tabla);
			$pr->execute();
			return $pr->fetchAll(PDO::FETCH_ASSOC);
		}
			
	}

?>