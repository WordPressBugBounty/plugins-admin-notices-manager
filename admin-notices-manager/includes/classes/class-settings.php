<?php
/**
 * Contains class Settings.
 *
 * @package AdminNoticesManager
 */

declare(strict_types=1);

namespace AdminNoticesManager;

use AdminNoticesManager\Select2_WPWS;
use AdminNoticesManager\Rational_Option_Pages;

if ( ! class_exists( '\AdminNoticesManager\Settings' ) ) {
	/**
	 * Takes care of the admin notices content capture.
	 *
	 * @package AdminNoticesManager
	 * @since   1.0.0
	 */
	class Settings {

		/**
		 * Page slug of the plugin settings page, used to match the current screen for the Black Friday banner.
		 *
		 * @var string
		 *
		 * @since 1.6.2
		 */
		public const PLUGIN_PAGE_SLUG = 'admin_notices_settings';

		/**
		 * Name of the option storing the plugin settings.
		 *
		 * @var string
		 */
		private static $option_name = 'anm_settings';

		/**
		 * Holds the array with the plugin options
		 *
		 * @var array
		 *
		 * @since latest
		 */
		private static $options = array();

		/**
		 * Settings constructor.
		 */
		public static function init() {

			\add_filter( 'plugin_action_links', array( __CLASS__, 'add_settings_link' ), 10, 2 );

			\add_action( 'all_admin_notices', array( __CLASS__, 'extra_event_banner' ), 1000000 );
			\add_action( 'wp_ajax_anm_dismiss_extra_event_banner', array( __CLASS__, 'dismiss_extra_event_banner' ) );

			$options = self::get_settings();

			$notice_handling_options = array(
				'popup-only' => esc_html__( 'Hide from the WordPress dashboard and show them in the plugin\'s popup', 'admin-notices-manager' ),
				'hide'       => esc_html__( 'Hide them completely (do not show in the WordPress dashboard or in the plugin\'s popup)', 'admin-notices-manager' ),
				'leave'      => esc_html__( 'Do not do anything (they will appear on the WordPress dashboard as per usual)', 'admin-notices-manager' ),
			);

			$system_notices_options = $notice_handling_options;
			unset( $system_notices_options['hide'] );

			$standard_notices = array(
				'success' => esc_html__( 'Success level notices', 'admin-notices-manager' ),
				'error'   => esc_html__( 'Error level notices', 'admin-notices-manager' ),
				'warning' => esc_html__( 'Warning level notices', 'admin-notices-manager' ),
				'info'    => esc_html__( 'Information level notices', 'admin-notices-manager' ),
			);

			$standard_notices_section_fields = array();
			foreach ( $standard_notices as $notice_type => $notice_field_title ) {
				$field_name                                     = $notice_type . '-notices';
				$standard_notices_section_fields[ $field_name ] = array(
					'title'   => $notice_field_title,
					'type'    => 'radio',
					'value'   => array_key_exists( $field_name, $options ) ? $options[ $field_name ] : 'popup-only',
					'choices' => $notice_handling_options,
				);
			}

			$popup_style_options = array(
				'slide-in' => esc_html__( 'Slide in from the right', 'admin-notices-manager' ),
				'popup'    => esc_html__( 'Popup', 'admin-notices-manager' ),
			);

			$notification_count = ( get_site_option( 'anm_update_notice_needed', false ) ) ? 1 : 0;

			$pages = array(
				self::$option_name => array(
					'menu_title'  => esc_html__( 'Admin notices settings', 'admin-notices-manager' ),
					'menu_slug'   => self::PLUGIN_PAGE_SLUG,
					'parent_slug' => 'options-general.php',
					'page_title'  => esc_html__( 'Admin notices settings', 'admin-notices-manager' ),
					'text'        => 'Use the settings in this page to configure how the plugin should handle different types of admin notices. Refer to the introduction to admin notices for a detailed explanation about the different types of admin notices available in WordPress.',
					'sections'    => array(
						'standard-notices'     => array(
							'title'  => esc_html__( 'Standard admin notices', 'admin-notices-manager' ),
							'fields' => $standard_notices_section_fields,
						),
						'non-standard-notices' => array(
							'title'  => esc_html__( 'Non-Standard admin notices', 'admin-notices-manager' ),
							'text'   => esc_html__( 'These type of admin notices are typically created by third party plugins and themes and do not have any severity level. Use the below settings to configure how the plugin should handle these type of admin notices.', 'admin-notices-manager' ),
							'fields' => array(
								'no-level-notices' => array(
									'title'   => esc_html__( 'No level notices', 'admin-notices-manager' ),
									'type'    => 'radio',
									'value'   => array_key_exists( 'no-level-notices', $options ) ? $options['no-level-notices'] : 'popup-only',
									'choices' => $notice_handling_options,
								),
								'exceptions'       => array(
									'title' => esc_html__( 'CSS selector', 'admin-notices-manager' ),
									'type'  => 'text',
									'value' => array_key_exists( 'css_selector', $options ) ? $options['css_selector'] : '',
									'text'  => esc_html__( 'Plugin will ignore all notices matching this CSS selector. Use jQuery compatible CSS selector. You can specify multiple selectors and comma separate them.', 'admin-notices-manager' ),
								),
							),
						),
						'system-notices'       => array(
							'title'  => esc_html__( 'WordPress system admin notices', 'admin-notices-manager' ),
							'text'   => esc_html__( 'These type of admin notices are used by WordPress to advise you about the status of specific actions, for example to confirm that the changed settings were saved, or that a plugin was successfully installed. It is recommended to let these admin notices appear in the WordPress dashboard.', 'admin-notices-manager' ),
							'fields' => array(
								'system-level-notices' => array(
									'title'   => esc_html__( 'WordPress system admin notices', 'admin-notices-manager' ),
									'type'    => 'radio',
									'value'   => array_key_exists( 'system-level-notices', $options ) ? $options['system-level-notices'] : 'leave',
									'choices' => $system_notices_options,
								),
							),
						),
						'user-visibility'      => array(
							'title'  => esc_html__( 'Hiding notifications', 'admin-notices-manager' ),
							'text'   => esc_html__( 'Plugin can hide the notifications from specific users or display them only to certain selected users. Use the below settings to configure this behaviour.', 'admin-notices-manager' ),
							'fields' => array(
								'user-visibility' => array(
									'title'    => esc_html__( 'Visibility', 'admin-notices-manager' ),
									'type'     => 'radio',
									'custom'   => true,
									'callback' => array( __CLASS__, 'render_user_visibility_field' ),
									'value'    => array_key_exists( 'user-visibility', $options ) ? $options['user-visibility'] : 'all',
									'choices'  => array(
										'all' => esc_html__( 'Hide notifications from all users', 'admin-notices-manager' ),
										'hide-for-selected' => esc_html__( 'Hide notifications only from these users', 'admin-notices-manager' ),
										'show-for-selected' => esc_html__( 'Hide notifications to all users but not these', 'admin-notices-manager' ),
									),
									'sanitize' => false, // Stops default sanitization. It would break the data.
								),
							),
						),
						'styling'              => array(
							'title'  => esc_html__( 'Admin notices popup styling', 'admin-notices-manager' ),
							'text'   => esc_html__( 'How do you want ANM to look?', 'admin-notices-manager' ),
							'fields' => array(
								'popup-style'         => array(
									'title'   => esc_html__( 'Popup style', 'admin-notices-manager' ),
									'type'    => 'radio',
									'value'   => array_key_exists( 'popup-style', $options ) ? $options['popup-style'] : 'slide-in',
									'choices' => $popup_style_options,
								),
								'slide_in_background' => array(
									'title' => esc_html__( 'Slide in background colour', 'admin-notices-manager' ),
									'type'  => 'color',
									'value' => array_key_exists( 'popup-style', $options ) ? $options['popup-style'] : '#1d2327',
								),
							),
						),
						'purge'                => array(
							'title'  => esc_html__( 'Restore hidden admin notices', 'admin-notices-manager' ),
							'text'   => esc_html__( 'Reset the current list of hidden admin notices from the database so they are displayed again.', 'admin-notices-manager' ),
							'fields' => array(
								'purge_now' => array(
									'title'    => esc_html__( 'Reset list now', 'admin-notices-manager' ),
									'type'     => 'text',
									'value'    => '',
									'custom'   => true,
									'callback' => array( __CLASS__, 'render_purge_field' ),
									'text'     => '',
									'sanitize' => false,
								),
							),
						),
					),
				),
			);

			new Rational_Option_Pages( $pages );
		}

		/**
		 * Retrieve plugin settings.
		 *
		 * @return array
		 */
		public static function get_settings() {
			return \wp_parse_args(
				\get_option( self::$option_name, array() ),
				array(
					'success_level_notices'          => 'popup-only',
					'error_level_notices'            => 'popup-only',
					'warning_level_notices'          => 'popup-only',
					'information_level_notices'      => 'popup-only',
					'no_level_notices'               => 'popup-only',
					'wordpress_system_admin_notices' => 'leave',
					'popup_style'                    => 'slide-in',
					'slide_in_background'            => '#1d2327',
					'css_selector'                   => '',
					'visibility'                     => array( 'choice' => 'all' ),
				)
			);
		}

		/**
		 * Checks if hiding of notices is allowed according to the plugin settings.
		 *
		 * @return bool True if notices' hiding is allowed for current user.
		 *
		 * @since latest
		 */
		public static function notice_hiding_allowed_for_current_user() {
			if ( ! is_user_logged_in() ) {
				return false;
			}

			$settings = self::get_settings();
			if ( ! array_key_exists( 'visibility', $settings ) || ! array_key_exists( 'choice', $settings['visibility'] ) ) {
				return true;
			}

			if ( 'all' === $settings['visibility']['choice'] ) {
				return true;
			}

			if ( 'hide-for-selected' === $settings['visibility']['choice']
			&& array_key_exists( 'hide-users', $settings['visibility'] )
			&& is_array( $settings['visibility']['hide-users'] )
			&& in_array( get_current_user_id(), $settings['visibility']['hide-users'] ) ) {
				return false;
			}

			if ( 'show-for-selected' === $settings['visibility']['choice']
			&& array_key_exists( 'show-users', $settings['visibility'] )
			&& is_array( $settings['visibility']['show-users'] )
			&& ! in_array( get_current_user_id(), $settings['visibility']['show-users'] ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Renders custom user visibility field(s).
		 *
		 * @param array               $field        Field data.
		 * @param string              $page_key     Settings page key.
		 * @param string              $section_key  Settings section key.
		 * @param string              $field_key    Field key.
		 * @param RationalOptionPages $option_pages Rational option pages object.
		 *
		 * @since latest
		 *
		 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		 */
		public static function render_user_visibility_field( $field, $page_key = '', $section_key = '', $field_key = '', $option_pages = '' ) {
			if ( ! class_exists( Select2_WPWS::class, false ) ) {
				return;
			}

			echo '<fieldset><legend class="screen-reader-text">' . $field['title'] . '</legend>';

			$options        = $option_pages->get_options();
			$field['value'] = isset( $options[ $field['id'] ]['choice'] ) ? $options[ $field['id'] ]['choice'] : 'all';

			$counter = 0;
			foreach ( $field['choices'] as $value => $label ) {
				$checked = 0 === strlen( $value ) || $value === $field['value'];
				if ( isset( self::$options[ $field['id'] ] ) ) {
					$checked = $value === self::$options[ $field['id'] ];
				}

				if ( is_null( $field['value'] ) && 'all' === $value ) {
					$checked = true;
				}

				$field_name = "{$page_key}[{$field['id']}]";
				printf(
					'<label><input %s %s id="%s" name="%s" type="radio" title="%s" value="%s">&nbsp; %s</label>',
					checked( $checked, true, false ),
					! empty( $field['class'] ) ? "class='{$field['class']}'" : '',
					$field['id'] . '-' . $value,
					$field_name . '[choice]',
					$label,
					$value,
					$label
				);

				echo '<br />';

				if ( 'all' === $value ) {
					continue;
				}

				if ( 'hide-for-selected' === $value ) {
					Select2_WPWS::insert(
						self::build_user_select_params(
							$options,
							$field_name,
							$field,
							$checked,
							'hide-users'
						)
					);
				} elseif ( 'show-for-selected' === $value ) {
					Select2_WPWS::insert(
						self::build_user_select_params(
							$options,
							$field_name,
							$field,
							$checked,
							'show-users'
						)
					);
				}

				echo $counter < count( $field['choices'] ) - 1 ? '<br>' : '';
				++$counter;
			}
			echo '</fieldset>';
		}

		/**
		 * Renders custom user visibility field(s).
		 *
		 * @param array               $field        Field data.
		 * @param string              $page_key     Settings page key.
		 * @param string              $section_key  Settings section key.
		 * @param string              $field_key    Field key.
		 * @param RationalOptionPages $option_pages Rational option pages object.
		 *
		 * @since latest
		 *
		 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		 */
		public static function render_purge_field( $field, $page_key, $section_key, $field_key, $option_pages ) {
			$nonce = wp_create_nonce( 'anm_purge_notices_nonce' );
			echo '<a href="#" class="button button-secondary" id="anm-purge-btn" data-nonce="' . esc_attr( $nonce ) . '">' . esc_html__( 'Reset', 'admin-notices-manager' ) . '</a> <span id="anm-notice-purged-text">' . esc_html__( 'Notices restored', 'admin-notices-manager' ) . '</span>';
		}

		/**
		 * Add Settings link to plugin list
		 *
		 * Add a Settings link to the options listed against this plugin
		 *
		 * @param array  $links  Current links.
		 * @param string $file   File in use.
		 *
		 * @return string          Links, now with settings added.
		 *
		 * @since 1.5.0
		 */
		public static function add_settings_link( $links, $file ) {

			if ( ADMIN_NOTICES_BASENAME === $file ) {
				$settings_link = '<a href="' . \add_query_arg(
					array(
						'page' => self::PLUGIN_PAGE_SLUG,
					),
					\admin_url( 'options-general.php' )
				) . '">' . __( 'Settings', 'admin-notices-manager' ) . '</a>';
				array_unshift( $links, $settings_link );
			}

			return $links;
		}

		/**
		 * Builds an array of parameters for the user selection form control.
		 *
		 * @param array  $options      Fields options.
		 * @param string $field_name   Field name.
		 * @param array  $field        Field data.
		 * @param bool   $checked      True if the field is checked.
		 * @param mixes  $option_value Option value.
		 *
		 * @return array
		 *
		 * @since latest.
		 */
		private static function build_user_select_params( $options, $field_name, $field, $checked, $option_value ) {
			$result = array(
				'placeholder'       => esc_html__( 'select user(s)', 'admin-notices-manager' ),
				'name'              => $field_name . '[' . $option_value . '][]',
				'width'             => 500,
				'data-type'         => 'user',
				'multiple'          => true,
				'extra_js_callback' => function ( $element_id ) {
					echo 'window.anm_settings.append_select2_events( s2 );';
				},
			);

			if ( $checked ) {
				$result['selected'] = $options[ $field['id'] ][ $option_value ];
			}

			return $result;
		}

		/**
		 * Checks whether a screen is the plugin settings page.
		 *
		 * @param \WP_Screen|null $screen - The screen to check.
		 *
		 * @return bool $is_plugin_screen - True if the screen is the plugin settings page.
		 *
		 * @since 1.6.2
		 */
		public static function is_extra_event_banner_screen( $screen ): bool {
			if ( ! $screen instanceof \WP_Screen ) {
				return false;
			}

			// The plugin supports PHP 7.4, so str_ends_with() (PHP 8.0) is not used.
			$suffix = '_page_' . self::PLUGIN_PAGE_SLUG;

			return substr( (string) $screen->base, -strlen( $suffix ) ) === $suffix;
		}

		/**
		 * Campaign phase of the Black Friday banner, in UTC.
		 *
		 * Each phase can be dismissed once. The reminder phase starts on 26 November
		 * even if an administrator first returns later, and 1 December is included in full.
		 *
		 * @return string $phase - Phase start date, or an empty string outside the campaign.
		 *
		 * @since 1.6.2
		 */
		public static function get_extra_event_banner_phase(): string {
			$today = \wp_date( 'Y-m-d', null, new \DateTimeZone( 'UTC' ) );

			if ( $today < '2026-11-17' || $today >= '2026-12-02' ) {
				return '';
			}

			if ( $today < '2026-11-26' ) {
				return '2026-11-17';
			}

			return '2026-11-26';
		}

		/**
		 * Shows the Black Friday banner on the plugin settings page.
		 *
		 * @return void
		 *
		 * @since 1.6.2
		 */
		public static function extra_event_banner() {
			if ( ! \current_user_can( 'manage_options' ) || ! self::is_extra_event_banner_screen( \get_current_screen() ) ) {
				return;
			}

			$phase = self::get_extra_event_banner_phase();

			if ( '' === $phase || \get_option( 'anm_extra_event_banner_dismissed', false ) === $phase ) {
				return;
			}
			?>
			<div class="black-friday anm-extra-event-banner">
				<img class="black-friday-svg" src="<?php echo \esc_url( ADMIN_NOTICES_MANAGER_URL . 'assets/img/upgrade-plugin-icon.svg' ); ?>" alt="" width="113" height="101">

				<div class="black-friday-content">
					<h2 class="black-friday-title"><?php \esc_html_e( 'Thank you for using our plugin!', 'admin-notices-manager' ); ?><br>
						<span class="bf-title-line-2">
						<?php
						printf(
							/* translators: %s: "up to 30% off this Black Friday", shown highlighted. */
							\esc_html__( 'Explore our premium WordPress security plugins, now %s.', 'admin-notices-manager' ),
							'<span class="bf-highlight">' . \esc_html__( 'up to 30% off this Black Friday', 'admin-notices-manager' ) . '</span>'
						);
						?>
						</span>
					</h2>
					<a href="https://melapress.com/black-friday/?utm_source=plugin&utm_medium=anm&utm_campaign=BFCM2026" target="_blank" rel="noopener noreferrer" class="bf-cta-link"><?php \esc_html_e( 'View deals', 'admin-notices-manager' ); ?></a>
					<p class="anm-extra-event-banner-error" role="alert" style="display:none;"><?php \esc_html_e( 'An error occurred. Please try again.', 'admin-notices-manager' ); ?></p>
				</div>

				<button type="button" aria-label="<?php \esc_attr_e( 'Close', 'admin-notices-manager' ); ?>" class="anm-extra-event-banner-close black-friday-close" data-dismiss-nonce="<?php echo \esc_attr( \wp_create_nonce( 'anm_dismiss_extra_event_banner' ) ); ?>"></button>
			</div>

			<script type="text/javascript">
				jQuery( document ).ready( () => {
					jQuery( 'body' ).on( 'click', '.anm-extra-event-banner-close', ( event ) => {
						const button = jQuery( event.currentTarget );
						const banner = button.closest( '.anm-extra-event-banner' );
						const error  = banner.find( '.anm-extra-event-banner-error' );

						error.hide();

						jQuery.ajax( {
							type: 'POST',
							url: '<?php echo \esc_url( \admin_url( 'admin-ajax.php' ) ); ?>',
							data: {
								action: 'anm_dismiss_extra_event_banner',
								nonce: button.attr( 'data-dismiss-nonce' ),
							},
							success: ( result ) => {
								if ( result && result.success ) {
									banner.slideUp( 300 );
								} else {
									error.show();
								}
							},
							error: () => {
								error.show();
							},
						} );
					} );
				} );
			</script>
			<style>
				@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=Quicksand:wght@600;700&display=swap');

				.anm-extra-event-banner.black-friday {
					--color-coral: #FF8977;
					--color-deep: #020E26;
					box-sizing: border-box;
					min-height: 126px;
					margin: 20px 20px 0 2px;
					padding: 12px 3rem 12px .6rem;
					background-color: var(--color-deep);
					background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 182 127"><path d="M181.413 -165.636L134.391 234.514L0 234.514L2.19345e-05 -165.636L181.413 -165.636Z" fill="%23B6C3F2"/></svg>');
					background-repeat: no-repeat;
					background-position: 0 0;
					font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
					-webkit-font-smoothing: subpixel-antialiased;
					color: #fff;
					display: flex;
					align-items: center;
					gap: 1.5rem;
					position: relative;
					overflow: hidden;
					border-left: 4px solid var(--color-coral);
				}

				.anm-extra-event-banner .black-friday-svg {
					flex-shrink: 0;
					width: 134px;
					height: 101px;
					margin-right: 32px;
					z-index: 1;
				}

				.anm-extra-event-banner .black-friday-content {
					z-index: 1;
				}

				.anm-extra-event-banner .black-friday-title {
					margin: 0 0 4px;
					font-family: Inter, sans-serif;
					font-size: 1.9em;
					font-weight: 900;
					line-height: .9em;
					letter-spacing: .5px;
					text-transform: uppercase;
					color: var(--color-coral);
				}

				.anm-extra-event-banner .bf-title-line-2 {
					color: #fff;
					font-size: .55em;
					line-height: 1.4em;
				}

				.anm-extra-event-banner .bf-highlight {
					color: var(--color-coral);
				}

				.anm-extra-event-banner .bf-cta-link {
					display: inline-block;
					margin: .25rem 0 0;
					padding: .55rem 1.3rem;
					border-radius: .25rem;
					background: #D9E4FD;
					color: #454BF7;
					font-size: .875rem;
					font-weight: bold;
					text-decoration: none;
					transition: all .2s ease-in-out;
				}

				.anm-extra-event-banner .bf-cta-link:hover,
				.anm-extra-event-banner .bf-cta-link:focus {
					background: #454BF7;
					color: #D9E4FD;
				}

				.anm-extra-event-banner .anm-extra-event-banner-error {
					margin: .5rem 0 0;
				}

				.anm-extra-event-banner .black-friday-close {
					position: absolute;
					top: 20px;
					right: 20px;
					width: 12px;
					height: 12px;
					padding: 0;
					border: none;
					background-color: transparent;
					background-image: url('<?php echo \esc_url( ADMIN_NOTICES_MANAGER_URL . 'assets/img/close-icon-reverse.svg' ); ?>');
					background-size: cover;
					cursor: pointer;
					z-index: 1;
				}

				@media (max-width: 1200px) {
					.anm-extra-event-banner.black-friday {
						flex-direction: column;
						gap: 1rem;
						padding: 1.66rem;
						background-image: none;
						text-align: center;
					}

					.anm-extra-event-banner .black-friday-svg {
						width: 90px;
						height: 80px;
						margin: 0;
					}
				}
			</style>
			<?php
		}

		/**
		 * Saves the current campaign phase as dismissed for this site.
		 *
		 * @return void
		 *
		 * @since 1.6.2
		 */
		public static function dismiss_extra_event_banner() {
			$nonce = isset( $_POST['nonce'] ) ? \sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';

			if ( ! \current_user_can( 'manage_options' ) || empty( $nonce ) || ! \wp_verify_nonce( $nonce, 'anm_dismiss_extra_event_banner' ) ) {
				\wp_send_json_error( \esc_html__( 'Nonce verification failed.', 'admin-notices-manager' ), 403 );
			}

			$phase = self::get_extra_event_banner_phase();

			if ( '' !== $phase ) {
				$option = 'anm_extra_event_banner_dismissed';

				// update_option() also returns false when the value is already saved.
				if ( ! \update_option( $option, $phase, false ) && \get_option( $option, false ) !== $phase ) {
					\wp_send_json_error( \esc_html__( 'An error occurred. Please try again.', 'admin-notices-manager' ) );
				}
			}

			\wp_send_json_success();
		}
	}
}
