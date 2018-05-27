<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 *
 * @since      1.0.0
 *
 * @package    YandexMoneyCheckout
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
} else {
    delete_option('ym_api_shop_id');
    delete_option('ym_api_shop_password');
    delete_option('ym_api_pay_mode');
    delete_option('ym_api_epl_installments');
    delete_option('ym_api_success');
    delete_option('ym_api_fail');
    delete_option('ym_api_tax_rates_enum');
    delete_option('ym_api_enable_receipt');
    delete_option('ym_debug_enabled');
    delete_option('ym_api_default_tax_rate');
    delete_option('ym_force_clear_cart');
    delete_option('ym_api_tax_rate');
}
