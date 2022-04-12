<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://empdigital.cl
 * @since      1.0.0
 *
 * @package    Wp_Opt_Data_Tool
 * @subpackage Wp_Opt_Data_Tool/admin
 */

define('HTML_WODT_ID','wodt-data-tbl');
define('WODT_APIREST_BASE_ROUTE_DEPARTURES','wodt/departures/');
define('WODT_APIREST_BASE_ROUTE_ARRIVES','wodt/arrives/');
define('WODT_APIREST_BASE_ROUTE_COSTS','wodt/costs/');
define('WODT_URI_ID_GET_ODT_SETS','list');
define('WODT_URI_ID_ADD_ODT_SET','add');
define('WODT_URI_ID_UPD_ODT_SET','update');
define('WODT_URI_ID_REM_ODT_SET','rem');

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Opt_Data_Tool
 * @subpackage Wp_Opt_Data_Tool/admin
 * @author     Jorge Garrido <jegschl@gmail.com>
 */
class Wp_Opt_Data_Tool_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Opt_Data_Tool_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Opt_Data_Tool_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-opt-data-tool-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Opt_Data_Tool_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Opt_Data_Tool_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-opt-data-tool-admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( 
			$this->plugin_name, 
			'wodt_config',
			array(
				'urlGetDepartures'	 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_DEPARTURES .WODT_URI_ID_GET_ODT_SETS . '/' ),
				'urlAddDeparture'	 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_DEPARTURES .WODT_URI_ID_ADD_ODT_SET . '/' ),
				'urlRemDeparture'	 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_DEPARTURES .WODT_URI_ID_REM_ODT_SET . '/' ),
				'urlUpdDeparture'	 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_DEPARTURES .WODT_URI_ID_UPD_ODT_SET . '/' ),
				'urlGetArrives'		 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_ARRIVES .WODT_URI_ID_GET_ODT_SETS . '/' ),
				'urlAddArrive'		 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_ARRIVES .WODT_URI_ID_ADD_ODT_SET . '/' ),
				'urlRemArrive'		 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_ARRIVES .WODT_URI_ID_REM_ODT_SET . '/' ),
				'urlUpdArrive'		 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_ARRIVES .WODT_URI_ID_UPD_ODT_SET . '/' ),
				'urlGetCosts'		 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_COSTS .WODT_URI_ID_GET_ODT_SETS . '/' ),
				'urlAddCost'		 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_COSTS .WODT_URI_ID_ADD_ODT_SET . '/' ),
				'urlRemCost'		 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_COSTS .WODT_URI_ID_REM_ODT_SET . '/' ),
				'urlUpdCost'		 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_COSTS .WODT_URI_ID_UPD_ODT_SET . '/' )
			) 
		);
		
		$script_fl = 'https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js';
		wp_enqueue_script(
			'wodt_jquery_datatable', 
			$script_fl,
			array('jquery'),
			null,
			false
		);

		$script_fl = 'https://kit.fontawesome.com/ca63732f24.js';
		wp_enqueue_script(
			'wodt_font_awesome', 
			$script_fl,
			array(),
			null,
			false
		);

		$script_fl = plugin_dir_url( __FILE__ );
		$script_fl = substr($script_fl,0,strpos($script_fl,'/admin'));
		$script_fl = $script_fl . '/public/js/libs/jq-blockui/jq-block-ui-2.70.0.js';
		wp_enqueue_script(
			'jq-blockui', 
			$script_fl,
			array('jquery'),
			'2.70.0',
			true
		);
	}

	//add_filter( 'script_loader_tag', 'my_script_loader_tag', 10 ,2 );
	public function script_loader_tag( $tag, $handle ){
		if ( $handle == 'wodt_font_awesome' ) {
			return str_replace( '<script', '<script crossorigin="anonymous"', $tag );
		}
		return $tag;    
	}

	public function wodt_menu() {
		add_menu_page( 
			'distancias y valores', 
			'Distancias y Valores', 
			'manage_options', 
			'wodt-admin',  //'wodt/wodt-admin.php', 
			array($this,'wodt_admin_page'), 
			'dashicons-forms', 
			11
		);
	}

	/* renderiza el contenido de la pgina de configuracin de ads-imgs */
	public function wodt_admin_page(){
		?>
		<!-- <form action='options.php' method='post'> -->

			<h2>Distancias y valores</h2>
			<div class="wodt-admin-main-container">
			<?php

			$this->file_data_set_stack_field_render()
	
			?>
			</div>
		<!-- </form> -->
		<?php
	}

	public function wodt_update_option( $data ) {
		
		$i=0;
		$data = array();
		for($i = 0; $i < count($_POST['img_url']); $i++){
			$data[] = array(
				'img_url' => $_POST['img_url'][$i],
				'ads_url' => $_POST['ads_url'][$i]
			);
		}
		return $data;
	}

	//add_action( 'admin_init', 'wodt_settings_init' );
	public function wodt_settings_init(){
		
		register_setting( 
			'wodt', 
			'wodt_settings', 
			array(
				'sanitize_callback' => array(
					$this,
					'wodt_update_option'
				)
			) 
		);
		
		add_settings_section(
			'wodt_section',
			'Indicar archivos de la librería para compartir o distribuir',
			array($this,'wodt_settings_section_header'),
			'wodt'
		);
		
		add_settings_field(
			'file_data_set',
			'',
			array($this,'file_data_set_stack_field_render'),
			'wodt',
			'wodt_section'
		);
	}

	public function wodt_settings_section_header(){
		echo __( 'Datos para compartir o distribuir archivos', 'wp-wodt' );
	}

	private function render_departures_section(){
		?>
		<div class="wodt-departures-wrapper">
			<div class="wodt-admin-header">
				
				<div id="add-wodt" class="action-wrapper"><i class="fas fa-plus-circle"></i>Agregar nuevo origen</div>
				<div id="rem-wodt" class="action-wrapper"><i class="fas fa-minus-circle"></i>Remover seleccionados</div>
						
			</div>

			<div class="wodt-admin-add-so" style="display: none;">
				<div class="fields-wrapper">
					<input type="hidden" id="wodt_departure_id" name="wodt_departure_id" val="" />
					<div class="fld-title">
						<label>Comuna o localidad</label>
						<input name="wodt_departure" id="wodt_departure" type="text" />
					</div>
				</div>
				<div class="actions-wrapper">
					<div class="save"><button>Guardar</button></div>
					<div class="cancel"><button>Cancelar</button></div>
				</div>
			</div>

			<div id="<?=HTML_WODT_DEPARTURES_ID?>">
				
				<table id="departures-dttbl" class="display" style="width:100%">
					
					<thead class="thead">
						<tr class="tr">
							<th>Seleccionar</th>						
							<th>Comuna o localidad</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<!--body-->
					<tfoot>
						<tr class="tr">
							<th>Seleccionar</th>
							<th>Comuna o localidad</th>
							<th>Acciones</th>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>
		
		<?php
	}

	private function render_arrives_section(){
		?>
		<div class="wodt-arrives-wrapper">
			<div class="wodt-admin-header">
				
				<div id="add-wodt" class="action-wrapper"><i class="fas fa-plus-circle"></i>Agregar nuevo destino</div>
				<div id="rem-wodt" class="action-wrapper"><i class="fas fa-minus-circle"></i>Remover seleccionados</div>
						
			</div>

			<div class="wodt-admin-add-so" style="display: none;">
				<div class="fields-wrapper">
					<div class="fld-title">
						<input type="hidden" id="wodt_arrive_id" name="wodt_arrive_id" val="" />
						<label>Comuna o localidad</label>
						<input name="wodt_arrive" id="wodt_arrive" type="text" />
					</div>
				</div>
				<div class="actions-wrapper">
					<div class="save"><button>Guardar</button></div>
					<div class="cancel"><button>Cancelar</button></div>
				</div>
			</div>

			<div id="<?=HTML_WODT_DEPARTURES_ID?>">
				
				<table id="arrives-dttbl" class="display" style="width:100%">
					
					<thead class="thead">
						<tr class="tr">
							<th>Seleccionar</th>						
							<th>Comuna o localidad</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<!--body-->
					<tfoot>
						<tr class="tr">
							<th>Seleccionar</th>
							<th>Comuna o localidad</th>
							<th>Acciones</th>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>

		<?php
	}

	private function render_costs_section(){
		?>
		<div class="wodt-costs-wrapper">
			<div class="wodt-admin-header">
				
				<div id="add-wodt" class="action-wrapper"><i class="fas fa-plus-circle"></i>Agregar nuevo valor de distancia</div>
				<div id="rem-wodt" class="action-wrapper"><i class="fas fa-minus-circle"></i>Remover seleccionados</div>
						
			</div>

			<div class="wodt-admin-add-so" style="display: none;">
				<div class="fields-wrapper">
					<input type="hidden" val="" id="wodt_costo_id">
					<div id="wodt_origen_wrapper" class="fld-so">
						<label>Seleccionar origen</label>
						<select id="wodt_origen" name="wodt_origen">
							<option val="">Seleccione una comuna o localidad de origen</option>
						</select>
						<input type='hidden' name='wodt_origent_id' id='wodt_origen_id' value='' />
					</div>
					<div id="wodt_destino_wrapper" class="fld-so">
						<label>Seleccionar destino</label>
						<select id="wodt_destino" name="wodt_destino">
							<option val="">Seleccione una comuna o localidad de destino</option>
						</select>
						<input type='hidden' name='wodt_destino_id' id='wodt_destino_id' value='' />
					</div>
					
					<div class="fld-so">
						<label>Indique el costo</label>
						<input id="wodt_costo" name="wodt_costo" placeholder="Costo de distancia">
					</div>
				</div>
				<div class="actions-wrapper">
					<div class="save"><button>Guardar</button></div>
					<div class="cancel"><button>Cancelar</button></div>
				</div>
			</div>

			<div id="<?=HTML_WODT_ID?>">
				
				<table id="costs-dttbl" class="display" style="width:100%">
					
					<thead class="thead">
						<tr class="tr">
							<th>Seleccionar</th>						
							<th>Origen</th>
							<th>Destino</th>
							<th>Valor</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<!--body-->
					<tfoot>
						<tr class="tr">
							<th>Seleccionar</th>
							<th>Origen</th>
							<th>Destino</th>
							<th>Valor</th>
							<th>Acciones</th>
						</tr>
					</tfoot>
				</table>

			</div>
		</div>

		<?php
	}

	public function file_data_set_stack_field_render(){
		$this->render_departures_section();
		$this->render_arrives_section();
		$this->render_costs_section();
	}

	//add_action( 'rest_api_init', 'emp_set_endpoints');
    public function set_endpoints(){
        register_rest_route(
            WODT_APIREST_BASE_ROUTE_DEPARTURES,
            '/'. WODT_URI_ID_GET_ODT_SETS . '/',
            array(
                'methods'  => 'GET',
                'callback' => array(
                    $this,
                    'send_wodt_departures'
                ),
                'permission_callback' => '__return_true'
            )
        );

        register_rest_route(
            WODT_APIREST_BASE_ROUTE_DEPARTURES,
            '/'.WODT_URI_ID_ADD_ODT_SET.'/',
            array(
                'methods'  => 'POST',
                'callback' => array(
                    $this,
                    'receive_new_wodt_departure'
                ),
                'permission_callback' => '__return_true',
            )
        );

		register_rest_route(
            WODT_APIREST_BASE_ROUTE_DEPARTURES,
            '/'.WODT_URI_ID_UPD_ODT_SET.'/',
            array(
                'methods'  => 'PUT',
                'callback' => array(
                    $this,
                    'receive_update_wodt_departure'
                ),
                'permission_callback' => '__return_true',
            )
        );

		register_rest_route(
            WODT_APIREST_BASE_ROUTE_DEPARTURES,
            '/'.WODT_URI_ID_REM_ODT_SET.'/',
            array(
                'methods'  => 'DELETE',
                'callback' => array(
                    $this,
                    'receive_remove_wodt_departures'
                ),
                'permission_callback' => '__return_true',
            )
        );

		register_rest_route(
            WODT_APIREST_BASE_ROUTE_ARRIVES,
            '/'. WODT_URI_ID_GET_ODT_SETS . '/',
            array(
                'methods'  => 'GET',
                'callback' => array(
                    $this,
                    'send_wodt_arrives'
                ),
                'permission_callback' => '__return_true'
            )
        );

        register_rest_route(
            WODT_APIREST_BASE_ROUTE_ARRIVES,
            '/'.WODT_URI_ID_ADD_ODT_SET.'/',
            array(
                'methods'  => 'POST',
                'callback' => array(
                    $this,
                    'receive_new_wodt_arrive'
                ),
                'permission_callback' => '__return_true',
            )
        );

		register_rest_route(
            WODT_APIREST_BASE_ROUTE_ARRIVES,
            '/'.WODT_URI_ID_UPD_ODT_SET.'/',
            array(
                'methods'  => 'PUT',
                'callback' => array(
                    $this,
                    'receive_update_wodt_arrive'
                ),
                'permission_callback' => '__return_true',
            )
        );

		register_rest_route(
            WODT_APIREST_BASE_ROUTE_ARRIVES,
            '/'.WODT_URI_ID_REM_ODT_SET.'/',
            array(
                'methods'  => 'DELETE',
                'callback' => array(
                    $this,
                    'receive_remove_wodt_arrives'
                ),
                'permission_callback' => '__return_true',
            )
        );

		register_rest_route(
            WODT_APIREST_BASE_ROUTE_COSTS,
            '/'. WODT_URI_ID_GET_ODT_SETS . '/',
            array(
                'methods'  => 'GET',
                'callback' => array(
                    $this,
                    'send_wodt_costs'
                ),
                'permission_callback' => '__return_true'
            )
        );

        register_rest_route(
            WODT_APIREST_BASE_ROUTE_COSTS,
            '/'.WODT_URI_ID_ADD_ODT_SET.'/',
            array(
                'methods'  => 'POST',
                'callback' => array(
                    $this,
                    'receive_new_wodt_cost'
                ),
                'permission_callback' => '__return_true',
            )
        );

		register_rest_route(
            WODT_APIREST_BASE_ROUTE_COSTS,
            '/'.WODT_URI_ID_UPD_ODT_SET.'/',
            array(
                'methods'  => 'PUT',
                'callback' => array(
                    $this,
                    'receive_update_wodt_cost'
                ),
                'permission_callback' => '__return_true',
            )
        );

		register_rest_route(
            WODT_APIREST_BASE_ROUTE_COSTS,
            '/'.WODT_URI_ID_REM_ODT_SET.'/',
            array(
                'methods'  => 'DELETE',
                'callback' => array(
                    $this,
                    'receive_remove_wodt_costs'
                ),
                'permission_callback' => '__return_true',
            )
        );
    }

	public function send_wodt_departures($r){
		global $wpdb;
		
		$limit = '';
		if(isset($_GET['length']) && $_GET['length']>0)
            $limit = ' LIMIT ' . $_GET['start'] . ',' . $_GET['length'];
        
		$where = '';
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $sv = $_GET['search']['value'];
            $where  = ' WHERE departure LIKE "%'. $sv . '%"';
        }

		$isql = "SELECT SQL_CALC_FOUND_ROWS
					wdso.id,
					departure
				FROM wp_wodt_departures wdso 
				$where 
				ORDER BY wdso.departure
				$limit";
		$qry = 'SELECT FOUND_ROWS() AS total_rcds';
		
		$sos = $wpdb->get_results($isql, OBJECT);
		$frs = $wpdb->get_row($qry, OBJECT);
        
		$rc = array();
        
        foreach($sos as $c){
            $rc[] = array(
				'DT_RowId'	  => 'departure-'.$c->id,
				'departure'	  => $c->departure,
				'selection'	  => '',
				'actions'	  => ''
            );
        }

        if($sos && empty($wpdb->last_error) ){
            $res = array(
                'draw' => isset($_GET['draw'])?$_GET['draw']:'',
                "recordsTotal" =>  intval($frs->total_rcds),
                "recordsFiltered" => intval($frs->total_rcds),
                'data' => $rc
            );
            $response = new WP_REST_Response( $res );
            $response->set_status( 200 );
            
        } else {
			$res = array(
                'draw' => isset($_GET['draw'])?$_GET['draw']:'',
                "recordsTotal" =>  intval($frs->total_rcds),
                "recordsFiltered" => intval($frs->total_rcds),
                'data' => array(),
				//'error' => new WP_Error( 'cant-read-dosf-sos', __( 'Can\'t get shared objects', 'wp-dosf' ), array( 'status' => 500 ) )
            );
            $response = new WP_REST_Response( $res );
            $response->set_status( 200 );
        }
        return $response;
	}

	public function receive_new_wodt_departure($r){
		$data = $r->get_json_params();
		// validaciones del lado del server.
		if(!isset($data['departure'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de origen vacía'
			];
		}

		if(empty($data['departure'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de origen vacía'
			];
		}
		
		global $wpdb;
		$tbl_nm_departures = $wpdb->prefix . 'wodt_departures';

		// verificando si ya existe una locación con el mismo nombre (case insensitive).
		$isql = "SELECT departure FROM $tbl_nm_departures
				WHERE LOWER(departure) = LOWER('" . $data['departure'] . "')";
		$qr = $wpdb->get_results($isql,OBJECT);
		if(count($qr)>0){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Locación de origen ya existe'
			];
		}

		$wpdb->insert(
			$tbl_nm_departures,
			array(
				'departure' 		 => $data['departure']
			)
		);

		return ['wodtAddNew_post_status' => 'ok'];
	}

	public function receive_update_wodt_departure($r){
		$data = $r->get_json_params();
		// validaciones del lado del server.
		if(!isset($data['departure'])){
			return [
				'wodtUpdate_put_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de origen vacía'
			];
		}

		if(empty($data['departure'])){
			return [
				'wodtUpdate_put_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de origen vacía'
			];
		}

		if(!isset($data['departure_id'])){
			return [
				'wodtUpdate_put_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de locación de origen vacía'
			];
		}

		if(empty($data['departure_id'])){
			return [
				'wodtUpdate_put_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de locación de origen vacía'
			];
		}

		global $wpdb;
		$tbl_nm_departures = $wpdb->prefix . 'wodt_departures';

		// verificando si ya existe una locación con el mismo nombre (case insensitive).

		$wpdb->update(
			$tbl_nm_departures,
			array(
				'departure' => $data['departure']
			),
			array(
				'id'		=> $data['departure_id']
			)
		);

		return ['wodtUpdate_put_status' => 'ok'];
	}

	public function receive_remove_wodt_departures($r){
		$data = $r->get_json_params();
		// validaciones del lado del server.
		

		if(!isset($data['ids'])){
			return [
				'wodtRemove_del_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de locación de origen vacía'
			];
		}

		if(empty($data['ids'])){
			return [
				'wodtRemove_del_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de locación de origen vacía'
			];
		}

		global $wpdb;
		$tbl_nm_departures = $wpdb->prefix . 'wodt_departures';

		// verificando si ya existe una locación con el mismo nombre (case insensitive).
		foreach($data['ids'] as $id){
			$wpdb->delete(
				$tbl_nm_departures,
				array(
					'id'		=> $id
				)
			);
		}

		return ['wodtRemove_del_status' => 'ok'];
	}

	public function send_wodt_arrives($r){
		global $wpdb;
		
		$limit = '';
		if(isset($_GET['length']) && $_GET['length']>0)
            $limit = ' LIMIT ' . $_GET['start'] . ',' . $_GET['length'];
        
		$where = '';
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $sv = $_GET['search']['value'];
            $where  = ' WHERE arrive LIKE "%'. $sv . '%"';
        }

		$isql = "SELECT SQL_CALC_FOUND_ROWS
					wdso.id,
					arrive
				FROM wp_wodt_arrives wdso 
				$where 
				ORDER BY wdso.arrive
				$limit";
		$qry = 'SELECT FOUND_ROWS() AS total_rcds';
		
		$sos = $wpdb->get_results($isql, OBJECT);
		$frs = $wpdb->get_row($qry, OBJECT);
        
		$rc = array();
        
        foreach($sos as $c){
            $rc[] = array(
				'DT_RowId'	  => 'arrive-'.$c->id,
				'arrive'	  => $c->arrive,
				'selection'	  => '',
				'actions'	  => ''
            );
        }

        if($sos && empty($wpdb->last_error) ){
            $res = array(
                'draw' => isset($_GET['draw'])?$_GET['draw']:'',
                "recordsTotal" =>  intval($frs->total_rcds),
                "recordsFiltered" => intval($frs->total_rcds),
                'data' => $rc
            );
            $response = new WP_REST_Response( $res );
            $response->set_status( 200 );
            
        } else {
			$res = array(
                'draw' => isset($_GET['draw'])?$_GET['draw']:'',
                "recordsTotal" =>  intval($frs->total_rcds),
                "recordsFiltered" => intval($frs->total_rcds),
                'data' => array(),
				//'error' => new WP_Error( 'cant-read-dosf-sos', __( 'Can\'t get shared objects', 'wp-dosf' ), array( 'status' => 500 ) )
            );
            $response = new WP_REST_Response( $res );
            $response->set_status( 200 );
        }
        return $response;
	}

	public function receive_new_wodt_arrive($r){
		$data = $r->get_json_params();
		// validaciones del lado del server.
		if(!isset($data['arrive'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de destino vacía'
			];
		}

		if(empty($data['arrive'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de destino vacía'
			];
		}
		
		global $wpdb;
		$tbl_nm_arrives = $wpdb->prefix . 'wodt_arrives';

		// verificando si ya existe una locación con el mismo nombre (case insensitive).
		$isql = "SELECT arrive FROM $tbl_nm_arrives
				WHERE LOWER(arrive) = LOWER('" . $data['arrive'] . "')";
		$qr = $wpdb->get_results($isql,OBJECT);
		if(count($qr)>0){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Locación de destino ya existe'
			];
		}

		$wpdb->insert(
			$tbl_nm_arrives,
			array(
				'arrive' 		 => $data['arrive']
			)
		);

		return ['wodtAddNew_post_status' => 'ok'];
	}

	public function receive_update_wodt_arrive($r){
		$data = $r->get_json_params();
		// validaciones del lado del server.
		if(!isset($data['arrive'])){
			return [
				'wodtUpdate_put_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de destino vacía'
			];
		}

		if(empty($data['arrive'])){
			return [
				'wodtUpdate_put_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de destino vacía'
			];
		}

		if(!isset($data['arrive_id'])){
			return [
				'wodtUpdate_put_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de locación de destino vacía'
			];
		}

		if(empty($data['arrive_id'])){
			return [
				'wodtUpdate_put_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de locación de destino vacía'
			];
		}

		global $wpdb;
		$tbl_nm_arrives = $wpdb->prefix . 'wodt_arrives';

		// verificando si ya existe una locación con el mismo nombre (case insensitive).

		$wpdb->update(
			$tbl_nm_arrives,
			array(
				'arrive' => $data['arrive']
			),
			array(
				'id'		=> $data['arrive_id']
			)
		);

		return ['wodtUpdate_put_status' => 'ok'];
	}

	public function receive_remove_wodt_arrives($r){
		$data = $r->get_json_params();
		// validaciones del lado del server.
		

		if(!isset($data['ids'])){
			return [
				'wodtRemove_del_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de locación de destino vacía'
			];
		}

		if(empty($data['ids'])){
			return [
				'wodtRemove_del_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de locación de destino vacía'
			];
		}

		global $wpdb;
		$tbl_nm_arrives = $wpdb->prefix . 'wodt_arrives';

		// verificando si ya existe una locación con el mismo nombre (case insensitive).
		foreach($data['ids'] as $id){
			$wpdb->delete(
				$tbl_nm_arrives,
				array(
					'id'		=> $id
				)
			);
		}

		return ['wodtRemove_del_status' => 'ok'];
	}



	public function send_wodt_costs($r){
		global $wpdb;
		
		$limit = '';
		if(isset($_GET['length']) && $_GET['length']>0)
            $limit = ' LIMIT ' . $_GET['start'] . ',' . $_GET['length'];
        
		$where = '';
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $sv = $_GET['search']['value'];
            $where  = ' WHERE 
							wwd.departure LIKE "%'. $sv . '%"
							OR wwa.arrive LIKE "%'. $sv . '%"';
        }

		$isql = "SELECT SQL_CALC_FOUND_ROWS
					wdso.id,
					wwd.departure AS departure,
					wwa.arrive AS arrive,
					cost
				FROM wp_wodt_costs wdso 
				INNER JOIN wp_wodt_departures wwd
					ON wwd.id = wdso.departure_id
				INNER JOIN wp_wodt_arrives wwa
					ON wwa.id = wdso.arrive_id
				$where 
				$limit";
		$qry = 'SELECT FOUND_ROWS() AS total_rcds';
		
		$sos = $wpdb->get_results($isql, OBJECT);
		$frs = $wpdb->get_row($qry, OBJECT);
        
		$rc = array();
        
        foreach($sos as $c){
            $rc[] = array(
				'DT_RowId'	  => 'cost-'.$c->id,
				'departure'	  => $c->departure,
				'arrive'	  => $c->arrive,
				'cost'	  	  => $c->cost,
				'selection'	  => '',
				'actions'	  => ''
            );
        }

        if($sos && empty($wpdb->last_error) ){
            $res = array(
                'draw' => isset($_GET['draw'])?$_GET['draw']:'',
                "recordsTotal" =>  intval($frs->total_rcds),
                "recordsFiltered" => intval($frs->total_rcds),
                'data' => $rc
            );
            $response = new WP_REST_Response( $res );
            $response->set_status( 200 );
            
        } else {
			$res = array(
                'draw' => isset($_GET['draw'])?$_GET['draw']:'',
                "recordsTotal" =>  intval($frs->total_rcds),
                "recordsFiltered" => intval($frs->total_rcds),
                'data' => array(),
				//'error' => new WP_Error( 'cant-read-dosf-sos', __( 'Can\'t get shared objects', 'wp-dosf' ), array( 'status' => 500 ) )
            );
            $response = new WP_REST_Response( $res );
            $response->set_status( 200 );
        }
        return $response;
	}

	public function receive_new_wodt_cost($r){
		$data = $r->get_json_params();
		// validaciones del lado del server.
		if(!isset($data['cost'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Costo vacío'
			];
		}

		if(empty($data['cost'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Costo vacío'
			];
		}

		if(!isset($data['departure_id'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de origen vacía'
			];
		}

		if(empty($data['departure_id'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de origen vacía'
			];
		}

		if(!isset($data['arrive_id'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de destino vacía'
			];
		}

		if(empty($data['arrive_id'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de destino vacía'
			];
		}
		
		global $wpdb;
		$tbl_nm_costs = $wpdb->prefix . 'wodt_costs';

		// verificando si ya existe un costo con los mismos valores.
		$isql = "SELECT cost FROM $tbl_nm_costs
				 WHERE cost = " . intval($data['cost']) . "
					  AND departure_id = " . intval($data['departure_id']) . "
					  AND arrive_id = ". intval($data['arrive_id']);
		$qr = $wpdb->get_results($isql,OBJECT);
		if(count($qr)>0){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Costo ya existe'
			];
		}

		$wpdb->insert(
			$tbl_nm_costs,
			array(
				'cost' 		 	=> intval($data['cost']),
				'departure_id'	=> intval($data['departure_id']),
				'arrive_id'		=> intval($data['arrive_id'])
			)
		);

		return ['wodtAddNew_post_status' => 'ok'];
	}

	public function receive_update_wodt_cost($r){
		$data = $r->get_json_params();
		// validaciones del lado del server.
		if(!isset($data['cost'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Costo vacío'
			];
		}

		if(empty($data['cost'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Costo vacío'
			];
		}

		if(!isset($data['departure_id'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de origen vacía'
			];
		}

		if(empty($data['departure_id'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de origen vacía'
			];
		}

		if(!isset($data['arrive_id'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de destino vacía'
			];
		}

		if(empty($data['arrive_id'])){
			return [
				'wodtAddNew_post_status' => 'error',
				'error_id' 				 => 1,
				'erro_msg'				 => 'Locación de destino vacía'
			];
		}

		global $wpdb;
		$tbl_nm_costs = $wpdb->prefix . 'wodt_costs';

		// verificando si ya existe una locación con el mismo nombre (case insensitive).

		$wpdb->update(
			$tbl_nm_costs,
			array(
				'departure_id'	=> intval($data['departure_id']),
				'arrive_id'		=> intval($data['arrive_id']),
				'cost' 			=> intval($data['cost'])
			),
			array(
				'id'		=> $data['cost_id']
			)
		);

		return ['wodtUpdate_put_status' => 'ok'];
	}

	public function receive_remove_wodt_costs($r){
		$data = $r->get_json_params();
		// validaciones del lado del server.
		

		if(!isset($data['ids'])){
			return [
				'wodtRemove_del_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de costo vacío'
			];
		}

		if(empty($data['ids'])){
			return [
				'wodtRemove_del_status' => 'error',
				'error_id' 				 => 2,
				'erro_msg'				 => 'Id de costo vacío'
			];
		}

		global $wpdb;
		$tbl_nm_costs = $wpdb->prefix . 'wodt_costs';

		// verificando si ya existe una locación con el mismo nombre (case insensitive).
		foreach($data['ids'] as $id){
			$wpdb->delete(
				$tbl_nm_costs,
				array(
					'id'		=> $id
				)
			);
		}

		return ['wodtRemove_del_status' => 'ok'];
	}
}
