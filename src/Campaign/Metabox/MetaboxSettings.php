<?php

namespace Example\Plugin\Campaign\Metabox;

use Example\Plugin\Campaign\Campaign;
use Example\Plugin\Campaign\RegisterPostType;
use WP_Post;

class MetaboxSettings {

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
	}

	/**
	 * @return void
	 */
	public function add_meta_boxes(): void {
		add_meta_box(
			'campaign-settings',
			__( 'Settings', 'plugin-slug' ),
			[ $this, 'render_metabox' ],
			RegisterPostType::POST_TYPE,
			'side',
			'low'
		);
	}

	/**
	 * @param WP_Post $post .
	 *
	 * @return void
	 */
	public function render_metabox( WP_Post $post ): void {
		$campaign = new Campaign( $post->ID );

		echo '<div class="ic-campaign-container">';

		woocommerce_form_field(
			Campaign::META_CLEAR_CART,
			[
				'label' => __( 'Clear cart when visiting this url', 'plugin-slug' ),
				'type'  => 'checkbox',
			],
			(int) $campaign->clear_cart()
		);

		woocommerce_form_field(
			Campaign::META_REDIRECT_TO,
			[
				'label'             => __( 'Redirect to', 'plugin-slug' ),
				'type'              => 'select',
				'options'           => [ '' => '' ] + $this->get_pages(),
				'required'          => true,
				'custom_attributes' => [
					'required' => 'required',
				],
				'input_class'       => [ 'wc-page-search', ],
			],
			$campaign->get_redirect_page_id()
		);
		echo '</div>';
	}

	/**
	 * @return array<int, string>
	 */
	private function get_pages(): array {
		return wp_list_pluck( get_pages(), 'post_title', 'ID' );
	}
}
