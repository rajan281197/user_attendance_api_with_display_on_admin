<?php
/**
 * moon-technolabs functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package moon-technolabs
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function moon_technolabs_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on moon-technolabs, use a find and replace
		* to change 'moon-technolabs' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'moon-technolabs', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'moon-technolabs' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'moon_technolabs_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'moon_technolabs_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function moon_technolabs_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'moon_technolabs_content_width', 640 );
}
add_action( 'after_setup_theme', 'moon_technolabs_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function moon_technolabs_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'moon-technolabs' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'moon-technolabs' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'moon_technolabs_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function moon_technolabs_scripts() {
	wp_enqueue_style( 'moon-technolabs-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'moon-technolabs-style', 'rtl', 'replace' );

	wp_enqueue_script( 'moon-technolabs-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDPj5ClObVWPZ8WJxxl8v5HRaZSniDz2gg', array('jquery'), null, true);

	wp_enqueue_script( 'dealerlocator-ajax', get_template_directory_uri() . '/js/dealerlocator-ajax.js', array('jquery'), null, true );
	$my_nonce = wp_create_nonce( 'store-locator-action' );

	wp_localize_script( 'dealerlocator-ajax', 'dealerlocator_ajax_object',
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => $my_nonce,
		)
	);



	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'moon_technolabs_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Register post type of dealer
 *
 * @return void
 */
function register_dealer_post_type() {
    $labels = array(
        'name'               => __('Dealers', 'text-domain'),
        'singular_name'      => __('Dealer', 'text-domain'),
        'add_new'            => __('Add New', 'text-domain'),
        'add_new_item'       => __('Add New Dealer', 'text-domain'),
        'edit_item'          => __('Edit Dealer', 'text-domain'),
        'new_item'           => __('New Dealer', 'text-domain'),
        'view_item'          => __('View Dealer', 'text-domain'),
        'search_items'       => __('Search Dealers', 'text-domain'),
        'not_found'          => __('No dealers found', 'text-domain'),
        'not_found_in_trash' => __('No dealers found in Trash', 'text-domain'),
        'parent_item_colon'  => '',
        'menu_name'          => __('Dealers', 'text-domain')
    );
    
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'dealers'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail')
    );
    
    register_post_type('dealer', $args);
}
add_action('init', 'register_dealer_post_type');

add_action( 'wp_ajax_get_dealer_locater', 'get_dealer_locater' );
add_action( 'wp_ajax_nopriv_get_dealer_locater', 'get_dealer_locater' );

function get_dealer_locater(){

	if ( ! isset( $_POST['dealerlocator_ajax_nonce'] ) || ! wp_verify_nonce( $_POST['dealerlocator_ajax_nonce'], 'store-locator-action' ) ) {
        wp_send_json_error( 'Invalid nonce' );
        exit;
    }

	$zipcode = sanitize_text_field( $_POST['zipcode'] );
	$response_data = array();
	// Get the latitude and longitude of the zip code using the Google Maps API
	$geocode_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($zipcode) . "&key=AIzaSyDPj5ClObVWPZ8WJxxl8v5HRaZSniDz2gg";
	$geocode_json = file_get_contents($geocode_url);
	$geocode_data = json_decode($geocode_json);
	$lat = $geocode_data->results[0]->geometry->location->lat;
	$lng = $geocode_data->results[0]->geometry->location->lng;

	// Calculate the minimum and maximum latitude and longitude for the search radius
	// $earth_radius = 3960; // miles
	// $search_radius = 50; // miles
	// $lat_range = rad2deg($search_radius / $earth_radius);
	// $lng_range = rad2deg($search_radius / $earth_radius / cos(deg2rad($lat)));
	// $min_lat = $lat - $lat_range;
	// $max_lat = $lat + $lat_range;
	// $min_lng = $lng - $lng_range;
	// $max_lng = $lng + $lng_range;

	// Loop through all posts of desired post type that have a zipcode ACF field
	$args = array(
		'post_type' => 'dealer',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'zipcode',
				'compare' => 'EXISTS',
			),
		),
	);
	$query = new WP_Query($args); ?>

		<?php if ( $query->have_posts() ) : ?>
			<!-- Use Google Maps API to calculate distance between user and each post -->
			<div class="outer-loop-location">
				<?php while ($query->have_posts()) : $query->the_post(); ?> <?php
					$post_latitude = get_field('latitude');
					$post_longitude = get_field('longitude');
					$distance = distance($lat, $lng, $post_latitude, $post_longitude);
					// If distance is less than 50 miles, include post in results
					if ($distance < 50) {
						// Do something with the post
						$post_id = get_the_ID();
						?>
						<div class='store-locator-listing'>
							<h2><?php the_title(); ?></h2>
							<p class="locator_address"><?php echo get_field('address',$post_id); ?></p>
							<p class="locator_zipcode"><?php echo get_field('zipcode',$post_id); ?></p>
							<p class="locator_latitude"><?php echo get_field('latitude',$post_id); ?></p>
							<p class="locator_longitude"><?php echo get_field('longitude',$post_id); ?></p>
							<?php 
							$dealers_profile = get_field('dealers_profile',$post_id);
							if( !empty( $dealers_profile ) ): ?>
								<img src="<?php echo esc_url($dealers_profile['url']); ?>" alt="<?php echo esc_attr($dealers_profile['alt']); ?>" />
							<?php endif; ?>
						</div>
						<?php
						$my_data[] = array(
							'p_id' 			=> get_the_ID(),
							'p_zipcode' 	=> get_field('zipcode',$post_id),
							'p_address' 	=> get_field('address',$post_id),
							'p_latitude' 	=> get_field('latitude',$post_id),
							'p_longitude' 	=> get_field('longitude',$post_id),
						);
					} ?>
				<?php endwhile;

				
				
				$response_data = array(
					'result' => 'success',
					'message' => 'Ajax request successful!',
					'data' => $my_data,
				 );
				 wp_send_json($response_data);

				?>
			</div>
				<!-- end of the loop -->

					<!-- pagination here -->

					<?php wp_reset_postdata(); ?>

				<?php else : ?>
					<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
		<?php endif; ?>

		<?php
	wp_die();
}

