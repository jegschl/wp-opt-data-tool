var dttbl_departures = null;
var departure_editor_mode = null; // 0:add , 1:edition

var dttbl_costs = null;
var cost_editor_mode = null;
var currentCostOrigenDsc = '';
var currentCostDestinDsc = '';

var arrive_editor_mode = null;
var dttbl_arrives = null;

function selection_data_render(data, type) {
	if (type === 'display') {
		let selection = '';
		if(data == true){
			selection = 'checked';
		}

		return '<input type="checkbox" ' + selection + ' />' ;
	}
		
	return data;
}

function actions_data_render(data, type){
	if (type === 'display') {
		var output = '';
		output += '<div class="actions">';

		output += '<div class="action edit-wodt">';
		output += '<i class="fas fa-edit"></i>';
		output += '</div>';

		output += '<div class="action remove-wodt">';
		output += '<i class="fas fa-minus-circle"></i>';
		output += '</div>';

		output += '</div>';
		return output ;
	}
		
	return data;
}



(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

	function initialize_traves_costs_section(){
		//debugger;
		const dttblId = '#costs-dttbl';
		const addWrapper = '.wodt-costs-wrapper ';

		dttbl_costs = $(dttblId).DataTable( {
			processing: true,
			serverSide: true,
			ajax: wodt_config.urlGetCosts,
			language: {
				url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es-cl.json'
			},
			columns: [
				{
					data: 'selection',
					render: selection_data_render
				},
				{
					data: 'departure'
				},
				{
					data: 'arrive'
				},
				{
					data: 'cost'
				},
				{
					data: 'actions',
					render: actions_data_render
				}
			]
		} );	

		function resetWodtAddCostFields(){
			if( cost_editor_mode == 0)
				$( '#wodt_costo' ).val('');
		}

		function onDepartureLoadForSelectSuccess(data,  textStatus,  jqXHR){
			console.log('Datos para rellenar el select de localidaddes de origen:');
			console.log(data);
			let i=0;
			let id='';
			let dsc='';
			
			let optionStr = '';
			$('#wodt_origen').find('option').remove();
			$('#wodt_origen').append('<option value="">Comuna lo localidad de origen</option>');
			for(i=0;i<data.data.length;i++){
				id  = data.data[i].DT_RowId.split('-')[1];
				dsc = data.data[i].departure;				

				if(cost_editor_mode == 1){
					if( data.data[i].departure == currentCostOrigenDsc ){
						optionStr = "<option selected></option>";
					} else {
						optionStr = "<option></option>";
					}
				} else { optionStr = "<option></option>"; }

				$('#wodt_origen').append($(optionStr)
                    .attr("value", id)
                    .text(dsc)); 
			}
		}

		function onDepartureLoadForSelectError(jqXHR, textStatus, errorThrown){
			console.log('Error obteniendo datos para select de localidades de origen.');
			console.log('El error fué el siguiente:');
			console.log(jqXHR);
		}

		function onDepartureLoadForSelectComplete(jqXHR, textStatus){
			$('#wodt_origen_wrapper').unblock();
		}

		function loadDeparturesOnSelect(){
			$('#wodt_origen_wrapper').block({ 
				message: '<h4>Cargando localidades de origen</h4>',
                css: { 
					width: "60%",
					border: '1px solid #a00' 
				} 
            });
			const ajxSettings = {
				url: wodt_config.urlGetDepartures,
				method: 'GET',
				success: onDepartureLoadForSelectSuccess,
				error: onDepartureLoadForSelectError,
				complete: onDepartureLoadForSelectComplete
			}

			$.ajax(ajxSettings);
		}

		function onArriveLoadForSelectSuccess(data,  textStatus,  jqXHR){
			console.log('Datos para rellenar el select de localidaddes de destino:');
			console.log(data);
			let i=0;
			let id='';
			let dsc='';
			let optionStr = '';
			$('#wodt_destino').find('option').remove();
			$('#wodt_destino').append('<option value="">Comuna o localidad de destino</option>');
			for(i=0;i<data.data.length;i++){
				id  = data.data[i].DT_RowId.split('-')[1];
				dsc = data.data[i].arrive;

				if(cost_editor_mode == 1){
					if( data.data[i].arrive == currentCostDestinDsc ){
						optionStr = "<option selected></option>";
					} else {
						optionStr = "<option></option>";
					}
				} else { optionStr = "<option></option>"; }

				$('#wodt_destino').append($(optionStr)
                    .attr("value", id)
                    .text(dsc)); 
			}
		}

		function onArriveLoadForSelectError(jqXHR, textStatus, errorThrown){
			console.log('Error obteniendo datos para select de localidades de destino.');
			console.log('El error fué el siguiente:');
			console.log(jqXHR);
		}

		function onArriveLoadForSelectComplete(jqXHR, textStatus){
			$('#wodt_destino_wrapper').unblock();
		}

		function loadArrivesOnSelect(){
			$('#wodt_destino_wrapper').block({ 
				message: '<h4>Cargando localidades de destino</h4>',
                css: { 
					width: "60%",
					border: '1px solid #a00' 
				} 
            });
			const ajxSettings = {
				url: wodt_config.urlGetArrives,
				method: 'GET',
				success: onArriveLoadForSelectSuccess,
				error: onArriveLoadForSelectError,
				complete: onArriveLoadForSelectComplete
			}

			$.ajax(ajxSettings);
		}

		function setWidgetsForWodtCostAddNew(){
			$(addWrapper + '.wodt-admin-header').hide();
			$(dttblId + '_wrapper').addClass('dtHidden');
			resetWodtAddCostFields();
			$(addWrapper + '.wodt-admin-add-so').show();
			loadDeparturesOnSelect();
			loadArrivesOnSelect();
		}

		function setWidgetsForWodtCostAddedOrCanceled(){
			$(addWrapper + '.wodt-admin-add-so').hide();
			$(addWrapper + '.wodt-admin-header').show();
			$(dttblId + '_wrapper').removeClass('dtHidden');
		}

		function onWodtCostNewError( jqXHR, textStatus, errorThrown ){
			console.log('Error al intentar enviar un nuevo wodt data set al server.');
			console.log(jqXHR);
		}

		function onWodtCostNewSuccess(  data,  textStatus,  jqXHR ){
			console.log('Datos enviados al server correctamente.');
			console.log(data);
			dttbl_costs.ajax.reload();
			
		}

		function onWodtCostNewComplete( jqXHR, textStatus ){
			setWidgetsForWodtCostAddedOrCanceled();
		}

		function onWodtCostDelError( jqXHR, textStatus, errorThrown ){
			console.log('Error al intentar enviar eliminación de wodt data set al server.');
			console.log(jqXHR);
		}

		function onWodtCostDelSuccess(  data,  textStatus,  jqXHR ){
			console.log('Datos para eliminación enviados al server correctamente.');
			console.log(data);
			dttbl_costs.ajax.reload();
			
		}

		function onWodtCostDelComplete( jqXHR, textStatus ){
			
		}

		function sendDeleteCostRequest(ids){
			var ajxSettings = {
				url: wodt_config.urlRemCost,
				method: 'DELETE',
				accepts: 'application/json; charset=UTF-8',
				contentType: 'application/json; charset=UTF-8',
				data: JSON.stringify({'ids':ids}),
				complete: onWodtCostDelComplete,
				success: onWodtCostDelSuccess,
				error: onWodtCostDelError
			}

			// Activando animación de proceso.

			// ejecutando AJAX.
			$.ajax(ajxSettings);
		}

		$( addWrapper + ' .wodt-admin-header #add-wodt' ).on('click',function(event){
			cost_editor_mode = 0; // 0: add
			setWidgetsForWodtCostAddNew();
		});

		dttbl_costs.on('draw', function(){
			$( dttblId + ' .actions .edit-wodt' ).off('click');
			$( dttblId + ' .actions .edit-wodt' ).on('click',function(event){
				cost_editor_mode = 1; // 1: edit
				setWidgetsForWodtCostAddNew();
				let i = 0;
				const wodtSetId = $(this).parent().parent().parent().attr('id');
				const id = wodtSetId.split('-')[1];
				const dpr = dttbl_costs.rows().data();
				console.log('Costos distancia:');
				console.log(dpr);
				for(i=0; dpr.length; i++){
					if( dpr[i].DT_RowId.split('-')[1] == id ){
						$('#wodt_costo').val(dpr[i].cost);
						$('#wodt_costo_id').val(id);
						currentCostOrigenDsc = dpr[i].departure;
						currentCostDestinDsc = dpr[i].arrive;
						break;
					}
				}
			});

			$( dttblId + ' .actions .remove-wodt' ).off('click');
			$( dttblId + ' .actions .remove-wodt' ).on('click',function(event){
				let i = 0;
				const wodtSetId = $(this).parent().parent().parent().attr('id');
				const id = wodtSetId.split('-')[1];
				const dpr = dttbl_costs.rows().data();
				var idToDelete = [];
				for(i=0; dpr.length; i++){
					if( dpr[i].DT_RowId.split('-')[1] == id ){
						idToDelete.push(id);
						break;
					}
				}

				sendDeleteCostRequest(idToDelete);
			});

		});

		$( addWrapper + ' .actions-wrapper .cancel' ).on('click',function(event){
			event.preventDefault();
			setWidgetsForWodtCostAddedOrCanceled();
		});

		$( addWrapper + ' .wodt-admin-add-so .actions-wrapper .save' ).on('click',function(event){
			event.preventDefault();

			// crear datos para enviar.
			var wodtCostNewData = null;
			var ajxUrl = '';
			var ajxMthd = '';
			switch(cost_editor_mode){
				case 0:
					wodtCostNewData = {
						'departure_id': $('#wodt_origen').val(),
						'arrive_id':	$('#wodt_destino').val(),
						'cost': 		$('#wodt_costo').val()
					};
					ajxUrl = wodt_config.urlAddCost;
					ajxMthd = 'POST';
					break;

				case 1:
					wodtCostNewData = {
						'departure_id': $('#wodt_origen').val(),
						'arrive_id':	$('#wodt_destino').val(),
						'cost': $('#wodt_costo').val(),
						'cost_id': $('#wodt_costo_id').val()
					};
					ajxUrl = wodt_config.urlUpdCost;
					ajxMthd = 'PUT';
					break;
			}

			// preparando la configuración de la llamada a endpoint para crear nuevo wodt.
			var ajxSettings = {
				url: ajxUrl,
				method: ajxMthd,
				accepts: 'application/json; charset=UTF-8',
				contentType: 'application/json; charset=UTF-8',
				data: JSON.stringify(wodtCostNewData),
				complete: onWodtCostNewComplete,
				success: onWodtCostNewSuccess,
				error: onWodtCostNewError
			}

			// Activando animación de proceso.

			// ejecutando AJAX.
			$.ajax(ajxSettings);

		});
	}

	function initialize_departures_section(){
		//debugger;
		const dttblId = '#departures-dttbl';
		const addWrapper = '.wodt-departures-wrapper ';

		dttbl_departures = $(dttblId).DataTable( {
			processing: true,
			serverSide: true,
			ajax: wodt_config.urlGetDepartures,
			language: {
				url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es-cl.json'
			},
			columns: [
				{
					data: 'selection',
					render: selection_data_render
				},
				{
					data: 'departure'
				},
				{
					data: 'actions',
					render: actions_data_render
				}
			]
		} );	

		function resetWodtAddDepartureFields(){
			$( '#wodt_departure' ).val('');
		}

		function setWidgetsForWodtDepartureAddNew(){
			$(addWrapper + '.wodt-admin-header').hide();
			$(dttblId + '_wrapper').addClass('dtHidden');
			resetWodtAddDepartureFields();
			$(addWrapper + '.wodt-admin-add-so').show();
		}

		function setWidgetsForWodtDepartureAddedOrCanceled(){
			$(addWrapper + '.wodt-admin-add-so').hide();
			$(addWrapper + '.wodt-admin-header').show();
			$(dttblId + '_wrapper').removeClass('dtHidden');
		}

		function onWodtDepartureNewError( jqXHR, textStatus, errorThrown ){
			console.log('Error al intentar enviar un nuevo wodt data set al server.');
			console.log(jqXHR);
		}

		function onWodtDepartureNewSuccess(  data,  textStatus,  jqXHR ){
			console.log('Datos enviados al server correctamente.');
			console.log(data);
			dttbl_departures.ajax.reload();
			
		}

		function onWodtDepartureNewComplete( jqXHR, textStatus ){
			setWidgetsForWodtDepartureAddedOrCanceled();
		}

		function onWodtDepartureDelError( jqXHR, textStatus, errorThrown ){
			console.log('Error al intentar enviar eliminación de wodt data set al server.');
			console.log(jqXHR);
		}

		function onWodtDepartureDelSuccess(  data,  textStatus,  jqXHR ){
			console.log('Datos para eliminación enviados al server correctamente.');
			console.log(data);
			dttbl_departures.ajax.reload();
			
		}

		function onWodtDepartureDelComplete( jqXHR, textStatus ){
			
		}

		function sendDeleteDepartureRequest(ids){
			var ajxSettings = {
				url: wodt_config.urlRemDeparture,
				method: 'DELETE',
				accepts: 'application/json; charset=UTF-8',
				contentType: 'application/json; charset=UTF-8',
				data: JSON.stringify({'ids':ids}),
				complete: onWodtDepartureDelComplete,
				success: onWodtDepartureDelSuccess,
				error: onWodtDepartureDelError
			}

			// Activando animación de proceso.

			// ejecutando AJAX.
			$.ajax(ajxSettings);
		}

		$( addWrapper + ' .wodt-admin-header #add-wodt' ).on('click',function(event){
			departure_editor_mode = 0; // 0: add
			setWidgetsForWodtDepartureAddNew();
		});

		dttbl_departures.on('draw', function(){
			$( dttblId + ' .actions .edit-wodt' ).off('click');
			$( dttblId + ' .actions .edit-wodt' ).on('click',function(event){
				departure_editor_mode = 1; // 1: edit
				setWidgetsForWodtDepartureAddNew();
				let i = 0;
				const wodtSetId = $(this).parent().parent().parent().attr('id');
				const id = wodtSetId.split('-')[1];
				const dpr = dttbl_departures.rows().data();
				console.log('Orígenes de salida:');
				console.log(dpr);
				for(i=0; dpr.length; i++){
					if( dpr[i].DT_RowId.split('-')[1] == id ){
						$('#wodt_departure').val(dpr[i].departure);
						$('#wodt_departure_id').val(id);
						break;
					}
				}
			});

			$( dttblId + ' .actions .remove-wodt' ).off('click');
			$( dttblId + ' .actions .remove-wodt' ).on('click',function(event){
				let i = 0;
				const wodtSetId = $(this).parent().parent().parent().attr('id');
				const id = wodtSetId.split('-')[1];
				const dpr = dttbl_departures.rows().data();
				var idToDelete = [];
				for(i=0; dpr.length; i++){
					if( dpr[i].DT_RowId.split('-')[1] == id ){
						idToDelete.push(id);
						break;
					}
				}

				sendDeleteDepartureRequest(idToDelete);
			});

		});

		$( addWrapper + ' .actions-wrapper .cancel' ).on('click',function(event){
			event.preventDefault();
			setWidgetsForWodtDepartureAddedOrCanceled();
		});

		$( addWrapper + ' .wodt-admin-add-so .actions-wrapper .save' ).on('click',function(event){
			event.preventDefault();

			// crear datos para enviar.
			var wodtDepartureNewData = null;
			var ajxUrl = '';
			var ajxMthd = '';
			switch(departure_editor_mode){
				case 0:
					wodtDepartureNewData = {
						'departure': $('#wodt_departure').val(),
					};
					ajxUrl = wodt_config.urlAddDeparture;
					ajxMthd = 'POST';
					break;

				case 1:
					wodtDepartureNewData = {
						'departure': $('#wodt_departure').val(),
						'departure_id': $('#wodt_departure_id').val()
					};
					ajxUrl = wodt_config.urlUpdDeparture;
					ajxMthd = 'PUT';
					break;
			}

			// preparando la configuración de la llamada a endpoint para crear nuevo wodt.
			var ajxSettings = {
				url: ajxUrl,
				method: ajxMthd,
				accepts: 'application/json; charset=UTF-8',
				contentType: 'application/json; charset=UTF-8',
				data: JSON.stringify(wodtDepartureNewData),
				complete: onWodtDepartureNewComplete,
				success: onWodtDepartureNewSuccess,
				error: onWodtDepartureNewError
			}

			// Activando animación de proceso.

			// ejecutando AJAX.
			$.ajax(ajxSettings);

		});

	}

	function initialize_arrives_section(){
		//debugger;
		const dttblId = '#arrives-dttbl';
		const addWrapper = '.wodt-arrives-wrapper ';

		dttbl_arrives = $(dttblId).DataTable( {
			processing: true,
			serverSide: true,
			ajax: wodt_config.urlGetArrives,
			language: {
				url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/es-cl.json'
			},
			columns: [
				{
					data: 'selection',
					render: selection_data_render
				},
				{
					data: 'arrive'
				},
				{
					data: 'actions',
					render: actions_data_render
				}
			]
		} );	

		function resetWodtAddArriveFields(){
			$( '#wodt_arrive' ).val('');
		}

		function setWidgetsForWodtArriveAddNew(){
			$(addWrapper + '.wodt-admin-header').hide();
			$(dttblId + '_wrapper').addClass('dtHidden');
			resetWodtAddArriveFields();
			$(addWrapper + '.wodt-admin-add-so').show();
		}

		function setWidgetsForWodtArriveAddedOrCanceled(){
			$(addWrapper + '.wodt-admin-add-so').hide();
			$(addWrapper + '.wodt-admin-header').show();
			$(dttblId + '_wrapper').removeClass('dtHidden');
		}

		function onWodtArriveNewError( jqXHR, textStatus, errorThrown ){
			console.log('Error al intentar enviar un nuevo wodt data set al server.');
			console.log(jqXHR);
		}

		function onWodtArriveNewSuccess(  data,  textStatus,  jqXHR ){
			console.log('Datos enviados al server correctamente.');
			console.log(data);
			dttbl_arrives.ajax.reload();
			
		}

		function onWodtArriveNewComplete( jqXHR, textStatus ){
			setWidgetsForWodtArriveAddedOrCanceled();
		}

		function onWodtArriveDelError( jqXHR, textStatus, errorThrown ){
			console.log('Error al intentar enviar eliminación de wodt data set al server.');
			console.log(jqXHR);
		}

		function onWodtArriveDelSuccess(  data,  textStatus,  jqXHR ){
			console.log('Datos para eliminación enviados al server correctamente.');
			console.log(data);
			dttbl_arrives.ajax.reload();
			
		}

		function onWodtArriveDelComplete( jqXHR, textStatus ){
			
		}

		function sendDeleteArriveRequest(ids){
			var ajxSettings = {
				url: wodt_config.urlRemArrive,
				method: 'DELETE',
				accepts: 'application/json; charset=UTF-8',
				contentType: 'application/json; charset=UTF-8',
				data: JSON.stringify({'ids':ids}),
				complete: onWodtArriveDelComplete,
				success: onWodtArriveDelSuccess,
				error: onWodtArriveDelError
			}

			// Activando animación de proceso.

			// ejecutando AJAX.
			$.ajax(ajxSettings);
		}

		$( addWrapper + ' .wodt-admin-header #add-wodt' ).on('click',function(event){
			arrive_editor_mode = 0; // 0: add
			setWidgetsForWodtArriveAddNew();
		});

		dttbl_arrives.on('draw', function(){
			$( dttblId + ' .actions .edit-wodt' ).off('click');
			$( dttblId + ' .actions .edit-wodt' ).on('click',function(event){
				arrive_editor_mode = 1; // 1: edit
				setWidgetsForWodtArriveAddNew();
				let i = 0;
				const wodtSetId = $(this).parent().parent().parent().attr('id');
				const id = wodtSetId.split('-')[1];
				const dpr = dttbl_arrives.rows().data();
				console.log('Orígenes de salida:');
				console.log(dpr);
				for(i=0; dpr.length; i++){
					if( dpr[i].DT_RowId.split('-')[1] == id ){
						$('#wodt_arrive').val(dpr[i].arrive);
						$('#wodt_arrive_id').val(id);
						break;
					}
				}
			});

			$( dttblId + ' .actions .remove-wodt' ).off('click');
			$( dttblId + ' .actions .remove-wodt' ).on('click',function(event){
				let i = 0;
				const wodtSetId = $(this).parent().parent().parent().attr('id');
				const id = wodtSetId.split('-')[1];
				const dpr = dttbl_arrives.rows().data();
				var idToDelete = [];
				for(i=0; dpr.length; i++){
					if( dpr[i].DT_RowId.split('-')[1] == id ){
						idToDelete.push(id);
						break;
					}
				}

				sendDeleteArriveRequest(idToDelete);
			});

		});

		$( addWrapper + ' .actions-wrapper .cancel' ).on('click',function(event){
			event.preventDefault();
			setWidgetsForWodtArriveAddedOrCanceled();
		});

		$( addWrapper + ' .wodt-admin-add-so .actions-wrapper .save' ).on('click',function(event){
			event.preventDefault();

			// crear datos para enviar.
			var wodtArriveNewData = null;
			var ajxUrl = '';
			var ajxMthd = '';
			switch(arrive_editor_mode){
				case 0:
					wodtArriveNewData = {
						'arrive': $('#wodt_arrive').val(),
					};
					ajxUrl = wodt_config.urlAddArrive;
					ajxMthd = 'POST';
					break;

				case 1:
					wodtArriveNewData = {
						'arrive': $('#wodt_arrive').val(),
						'arrive_id': $('#wodt_arrive_id').val()
					};
					ajxUrl = wodt_config.urlUpdArrive;
					ajxMthd = 'PUT';
					break;
			}

			// preparando la configuración de la llamada a endpoint para crear nuevo wodt.
			var ajxSettings = {
				url: ajxUrl,
				method: ajxMthd,
				accepts: 'application/json; charset=UTF-8',
				contentType: 'application/json; charset=UTF-8',
				data: JSON.stringify(wodtArriveNewData),
				complete: onWodtArriveNewComplete,
				success: onWodtArriveNewSuccess,
				error: onWodtArriveNewError
			}

			// Activando animación de proceso.

			// ejecutando AJAX.
			$.ajax(ajxSettings);

		});

	}

	$(document).ready(function(){
		initialize_departures_section();
		initialize_arrives_section();
		initialize_traves_costs_section();
	});

})( jQuery );
