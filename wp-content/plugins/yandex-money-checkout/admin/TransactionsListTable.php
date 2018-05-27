<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}


class TransactionsListTable extends WP_List_Table
{

    public $pageSize = 10;

    /**
     *
     */
    public static function render()
    {
        $instance = new static();
        $instance->prepare_items();
        $instance->display();
    }

    /**
     * @inheritdoc
     */
    public function prepare_items()
    {

        $pageNumber  = $this->get_pagenum();
        $this->items = $this->getTransactions($this->pageSize, $pageNumber);
        $totalItems  = $this->getItemsCount();

        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = array();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page'    => $this->pageSize,
            'total_pages' => ceil($totalItems / $this->pageSize),
        ));
    }

    /**
     * @inheritdoc
     */
    public function get_columns()
    {
        $columns = array(
            'cb'             => '<input type="checkbox" />',
            'ID'             => __('ID заказа', 'yandexcheckout'),
            'transaction_id' => __('ID платежа', 'yandexcheckout'),
            'post_status'    => __('Статус', 'yandexcheckout'),
            'post_title'     => __('Описание заказа', 'yandexcheckout'),
        );

        return $columns;
    }

    /**
     * @inheritdoc
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'ID':
            case 'post_title':
            case 'transaction_id':
                return $item[$column_name];
                break;
            case 'post_status':
                return $this->getOrderStatusTitle($item[$column_name]);
                break;
            default:
                return print_r($item, true);
        }
    }

    /**
     * @inheritdoc
     */
    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-action[]" value="%s" />',
            $item['transaction_id']
        );
    }

    /**
     * @inheritdoc
     */
    public function get_bulk_actions()
    {
        $actions = array(
            'capture' => __('Провести платежи', 'yandexcheckout'),
        );

        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function process_bulk_action()
    {

        // security check!
        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {

            $nonce  = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
            $action = 'bulk-'.$this->_args['plural'];

            if (!wp_verify_nonce($nonce, $action)) {
                wp_die('Nope! Security check failed!');
            }

        }

        $action         = $this->current_action();
        $paymentCharger = new PaymentChargeDispatcher();
        if (isset($_POST['bulk-action'])) {
            $transactionIds = esc_sql($_POST['bulk-action']);
        } else {
            $transactionIds = array();
        }

        switch ($action) {
            case 'capture':
                foreach ($transactionIds as $transactionId) {
                    $paymentCharger->tryChargePayment($transactionId);
                }
                break;
            default:
                return;
                break;
        }

        return;
    }

    /**
     * @param $pageSize
     * @param $pageNumber
     *
     * @return array|null|object
     */
    protected function getTransactions($pageSize, $pageNumber)
    {
        global $wpdb;
        $offset = ($pageNumber - 1) * $pageSize;
        $query  = "
			SELECT p.ID, p.post_status, p.post_title, mt.meta_value AS transaction_id FROM $wpdb->posts p 
			INNER JOIN $wpdb->postmeta mm 
				ON p.ID = mm.post_id
			INNER JOIN $wpdb->postmeta mt 
				ON p.ID = mt.post_id
			WHERE mt.meta_key = '_transaction_id'
			AND mt.meta_value <> '' 
			AND mm.meta_key = '_payment_method' 
			AND mm.meta_value IN (
				'ym_api_alfabank',
				'ym_api_bank_card',
				'ym_api_cash',
				'ym_api_epl',
				'ym_api_qiwi',
				'ym_api_sberbank',
				'ym_api_wallet',
				'ym_api_webmoney',
				'ym_api_installments'
			) 
			ORDER BY p.post_date DESC
			LIMIT $pageSize
			OFFSET $offset";

        $result = $wpdb->get_results($query, 'ARRAY_A');

        return $result;
    }

    private function getItemsCount()
    {
        global $wpdb;
        $query = "
			SELECT COUNT(*) FROM $wpdb->posts p 
			INNER JOIN $wpdb->postmeta mm 
				ON p.ID = mm.post_id
			INNER JOIN $wpdb->postmeta mt 
				ON p.ID = mt.post_id
			WHERE mt.meta_key = '_transaction_id'
			AND mt.meta_value <> '' 
			AND mm.meta_key = '_payment_method' 
			AND mm.meta_value IN (
				'ym_api_alfabank',
				'ym_api_bank_card',
				'ym_api_cash',
				'ym_api_epl',
				'ym_api_qiwi',
				'ym_api_sberbank',
				'ym_api_wallet',
				'ym_api_webmoney',
				'ym_api_installments'
			)";

        $result = $wpdb->get_var($query);

        return $result;
    }

    private function getOrderStatusTitle($status)
    {
        $statusList = wc_get_order_statuses();
        if (in_array($status, array_keys($statusList))) {
            return $statusList[$status];
        }

        return '--';
    }
}