// Function to calculate distance between two sets of coordinates
function distance($lat1, $lon1, $lat2, $lon2) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return $miles;
	
}




// function distance_calculation($lat1, $lon1, $lat2, $lon2) {
// 	$earth_radius = 3959; // in miles
// 	$delta_lat = deg2rad($lat2 - $lat1);
// 	$delta_lon = deg2rad($lon2 - $lon1);
// 	$a = sin($delta_lat/2) * sin($delta_lat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($delta_lon/2) * sin($delta_lon/2);
// 	$c = 2 * atan2(sqrt($a), sqrt(1-$a));
// 	$distance_calculation = $earth_radius * $c;
// 	return $distance_calculation;
//   }
  
//   $zip1 = "382480";
//   $zip2 = "382350";
  
//   // Fetch the lat/long coordinates for each zip code
//   $zip1_coords = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" . $zip1 . "&key=AIzaSyDPj5ClObVWPZ8WJxxl8v5HRaZSniDz2gg");
//   $zip1_coords = json_decode($zip1_coords, true);
//   $lat1 = $zip1_coords["results"][0]["geometry"]["location"]["lat"];
//   $lon1 = $zip1_coords["results"][0]["geometry"]["location"]["lng"];
  
//   $zip2_coords = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" . $zip2 . "&key=AIzaSyDPj5ClObVWPZ8WJxxl8v5HRaZSniDz2gg");
//   $zip2_coords = json_decode($zip2_coords, true);
//   $lat2 = $zip2_coords["results"][0]["geometry"]["location"]["lat"];
//   $lon2 = $zip2_coords["results"][0]["geometry"]["location"]["lng"];
  
//   // Calculate the distance_calculation between the two coordinates
//   $distance_calculation = distance_calculation($lat1, $lon1, $lat2, $lon2);
  
//   echo "The distance_calculation between zip codes " . $zip1 . " and " . $zip2 . " is " . round($distance_calculation, 2) . " miles.";
  
