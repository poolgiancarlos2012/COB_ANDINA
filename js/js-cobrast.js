$(document).ready(function(){
	_createDialogEditAvatar();
	$('#btnOtrosMenuMainCobrast').button({icons : { primary : "" }});
	$('.nav-sub').navDesplegable();
});


/*_mostrarFormularioAlCentroVentana=function(idForm){
	var w = $(this).width(); 
   	var h = $(this).height(); 
    //Centrar
	var _w = $("#"+idForm).width(); 
	var _h = $("#"+idForm).height(); 
	w = (w/2) - (_w/2); 
   	h = (h/2) - (_h/2); 
   	$("#"+idForm).css("left",w + "px"); 
   	$("#"+idForm).css("top",h + "px");
	var disp=$("#"+idForm).css('display');
	if(disp=='none'){
		$("#"+idForm).fadeIn('slow'); 	
	}else{
		$("#"+idForm).fadeOut('slow'); 	
	}
}*/
$.fn.navDesplegable = function(){
    this
    .hover(
        function(){
            //$(this).find('ul').css('display','block')
        }, 
        function(){
            $(this).children('ul').css('display','none')
        }
    )
    .click(
        function(){
            var d=$(this).children('ul').css('display');
            if(d=='none'){
                $(this).children('ul').css('display','block');
            }else{
                $(this).children('ul').css('display','none');
            }
        }
    );
}
_selectedRow=function(idTable,idTr){
	$("#"+idTable).find("tr").removeClass("ui-state-highlight").addClass("ui-state-active");
	$("#"+idTr).removeClass("ui-state-active").addClass("ui-state-highlight");
	}
_overRow=function(idTable,idTr){
	$("#"+idTable).find("tr").removeClass("ui-state-default").addClass("ui-state-active");
	$("#"+idTr).removeClass("ui-state-active").addClass("ui-state-default");
	}
_sliderBarLayer=function(){
    var w=$("#barLayer").width();
    if(w==210){
        $("#barLayer").animate({
            width:'0px'
        },400);
        $("#iconSlider").removeClass("sliderIconUp").addClass("sliderIconDown");
    }else if(w==0){
        $("#barLayer").animate({
            width:'210px'
        },400);
        $("#iconSlider").removeClass("sliderIconDown").addClass("sliderIconUp");
    }
	
}
_sliderFadeBarLayer=function(){
    var c=$("#barLayer").css('display');
    if(c=='block'){
        $("#barLayer").fadeOut('fast',function(){
            $("#iconSlider").removeClass("sliderIconUp").addClass("sliderIconDown");
        });
		
    }else if(c=='none'){
        $("#barLayer").fadeIn('fast',function(){
            $("#iconSlider").removeClass("sliderIconDown").addClass("sliderIconUp");
        });
		
    }
}
_sliderFadeLayer=function(idlayer){
    var c=$("#"+idlayer).css('display');
    if(c=='block'){
        $("#"+idlayer).fadeOut('fast'
            //$("#iconSlider").removeClass("sliderIconUp").addClass("sliderIconDown");
        );
		$("#"+idlayer).fadeIn('fast',function(){
            //$("#iconSlider").removeClass("sliderIconDown").addClass("sliderIconUp");
        });
	}else if(c=='none'){
        $("#"+idlayer).fadeIn('fast',function(){
            //$("#iconSlider").removeClass("sliderIconDown").addClass("sliderIconUp");
        });
		
    }
}
_activeTab=function(){
	
    /*$('div[id^="tab"]').not(element).removeClass("itemTabActive").addClass("itemTab");
    $('div[id^="tab"]').not(element).find('a').removeClass("text-white").addClass("AitemTab");
    $(element).removeClass("itemTab").addClass("itemTabActive");
    $(element).find("a").removeClass("AitemTab").addClass("text-white");*/
	$('#closeWindowCobrastOverlay,#closeWindowCobrastProgressBar').css('display','block');
	setTimeout("_CloseActiveTab()",5000);
}
_CloseActiveTab=function(){
	$('#closeWindowCobrastOverlay,#closeWindowCobrastProgressBar').hide();
}
_activeTabLayer=function(contentTab,prefixTab,tab,contentLayer,prefixLayer,idLayer){
    $('#'+contentTab+' div[id^="'+prefixTab+'"]').not(tab).removeClass("itemTabActive ui-widget-header").addClass("itemTab ui-widget-content");
	//$('#'+contentTab+' div[id^="'+prefixTab+'"]').not(tab).removeClass("itemTabActive").addClass("itemTab");
    $('#'+contentTab+' div[id^="'+prefixTab+'"]').not(tab).find('div').removeClass("text-white").addClass("AitemTab");
	//$(tab).removeClass("itemTab").addClass("itemTabActive");
    $(tab).removeClass("itemTab ui-widget-content").addClass("itemTabActive ui-widget-header");
    $(tab).find("div").removeClass("AitemTab").addClass("text-white");
	
    $('#'+contentLayer+' div[id^="'+prefixLayer+'"]').not('#'+contentLayer+' #'+idLayer).hide();
    //$('#'+contentLayer+' #'+idLayer).fadeIn('normal');
	$('#'+contentLayer+' #'+idLayer).show();
}
_activeTabLayer2=function(contentTab,prefixTab,tab,contentLayer,prefixLayer,idLayer){

    $('#'+contentTab+' div[id^="'+prefixTab+'"]').not(tab).removeClass("text-alert").addClass("text-gris");
    $(tab).removeClass("text-gris").addClass("text-alert");
    
    $('#'+contentLayer+' div[id^="'+prefixLayer+'"]').not('#'+contentLayer+' #'+idLayer).hide();
 	$('#'+contentLayer+' #'+idLayer).show();
}
_slide=function(element,id){
    $("#"+id).slideToggle('fast',function(){
        var c=$("#"+id).css('display');
        if(c=='block'){
            $(element).removeClass("iconPinBlueUp").addClass("iconPinBlueDown");
        }else if(c=='none'){
            $(element).removeClass("iconPinBlueDown").addClass("iconPinBlueUp");
        }
    });
	
}
_slide2 = function (element, id ) {
	$("#"+id).slideToggle('fast',function(){
        var c=$("#"+id).css('display');
        if(c=='block'){
			$(element).find('.backPanel').removeClass("iconPinBlueUp").addClass("iconPinBlueDown");
        }else if(c=='none'){
			$(element).find('.backPanel').removeClass("iconPinBlueDown").addClass("iconPinBlueUp");
        }
    });
}

