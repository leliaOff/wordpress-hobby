<?php


class YandexMoneyStatistic
{
    public static function send()
    {
        $epl         = (bool)(get_option('ym_api_pay_mode') == '1');
        $array       = array(
            'url'      => get_option('siteurl'),
            'cms'      => 'wordpress-woo',
            'version'  => WOOCOMMERCE_VERSION,
            'ver_mod'  => YAMONEY_API_VERSION,
            'yacms'    => false,
            'email'    => get_option('admin_email'),
            'shopid'   => get_option('ym_api_shop_id'),
            'settings' => array(
                'kassa'     => true,
                'kassa_epl' => $epl,
            ),
        );
        $array_crypt = base64_encode(serialize($array));

        $args     = array(
            'body'      => array('data' => $array_crypt, 'lbl' => 0),
            'sslverify' => false,
        );
        $response = wp_remote_post('https://statcms.yamoney.ru/v2/', $args);
    }
}