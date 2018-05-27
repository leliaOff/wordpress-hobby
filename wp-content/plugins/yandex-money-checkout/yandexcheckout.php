<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           YandexMoneyCheckout
 *
 * @wordpress-plugin
 * Plugin Name:       Яндекс.Касса 2.0 для Woocommerce
 * Plugin URI:        https://wordpress.org/plugins/yandexcheckout/
 * Description:       Платежный модуль для работы с сервисом Яндекс.Касса через плагин WooCommerce
 * Version:           1.1.4
 * Author:            Yandex.Money
 * Author URI:        http://kassa.yandex.ru
 * License URI:       https://money.yandex.ru/doc.xml?id=527132
 * Text Domain:       yandex-money-checkout
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function yandexcheckout_plugin_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/YandexMoneyCheckoutActivator.php';
	YandexMoneyCheckoutActivator::activate();
}

function yandexcheckout_plugin_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/YandexMoneyCheckoutDeactivator.php';
	YandexMoneyCheckoutDeactivator::deactivate();
}

register_activation_hook( __FILE__, 'yandexcheckout_plugin_activate' );
register_deactivation_hook( __FILE__, 'yandexcheckout_plugin_deactivate' );

require plugin_dir_path( __FILE__ ) . 'includes/YandexMoneyCheckout.php';

$plugin = new YandexMoneyCheckout();

define( 'YAMONEY_API_VERSION', $plugin->getVersion() );

$plugin->run();