_slide3 = function (element, id,indic ) {
	if(indic==1){
		$("#"+id).slideDown('fast',function(){
        	var c=$("#"+id).css('display');
        	if(c=='block'){
				//$(element).find('.backPanel').removeClass("iconPinBlueUp").addClass("iconPinBlueDown");
        	}else if(c=='none'){
				//$(element).find('.backPanel').removeClass("iconPinBlueDown").addClass("iconPinBlueUp");
        	}
    	});	
	}else if(indic==2){
		$("#"+id).slideUp('fast',function(){
        	var c=$("#"+id).css('display');
        	if(c=='block'){
				
				//$(element).find('.backPanel').removeClass("iconPinBlueUp").addClass("iconPinBlueDown");
        	}else if(c=='none'){
				//$(element).find('.backPanel').removeClass("iconPinBlueDown").addClass("iconPinBlueUp");
        	}
		});		
	}else{
		$("#"+id).slideToggle('fast',function(){
			var c=$("#"+id).css('display');
			if(c=='block'){
				//$(element).find('.backPanel').removeClass("iconPinBlueUp").addClass("iconPinBlueDown");
			}else if(c=='none'){
				//$(element).find('.backPanel').removeClass("iconPinBlueDown").addClass("iconPinBlueUp");
			}
		});	
	}
	
}

