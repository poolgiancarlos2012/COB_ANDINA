$(document).ready(function(){
    _listar_menus();
});
_listar_menus = function (){
    var xdata = {
        action:'listarMenus'
    };
    PermisosDAO.load(xdata,"listaMenus");
}