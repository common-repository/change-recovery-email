<?php
/**
 * Setting Page Output.
 *
 * @since 1.0.0
 *
 * @package Change Recovery Email
 */

$email= '' != get_option( 're_email' ) ? get_option( 're_email' ) : get_option('admin_email');
?>

<h1><?php esc_html_e( 'Recovery Email', 're' ); ?></h1>

<form method="post">

	<?php wp_nonce_field( 'iwc-re-nonce', 'iwc-re-nonce' ); ?>

	<table class="form-table">

		<tbody>
			<tr>
				<th scope="row">
					<label for="recovery_email"><?php esc_html_e( 'Recovery Email', 're' ); ?></label>
				</th>
				<td>
					<input name="recovery_email" type="text" id="blogname" value="<?php echo sanitize_email( $email ); ?>" class="regular-text">
				</td>
			</tr>

		</tbody>
	</table>

	<p class="submit">
		<?php submit_button( __( 'Save Settings', 're' ), 'primary', 'iwc-re-submit', false ); ?>
	</p>

</form>