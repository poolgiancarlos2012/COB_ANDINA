var PermisosDAO = {
    url:'../controller/ControllerPermisos.php',
    xdataType:'json',
    insert:function(xdata){
        $.ajax({
            url:this.url,
            dataType:this.xdataType,
            data:xdata,
            beforeSend:function(){},
            success:function(){}
        });
    },
    update:function(xdata){
        $.ajax({
            url:this.url,
            dataType:this.xdataType,
            data:xdata,
            beforeSend:function(){},
            success:function(){}
        });
    },
    search:function(xdata){
        $.ajax({
            url:this.url,
            dataType:this.xdataType,
            data:xdata,
            beforeSend:function(){},
            success:function(){}
        });
    },
    load:function(xdata,xidLayer){
        $.ajax({
            url:this.url,
            dataType:this.xdataType,
            data:xdata,
            beforeSend:function(){
                var _html = '';
                _html+='<div style="heigth:100px;width:100%;display:inline-table;">';
                    _html+='<img src="../img/loading_.gif"/>';
                _html+='</div>';
                $("#"+xidLayer).html(_html);                
            },
            success:function(_obj){
                var _html='';
                _html+='<ol>';
                $.each(_obj,function(){
                    _html+='<li>'+_obj.menu;
                    _html+='<ul>';
                    for(var i = 0 ; i < _obj.url.length;i++){
                        _html+='<li>'+_obj.url[i]+'<span><input type="checkbox"></span></li>';
                    }
                    _html+='<ul>';
                    _html+='</li>';
                });
                _html+='</ol>';
                $("#"+xidLayer).html(_html);
            }
        });
    }
}
