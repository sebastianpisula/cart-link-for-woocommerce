<?php

namespace Tests\Unit\Campaign;

use Example\Plugin\Campaign\ModifyPermalink;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class ModifyPermalinkTest extends TestCase {
	/**
	 * @var ModifyPermalink
	 */
	private $modify_permalink_unter_tests;

	public function setUp(): void {
		WP_Mock::setUp();

		$this->modify_permalink_unter_tests = new ModifyPermalink();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function testShouldAddHooks(): void {
		// Expects
		WP_Mock::expectFilterAdded( 'post_type_link', [
			$this->modify_permalink_unter_tests,
			'post_type_link'
		] );

		WP_Mock::expectFilterAdded( 'get_sample_permalink', [
			$this->modify_permalink_unter_tests,
			'get_sample_permalink'
		], 10, 2 );

		// When
		$this->modify_permalink_unter_tests->hooks();

		// Then
		self::assertTrue( true );
	}

	public function testShouldGetDefaultPostTypeLink() {
		// Except.
		/** @var \WP_Post $post */
		$post = \Mockery::mock( 'WP_Post' );

		WP_Mock::userFunction( 'get_post_type' )->once()->with( $post )->andReturn( 'unknown' );

		// Given.
		$expected = 'https://example.pl/';

		// When.
		$actual = $this->modify_permalink_unter_tests->post_type_link( $expected, $post );

		// Then.
		$this->assertEquals( $expected, $actual );
	}
}
