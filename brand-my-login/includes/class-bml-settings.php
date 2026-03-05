<?php
/**
 * Admin settings for Brand My Login.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Brand_My_Login_Settings {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    public function register_menu() {
        add_options_page(
            __( 'Brand My Login', 'brand-my-login' ),
            __( 'Brand My Login', 'brand-my-login' ),
            'manage_options',
            'brand-my-login',
            [ $this, 'render_settings_page' ]
        );
    }

    public function register_settings() {
        register_setting( 'brand_my_login_settings', BML_OPTION_KEY, [ $this, 'sanitize_options' ] );
    }

    public function sanitize_options( $input ) {
        $output = [];

        if ( isset( $input['logo_id'] ) ) {
            $output['logo_id'] = absint( $input['logo_id'] );
        }

        if ( isset( $input['brand_color'] ) && $input['brand_color'] ) {
            $color = sanitize_hex_color( $input['brand_color'] );
            if ( $color ) {
                $output['brand_color'] = $color;
            }
        }

        if ( isset( $input['button_hover_color'] ) && $input['button_hover_color'] ) {
            $color = sanitize_hex_color( $input['button_hover_color'] );
            if ( $color ) {
                $output['button_hover_color'] = $color;
            }
        }

        if ( isset( $input['link_color'] ) && $input['link_color'] ) {
            $color = sanitize_hex_color( $input['link_color'] );
            if ( $color ) {
                $output['link_color'] = $color;
            }
        }

        if ( isset( $input['background_color'] ) && $input['background_color'] ) {
            $color = sanitize_hex_color( $input['background_color'] );
            if ( $color ) {
                $output['background_color'] = $color;
            }
        }

        return $output;
    }

    public function enqueue_admin_assets( $hook_suffix ) {
        if ( 'settings_page_brand-my-login' !== $hook_suffix ) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script(
            'brand-my-login-admin',
            BML_PLUGIN_URL . 'assets/js/admin.js',
            [ 'jquery', 'wp-color-picker' ],
            BML_PLUGIN_VERSION,
            true
        );

        $options = get_option( BML_OPTION_KEY, [] );

        wp_localize_script( 'brand-my-login-admin', 'BML_SETTINGS', [
            'logoId'     => isset( $options['logo_id'] ) ? absint( $options['logo_id'] ) : 0,
            'logoUrl'    => isset( $options['logo_id'] ) ? wp_get_attachment_image_url( $options['logo_id'], 'medium' ) : '',
            'chooseText' => __( 'Choose Logo', 'brand-my-login' ),
            'removeText' => __( 'Remove Logo', 'brand-my-login' ),
        ] );
    }

    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $options    = get_option( BML_OPTION_KEY, [] );
        $logo_id    = isset( $options['logo_id'] ) ? absint( $options['logo_id'] ) : 0;
        $logo_url   = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
        $brand_color = isset( $options['brand_color'] ) ? sanitize_hex_color( $options['brand_color'] ) : '';
        $button_hover_color = isset( $options['button_hover_color'] ) ? sanitize_hex_color( $options['button_hover_color'] ) : '';
        $link_color = isset( $options['link_color'] ) ? sanitize_hex_color( $options['link_color'] ) : '';
        $background_color = isset( $options['background_color'] ) ? sanitize_hex_color( $options['background_color'] ) : '';
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Brand My Login', 'brand-my-login' ); ?></h1>
            <p><?php esc_html_e( 'Give your login page a quick brand makeover with your logo and colors.', 'brand-my-login' ); ?></p>

            <form method="post" action="options.php">
                <?php settings_fields( 'brand_my_login_settings' ); ?>
                <table class="form-table" role="presentation">
                    <tbody>
                    <tr>
                        <th scope="row"><label for="bml-logo-id"><?php esc_html_e( 'Logo Upload', 'brand-my-login' ); ?></label></th>
                        <td>
                            <div class="bml-logo-preview" style="margin-bottom: 10px;">
                                <?php if ( $logo_url ) : ?>
                                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php esc_attr_e( 'Selected logo preview', 'brand-my-login' ); ?>" style="max-width: 200px; height: auto;" />
                                <?php else : ?>
                                    <em><?php esc_html_e( 'No logo selected yet.', 'brand-my-login' ); ?></em>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" id="bml-logo-id" name="<?php echo esc_attr( BML_OPTION_KEY ); ?>[logo_id]" value="<?php echo esc_attr( $logo_id ); ?>" />
                            <button type="button" class="button bml-upload-logo"><?php esc_html_e( 'Choose Logo', 'brand-my-login' ); ?></button>
                            <button type="button" class="button button-link-delete bml-remove-logo" <?php disabled( ! $logo_id ); ?>><?php esc_html_e( 'Remove', 'brand-my-login' ); ?></button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bml-brand-color"><?php esc_html_e( 'Brand Color', 'brand-my-login' ); ?></label></th>
                        <td>
                            <input type="text" id="bml-brand-color" name="<?php echo esc_attr( BML_OPTION_KEY ); ?>[brand_color]" value="<?php echo esc_attr( $brand_color ); ?>" class="regular-text" data-default-color="#2271b1" />
                            <p class="description"><?php esc_html_e( 'Primary button color and links.', 'brand-my-login' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bml-button-hover-color"><?php esc_html_e( 'Button Hover Color', 'brand-my-login' ); ?></label></th>
                        <td>
                            <input type="text" id="bml-button-hover-color" name="<?php echo esc_attr( BML_OPTION_KEY ); ?>[button_hover_color]" value="<?php echo esc_attr( $button_hover_color ); ?>" class="regular-text" data-default-color="#1a4f7a" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bml-link-color"><?php esc_html_e( 'Link Color', 'brand-my-login' ); ?></label></th>
                        <td>
                            <input type="text" id="bml-link-color" name="<?php echo esc_attr( BML_OPTION_KEY ); ?>[link_color]" value="<?php echo esc_attr( $link_color ); ?>" class="regular-text" data-default-color="#2271b1" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bml-background-color"><?php esc_html_e( 'Background Color', 'brand-my-login' ); ?></label></th>
                        <td>
                            <input type="text" id="bml-background-color" name="<?php echo esc_attr( BML_OPTION_KEY ); ?>[background_color]" value="<?php echo esc_attr( $background_color ); ?>" class="regular-text" data-default-color="#f0f0f1" />
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
