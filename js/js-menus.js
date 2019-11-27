var flagSave=true;
$(document).ready(function(){
    $("#btnGrabar").click(_grabar);
    _jqgrid_campania();
});
_grabar = function (){    
    var xdata={};
    if(!flagSave){
        xdata={menu:$("#txtMenu").val(),estado:1,id:$("#hddCodigo").val()};
        MenuDAO.update(xdata);
    }else{
        xdata={menu:$("#txtMenu").val(),estado:1};
        MenuDAO.insert(xdata);
    }    
    
}
_jqgrid_campania=function(){
   MenuDAO.load();
}
_showMenu = function(_this){
    var estd = $(_this).parent().prev().text();
    var menu = $(_this).parent().prev().prev().text();
    var codi = $(_this).parent().prev().prev().prev().text();
    
    $("#txtMenu").val(menu);
    $("#hddCodigo").val(codi);
    $("#cboEstado").val(estd);
    flagSave=false;
}
