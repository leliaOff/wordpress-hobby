<?php

/**
 * Fired during plugin deactivation
 */
class YandexMoneyCheckoutDeactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        delete_option('woocommerce_ym_api_qiwi_settings');
        delete_option('woocommerce_ym_api_bank_card_settings');
        delete_option('woocommerce_ym_api_epl_settings');
        delete_option('woocommerce_ym_api_sberbank_settings');
        delete_option('woocommerce_ym_api_wallet_settings');
        delete_option('woocommerce_ym_api_cash_settings');
        delete_option('woocommerce_ym_api_webmoney_settings');
        delete_option('woocommerce_ym_api_alfabank_settings');
        delete_option('woocommerce_ym_api_installments_settings');
    }

}