// Add the two new user roles
function add_custom_roles() {
    add_role( 'business', 'Business', array( 'read' => true ) );
    add_role( 'student', 'Student', array( 'read' => true ) );

	if(!is_admin()) {

	// Create a new user with the "business" role
	$user_id = wp_insert_user( array(
		'user_login' => 'haritp',
		'user_pass' => 'Harit@123',
		'user_email' => 'Harit@example.com',
		'role' => 'business'
	) );

	// Update the ACF field with the referral ID
		$referral_id = wp_generate_password(16, false); // Your referral ID here
		// echo $referral_id;
		if ( ! get_user_meta( $user_id, 'referal_id' ) ) {
			update_user_meta( $user_id, 'referal_id', $referral_id,false );
		}
		if ( ! get_user_meta( $user_id, 'user_purchase_count' ) ) {
			update_user_meta( $user_id, 'user_purchase_count', '200',false );
		}
		$business_user_referral_id =  get_user_meta( 32, 'referal_id',false  );
		$business_user_purchased_count =  get_user_meta( 32, 'user_purchase_count',false  );
	// create a new user with the 'student' user role
	$user_data = array(
		'user_login' => 'bhavesh', // set the username
		'user_pass' => 'bhavesh', // set the password
		'user_email' => 'bhavesh@email.com', // set the user email
		'role' => 'student' // set the user role to 'student'
	);
	$student_user_id = wp_insert_user( $user_data );

	// retrieve the user ID of the business role user with the referral ID

	// update the user meta of the generated student user to store the referral ID of the business user
	update_user_meta( $student_user_id, 'referal_id', $business_user_referral_id,false );

	// print_r($business_user_purchased_count);
	$string = implode(',', $business_user_purchased_count);
	// echo $string; // Output: "200"


	if (!is_wp_error($student_user_id)) {
			if ($string > 0) {
			$string--; // Reduce the user_purchase_count meta field value by 1
			update_user_meta(32, 'user_purchase_count', $string); // Update the user_purchase_count meta field value for the Business User
			}
	}

	$get_student_info = array(
		'role' => 'student',	
	 );


	 $user_query = new WP_User_Query( $get_student_info );

if ( ! empty( $user_query->results ) ) {
    foreach ( $user_query->results as $user ) {
       
		;
		// print_r($user);
		$test = get_user_meta( $user->ID, 'referal_id', true );
		
		if ($business_user_referral_id = $test) {
			// echo "<pre>";
			// print_r($test);
			// print_r($user->ID);
			// print_r($user->display_name);
			// echo "</pre>";
		}
		
    }
} else {
    echo 'No users found.';
}

	}
}


add_action( 'init', 'add_custom_roles' );

/**
 * API Route created.
 */
function user_follow_unfollow_api_callback() {
	register_rest_route('auth', '/follow', array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'follow_user_by_user_id',
	));
	register_rest_route('auth', '/tossselect', array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'toss_selection_by_user',
	));
	register_rest_route('auth', '/delivery_estimation_calculation', array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'get_estimation_between_two_distance_endpoints',
	));
	register_rest_route('auth', '/user_attendance', array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'triggered_user_attendance_based_on_action',
	));

	register_rest_route('auth', '/user_attendance_history', array(
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'get_user_attendance_history',
	));

	

}
add_action( 'rest_api_init', 'user_follow_unfollow_api_callback' );

function toss_selection_by_user( $request ) {
    $captain1 				= $request->get_param( 'captain1' );
    $captain2 				= $request->get_param( 'captain2' );
	$toss_call_by_captain 	= $request->get_param( 'toss_call_by_captain' );
	$toss_call 				= $request->get_param( 'toss_call' );
	$available_toss_value	= array("tail", "haid");
    $user_1 				= get_user_meta( $captain1 );
	$user_2 				= get_user_meta( $captain2 );
	$random_digit 			= wp_rand(0, 1000);


	if ( ! $user_1 ) {
        return new WP_Error( 'invalid_captain1', "$captain1 is Invalid Captain ID", array( 'status' => 404 ) );
    }

	if ( ! $user_2 ) {
        return new WP_Error( 'invalid_captain2', "$captain2 is Invalid Captain ID", array( 'status' => 404 ) );
    }

	if ( $toss_call_by_captain !== $captain1 && $toss_call_by_captain !== $captain2 ) {
		return new WP_Error( 'toss_call_by_captain', "Please enter a valid captain ID for toss selection", array( 'status' => 404 ) );
	}

	if(!in_array($toss_call, $available_toss_value)){
		return new WP_Error( 'invalid_toss_call', "Available call for toss is either tail or haid", array( 'status' => 404 ) );
	}

	if ($random_digit % 2 === 0) {
		// if $random_digit is even
		update_user_meta($toss_call_by_captain, 'is_toss_won', 'Won the toss');
		update_user_meta($captain1, 'is_toss_won', 'Loss the toss');
	} else {
		// if $random_digit is odd
		update_user_meta($toss_call_by_captain, 'is_toss_won', 'Loss the toss');
		update_user_meta($captain1, 'is_toss_won', 'Won the toss');

	}
	


	echo $captain1;
	echo $captain2;
	echo $toss_call_by_captain;
	echo $toss_call;
	echo $random_digit;
	
}

