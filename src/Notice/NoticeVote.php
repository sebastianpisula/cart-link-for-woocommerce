<?php

namespace Example\Plugin\Notice;

use Example\Plugin\PluginData;

class NoticeVote {
	public const OPTION_NAME_USED = 'campaign_used';
	public const OPTION_NAME_VOTE = 'redirect_to_vote';

	public const ACTION       = 'ic_vote_redirect';
	public const REDIRECT_URL = 'https://wordpress.org/support/plugin/woocommerce/reviews?rate=5#new-post';

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	/**
	 * @param PluginData $plugin_data
	 */
	public function __construct( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'admin_notices', [ $this, 'display_notice' ] );
		add_action( 'admin_post_' . self::ACTION, [ $this, 'redirect_to_vote' ] );
	}

	/**
	 * @return void
	 */
	public function redirect_to_vote() {
		update_option( self::OPTION_NAME_VOTE, true );

		wp_redirect( self::REDIRECT_URL );
		die();
	}

	/**
	 * @return void
	 */
	public function display_notice(): void {
		if ( ! $this->should_display_notice() ) {
			return;
		}

		$url = $this->get_url();

		include $this->plugin_data->get_plugin_absolute_path( 'views/html-notice-vote.php' );
	}

	/**
	 * @return bool
	 */
	private function should_display_notice(): bool {
		$campaign_used    = (bool) get_option( self::OPTION_NAME_USED, false );
		$redirect_to_vote = (bool) get_option( self::OPTION_NAME_VOTE, false );

		return $campaign_used && ! $redirect_to_vote;
	}

	/**
	 * @return string
	 */
	private function get_url(): string {
		return add_query_arg( 'action', self::ACTION, admin_url( 'admin-post.php' ) );
	}
}
