<?php

namespace Tests\Unit;

use Example\Plugin\PluginData;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class ExampleTest extends TestCase {
	public function setUp(): void {
		WP_Mock::setUp();
	}

	public function tearDown(): void {
		WP_Mock::tearDown();
	}

	public function testShould() {
		// Except.


		// Given.
		$plugin_data = new PluginData( __FILE__, 'Test', '1.0.0', '$text_domain');

		// When.

		// Then.
		$this->assertEquals( '1.0.0', $plugin_data->get_version() );
	}
}