function triggered_user_attendance_based_on_action( $request ) {
	
	$user_id 			= $request->get_param( 'user_id' );
	$attendance_action 	= $request->get_param( 'attendance_action' );

	$available_action	= array("checkin", "checkout");

	$user = get_user_meta( $user_id );
    
    if ( ! $user ) {
        return new WP_Error( 'invalid_user_id', 'Invalid user ID', array( 'status' => 404 ) );
    }

	if ( empty( $user ) ) {
        return new WP_Error( 'empty_attendance_action', 'Empty Attendance Action', array( 'status' => 404 ) );
    }

	// Check if attendance action is valid
	if (!in_array($attendance_action, $available_action))
	{
		return new WP_Error( 'invalid_attendance_action', 'Attendance Action not found', array( 'status' => 404 ) );
	}
	
	// Get the current time
	$timestamp = current_time('mysql');
	
	// Get the previous attendance action for the user
	$previous_action = get_user_meta($user_id, 'attendance_previous_action', true);
	
	// If the previous action is checkin and the current action is also checkin, return an error
	if($previous_action == 'checkin' && $attendance_action == 'checkin') {
	return new WP_Error('invalid_attendance_action', 'Invalid attendance action', array('status' => 400));
	}

	// If the previous action is checkout and the current action is also checkout, return an error
	if($previous_action == 'checkout' && $attendance_action == 'checkout') {
	return new WP_Error('invalid_attendance_action', 'Invalid attendance action', array('status' => 400));
	}

	 // Get the existing attendance records for the user
	 $attendance_records = get_user_meta($user_id, 'attendance_' . $attendance_action, true);

	  // If there are no existing attendance records, create a new array
	if(!is_array($attendance_records)) {
		$attendance_records = array();
	}

	// Add the current timestamp to the attendance records array
	array_push($attendance_records, $timestamp);

	// Save the updated attendance records to user meta
	update_user_meta($user_id, 'attendance_' . $attendance_action, $attendance_records);

	// Save the current attendance action as the previous action for the user
	update_user_meta($user_id, 'attendance_previous_action', $attendance_action);

	// Return a success message
	return array('status' => 'success', 'message' => ucfirst($attendance_action) . ' saved','timestamp' => $timestamp);
}

function get_user_attendance_history( $request ) {

	$user_id 			= $request->get_param( 'user_id' );
	$attendance_type 			= $request->get_param( 'attendance_type' );

	$user = get_user_meta( $user_id );
    
    if ( ! $user ) {
        return new WP_Error( 'invalid_user_id', 'Invalid user ID', array( 'status' => 404 ) );
    }

	if ( empty( $user ) ) {
        return new WP_Error( 'empty_attendance_action', 'Empty Attendance Action', array( 'status' => 404 ) );
    }

	if ( empty( $attendance_type ) ) {
        return new WP_Error( 'empty_attendance_type', 'Empty Attendance Type', array( 'status' => 404 ) );
    }


	$attendance_checkin = get_user_meta($user_id, "attendance_$attendance_type", false);

	// loop through the array of attendance_checkin values and do something with each timestamp
	$counter = 0;

	foreach ($attendance_checkin[0] as $checkin_time) {
			$checkin_data[] = array(
				$attendance_type."_".$counter => $checkin_time,
				
			);
			$counter++;
	}
	return array('status' => 'success', 'message' => 'Data Fetched','data' => $checkin_data);

}

add_action( 'show_user_profile', 'user_additional_profile_fields' );
add_action( 'edit_user_profile', 'user_additional_profile_fields' );

/**
 * Add new fields above 'Update' button.
 *
 * @param WP_User $user User object.
 */
function user_additional_profile_fields( $user ) {

	$attendance_history = array();

	$user_id              = $user->data->ID;
	$attendance_history_checkin = get_user_meta( $user_id, 'attendance_checkin', true) ? get_user_meta( $user_id, 'attendance_checkin', true) : null;
	$attendance_history_checkout = get_user_meta( $user_id, 'attendance_checkout', true) ? get_user_meta( $user_id, 'attendance_checkout', true) : null;
	$attendance_history  = array_combine($attendance_history_checkin, $attendance_history_checkout);
	
	?>
	<style>
		#students {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}
		#students th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			background-color: #2271b1;
			color: white;
		}
		#students td, #students th {
			border: 1px solid #ddd;
			padding: 8px;
		}
	</style>
		<h3>Attendance History</h3>
			<table id="students" >
				<tr>
					<th><label for="birth-date-day">User ID</label></th>
					<th><label for="birth-date-day">Checkin Time</label></th>
					<th><label for="birth-date-day">Checkout Time</label></th>
				</tr>
				<?php
												
				foreach ( $attendance_history  as $checkin => $checkout ) {
					$student_id = $user->ID;
					?>
					<tr>
						<td><?php echo $student_id; ?></td>
						<td><?php echo $checkin; ?></td>
						<td><?php echo $checkout; ?></td>
					</tr>
					<?php
				}
				?>
			</table>
		<?php
}

