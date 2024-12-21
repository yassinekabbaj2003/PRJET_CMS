<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) != 'yes' ) : ?>
    <p class="et-message et-info">
        <?php echo sprintf(esc_html__( 'Please, enable %1s option to make this feature work!', 'xstore-core' ), '<a href="'.admin_url( 'admin.php?page=wc-settings&tab=account' ).'" rel="nofollow"">'.esc_html__('Allow customers to create an account on the "My account" page', 'xstore-core') . '</a>'); ?>
    </p>
<?php endif; ?>

<?php $global_admin_class->xstore_panel_settings_select_field( $tab_content,
    'active_tab',
    esc_html__( 'Active tab', 'xstore-core' ),
    false,
    array(
        'login' => esc_html__( 'Login', 'xstore-core' ),
        'register'  => esc_html__( 'Register', 'xstore-core' ),
    ),
    'login' ); ?>

<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'login_text',
    esc_html__( 'Login text', 'xstore-core' ),
    esc_html__( 'Write your text to be displayed below the login heading or leave this field empty.', 'xstore-core' ),
    esc_html__('Please log in to your account below in order to continue shopping.', 'xstore-core') ); ?>

<?php $global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
    'register_text',
    esc_html__( 'Register text', 'xstore-core' ),
    esc_html__( 'Write your text to be displayed below the register heading or leave this field empty.', 'xstore-core' ),
    esc_html__('Registering for this site will give you access to your order status and history. Please fill in the fields below and we will quickly set up a new account for you. We will only ask you for information that is necessary to make the purchasing process faster and easier.', 'xstore-core') ); ?>