_slideBarLayer=function(element,id){
    $("#"+id).slideToggle('fast',function(){
        var c=$("#"+id).css('display');
        if(c=='block'){
            $(element).removeClass("iconPinDown").addClass("iconPinUp");
        }else if(c=='none'){
            $(element).removeClass("iconPinUp").addClass("iconPinDown");
        }
    });
}
_display_submenu=function(element,idSubmenu){
    var c=$('#'+idSubmenu).css("display");
    if(c=='block'){
        $("#"+idSubmenu).fadeOut('normal',function(){
            $(element).removeClass("minus-icon").addClass("plus-icon");
        });
    }else if(c=='none'){
        $("#"+idSubmenu).fadeIn('normal',function(){
            $(element).removeClass("plus-icon").addClass("minus-icon");
        });
    }
}
_display_panel=function(idPanel){
    //$("#cobrastHOME div[id^='panel']").not("#cobrastHOME #"+idPanel).fadeOut('fast');
    $("#cobrastHOME div[id^='panel']").not("#cobrastHOME #"+idPanel).hide();
    $("#cobrastHOME #"+idPanel).fadeIn('slow');
}
_displayBeforeSend = function (text,width ) {
    $('#beforeSendShadow,#MsgBeforeSend').width(width);
    $('#MsgBeforeSend').text(text);
    $('#beforeSendShadow,#MsgBeforeSend').css('display','block');
}
_noneBeforeSend = function ( ) {
    $('#beforeSendShadow,#MsgBeforeSend').css('display','none');
}
_displayBeforeSendDl = function ( text,width ) {
	$('#beforeSendShadow,#MsgBeforeSend').width(width);
	$('#MsgBeforeSend').text(text);
	$('#beforeSendShadow,#MsgBeforeSend').fadeIn().delay(4000).fadeOut();
}
_displayBarAlerts = function ( id,element ) {
	//var display=$('#'+id).css('display');
}
_mostrarFrm=function(idFrm){
	$('#'+idFrm).dialog('open');
}
_showCrear = function ( value ) {
	if(value=='campania'){
		$('#dialogCampania').dialog('open');
	}else if( value=='usuario' ){
		$('#dialogUsuario').dialog('open');
	}

}
_createDialogEditAvatar = function ( ) {
	
	if( !$.fn.dialog ) {
		return false;
	}
	
	var html = '';
	html+='<div id="_dialogEditAvatar">';
		html+='<table>';
			html+='<tr>';
				html+='<td>Nombre</td>';
				html+='<td><input class="cajaForm" id="_txtnombreEditAvatar" type="text"></td>';
			html+='</tr>';
			html+='<tr>';
				html+='<td>Paterno</td>';
				html+='<td><input class="cajaForm" id="_txtpaternoEditAvatar" type="text"></td>';
			html+='</tr>';
			html+='<tr>';
				html+='<td>Materno</td>';
				html+='<td><input class="cajaForm" id="_txtmaternoEditAvatar" type="text"></td>';
			html+='</tr>';
			html+='<tr>';
				html+='<td>Email</td>';
				html+='<td><input class="cajaForm" id="_txtemailEditAvatar" type="text"></td>';
			html+='</tr>';
			html+='<tr>';
				html+='<td>DNI</td>';
				html+='<td><input class="cajaForm" id="_txtdniEditAvatar" type="text"></td>';
			html+='</tr>';
			html+='<tr>';
				html+='<td>Clave</td>';
				html+='<td><input class="cajaForm" id="_txtclaveEditAvatar" type="password"></td>';
			html+='</tr>';
			html+='<tr>';
				html+='<td>Confirmar Clave</td>';
				html+='<td><input class="cajaForm" id="_txtconfclaveEditAvatar" type="password"></td>';
			html+='</tr>';
			html+='<tr>';
				html+='<td>Avatar</td>';
				html+='<td>'
					html+='<select id="_cbavatarEditAvatar" class="combo">';
						html+='<option value="user1.png">user1</option>';
						html+='<option value="angel.png">angel</option>';
						html+='<option value="atom.png">atom</option>';
						html+='<option value="bell.png">bell</option>';
						html+='<option value="bomb.png">bomb</option>';
						html+='<option value="bug_green.png">bug_green</option>';
						html+='<option value="businessman2.png">businessman2</option>';
						html+='<option value="doctor.png">doctor</option>';
						html+='<option value="dude1.png">dude1</option>';
						html+='<option value="dude2.png">dude2</option>';
						html+='<option value="dude3.png">dude3</option>';
						html+='<option value="dude4.png">dude4</option>';
						html+='<option value="dude5.png">dude5</option>';
						html+='<option value="earth.png">earth</option>';
						html+='<option value="ghost.png">ghost</option>';
						html+='<option value="goldbars.png">goldbars</option>';
						html+='<option value="hat_gray.png">hat_gray</option>';
						html+='<option value="heart.png">heart</option>';
						html+='<option value="heart_broken.png">heart_broken</option>';
						html+='<option value="help2.png">help2</option>';
						html+='<option value="masks.png">masks</option>';
						html+='<option value="microphone2.png">microphone2</option>';
						html+='<option value="money.png">money</option>';
						html+='<option value="oszillograph.png">oszillograph</option>';
						html+='<option value="pda2.png">pda2</option>';
						html+='<option value="pilot2.png">pilot2</option>';
						html+='<option value="planet.png">planet</option>';
						html+='<option value="policeman_usa.png">policeman_usa</option>';
						html+='<option value="remotecontrol2.png">remotecontrol2</option>';
						html+='<option value="security_agent.png">security_agent</option>';
						html+='<option value="sportscar.png">sportscar</option>';
						html+='<option value="spy.png">spy</option>';
						html+='<option value="thermometer.png">thermometer</option>';
						html+='<option value="user3.png">user3</option>';
						html+='<option value="user_headphones.png">user_headphones</option>';
						html+='<option value="workstation2.png">workstation2</option>';
						html+='<option value="yinyang.png">yinyang</option>';
					html+='</select>';
				html+='</td>';
			html+='</tr>';
		html+='</table>';
	html+='</div>';
	$(document.body).append(html);
	
	$.ajax({
			url :  '../controller/ControllerCobrast.php',
			type : 'GET',
			dataType : 'json',
			data : { command : 'usuario', action : 'queryByUser', idusuario : $('#hdCodUsuario').val() },
			beforeSend : function ( ) { },
			success : function ( obj ) { 
					if( obj.length > 0 ) {
						$('#_txtnombreEditAvatar').val(obj[0].nombre);
						$('#_txtpaternoEditAvatar').val(obj[0].paterno);
						$('#_txtmaternoEditAvatar').val(obj[0].materno);
						$('#_txtemailEditAvatar').val(obj[0].email);
						$('#_txtdniEditAvatar').val(obj[0].dni);
						$('#_cbavatarEditAvatar').val(obj[0].img_avatar);
					}
				},
			error : function ( ) { }
			
			});
	
	$('#_dialogEditAvatar').dialog({
									height : 350,
									autoOpen : false,
									width : 300 ,
									title : 'Editar Perfil',
									modal : true,
									buttons : {
											Cancel : function ( ) {
													$(this).dialog('close');
												},
											Grabar : function ( ){
													_updateAvatar();
												}
										}
									});
}
_updateAvatar = function ( ) {
	
	var xnombre = $.trim( $('#_txtnombreEditAvatar').val() );
	var xpaterno = $.trim( $('#_txtpaternoEditAvatar').val() );
	var xmaterno = $.trim( $('#_txtmaternoEditAvatar').val() );
	var xemail = $.trim( $('#_txtemailEditAvatar').val() );
	var ximg_avatar = $('#_cbavatarEditAvatar').val();
	var xdni = $.trim( $('#_txtdniEditAvatar').val() );
	var xclave = $.trim( $('#_txtclaveEditAvatar').val() );
	
	var rs = confirm("Verifique si los datos son los correctos");
	
	if( xdni == '' ) {
		alert('Ingrese DNI');
	}
	
	if( rs ) {
		
		$.ajax({
				url :  '../controller/ControllerCobrast.php',
				type : 'POST',
				dataType : 'json',
				data : { 
						command : 'usuario', 
						action : 'update_avatar',
						idusuario : $('#hdCodUsuario').val(),
						nombre : xnombre,
						paterno : xpaterno,
						materno : xmaterno,
						email : xemail,
						dni : xdni,
						clave : xclave,
						img_avatar : ximg_avatar 
					},
				beforeSend : function ( ) { },
				success : function ( obj ) { 
						if( obj.rst ) {
							alert("Vuelva a loguearse");
						}else{
							alert("Error al actualizar perfil");
						}
					},
				error : function ( ) {
						alert("Error al actualizar perfil"); 
					}
					
				});
		
	}
	
}
_cobrast_arriba_item = function ( obj ) {
	//obj=document.getElementById('sel');
	indice=obj.selectedIndex;
	if (indice>0) _cobrast_cambiar_item(obj,indice,indice-1);
}
_cobrast_abajo_item = function  ( obj ) {
	//obj=document.getElementById('sel');
	indice=obj.selectedIndex;
	if (indice!=-1 && indice<obj.length-1)
		_cobrast_cambiar_item(obj,indice,indice+1);
}
_cobrast_cambiar_item = function ( obj, index1, index2 ) {
	proVal=obj.options[index1].value;
	proTex=obj.options[index1].text;
	obj.options[index1].value=obj.options[index2].value;	
	obj.options[index1].text=obj.options[index2].text;	
	obj.options[index2].value=proVal;
	obj.options[index2].text=proTex;
	obj.selectedIndex=index2;
}

