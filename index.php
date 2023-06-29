<?php
/*
Plugin Name: Codeline Hotel Booking
Description: This plugin is a test plugin. Open the includes/js/custom.js and edit the BASE_API to the API url of the laravel app
Author: Zacchaeus Bolaji
*/
// Hook the 'wp_footer' action hook, add the function named 'mfp_Add_Text' to it
add_filter("the_content", "hotel_booking_form");

// Define 'hotel_booking_form'
function hotel_booking_form($the_Post)
{
	if(strpos($the_Post, "[HOTEL_BOOKING_FORM form='1']") && is_page()) {
		?>
		<style>
			<?php
		global $current_user;
	  get_currentuserinfo();
		?>
		</style>
		<div class="w3_agile_main_grids">

			<div id='progress'><div id='progress-complete'></div></div>
			<?php if(isset($response)) echo $response; ?>
			<div id='response' class="alert alert-success" style="display: none"></div>

			<form id="SignupForm" class="agile_form" method="post">
				<input type="hidden" name="customer_id" value="<?=$current_user->ID ?? '' ?>">
				<fieldset>
					<div >
						<label>Start Date</label>
						<input type="date" class="form-control" name="date_start" id="date_from">
						<label>End Date</label>
						<input type="date" class="form-control" name="date_end" id="date_to">
					</div>
					<h4 class="w3layouts_type">What type of room would you want ?</h4>
					<div id="room_types">

					</div>
					<h4 class="w3layouts_type">What capacity of room would you want ?</h4>
					<div id="room_capacities">

					</div>
					<div class="clear"> </div>
				</fieldset>
				<fieldset>
					<h4 class="w3layouts_type">What room would you want ?</h4>
					<select onchange="fetchPrices()" id="rooms" name="room_id" class="form-control">

					</select>

					<h4 class="w3layouts_type">What price of would you want ?</h4>
					<select id="prices" name="price" class="form-control">

					</select>
					<div class="clear"> </div>
				</fieldset>

				<fieldset>
					<h3>Account information</h3>
					<div class="form-group">
						<label>First Name</label>
						<div class="wthree_input">
							<i class="fa fa-user" aria-hidden="true"></i>
							<input value="<?=$current_user->user_firstname ?? ''?>" id="Name" type="text" name="first_name" class="form-control" placeholder="First Name" required="" />
						</div>
					</div>
					<div class="form-group">
						<label>Last Name</label>
						<div class="wthree_input">
							<i class="fa fa-user" aria-hidden="true"></i>
							<input value="<?=$current_user->user_lastname ?? ''?>" id="Name" type="text" name="last_name" class="form-control" placeholder="First Name" required />
						</div>
					</div>
					<div class="form-group">
						<label>Email</label>
						<div class="wthree_input">
							<i class="fa fa-user" aria-hidden="true"></i>
							<input value="<?=$current_user->user_email ?? ''?>" id="Name" type="email" name="email" class="form-control" placeholder="First Name" required />
						</div>
					</div>
					<div class="form-group">
						<label>Phone</label>
						<div class="wthree_input">
							<i class="fa fa-user" aria-hidden="true"></i>
							<input id="Name" type="text" name="last_name" class="form-control" placeholder="Phone" required />
						</div>
					</div>
					<div class="form-group agileits_w3layouts_form">
						<div class="wthree_input">
							<label>Country</label>
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<select onchange="fetchStates(this.value)" id="countries" name="country_id" class="form-control">

							</select>

						</div>
					</div>
					<div class="form-group agileits_w3layouts_form">
						<div class="wthree_input">
							<label>State</label>
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<select onchange="fetchCities(this.value)" id="states" name="state_id" class="form-control">

							</select>

						</div>
					</div>
					<div class="form-group agileits_w3layouts_form">
						<div class="wthree_input">
							<label>City</label>
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<select id="cities" name="city_id" class="form-control">

							</select>

						</div>
					</div>
					<div class="clear"> </div>
				</fieldset>

				<p>
					<input type="hidden" name="btnSaveForm" value="true">
					<button id="SaveAccount" class="btn btn-primary agileinfo_primary submit">Submit form</button>
				</p>
			</form>

		</div>
		<?php
	}
}

// Define 'mfp_Add_Text'
add_action("wp_footer", "hbp_footer_script");
function hbp_footer_script()
{
	?>
	<script src="<?=plugin_dir_url( __FILE__ ) ?>includes/js/jquery.validate.min.js"></script>
	<script src="<?=plugin_dir_url( __FILE__ ) ?>includes/js/jquery.formtowizard.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LdclqoUAAAAAAuPnnM8GL5erpT9Mg4si_BXq1aA"></script>
    <script src="<?=plugin_dir_url( __FILE__ ) ?>includes/js/custom.js"></script>
	<script>
        jQuery(function ($) {
            fetchCountries($);
            fetchRoomTypes($);
            fetchRoomCapacities($);
            var $signupForm = $( '#SignupForm' );
            $signupForm.submit(function (e) {
                e.preventDefault();
                grecaptcha.ready(function () {
                    // do request for recaptcha token
                    // response is promise with passed token
                    grecaptcha.execute(GOOGLE_PUBLIC, {action: 'create_comment'}).then(function (token) {
                        // add token to form

                        jQuery('#SignupForm').prepend('<input type="hidden" name="captcha_token" value="' + token + '">');
                        validateCaptcha($, token, $signupForm )
                    });
                });
            });

            $signupForm.validate({errorElement: 'em'});

            $signupForm.formToWizard({
                submitButton: 'SaveAccount',
                nextBtnClass: 'btn btn-primary next',
                prevBtnClass: 'btn btn-default prev',
                buttonTag:    'button',
                validateBeforeNext: function(form, step) {
                    var stepIsValid = true;
                    var validator = form.validate();
                    $(':input', step).each( function(index) {
                        var xy = validator.element(this);
                        stepIsValid = stepIsValid && (typeof xy == 'undefined' || xy);
                    });
                    return stepIsValid;
                },
                progress: function (i, count) {
                    if(i) fetchRooms();
                    $('#progress-complete').width(''+(i/count*100)+'%');
                }
            });
        });
	</script>

	<?php

}

add_action( 'wp_enqueue_scripts', 'enqueue_load_fa' );
function enqueue_load_fa() {
	wp_enqueue_style( 'load-fa', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
}

add_action( 'wp_enqueue_scripts', 'custom_load_bootstrap' );
/**
 * Enqueue Bootstrap.
 */
function custom_load_bootstrap() {
	wp_enqueue_style( 'bootstrap-css', '//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css' );
}

add_action('init', 'saveCustomer');
function saveCustomer() {
	if ( isset( $_POST['btnSaveForm'] ) ) {

		// Create post object
		$my_post = array(
			'post_title'   => wp_strip_all_tags( $_POST['first_name'] ),
			'post_content' => json_encode( $_POST ),
			'post_status'  => 'pending',
			'post_author'  => 1,
			'post_type'    => 'customer'
		);

// Insert the post into the database
		try {
			wp_insert_post( $my_post );
//	        $result = '<div class="alert alert-success">Booking completed successfully</div>';
		} catch (Exception $exception) {
//	        $result ='<div class="alert alert-danger">Failed to save booking details</div>';
		}

	}
}