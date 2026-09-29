<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook - woocommerce_before_edit_account_form.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_before_edit_account_form' );
?>


<form class="woocommerce-EditAccountForm edit-account white" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
	<div class="form-row mb-3">
		<div class="form-group col-md-6">
			<label for="account_first_name">
				<?php esc_html_e( 'First name', 'bgh-theme' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
			</label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" />
		</div>
		<div class="form-group col-md-6">
			<label for="account_last_name">
				<?php esc_html_e( 'Last name', 'bgh-theme' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
			</label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" aria-required="true" />
		</div>
	</div>
	<div class="form-row mb-3">
		<div class="form-group col-md-6">
			<label for="account_email">
				<?php esc_html_e( 'Email address', 'bgh-theme' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span>
			</label>
			<input type="email" class="woocommerce-Input woocommerce-Input--email input-text form-control" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" aria-required="true" />
		</div>
	</div>

	<?php
		/**
		 * Hook where additional fields should be rendered.
		 *
		 * @since 8.7.0
		 */
		do_action( 'woocommerce_edit_account_form_fields' );
	?>

	<fieldset>
		<div class="form-row">
			<div class="form-group col">
				<legend><?php esc_html_e( 'Password change', 'bgh-theme' ); ?></legend>
			</div>
		</div>
		<div class="form-row mb-3">
			<div class="form-group col-md-6">
				<label for="password_current">
					<?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'bgh-theme' ); ?>
				</label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text form-control" name="password_current" id="password_current" autocomplete="current-password" />
			</div>
		</div>
		<div class="form-row mb-3">
			<div class="form-group col-md-6">
				<label for="password_1">
					<?php esc_html_e( 'New password (leave blank to leave unchanged)', 'bgh-theme' ); ?>
				</label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text form-control" name="password_1" id="password_1" autocomplete="new-password" />
			</div>
			<div class="form-group col-md-6">
				<label for="password_2">
					<?php esc_html_e( 'Confirm new password', 'bgh-theme' ); ?>
				</label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text form-control" name="password_2" id="password_2" autocomplete="new-password" />
			</div>
		</div>
	</fieldset>
	<div class="clear"></div>

	<?php
		/**
		 * My Account edit account form.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_edit_account_form' );
	?>

	<div class="form-row">
		<div class="col">
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<input type="hidden" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
			<button type="submit" class="button primary-cta<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'bgh-theme' ); ?>">
				<?php esc_html_e( 'Save changes', 'bgh-theme' ); ?>
			</button>
			<input type="hidden" name="action" value="save_account_details" />
		</div>
	</div>
	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
