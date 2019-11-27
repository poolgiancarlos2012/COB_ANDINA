var indexDAO={
                    url:'controller/ControllerCobrast.php',
					idLayerMessage : 'layerMessage',
                    checkUser:function( ){
                        $.ajax({
                                url:this.url,
                                type:'POST',
                                dataType:'json',
                                data:{
										command:'login',
										action:'check',
										Usuario:$.trim( $('#txtUsuario').val() ),
										Password:$.trim( $('#txtPsw').val() ),
										Servicio:$('#cbServicio').val()
										},
                                beforeSend:function(){
                                   $('#layerOverlay,#layerLoading').css('display','block');
                                },
                                success:function(obj){
                                    
                                    if(obj.rst){
                                        window.location.href='view/ui-cobrast.php?menu=home';
                                    }else{
										$('#layerOverlay,#layerLoading').hide();
                                        $('#'+indexDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
										indexDAO.setTimeOut_hide_message();
                                    }
                                },
                                error:function(){
									$('#layerOverlay,#layerLoading').hide();
                                    $('#'+indexDAO.idLayerMessage).html(templates.MsgError("Error en el servidor",'250px'));
									indexDAO.setTimeOut_hide_message();
                                }
                        });
                    },
					checkUserAdmin:function( ){
                        $.ajax({
                                url:this.url,
                                type:'POST',
                                dataType:'json',
                                data:{
										command:'login',
										action:'check',
										Usuario:$.trim( $('#txtUsuario').val() ),
										Password:$.trim( $('#txtPsw').val() ),
										Servicio:1
										},
                                beforeSend:function(){
                                   $('#layerOverlay,#layerLoading').css('display','block');
                                },
                                success:function(obj){
                                    
                                    if(obj.rst){
                                        window.location.href='view/ui-cobrast.php?menu=home';
                                    }else{
										$('#layerOverlay,#layerLoading').hide();
                                        $('#'+indexDAO.idLayerMessage).html(templates.MsgError(obj.msg,'250px'));
										indexDAO.setTimeOut_hide_message();
                                    }
                                },
                                error:function(){
									$('#layerOverlay,#layerLoading').hide();
                                    $('#'+indexDAO.idLayerMessage).html(templates.MsgError("Error en el servidor",'250px'));
									indexDAO.setTimeOut_hide_message();
                                }
                        });
                    },
                    servicio:function ( ) {
                        $.ajax({
                            url:this.url,
                            type:'GET',
                            dataType:'json',
                            data:{command:'servicio',action:'listar_servicio'},
                            success:function ( obj ) {

									if( !obj.rst && obj.msg!=undefined ) {
										$('#'+indexDAO.idLayerMessage).html(templates.MsgError(obj.msg,'300px'));
										indexDAO.setTimeOut_hide_message();
										return false;
									}
									templates.combo(obj,'cbServicio');
									
                                   
                            } ,
                            error: function ( ) {
                                    $('#'+indexDAO.idLayerMessage).html(templates.MsgError("Error en el servidor",'250px'));
									indexDAO.setTimeOut_hide_message();
                            }
                        });
                    },
					hide_message : function ( ) {
							$('#'+indexDAO.idLayerMessage).effect('blind',{direction:'vertical'},'slow',function(){ $(this).empty().css('display','block'); });
								
						},
					setTimeOut_hide_message : function ( ) {
							setTimeout("indexDAO.hide_message()",2000);
						}

}


