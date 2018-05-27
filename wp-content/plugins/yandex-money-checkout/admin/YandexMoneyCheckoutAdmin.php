<?php

/**
 * The admin-specific functionality of the plugin.
 */
class YandexMoneyCheckoutAdmin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            YandexMoneyCheckout::$pluginUrl.'/assets/css/yandex-checkout-admin.css',
            array(),
            $this->version,
            'all'
        );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            $this->plugin_name,
            YandexMoneyCheckout::$pluginUrl.'/assets/js/yandex-checkout-admin.js',
            array('jquery'),
            '12412312',
            'all'
        );
    }

    public function addMenu()
    {
        add_submenu_page(
            'woocommerce',
            __('Настройки Яндекс.Касса 2.0', 'yandexcheckout'),
            __('Настройки Яндекс.Касса 2.0', 'yandexcheckout'),
            'manage_options',
            'yandex_money_api_menu',
            array($this, 'renderAdminPage')
        );
    }

    public function registerSettings()
    {
        register_setting('woocommerce-yamoney-api', 'ym_api_shop_id');
        register_setting('woocommerce-yamoney-api', 'ym_api_shop_password');
        register_setting('woocommerce-yamoney-api', 'ym_api_pay_mode');
        register_setting('woocommerce-yamoney-api', 'ym_api_epl_installments');
        register_setting('woocommerce-yamoney-api', 'ym_api_success');
        register_setting('woocommerce-yamoney-api', 'ym_api_fail');
        register_setting('woocommerce-yamoney-api', 'ym_api_tax_rates_enum');
        register_setting('woocommerce-yamoney-api', 'ym_api_enable_receipt');
        register_setting('woocommerce-yamoney-api', 'ym_debug_enabled');
        register_setting('woocommerce-yamoney-api', 'ym_api_default_tax_rate');
        register_setting('woocommerce-yamoney-api', 'ym_force_clear_cart');
        register_setting('woocommerce-yamoney-api', 'ym_api_tax_rate');

        update_option(
            'ym_api_tax_rates_enum',
            array(
                1 => "Не облагается",
                2 => "0%",
                3 => "10%",
                4 => "18%",
                5 => "Расчетная ставка 10/110",
                6 => "Расчетная ставка 18/118",
            )
        );
    }

    public function renderAdminPage()
    {
        $wcTaxes          = $this->getAllTaxes();
        $wcCalcTaxes      = get_option('woocommerce_calc_taxes');
        $ymTaxRatesEnum   = get_option('ym_api_tax_rates_enum');
        $pages            = get_pages();
        $ymTaxes          = get_option('ym_api_tax_rate');
        $isReceiptEnabled = get_option('ym_api_enable_receipt');
        $isDebugEnabled   = get_option('ym_debug_enabled');
        $forceClearCart   = get_option('ym_force_clear_cart');
        $testMode         = $this->isTestMode();
        $active_tab       = isset($_GET['tab']) ? $_GET['tab'] : 'yandex-checkout-settings';

        $shopId             = get_option('ym_api_shop_id');
        $password           = get_option('ym_api_shop_password');
        $isValidCredentials = null;
        if (!empty($shopId) && !empty($password)) {
            $isValidCredentials = $this->testConnection($shopId, $password);
        }

        $this->render(
            'partials/admin-settings-view.php',
            array(
                'wcTaxes'          => $wcTaxes,
                'pages'            => $pages,
                'wcCalcTaxes'      => $wcCalcTaxes,
                'ymTaxRatesEnum'   => $ymTaxRatesEnum,
                'ymTaxes'          => $ymTaxes,
                'isReceiptEnabled' => $isReceiptEnabled,
                'testMode'         => $testMode,
                'isDebugEnabled'   => $isDebugEnabled,
                'forceClearCart'   => $forceClearCart,
                'validCredentials' => $isValidCredentials,
                'active_tab'       => $active_tab,
            )
        );
    }

    public function sendStatistic()
    {
        YandexMoneyStatistic::send();
    }

    public function getAllTaxes()
    {
        global $wpdb;

        $query = "
			SELECT *
			FROM {$wpdb->prefix}woocommerce_tax_rates
			WHERE 1 = 1
		";

        $order_by = ' ORDER BY tax_rate_order';

        $result = $wpdb->get_results($query.$order_by);

        return $result;
    }

    private function render($viewPath, $args)
    {
        extract($args);

        include(plugin_dir_path(__FILE__).$viewPath);
    }

    private function isTestMode()
    {
        $shopPassword = get_option('ym_api_shop_password');
        $prefix       = substr($shopPassword, 0, 4);

        return $prefix == "test";
    }

    private function testConnection($shopId, $password)
    {
        require_once plugin_dir_path(dirname(__FILE__)).'includes/lib/autoload.php';

        $apiClient = new YandexCheckout\Client();
        $apiClient->setAuth($shopId, $password);

        try {
            $payment = $apiClient->getPaymentInfo('00000000-0000-0000-0000-000000000001');
        } catch (\YandexCheckout\Common\Exceptions\NotFoundException $e) {
            return true;
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
