<?php

class wp_ng_theme {
	
	function enqueue_scripts() {
		
		wp_enqueue_style( 'bootstrapCSS', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css', array(), '1.0', 'all' );
		wp_enqueue_script( 'angular-core', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js', array( 'jquery' ), '1.0', false );
		wp_enqueue_script( 'angular-resource', '//ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular-resource.js', array('angular-core'), '1.0', false );
		wp_enqueue_script( 'ui-router', 'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.2.15/angular-ui-router.min.js', array( 'angular-core' ), '1.0', false );
		wp_enqueue_script( 'ngScripts', get_template_directory_uri() . '/assets/js/angular-theme.js', array( 'ui-router' ), '1.0', false );
		wp_localize_script( 'ngScripts', 'appInfo',
			array(
				
				'api_url'			 => rest_get_url_prefix() . '/wp/v2/',
				'template_directory' => get_template_directory_uri() . '/',
				'nonce'				 => wp_create_nonce( 'wp_rest' ),
				'is_admin'			 => current_user_can('administrator')
				
			)
		);
		
	}

	function register_new_field() {

		register_api_field( 'post',
			'my_awesome_field',
			array(
				'get_callback' => array( $this, 'awesome_field' ) 
			)
		);

	}

	function awesome_field( $object, $field_name, $request ) {

		return 'My Awesome String';

	}

	function my_awesome_route() {

		register_rest_route( 'wp/v2', '/roy', array(
			'methods' => 'GET',
			'callback' => array( $this, 'my_awesome_route_callback' )
			)
		);


	}

	function my_awesome_route_callback( $data ) {

		$new_data = array( 'name' => 'Roy Sivan', 'age' => 29, 'location' => 'Los Angeles' );
		$response = new WP_REST_Response( $new_data );

		return $response;

	}
	


}

$ngTheme = new wp_ng_theme();
add_action( 'wp_enqueue_scripts', array( $ngTheme, 'enqueue_scripts' ) );
add_action( 'rest_api_init', array( $ngTheme, 'register_new_field' ) );
add_action( 'rest_api_init', array( $ngTheme, 'my_awesome_route' ) );

?>