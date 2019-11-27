<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of servletMenu
 *
 * @author Davis
 */
class servletMenu extends CommandController {

    public function doPost() {
        $dao = DAOFactory::getDAOMenu();
        switch ($_POST["action"]) {
            case "insert":
                $dtoMenu = new dto_menu();
                $dtoMenu->setNombreMenu($_POST["menu"]);
                $dtoMenu->setEstado("1");
                echo $dao->insert($dtoMenu);
                break;
            case 'update':
                $dtoMenu = new dto_menu();
                $dtoMenu->setNombreMenu($_POST["menu"]);
                $dtoMenu->setEstado($_POST["estado"]);
                $dtoMenu->setId($_POST["id"]);
                echo $dao->update($dtoMenu);
                break;
            case 'delete':
                $dtoMenu = new dto_menu();
                $dtoMenu->setId($_POST["id"]);
                echo $dao->delete($dtoMenu);
                break;
        }
    }
    public function doGet() {
        $dao = DAOFactory::getDAOMenu();
        switch ($_GET["action"]) {
            case "jqGridListarMenus":
                $page=$_GET["page"];
                $limit=$_GET["rows"];
                $sidx=$_GET["sidx"];
                $sord=$_GET["sord"];

                if(!$sidx)$sidx=1;
                $row=$dao->count();
                $count=$row[0]['COUNT'];
                if($count>0) {
                    $total_pages=ceil($count/$limit);
                }else {
                    $total_pages=0;
                }
                if($page>$total_pages) $page=$total_pages;                
                $start=$page*$limit-$limit;
                $header=array('page'=>$page,'total'=>$total_pages,'records'=>$count);                
                $stmt=" SELECT id,menu,CASE WHEN estado=1 THEN 'ACTIVO' WHEN estado =2 THEN 'INACTIVO' END as estado FROM ca_menu WHERE estado=1 ORDER BY $sidx $sord LIMIT $start , $limit ";
                echo $dao->listJqGrid($stmt, $header);
                break;
        }

    }
}
?>
