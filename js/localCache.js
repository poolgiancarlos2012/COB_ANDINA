var localCache = {

	localStorage : {
		success : null,
		init : function ( ) 
		{
			if( window.localStorage ) {
				
				this.success = window.localStorage;
				
			}
		},
		setItem : function ( key, value, type ) 
		{
			if( this.success ) {
				
				switch( type )
				{
					case 'json':
						
					break;
					default:
						this.success.setItem(key, value);
					;
				}
				
			}
		},
		getItem : function ( key, type ) {
			var item = null;
			if( this.success ) {
				
				switch( type )
				{
					case 'json':
						var data = this.success.setItem(key, value);
						
						
						
					break;
					default:
						item = this.success.getItem(key);
					;
				}
				
			}
			return item;
		},
		clear : function ( ) 
		{
			if( this.success )
			{
				this.success.clear();
			}
		},
		removeItem : function ( key ) {
			if( this.success )
			{
				this.success.removeItem(key);
			}
		}
	},
	sessionStorage : {
		
		init : function ( ) 
		{
			
		}
		
	},
	indexedDB : {
		
		init : function ( )
		{
			
			
			
		}
		
	},
	WebSQLDatabases : {
		db : null,
		init : function ( ) 
		{
			
		}
		
	}
	
}