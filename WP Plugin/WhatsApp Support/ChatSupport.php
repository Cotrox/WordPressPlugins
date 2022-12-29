<?php

/**
 * Plugin Name:       ChatSupport
 * Plugin URI:        https://graficarulez.forumfree.it/
 * Description:       ChatSupport let you add a new and Fluent WhatsApp Support Button to your WordPress Website.
 * Version:           0.1
 * Requires at least: 6.0
 * Requires PHP:      7.2
 * Author:            Giuseppe Antonino Cotroneo | Cotrox
 * Author URI:        https://www.behance.net/Cotrox
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       Chat Support
 * Domain Path:       /languages
 */

 /* WhatsApp Support Plugin Settings Page */

 class WhatsAppSupport {
	private $whatsapp_support_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'whatsapp_support_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'whatsapp_support_page_init' ) );
	}

	public function whatsapp_support_add_plugin_page() {
		add_menu_page(
			'WhatsApp Support', // page_title
			'WA Support', // menu_title
			'manage_options', // capability
			'whatsapp-support', // menu_slug
			array( $this, 'whatsapp_support_create_admin_page' ), // function
			'dashicons-format-status', // icon_url
			2 // position
		);
	}

	public function whatsapp_support_create_admin_page() {
		$this->whatsapp_support_options = get_option( 'whatsapp_support_option_name' ); ?>

		<div class="wrap">
			<h2>WhatsApp Support</h2>
			<p>Settings page for WhatsApp Support Plugin.<br><a href="https://graficarulez.forumfree.it/?f=10805258">Contact Us</a> for <b>answer</b> and <b>support</b>.</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'whatsapp_support_option_group' );
					do_settings_sections( 'whatsapp-support-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function whatsapp_support_page_init() {
		register_setting(
			'whatsapp_support_option_group', // option_group
			'whatsapp_support_option_name', // option_name
			array( $this, 'whatsapp_support_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'whatsapp_support_setting_section', // id
			'Settings', // title
			array( $this, 'whatsapp_support_section_info' ), // callback
			'whatsapp-support-admin' // page
		);

		add_settings_field(
			'phone_number_0', // id
			'Phone Number', // title
			array( $this, 'phone_number_0_callback' ), // callback
			'whatsapp-support-admin', // page
			'whatsapp_support_setting_section' // section
		);
	}

	public function whatsapp_support_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['phone_number_0'] ) ) {
			$sanitary_values['phone_number_0'] = sanitize_text_field( $input['phone_number_0'] );
		}

		return $sanitary_values;
	}

	public function whatsapp_support_section_info() {
		
	}

	public function phone_number_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="whatsapp_support_option_name[phone_number_0]" id="phone_number_0" value="%s">',
			isset( $this->whatsapp_support_options['phone_number_0'] ) ? esc_attr( $this->whatsapp_support_options['phone_number_0']) : ''
		);
	}

}
if ( is_admin() )
	$whatsapp_support = new WhatsAppSupport();

/*
 * Retrieve this value with:
 * $whatsapp_support_options = get_option( 'whatsapp_support_option_name' ); // Array of All Options
 * $phone_number_0 = $whatsapp_support_options['phone_number_0']; // Phone Number
 */

 add_action( 'wp_footer', 'add_whatsapp_support' );

function whatsapp_support_add(){
  ?>

<div id="whatsapp-business">
	<a href="https://wa.me/<?php 
		echo esc_html(get_option( 'whatsapp_support_option_name' )['phone_number_0'])
	?>" class="whatsapp-business-icon"></a>
</div>

<style>
  #whatsapp-business {
    position: fixed;
    background: #25d366;
    width: 50px;
    height: 50px;
    bottom: 30px;
    right: 30px;
    border-radius: 100%;
    box-shadow: 0 0 5px #00000040;
    opacity: 0;
    transition: .2s ease-out;
    animation: fadeIn .2s linear.2s normal forwards;
    cursor: pointer;
    z-index: 100;
}

#whatsapp-business:hover {
    transform: scale(1.2);
}

.whatsapp-business-icon:after {
    content: '\f232';
    font-family: 'FontAwesome';
    font-weight: bold;
    color: #fff;
    font-size: 24pt;
    position: absolute;
    left: 12px;
    bottom: 1px;
    cursor: pointer;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}
</style>
  
  <?php
}