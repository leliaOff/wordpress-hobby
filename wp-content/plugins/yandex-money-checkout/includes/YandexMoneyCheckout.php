<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * payment-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 */
class YandexMoneyCheckout
{

    public static $pluginUrl;

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      YandexMoneyCheckoutLoader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the payment-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        $this->plugin_name = 'yandex-money-checkout';
        $this->version     = '1.1.4';
        self::$pluginUrl   = plugin_dir_url(dirname(__FILE__));

        $this->loadDependencies();
        $this->setLocale();
        $this->defineAdminHooks();
        $this->definePaymentHooks();
        $this->defineShopHooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - YandexMoneyCheckoutLoader. Orchestrates the hooks of the plugin.
     * - YandexMoneyCheckouti18n. Defines internationalization functionality.
     * - YandexMoneyCheckoutAdmin. Defines all hooks for the admin area.
     * - YandexMoneyCheckoutPublic. Defines all hooks for the payment side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function loadDependencies()
    {

        require_once plugin_dir_path(dirname(__FILE__)).'includes/lib/autoload.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/YandexMoneyCheckoutLoader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/YandexMoneyCheckoutI18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'admin/YandexMoneyCheckoutAdmin.php';
        require_once plugin_dir_path(dirname(__FILE__)).'admin/YandexMoneyStatistic.php';
	    require_once plugin_dir_path(dirname(__FILE__)).'admin/TransactionsListTable.php';
		require_once plugin_dir_path(dirname(__FILE__)).'admin/PaymentChargeDispatcher.php';

	    /**
         * The class responsible for defining all actions that occur in the payment-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)).'includes/YandexMoneyCheckoutPayment.php';
        require_once plugin_dir_path(dirname(__FILE__)).'includes/YandexMoneyLogger.php';

        $this->loader = new YandexMoneyCheckoutLoader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the YandexMoneyCheckouti18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function setLocale()
    {

        $plugin_i18n = new YandexMoneyCheckoutI18n();

        $this->loader->addAction('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function defineAdminHooks()
    {
        $plugin_admin = new YandexMoneyCheckoutAdmin($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->addAction('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->addAction('admin_menu', $plugin_admin, 'addMenu');
        $this->loader->addAction('admin_init', $plugin_admin, 'registerSettings');
        $this->loader->addAction('update_option_ym_api_shop_id', $plugin_admin, 'sendStatistic');
    }

    /**
     * Register all of the hooks related to the payment-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function definePaymentHooks()
    {
        $paymentKernel = new YandexMoneyCheckoutPayment($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('plugins_loaded', $paymentKernel, 'loadGateways');
        $this->loader->addAction('parse_request', $paymentKernel, 'processCallback');
        $this->loader->addFilter('woocommerce_payment_gateways', $paymentKernel, 'addGateways');
        $this->loader->addFilter('woocommerce_valid_order_statuses_for_payment', $paymentKernel, 'validStatuses', 10,
            2);
    }

    /**
     * Register all of the hooks related to the shop-facing functionality
     * of the plugin.
     *
     * @since    1.1.4
     * @access   private
     */
    private function defineShopHooks()
    {
        $paymentKernel = new YandexMoneyCheckoutPayment($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('woocommerce_single_product_summary', $paymentKernel, 'showInstallmentsInfo', 15);
        $this->loader->addAction('woocommerce_after_checkout_form', $paymentKernel, 'showExtraInstallmentsCheckoutInfo');
        $this->loader->addAction('woocommerce_cart_totals_after_order_total', $paymentKernel, 'showExtraInstallmentsCheckoutInfo');
        $this->loader->addAction('woocommerce_review_order_after_order_total', $paymentKernel, 'showExtraInstallmentsCheckoutInfo');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function getPluginName()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    YandexMoneyCheckoutLoader  Orchestrates the hooks of the plugin.
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function getVersion()
    {
        return $this->version;
    }

}
