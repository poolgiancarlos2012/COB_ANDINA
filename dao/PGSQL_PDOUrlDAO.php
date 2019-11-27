<?php
/**
 * Description of postgres_pdoUrlDAO
 *
 * @author Davis
 */
class PGSQL_PDOUrlDAO {
    public function insert(dto_url $obj){
        $sql="INSERT INTO ca_url (url,estado,idMenu) VALUES(?,?,?)";
        $cn=FactoryConnection::create('postgres_pdo')->Connection();
        $stm = $cn->prepare($sql);
        $stm->bindParam(1,$obj->getNombreUrl(),PDO::PARAM_STR);
        $stm->bindParam(2,$obj->getEstado(),PDO::PARAM_STR);
        $stm->bindParam(3,$obj->getIdMenu(),PDO::PARAM_INT);
        return $stm->execute();
    }
    public function update(dto_url $obj){
        $sql="UPDATE ca_url SET url=?,estado=?,idMenu=? WHERE id=?";
        $cn=FactoryConnection::create('postgres_pdo')->Connection();
        $stm = $cn->prepare($sql);
        $stm->bindParam(1,$obj->getNombreUrl(),PDO::PARAM_STR);
        $stm->bindParam(2,$obj->getEstado(),PDO::PARAM_STR);
        $stm->bindParam(3,$obj->getIdMenu(),PDO::PARAM_INT);
        $stm->bindParam(4,$obj->getId(),PDO::PARAM_INT);
        return $stm->execute();
    }
    public function delete(dto_url $obj){
        $sql="DELETE FROM ca_url WHERE id=?";
        $cn=FactoryConnection::create('postgres_pdo')->Connection();
        $stm = $cn->prepare($sql);
        $stm->bindParam(1,$obj->getId(),PDO::PARAM_INT);
        return $stm->execute();
    }
    public function listarUrl(){
        $sql="SELECT u.id,u.url,u.estado,m.menu FROM ca_url u INNER JOIN ca_menu m ON u.idMenu=m.id";
        $cn=FactoryConnection::create('postgres_pdo')->Connection();
        $stm=$cn->prepare($sql);
        return $stm->execute();
    }
    public function listarUrlxMenu(dto_url $obj){
        $sql="SELECT u.id,u.url,u.estado,m.menu FROM ca_url u INNER JOIN ca_menu m ON u.idMenu=m.id WHERE m.id=?";
        $cn=FactoryConnection::create('postgres_pdo')->Connection();
        $stm=$cn->prepare($sql);
        $stm->bindParam(1,$obj->getIdMenu(),PDO::PARAM_INT);
        return $stm->execute();
    }
}
?>
