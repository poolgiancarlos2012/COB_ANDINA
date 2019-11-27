var validacion={
		
        check : function ( Json  ) {
			var rs=true;
            for ( i=0; i<Json.length ; i++ ){
				
                data = Json[i];
				
                //if(data.type=='text'){
                    var trim = $.trim( $('#'+data.id).val()  );
					
                    if(data.required){
                        if( trim=='' ){
                            data.errorRequiredFunction();
							rs=false;
							//return "false";
                            break;
                        }
                    }
					
					if ( data.isEmail  ) {
                        var email=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
						
						if ( data.required==false && trim!='' ) {

							if(!email.test(trim)){
								data.errorEmailFunction();
								rs=false;
								break;
							}
						}else if( data.required==true ) {
							
							if(!email.test(trim)){
								data.errorEmailFunction();
								rs=false;
								//return "false";
								break;
							}	
							
						}
						
                    }
					
					if ( data.isNumber ) {
                        var number=/^(?:\+|-)?\d+$/;
                        if(!number.test(trim)){
                            data.errorNumberFunction();
							rs=false;
							//return "false";
                            break;
                        }
                    }
					
					if ( data.isDecimal ) {
                        var decimal=/^\d+\.?\d*$/;
						
						if( data.required==false && trim!='' ){
							if(!decimal.test(trim)){
								data.errorDecimalFunction();
								rs=false;
								//return "false";
								break;
							}	
						}else if( data.required==true ) {
							if(!decimal.test(trim)){
								data.errorDecimalFunction();
								rs=false;
								//return "false";
								break;
							}	
						}
						
                    }
					
					if( data.isDependence==true && trim!='' ) {
						var dataDependence=$.trim( $('#'+data.idDependence).val() );
						if( dataDependence=='' ) {
							data.errorDependenceFunction();
							rs=false;
							return false;
						}
					}
					
					if ( data.isAlphaNumeric ) {
                        var alphanumeric=/^([a-z]|\d|\s|-|\.|_|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+$/i;
						
						if ( data.required==false && trim!='' ) {
							if(!alphanumeric.test(trim)){
								data.errorAlphaNumericFunction();
								rs=false;
								break;
							}
						}else if( data.required==true ) {
							if(!alphanumeric.test(trim)){
								data.errorAlphaNumericFunction();
								rs=false;
								break;
							}
						}
					
                    }
					
					if( data.isDNI ) {
                        var dni=/^\d+$/;
                        if(!dni.test(trim)){
                           data.errorDniFunction();
						   rs=false;
						   //return "false";
                           break;
                        }
                    }
					
					
					if( data.isNotValue==trim ) {
						data.errorNotValueFunction();
						rs=false;
						break;
					}
					
					if( data.isConfirm ){
						var valConfirm=$('#'+data.idConfirm).val();
						
						if( valConfirm!=trim ) {
							data.errorConfirmFunction();
							rs=false;
							break;	
						}
						
					}
                              
            } 
			return rs; 
        }

    }
	