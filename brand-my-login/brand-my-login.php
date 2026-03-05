<?php
/**
 * Plugin Name: Brand My Login
 * Description: Replace the default WordPress login logo and colors with your brand in seconds.
 * Version: 1.0.0
 * Author: Techoven Solutions
 * Author URI: https://techovensolutions.com
 * Text Domain: brand-my-login
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'BML_PLUGIN_VERSION', '1.0.0' );
define( 'BML_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BML_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

defined( 'BML_OPTION_KEY' ) || define( 'BML_OPTION_KEY', 'brand_my_login_options' );

require_once BML_PLUGIN_DIR . 'includes/class-bml-settings.php';

add_action( 'plugins_loaded', function () {
    new Brand_My_Login_Settings();
} );

add_action( 'login_enqueue_scripts', function () {
    $options     = get_option( BML_OPTION_KEY, [] );
    $logo_id     = isset( $options['logo_id'] ) ? absint( $options['logo_id'] ) : 0;
    $brand_color = isset( $options['brand_color'] ) ? sanitize_hex_color( $options['brand_color'] ) : '';
    $button_hover_color = isset( $options['button_hover_color'] ) ? sanitize_hex_color( $options['button_hover_color'] ) : '';
    $link_color  = isset( $options['link_color'] ) ? sanitize_hex_color( $options['link_color'] ) : '';
    $background_color = isset( $options['background_color'] ) ? sanitize_hex_color( $options['background_color'] ) : '';

    if ( ! $logo_id && ! $brand_color && ! $button_hover_color && ! $link_color && ! $background_color ) {
        return;
    }

    $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

    $inline_css = '';

    if ( $logo_url ) {
        $inline_css .= sprintf(
            '#login h1 a { background-image: url(%1$s); background-size: contain; width: 100%%; height: 80px; }
             @media (max-width: 480px) { #login h1 a { height: 60px; } }',
            esc_url( $logo_url )
        );
    }

    if ( $brand_color ) {
        $inline_css .= sprintf(
            '.login #loginform .button-primary { background-color: %1$s; border-color: %1$s; box-shadow: none; text-shadow: none; }
             .login #loginform .button-primary:hover,
             .login #loginform .button-primary:focus { background-color: %1$s; border-color: %1$s; }
             .login #nav a, .login #backtoblog a { color: %1$s !important; }
             .login #nav a:hover, .login #backtoblog a:hover { opacity: .8; }',
            esc_attr( $brand_color )
        );
    }

    if ( $button_hover_color ) {
        $inline_css .= sprintf(
            '.login #loginform .button-primary:hover,
             .login #loginform .button-primary:focus { background-color: %1$s !important; border-color: %1$s !important; }',
            esc_attr( $button_hover_color )
        );
    }

    if ( $link_color ) {
        $inline_css .= sprintf(
            '.login #nav a, .login #backtoblog a { color: %1$s !important; }
             .login #nav a:hover, .login #backtoblog a:hover { opacity: .8; }',
            esc_attr( $link_color )
        );
    }

    if ( $background_color ) {
        $inline_css .= sprintf(
            '.login { background-color: %1$s; }',
            esc_attr( $background_color )
        );
    }

    if ( $inline_css ) {
        wp_add_inline_style( 'login', $inline_css );
    }
} );

add_filter( 'login_headerurl', function () {
    return home_url();
} );

add_filter( 'login_headertext', function () {
    return get_bloginfo( 'name' );
} );
