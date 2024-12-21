<?php
//add_action('wp', function () {
    if (get_option('woocommerce_checkout_full_name_fields', '') == 'merged') {
        add_filter('woocommerce_default_address_fields', function ($fields) {
            $default_priority = 10;
            $required = true;
            if (isset($fields['last_name'])) {
                if (isset($fields['last_name']['priority']))
                    $default_priority = $fields['last_name']['priority'];
                $required = isset($fields['last_name']['required']) ? $fields['last_name']['required'] : false;
                unset($fields['last_name']);
            }
            if (isset($fields['first_name'])) {
                if (isset($fields['first_name']['priority']))
                    $default_priority = $fields['first_name']['priority'];
                $required = isset($fields['first_name']['required']) ? $fields['first_name']['required'] : false;
                unset($fields['first_name']);
            }
            $fields['full_name'] = array(
                'label' => __('Full Name', 'xstore'),
                'required' => $required,
                'class' => array('form-row-wide'),
                'autocomplete' => 'given-name',
                'priority' => $default_priority,
            );
            return $fields;
        });
        foreach (array('billing', 'shipping') as $address_type) {
            add_filter('woocommerce_admin_' . $address_type . '_fields', function ($fields) use ($address_type) {
                global $post;
                global $theorder;
                $display = true;
                $first_name_field_name = $address_type . '_first_name';

                if (is_callable(array($theorder, 'get_' . $first_name_field_name))) {
                    $first_name = $theorder->{"get_$first_name_field_name"}('edit');
                } elseif (is_callable(array($theorder, 'get_meta'))) {
                    $first_name = $theorder->get_meta('_' . $first_name_field_name);
                }
                else{
                    $first_name = '';
                }

                if ($first_name) {
                    $display = false;
                } else {
                    $last_name_field_name = $address_type . '_last_name';

                    if (is_callable(array($theorder, 'get_' . $last_name_field_name))) {
                        $last_name = $theorder->{"get_$last_name_field_name"}('edit');
                    } elseif (is_callable(array($theorder, 'get_meta'))) {
                        $last_name = $theorder->get_meta('_' . $last_name_field_name);
                    }
                    else {
                        $last_name = '';
                    }
                    if ($last_name)
                        $display = false;
                }

                // prevent display full_name input field if previously was set first name or last name
                if (!$display)
                    return $fields;

                $show = true;
                if (isset($fields['last_name']) && isset($fields['last_name']['show']))
                    $show = $fields['last_name']['show'];
                if (isset($fields['first_name']) && isset($fields['first_name']['show']))
                    $show = $fields['first_name']['show'];
                return array_merge(array(
                    'full_name' => array(
                        'label' => __('Full Name', 'xstore'),
                        'show' => $show,
                        'class' => 'wide',
                        'placeholder' => esc_html__('Full Name', 'xstore')
                    )
                ), $fields);
            });
        }
        add_filter('woocommerce_get_order_address', function ($address_data, $address_type, $object) {
            $full_name_key = 'full_name';
            $full_name = $object->get_meta('_' . $address_type . '_' . $full_name_key);
            if ($full_name)
                $address_data[$full_name_key] = $full_name;
            return $address_data;
        }, 10, 3);
        add_filter('woocommerce_formatted_address_replacements', function ($replacement, $args) {
            if (isset($args['full_name'])) {
                $replacement['{first_name}'] = $args['full_name'];
                $replacement['{name}'] = $args['full_name'];
            }
            return $replacement;
        }, 10, 2);
        add_filter('woocommerce_order_formatted_billing_address', function ($raw_address, $object) {
            $address_type = 'billing';
            $full_name_key = 'full_name';
            $full_name = $object->get_meta('_' . $address_type . '_' . $full_name_key);
            if ($full_name)
                $raw_address[$full_name_key] = $full_name;
            return $raw_address;
        }, 10, 2);
//        add_filter('woocommerce_localisation_address_formats', function ($formats) {
//            $formats_rendered = array();
//            foreach ($formats as $format_key => $format_value) {
//                $formats_rendered[$format_key] = str_replace("{name}", "{full_name}\n{name}", $format_value);
//            }
//            return $formats_rendered;
//        }, 100, 1);

        add_action('woocommerce_edit_account_form_start', function () {
           $user = get_user_by( 'id', get_current_user_id() ); ?>
            <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-wide">
                <p><?php esc_html_e( 'Full Name', 'xstore' ); ?>: <i><?php echo implode(' ', array($user->first_name, $user->last_name)); ?></i></p>
            </p>
            <?php
        });
        /**
         * Filter values in emails
         * replaces basic {first_name} with {full_name} set in billing details when making new order
         *
         */
        add_filter('woocommerce_order_get_billing_first_name', function ($value, $object) {
            $full_name_key = 'full_name';
            $full_name = $object->get_meta('_billing_' . $full_name_key);
            if ($full_name)
                $value = $full_name;
            return $value;
        }, 10, 2);

        add_filter('woocommerce_customer_get_first_name', function ($value, $object) {
            $full_name_key = 'full_name';
            $full_name = $object->get_meta('billing_' . $full_name_key);
            if ($full_name)
                $value = $full_name;
            return $value;
        }, 10, 2);
//});
    }