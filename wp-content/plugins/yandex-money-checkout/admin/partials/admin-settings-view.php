<?php


?>

<!-- Start tabs -->
<h2 class="nav-tab-wrapper">
    <a class="nav-tab <?php echo $active_tab == 'yandex-checkout-settings' ? 'nav-tab-active' : ''; ?>"
       href="?page=yandex_money_api_menu&tab=yandex-checkout-settings">
        <?= __('Настройки модуля Яндекс.Касса для WooCommerce', 'yandexcheckout'); ?>
    </a>
    <a class="nav-tab <?php echo $active_tab == 'yandex-checkout-transactions' ? 'nav-tab-active' : ''; ?>"
       href="?page=yandex_money_api_menu&tab=yandex-checkout-transactions">
        <?= __('Список платежей через модуль Кассы', 'yandexcheckout'); ?>
    </a>
</h2>
<div class="wrap">
    <h2><?= __( 'Настройки модуля Яндекс.Касса для WooCommerce', 'yandexcheckout'); ?></h2>
    <?php if ($testMode): ?>
        <div style="background: rgba(8, 88, 231, 0.26);
        padding: 10px;
        width: 865px;
        border-radius: 10px;
        font-size: 14px;">
            <?= __('Вы включили тестовый режим приема платежей. Проверьте, как проходит оплата, и напишите менеджеру Кассы.
            Он выдаст рабочие shopId и Секретный ключ.
            <a href="https://yandex.ru/support/checkout/payments/api.html#api__04">Инструкция</a>.', 'yandexcheckout'); ?>
        </div>
    <?php endif; ?>
    <?php if ($validCredentials === false): ?>
        <div style="background: rgba(231, 88, 88, 0.26);
        padding: 10px;
        width: 865px;
        border-radius: 10px;
        font-size: 14px;
        margin: 3px 0 0 0;">
            <?= __('Проверьте shopId и Секретный ключ — где-то есть ошибка. А лучше скопируйте их прямо из
            <a href="https://kassa.yandex.ru/my" target="_blank">личного кабинета Яндекс.Кассы</a>', 'yandexcheckout'); ?>
        </div>
    <?php endif; ?>
    <?= __('<p>Работая с модулем, вы автоматически соглашаетесь с <a href=\'https://money.yandex.ru/doc.xml?id=527132\'
                                                             target=\'_blank\'>условиями его использования</a>.</p>', 'yandexcheckout');?>

    <p><?= __('Версия модуля', 'yandexcheckout')?> <?php echo YAMONEY_API_VERSION; ?></p>
    <p><?= __('Для работы с модулем необходимо подключить магазин к <a target="_blank" href="https://kassa.yandex.ru/">Яндекс.Кассе</a>', 'yandexcheckout');?>
    </p>
    <div class="tab-panel"
         id="yandex-checkout-settings" <?php echo $active_tab != 'yandex-checkout-settings' ? 'style="display: none;' : ''; ?>>
        <form id="ym-settings" method="post" action="options.php">
            <?php
            wp_nonce_field('update-options');
            settings_fields('woocommerce-yamoney-api');
            do_settings_sections('woocommerce-yamoney-api');
            ?>

            <h3><?= __('Параметры из личного кабинета Яндекс.Кассы', 'yandexcheckout');?></h3>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">shopId</th>
                    <td><input type="text" id="ym_api_shop_id" name="ym_api_shop_id"
                               value="<?php echo get_option('ym_api_shop_id'); ?>"/>
                        <br/>
                        <div id="shop_id_error" class="error-msg" style="display:none">
                        <span><?= __('Такого shopId нет. Пожалуйста, скопируйте параметр в <a
                                    href="https://money.yandex.ru/joinups">личном кабинете Яндекс.Кассы</a>  (наверху любой страницы)', 'yandexcheckout');?> </span>
                        </div>
                        <span class="help-text"><?= __('Скопируйте shopId из личного кабинета Яндекс.Кассы', 'yandexcheckout');?></span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?= __('Секретный ключ', 'yandexcheckout')?></th>
                    <td><input type="text" id="ym_api_shop_password" name="ym_api_shop_password"
                               value="<?php echo get_option('ym_api_shop_password'); ?>"/>
                        <br/>
                        <div id="shop_pass_error" class="error-msg" style="display:none">
                        <span> <?= __('Такого секретного ключа нет. Если вы уверены, что скопировали ключ правильно, значит, он по какой-то причине не работает. Выпустите и активируйте ключ заново — <a
                                    href="https://money.yandex.ru/joinups">в личном кабинете Яндекс.Кассы</a>', 'yandexcheckout');?></span>
                        </div>
                        <span class="help-text"><?= __('Выпустите и активируйте секретный ключ в личном кабинете Яндекс.Кассы. Потом скопируйте его сюда.', 'yandexcheckout');?><span>
                    </td>
                </tr>
            </table>
            <h3><?= __('Настройка сценария оплаты', 'yandexcheckout');?></h3>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?= __('Сценарий оплаты','yandexcheckout');?></th>
                    <td>
                        <?php if (!$testMode): ?>
                            <label><input type="radio" name="ym_api_pay_mode"
                                   value="1" <?php if (get_option('ym_api_pay_mode') == '1') {
                                echo ' checked="checked" ';
                            } ?> /> <?= __('Выбор оплаты на стороне сервиса Яндекс.Касса', 'yandexcheckout');?></label><br>
                            <div class="selectPayShop">
                            <input type="checkbox" name="ym_api_epl_installments"
                                   value="1" <?php if (get_option('ym_api_epl_installments') == '1') {
                                echo ' checked="checked" ';
                            } ?> /> <?= __('Добавить метод «Заплатить по частям» на страницу оформления заказа', 'yandexcheckout');?>
                            </div>
                        <?php endif; ?>
                        <label><input type="radio" name="ym_api_pay_mode"
                               value="0" <?php if (get_option('ym_api_pay_mode') != '1') {
                            echo ' checked="checked" ';
                            } ?> /><?= __('Выбор оплаты на стороне магазина', 'yandexcheckout');?></label>
                            <br>
                            <br>
                        <a href='https://tech.yandex.ru/money/doc/payment-solution/payment-form/payment-form-docpage/'
                           target='_blank'><?= __('Подробнее о сценариях оплаты', 'yandexcheckout');?></a>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?= __('Страница успеха платежа', 'yandexcheckout');?></th>
                    <td>
                        <select id="ym_api_success" name="ym_api_success">
                            <option value="wc_success" <?php echo((get_option(
                                                                       'ym_api_success'
                                                                   ) == 'wc_success') ? ' selected' : ''); ?>>
                                <?= __('Страница "Заказ принят" от WooCommerce', 'yandexcheckout');?>
                            </option>
                            <option value="wc_checkout" <?php echo((get_option(
                                                                        'ym_api_success'
                                                                    ) == 'wc_checkout') ? ' selected' : ''); ?>>
                                <?= __('Страница оформления заказа от WooCommerce', 'yandexcheckout');?>
                            </option>
                            <?php
                            if ($pages) {
                                foreach ($pages as $page) {
                                    $selected = ($page->ID == get_option('ym_api_success')) ? ' selected' : '';
                                    echo '<option value="'.$page->ID.'"'.$selected.'>'.$page->post_title.'</option>';
                                }
                            }
                            ?>
                        </select>
                        <br/>
                        <span class="help-text"><?= __('Эту страницу увидит покупатель, когда оплатит заказ', 'yandexcheckout');?><span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Страница отказа</th>
                    <td><select id="ym_api_fail" name="ym_api_fail">
                            <option value="wc_checkout" <?php echo((get_option(
                                                                        'ym_api_fail'
                                                                    ) == 'wc_checkout') ? ' selected' : ''); ?>>
                                <?= __('Страница оформления заказа от WooCommerce', 'yandexcheckout');?>
                            </option>
                            <option value="wc_payment" <?php echo((get_option(
                                                                       'ym_api_fail'
                                                                   ) == 'wc_payment') ? ' selected' : ''); ?>>
                                <?= __('Страница оплаты заказа от WooCommerce', 'yandexcheckout');?>
                            </option>
                            <?php
                            if ($pages) {
                                foreach ($pages as $page) {
                                    $selected = ($page->ID == get_option('ym_api_fail')) ? ' selected' : '';
                                    echo '<option value="'.$page->ID.'"'.$selected.'>'.$page->post_title.'</option>';
                                }
                            }
                            ?></select>
                        <br/><span class="help-text"> <?= __('Эту страницу увидит покупатель, если что-то пойдет не так: например, если ему не хватит денег на карте', 'yandexcheckout');?><span>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?= __('Отправлять в Яндекс.Кассу данные для чеков (54-ФЗ)', 'yandexcheckout');?></th>
                    <td>
                        <input type="checkbox" id="ym_api_enable_receipt"
                               name="ym_api_enable_receipt" <?php echo $isReceiptEnabled == 'on' ? "checked" : ""; ?> >
                    </td>
                </tr>
                <tr valign="top">
                    <th></th>
                    <td>
                        <?php if ($isReceiptEnabled): ?>
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?= __('Ставка по умолчанию', 'yandexcheckout');?></th>
                                    <td>
                                        <select id="ym_api_default_tax_rate" name="ym_api_default_tax_rate">
                                            <?php foreach ($ymTaxRatesEnum as $taxId => $taxName) : ?>
                                                <option value="<?php echo $taxId ?>" <?php echo $taxId == get_option(
                                                    'ym_api_default_tax_rate'
                                                ) ? 'selected=\'selected\'' : ''; ?>><?php echo $taxName ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <br/>
                                        <span class="help-text"><?= __('Ставка по умолчанию будет в чеке, если в карточке товара не указана другая ставка.', 'yandexcheckout');?><span>
                                    </td>
                                </tr>
                            </table>

                            <?php if ($wcCalcTaxes == 'yes' && $wcTaxes) : ?>
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row"><?= __('Сопоставьте ставки', 'yandexcheckout');?></th>
                                    </tr>
                                </table>
                                <table class="form-table">
                                    <tr valign="top">
                                        <td scope="row">
                                            <?= __('Ставка в вашем магазине','yandexcheckout');?>
                                        </td>

                                        <td>
                                            <?= __('Ставка для чека в налоговую', 'yandexcheckout');?>
                                        </td>
                                    </tr>
                                    <?php foreach ($wcTaxes as $wcTax) : ?>
                                        <tr valign="top">
                                            <th scope="row" style="padding-left: 10px;"><?php echo round(
                                                    $wcTax->tax_rate
                                                ) ?>%
                                            </th>
                                            <td>
                                                <div>
                                                    <?php $selected = isset($ymTaxes[$wcTax->tax_rate_id]) ? $ymTaxes[$wcTax->tax_rate_id] : null; ?>
                                                    <select id="ym_api_tax_rate[<?php echo $wcTax->tax_rate_id ?>]"
                                                            name="ym_api_tax_rate[<?php echo $wcTax->tax_rate_id ?>]">
                                                        <?php foreach ($ymTaxRatesEnum as $taxId => $taxName) : ?>
                                                            <option value="<?php echo $taxId ?>" <?php echo $selected == $taxId ? 'selected' : ''; ?> >
                                                                <?php echo $taxName ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <br/>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <table class="form-table">

                <tr valign="top">
                    <th scope="row"><?= __('Url для уведомлений', 'yandexcheckout');?></th>
                    <td><code><?php echo site_url('/?yandex_money=callback', 'https'); ?></code><br>
                        <span class="help-text"><?= __('Этот адрес понадобится, только если его попросят специалисты Яндекс.Кассы', 'yandexcheckout');?><span>
                    </td>
                </tr>
            </table>

            <table class="form-table">

                <tr valign="top">
                    <th scope="row"></th>
                    <td>
                        <input type="checkbox" id="ym_force_clear_cart"
                               name="ym_force_clear_cart" <?php echo $forceClearCart == 'on' ? "checked" : ""; ?> >
                        <span class="help-text"><?= __('Удалить товары из корзины, когда покупатель переходит к оплате.', 'yandexcheckout');?><span>
                    </td>
                </tr>
            </table>

            <table class="form-table">

                <tr valign="top">
                    <th scope="row"><?= __('Запись отладочной информации', 'yandexcheckout');?></th>
                    <td>
                        <input type="checkbox" id="ym_debug_enabled"
                               name="ym_debug_enabled" <?php echo $isDebugEnabled == 'on' ? "checked" : ""; ?> >
                        <span class="help-text"><?= __('Настройку нужно будет поменять, только если попросят специалисты Яндекс.Денег', 'yandexcheckout');?><span>
                    <?php if ($isDebugEnabled && file_exists(WP_CONTENT_DIR.'/ym-checkout-debug.log')): ?>
                        <br>
                        <br>
                        <div>
                            <a class="button-primary" href="<?= content_url(); ?>/ym-checkout-debug.log"
                               target="_blank" rel="nofollow" download="debug.log"><?= __('Скачать лог', 'yandexcheckout');?></a>
                        </div>
                    <?php endif; ?>
                    </td>
                </tr>
            </table>


            <input type="hidden" name="action" value="update"/>
            <input type="hidden" name="page_options"
                   value="ym_api_shop_id,ym_api_shop_password,ym_api_success,ym_api_fail,ym_api_pay_mode,ym_api_epl_installments,ym_api_enable_receipt,ym_api_default_tax_rate,ym_api_tax_rate"/>
            <p class="submit">
                <input type="submit" id="save-settings" class="button-primary"
                       value="<?php echo __('Сохранить изменения', 'yandexcheckout') ?>"/>
            </p>
        </form>
    </div>

    <div class="tab-panel"
         id="yandex-checkout-transactions" <?php echo $active_tab != 'yandex-checkout-transactions' ? 'style="display: none;' : ''; ?>>
        <form id="events-filter" method="POST">
            <?php
            TransactionsListTable::render();
            ?>
        </form>
    </div>
</div>
<!-- End tabs -->


