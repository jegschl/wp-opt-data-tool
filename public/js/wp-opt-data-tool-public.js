(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	let selectE = null;
	let parentE = null;
	let departuresData = null;
	let costoE = null;
	$(document).ready(function(){
		function onSelectChange(e){
			let i = 0;
			let costoFormateado = '';
			var formatter = new Intl.NumberFormat('es-CL', {
				style: 'currency',
				currency: 'CLP',
			  
				// These options are needed to round to whole numbers if that's what you want.
				//minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
				//maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
			});
			for(i=0; i<departuresData.length; i++){
				if(departuresData[i].departure == $(this).val()){
					costoFormateado = formatter.format(parseInt(departuresData[i].cost));
					costoE.text(costoFormateado);
				}
			}
		}
		function onFrontSelectLoadSuccess(data,  textStatus,  jqXHR){
			console.log('Datos para rellenar el select de localidaddes de origen:');
			console.log(data);
			let i=0;
			let id='';
			let dsc='';
			departuresData = data.data;
			selectE.find('option').remove();
			selectE.append('<option value="">Selecciona una localidad</option>');
			for(i=0;i<departuresData.length;i++){
				id  = departuresData[i].DT_RowId.split('-')[1];
				dsc = departuresData[i].departure;
				selectE.append($("<option></option>")
                    .attr("value", dsc)
                    .text(dsc)); 
			}

			selectE.off('change');
			selectE.on('change',onSelectChange);
		}

		function onFrontSelectLoadError(jqXHR, textStatus, errorThrown){
			console.log('Error obteniendo datos para select de localidades de origen.');
			console.log('El error fué el siguiente:');
			console.log(jqXHR);
		}

		function onFrontSelectLoadComplete(jqXHR, textStatus){
			parentE.unblock();
		}

		function initSelect(){
			const selectHtmlId = wodt_front_config.selectHtmlId;
			const parentHtmlId = wodt_front_config.parentHtmlId;
			const costoHtmlId  = wodt_front_config.costHtmlId;
			const url = wodt_front_config.urlGetCosts;
			selectE = $(selectHtmlId);
			parentE = $(parentHtmlId);
			costoE  = $(costoHtmlId);
			
			if(selectE.length == 0) return;

			parentE.block({
				message: '<h4>Descargando comunas de partida</h4>',
				css: {
					width: "60%",
					border: '1px solid #a00'
				}
			});
			const ajxSttings = {
				method: 'GET',
				url: url,
				accepts: 'application/json; charset=UTF-8',
				contentType: 'application/json; charset=UTF-8',
				complete: onFrontSelectLoadComplete,
				success: onFrontSelectLoadSuccess,
				error: onFrontSelectLoadError

			};

			$.ajax(ajxSttings);
		}

		initSelect();		
	});

})( jQuery );