function follow_user_by_user_id( $request ) {
    $user_id = $request->get_param( 'id' );
    $cur_user_id = $request->get_param( 'current_user_id' );
    $user = get_user_meta( $user_id );
    
    if ( ! $user ) {
        return new WP_Error( 'invalid_user_id', 'Invalid user ID', array( 'status' => 404 ) );
    }

	if ( $user_id == $cur_user_id ) {
        return new WP_Error( 'duplicate_user_id', 'You can not follow Your self', array( 'status' => 404 ) );
    }

    // Check if the current user is already following the target user
    $followed_users = get_user_meta( $cur_user_id, 'followed_users', true );
    if ( ! is_array( $followed_users ) ) {
        $followed_users = array();
    }
    $is_following = in_array( $user_id, $followed_users );

    if ( $is_following ) {
        // Unfollow the user
        $followed_users = array_diff( $followed_users, array( $user_id ) );
		print_r( $followed_users );
		echo "rajan";
        update_user_meta( $cur_user_id, 'followed_users', $followed_users );
		return array( 'success' => true,'message' => "Unfollowed user successfully" );

    } else {
        // Follow the user
        $followed_users[] = $user_id;
		print_r( $followed_users );
		echo "panchal";
        update_user_meta( $cur_user_id, 'followed_users', $followed_users );
		return array( 'success' => true,'message' => "Followed user successfully" );
    }
}

