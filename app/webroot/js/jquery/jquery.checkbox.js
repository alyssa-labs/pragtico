jQuery.fn.checkbox = function(mode) {
	var mode = mode || 'seleccionar';
	var c = 0;
	var ids = new Array();
	var id;
	this.each(
		function() {
			switch(mode) {
				case 'seleccionar':
					this.checked = true;
					break;
				case 'deseleccionar':
					this.checked = false;
				break;
				case 'invertir':
					this.checked = !this.checked;
					break;
				case 'contar':
					if(this.checked) {
						c++;
					}
					break;
				case 'retornarIds':
					if(this.checked) {
						id = this.id.toString();
						ids.push(id.substring(19));
					}
					break;
			}
   		}
   	);
   	
   	
   	if(mode == 'contar') {
   		return c;
   	}
   	else if(mode == 'retornarIds') {
   		return ids.join("|");
   	}
};