<?php
/**
 * Publish to Apple News tests: Test_End_Of_Article class
 *
 * @package Apple_News
 * @subpackage Tests
 */

/**
 * A class to test the behavior of the
 * Apple_Exporter\Components\End_Of_Article class.
 *
 * @package Apple_News
 * @subpackage Tests
 */
class Test_End_Of_Article extends Component_TestCase {

		/**
	 * Returns an array of arrays representing function arguments to the
	 * test_filter function.
	 */
	public function data_default_end_of_article_setting() {
		return [
			[ [ 'cover', 'slug', 'title', 'byline' ], 3, 4 ],
			[ [ 'cover', 'slug', 'title', 'author', 'date' ], 4, 5 ],
		];
	}

	/**
	 * Test default End Of Article behavior
	 *
	 * @dataProvider data_default_end_of_article_setting
	 */
	public function test_default_end_of_article_setting( $meta_order, $index ) {
		$this->set_theme_settings( [ 'meta_component_order' => $meta_order ] );
		$post_id = self::factory()->post->create();
		$json    = $this->get_json_for_post( $post_id );
		$this->assertEquals( $index, count( $json['components'] ) );
	}

	/**
	 * Test adding of End Of Article JSON content
	 *
	 * @dataProvider data_default_end_of_article_setting
	 */
	public function test_end_of_article_content( $meta_order, $index, $count ) {
		// Setup.
		$this->set_theme_settings(
			[
				'json_templates'       => [
					'end_of_article' => [
						'json'   => [
							'role'      => 'heading',
							'text'      => '<strong>Heading <em>1<\/em><\/strong> Test',
							'format'    => 'html',
							'textStyle' => 'default-heading-1',
							'layout'    => 'heading-layout'
						],
						'layout' => [],
					],
				],
				'meta_component_order' => $meta_order
			]
		);

		$post_id = self::factory()->post->create();
		$json    = $this->get_json_for_post( $post_id );
		$this->assertEquals( $count, count( $json['components'] ) );
		$this->assertEquals( 'heading', $json['components'][ $index ]['role'] );
		$this->assertEquals( '<strong>Heading <em>1</em></strong> Test', $json['components'][ $index ]['text'] );
	}
}