function my_acf_google_map_api( $api ){
    $api['key'] = 'AIzaSyDPj5ClObVWPZ8WJxxl8v5HRaZSniDz2gg';
    return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');


function get_estimation_between_two_distance_endpoints( $request ) {

	// Set the API key and the endpoints
	$api_key 			= 'AIzaSyDPj5ClObVWPZ8WJxxl8v5HRaZSniDz2gg';
	$base_url 			= 'https://maps.googleapis.com/maps/api/distancematrix/json';

	$vehicle_type 		= sanitize_text_field( $request->get_param( 'vehicle_type' ) );
	$pickup_location 	= sanitize_text_field( $request->get_param( 'pickup_location' ) );
	$drop_location 		= sanitize_text_field( $request->get_param( 'drop_location' ) );
	$sender_name 		= sanitize_text_field( $request->get_param( 'sender_name' ) );
	$sender_phone 		= sanitize_text_field( $request->get_param( 'sender_phone' ) );
	$receiver_name 		= sanitize_text_field( $request->get_param( 'receiver_name' ) );
	$receiver_phone 	= sanitize_text_field( $request->get_param( 'receiver_phone' ) );
	$good_type 			= sanitize_text_field( $request->get_param( 'good_type' ) );

	// Geocode the pickup location
	$pickup_url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $pickup_location ) . '&key=' . $api_key;
	$pickup_response = file_get_contents( $pickup_url );
	$pickup_data = json_decode( $pickup_response );
	$pickup_latitude = $pickup_data->results[0]->geometry->location->lat;
	$pickup_longitude = $pickup_data->results[0]->geometry->location->lng;

	// Geocode the drop location
	$drop_url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $drop_location ) . '&key=' . $api_key;
	$drop_response = file_get_contents( $drop_url );
	$drop_data = json_decode( $drop_response );
	$drop_latitude = $drop_data->results[0]->geometry->location->lat;
	$drop_longitude = $drop_data->results[0]->geometry->location->lng;

	$pickup_field_value = array(
		"address" => $pickup_location, 
		"lat" => $pickup_latitude, 
		"lng" => $pickup_longitude
	);

	$drop_field_value = array(
		"address" => $drop_location, 
		"lat" => $drop_latitude, 
		"lng" => $drop_longitude
	);

	if ( empty( $vehicle_type ) ) {
		return new WP_Error( 'missing_vehicle_type', 'Please provide vehicle type.' );
	}

	if ( empty( $pickup_location ) ) {
		return new WP_Error( 'missing_pickup_location', 'Please provide pickup location.' );
	}

	if ( empty( $drop_location ) ) {
		return new WP_Error( 'missing_drop_location', 'Please provide drop location.' );
	}

	if ( empty( $sender_name ) ) {
		return new WP_Error( 'missing_sender_name', 'Please provide sender name.' );
	}

	if ( empty( $sender_phone ) ) {
		return new WP_Error( 'missing_sender_phone', 'Please provide sender phone.' );
	}

	if ( empty( $receiver_name ) ) {
		return new WP_Error( 'missing_receiver_name', 'Please provide receiver name.' );
	}

	if ( empty( $receiver_phone ) ) {
		return new WP_Error( 'missing_receiver_phone', 'Please provide receiver phone.' );
	}

	if ( empty( $good_type ) ) {
		return new WP_Error( 'missing_good_type', 'Please provide good type.' );
	}

	// Build the request URL
	$url 			= $base_url . '?origins=' . $pickup_latitude . ',' . $pickup_longitude . '&destinations=' . $drop_latitude . ',' . $drop_longitude . '&key=' . $api_key;

	$response 		= file_get_contents( $url );

	// Parse the JSON response
	$data 			= json_decode( $response );

	// Get the distance in meters
	$distance_in_meters = $data->rows[0]->elements[0]->distance->value;

	// Convert the distance to kilometers
	$distance_in_kms = $distance_in_meters / 1000;

	$distance_calculation = $distance_in_kms;

	$cost_per_km = (int)50;

	$shipping_information = array(
		'vehicle_type'		=> $vehicle_type,
		'pickup_location'	=> $pickup_location,
		'drop_location'		=> $drop_location,
		'sender_name'		=> $sender_name,
		'sender_phone'		=> $sender_phone,
		'receiver_name'		=> $receiver_name,
		'receiver_phone'	=> $receiver_phone,
		'good_type'			=> $good_type,
	);

	// Prepare the data for creating a new Porter post type
	$port_post = array(
		'post_title' 	=> 'Porter Delivery: ' . $pickup_location . ' to ' . $drop_location,
		'post_status' 	=> 'publish',
		'post_type' 	=> 'porter',
	);

	$port_post_id = wp_insert_post( $port_post );

	update_field( 'vehicle_type', $vehicle_type, $port_post_id );
	update_field('pickup_location', $pickup_field_value, $port_post_id);
	update_field('drop_location', $drop_field_value, $port_post_id);
	update_field( 'sender_name', $sender_name, $port_post_id );
	update_field( 'sender_phone', $sender_phone, $port_post_id );
	update_field( 'receiver_name', $receiver_name, $port_post_id );
	update_field( 'receiver_phone', $receiver_phone, $port_post_id );
	update_field( 'good_type', $good_type, $port_post_id );
	update_field( 'total_distance', $distance_calculation."Kms", $port_post_id );
	update_field( 'courier_charges', ($distance_calculation*$cost_per_km."₹"), $port_post_id );

	if ( ! is_wp_error( $port_post_id ) ) {

		return new WP_REST_Response( array(
			'message' 			=> 'Please Review your information before placing an order.',
			'total_distance'	=> "$distance_calculation" . 'Kms',
			'courier_charges' 	=> $distance_calculation*$cost_per_km."₹",
			'data'				=> $shipping_information,
		), 200 );

	}
}


// Convert External Image URL into Internal media URL
function convert_external_url_into_internal($external_image_url) {

    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    // Define the external image URL
    // Define the external image URL
    // $external_image_url = '';

    //  convert an attachment URL into a post ID.
    $attachment_id = attachment_url_to_postid($external_image_url);

    if ($attachment_id) {
        // Image already exists, return the URL else return false
        $image_url = wp_get_attachment_url($attachment_id);
    } else {
        // Use media_sideload_image function to download and save the external image
        $attachment_id = media_sideload_image($external_image_url, 0);

        // Get the URL for the saved image

		if (is_wp_error($attachment_id)) {
			// Handle the WP_Error object
			echo "Error occurred: " . $attachment_id->get_error_message();
			return;
		}
		
		// Convert $attachment_id to integer
		$attachment_id = intval($attachment_id);
		
		// Get the URL for the saved image
		$image_url = wp_get_attachment_url($attachment_id);
		
		// Do something with the image URL, for example:
		echo "Image URL: " . $image_url;
    }
}


add_action( 'init', 'convert_external_url_into_internal_callback' );


function convert_external_url_into_internal_callback(){
    // convert_external_url_into_internal('https://www.pngmart.com/files/22/Google-Download-PNG-Isolated-Image.png');
}

