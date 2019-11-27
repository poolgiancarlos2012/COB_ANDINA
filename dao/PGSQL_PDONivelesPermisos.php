<?php
/**
 * Description of postgres_pdoNivelesPermisos
 *
 * @author Davis
 */
class PGSQL_PDONivelesPermisos {
    public function insert(dto_niveles_permisos $obj) {
        $sql="INSERT INTO ca_niveles_permisos(nombreNivel,estado) VALULES(?,?)";
        $cn=FactoryConnection::create('postgres_pdo')->Connection();
        $stm = $cn->prepare($sql);
        $stm->bindParam(1,$obj->getNombreNivel(),PDO::PARAM_STR);
        $stm->bindParam(2,$obj->getEstado(),PDO::PARAM_STR);
        return $stm->execute();
    }
    public function listarNivelesPermisos(){
        $sql="SELECT idpermiso,nombreNivel,estado FROM ca_niveles_permisos";
        $cn=FactoryConnection::create('postgres_pdo')->Connection();
        $stm=$cn->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
