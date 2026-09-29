<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<section class="py-5 login" id="customer_login">
	<div class="container">
		<div class="row">
			<div class="col-md-6 offset-md-3">
				<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

					<h2><?php esc_html_e( 'Login', 'bgh-theme' ); ?></h2>
					<?php do_action( 'woocommerce_login_form_start' ); ?>
					<div class="form-group">
						<label for="username"><strong><?php esc_html_e( 'Username or email address', 'bgh-theme' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></strong></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
					</div>
					<div class="form-group">
						<label for="password"><strong><?php esc_html_e( 'Password', 'bgh-theme' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></strong></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text form-control" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
					</div>

					<?php do_action( 'woocommerce_login_form' ); ?>

					<div class="form-group">
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<label for="rememberme" class="woocommerce-form__label woocommerce-form__label-for-checkbox inline form-check-label">
							<span><?php esc_html_e( 'Remember me', 'bgh-theme' ); ?></span>
						</label>
					</div>
					<button type="submit" class="button primary-cta mb-3<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'bgh-theme' ); ?>"><?php esc_html_e( 'Log in', 'bgh-theme' ); ?></button>
					<p class="woocommerce-LostPassword lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'bgh-theme' ); ?></a>
					</p>
					<?php do_action( 'woocommerce_login_form_end' ); ?>
				</form>
			</div> <!-- col-12 -->
		</div>
	</div>
</section>
<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
	<section class="py-5 register">
		<div class="container">
			<div class="row">
				<div class="col-md-6 offset-md-3">
					<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
						<h2><?php esc_html_e( 'Register', 'bgh-theme' ); ?></h2>
						<?php do_action( 'woocommerce_register_form_start' ); ?>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

							<div class="form-group">
								<label for="reg_username"><strong><?php esc_html_e( 'Username', 'bgh-theme' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></strong></label>
								<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
							</div>

						<?php endif; ?>

						<div class="form-group">
							<label for="reg_email"><strong><?php esc_html_e( 'Email address', 'bgh-theme' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></strong></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
						</div>

						<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

							<div class="form-group">
								<label for="reg_password"><strong><?php esc_html_e( 'Password', 'bgh-theme' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></strong></label>
								<input type="password" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
							</div>

						<?php endif; ?>

						<?php do_action( 'woocommerce_register_form' ); ?>

						<div class="form-group">
							<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
							<button type="submit" class="button primary-cta<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'bgh-theme' ); ?>"><?php esc_html_e( 'Register', 'bgh-theme' ); ?></button>
						</div>

						<?php do_action( 'woocommerce_register_form_end' ); ?>

					</form>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
