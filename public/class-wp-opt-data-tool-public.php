<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://empdigital.cl
 * @since      1.0.0
 *
 * @package    Wp_Opt_Data_Tool
 * @subpackage Wp_Opt_Data_Tool/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Opt_Data_Tool
 * @subpackage Wp_Opt_Data_Tool/public
 * @author     Jorge Garrido <jegschl@gmail.com>
 */
class Wp_Opt_Data_Tool_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-opt-data-tool-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		$wodt_front_config = array(
			'selectHtmlId' => apply_filters('wodt/front/selectId','.departures-locations'),
			'urlGetCosts'		 => rest_url( '/'. WODT_APIREST_BASE_ROUTE_COSTS .WODT_URI_ID_GET_ODT_SETS . '/' ),
			'parentHtmlId'		=> apply_filters('wodt/front/selecParentEl','#wpcf7-f4527-p4466-o1'),
			'costHtmlId'	=> apply_filters('wodt/front/costEl','.front-costo')
		);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-opt-data-tool-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'wodt_front_config',
			$wodt_front_config
		);
		$script_fl = plugin_dir_url( __FILE__ );
		$script_fl = $script_fl . 'js/libs/jq-blockui/jq-block-ui-2.70.0.js';
		wp_enqueue_script(
			'jq-blockui', 
			$script_fl,
			array('jquery'),
			'2.70.0',
			true
		);
	}

	//add_action( 'rest_api_init', 'emp_set_endpoints');
    public function set_endpoints(){

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

		$isql = "SELECT 
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
                'draw' => $_GET['draw'],
                "recordsTotal" =>  intval($frs->total_rcds),
                "recordsFiltered" => intval($frs->total_rcds),
                'data' => $rc
            );
            $response = new WP_REST_Response( $res );
            $response->set_status( 200 );
            
        } else {
			$res = array(
                'draw' => $_GET['draw'],
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

}
