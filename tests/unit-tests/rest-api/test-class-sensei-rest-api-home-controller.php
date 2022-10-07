<?php
/**
 * Sensei REST API: Sensei_REST_API_Home_Controller tests
 *
 * @package sensei-lms
 * @since   $$next-version$$
 */

/**
 * Class Sensei_REST_API_Home_Controller tests.
 *
 * @covers Sensei_REST_API_Home_Controller
 */
class Sensei_REST_API_Home_Controller_Test extends WP_Test_REST_TestCase {
	use Sensei_Test_Login_Helpers;

	/**
	 * A server instance that we use in tests to dispatch requests.
	 *
	 * @var WP_REST_Server $server
	 */
	protected $server;

	const REST_ROUTE = '/sensei-internal/v1/home';

	/**
	 * Test specific setup.
	 */
	public function setUp() {
		parent::setUp();

		global $wp_rest_server;
		$wp_rest_server = new WP_REST_Server();
		$this->server   = $wp_rest_server;

		do_action( 'rest_api_init' );
	}

	/**
	 * Test specific teardown.
	 */
	public function tearDown() {
		parent::tearDown();

		global $wp_rest_server;
		$wp_rest_server = null;
	}

	/**
	 * Asserts that guests cannot access Home Data.
	 */
	public function testRESTRequestReturns401ForGuests() {
		$this->login_as( null );

		$request  = new WP_REST_Request( 'GET', self::REST_ROUTE );
		$response = $this->server->dispatch( $request );

		$this->assertEquals( 401, $response->get_status() );
	}

	/**
	 * Asserts that admins can access Home Data.
	 */
	public function testRESTRequestReturns200ForAdmins() {
		$this->login_as_admin();

		// Prevent requests.
		add_filter(
			'pre_http_request',
			function() {
				return [ 'body' => '[]' ];
			}
		);

		$request  = new WP_REST_Request( 'GET', self::REST_ROUTE );
		$response = $this->server->dispatch( $request );
		remove_all_filters( 'pre_http_request' );

		$this->assertEquals( 200, $response->get_status() );
	}

	/**
	 * Check that Home Data contains Quick Links section as generated by the provider.
	 */
	public function testHomeDataReturnsQuickLinksGeneratedByProvider() {
		// Stubs
		$help_provider_stub   = $this->createMock( Sensei_Home_Help_Provider::class );
		$promo_provider_stub  = $this->createMock( Sensei_Home_Promo_Banner_Provider::class );
		$tasks_provider_stub  = $this->createMock( Sensei_Home_Tasks_Provider::class );
		$news_provider_stub   = $this->createMock( Sensei_Home_News_Provider::class );
		$guides_provider_stub = $this->createMock( Sensei_Home_Guides_Provider::class );

		// Mock provider.
		$mocked_quick_links        = [ 'quick_links' ];
		$quick_links_provider_mock = $this->createMock( Sensei_Home_Quick_Links_Provider::class );
		$quick_links_provider_mock->expects( $this->once() )
			->method( 'get' )
			->willReturn( $mocked_quick_links );

		// Do the actual call.
		$controller = new Sensei_REST_API_Home_Controller(
			'namespace',
			$quick_links_provider_mock,
			$help_provider_stub,
			$promo_provider_stub,
			$tasks_provider_stub,
			$news_provider_stub,
			$guides_provider_stub
		);
		$result     = $controller->get_data();

		// Assert 'quick_links' are returned as received from the mapper.
		$this->assertArrayHasKey( 'quick_links', $result );
		$this->assertEquals( $mocked_quick_links, $result['quick_links'] );
	}


	/**
	 * Check that Home Data contains News section as generated by the provider.
	 */
	public function testHomeDataReturnsNewsGeneratedByProvider() {
		// Stubs
		$help_provider_stub        = $this->createMock( Sensei_Home_Help_Provider::class );
		$promo_provider_stub       = $this->createMock( Sensei_Home_Promo_Banner_Provider::class );
		$tasks_provider_stub       = $this->createMock( Sensei_Home_Tasks_Provider::class );
		$quick_links_provider_stub = $this->createMock( Sensei_Home_Quick_Links_Provider::class );
		$guides_provider_stub      = $this->createMock( Sensei_Home_Guides_Provider::class );

		// Mock provider.
		$mocked_news        = [ 'news' ];
		$news_provider_mock = $this->createMock( Sensei_Home_News_Provider::class );
		$news_provider_mock->expects( $this->once() )
			->method( 'get' )
			->willReturn( $mocked_news );

		// Do the actual call.
		$controller = new Sensei_REST_API_Home_Controller(
			'namespace',
			$quick_links_provider_stub,
			$help_provider_stub,
			$promo_provider_stub,
			$tasks_provider_stub,
			$news_provider_mock,
			$guides_provider_stub
		);
		$result     = $controller->get_data();

		// Assert 'news' are returned as received from the mapper.
		$this->assertArrayHasKey( 'news', $result );
		$this->assertEquals( $mocked_news, $result['news'] );
	}

	/**
	 * Check that Home Data contains Guides section as generated by the provider.
	 */
	public function testHomeDataReturnsGuidesGeneratedByProvider() {
		// Stubs
		$help_provider_stub        = $this->createMock( Sensei_Home_Help_Provider::class );
		$promo_provider_stub       = $this->createMock( Sensei_Home_Promo_Banner_Provider::class );
		$tasks_provider_stub       = $this->createMock( Sensei_Home_Tasks_Provider::class );
		$quick_links_provider_stub = $this->createMock( Sensei_Home_Quick_Links_Provider::class );
		$news_provider_stub        = $this->createMock( Sensei_Home_News_Provider::class );

		// Mock provider.
		$mocked_guides        = [ 'guides' ];
		$guides_provider_mock = $this->createMock( Sensei_Home_Guides_Provider::class );
		$guides_provider_mock->expects( $this->once() )
			->method( 'get' )
			->willReturn( $mocked_guides );

		// Do the actual call.
		$controller = new Sensei_REST_API_Home_Controller(
			'namespace',
			$quick_links_provider_stub,
			$help_provider_stub,
			$promo_provider_stub,
			$tasks_provider_stub,
			$news_provider_stub,
			$guides_provider_mock
		);
		$result     = $controller->get_data();

		// Assert 'guides' are returned as received from the mapper.
		$this->assertArrayHasKey( 'guides', $result );
		$this->assertEquals( $mocked_guides, $result['guides'] );
	}

	/**
	 * Check that Home Data contains Help section as generated by the provider.
	 */
	public function testHomeDataReturnsHelpGeneratedByProvider() {
		// Stubs
		$quick_links_provider_stub = $this->createMock( Sensei_Home_Quick_Links_Provider::class );
		$promo_provider_stub       = $this->createMock( Sensei_Home_Promo_Banner_Provider::class );
		$tasks_provider_stub       = $this->createMock( Sensei_Home_Tasks_Provider::class );
		$news_provider_stub        = $this->createMock( Sensei_Home_News_Provider::class );
		$guides_provider_stub      = $this->createMock( Sensei_Home_Guides_Provider::class );

		// Mock provider.
		$mocked_help_categories = [ 'category' ];
		$help_provider_mock     = $this->createMock( Sensei_Home_Help_Provider::class );
		$help_provider_mock->expects( $this->once() )
			->method( 'get' )
			->willReturn( $mocked_help_categories );

		// Do the actual call.
		$controller = new Sensei_REST_API_Home_Controller(
			'namespace',
			$quick_links_provider_stub,
			$help_provider_mock,
			$promo_provider_stub,
			$tasks_provider_stub,
			$news_provider_stub,
			$guides_provider_stub
		);
		$result     = $controller->get_data();

		// Assert 'help' is returned as received from the mapper.
		$this->assertArrayHasKey( 'help', $result );
		$this->assertEquals( $mocked_help_categories, $result['help'] );
	}

	/**
	 * Check that Home Data contains Promotional Banner section as generated by the provider.
	 */
	public function testHomeDataReturnsPromoBannerGeneratedByProvider() {
		// Stubs
		$quick_links_provider_stub = $this->createMock( Sensei_Home_Quick_Links_Provider::class );
		$help_provider_stub        = $this->createMock( Sensei_Home_Help_Provider::class );
		$tasks_provider_stub       = $this->createMock( Sensei_Home_Tasks_Provider::class );
		$news_provider_stub        = $this->createMock( Sensei_Home_News_Provider::class );
		$guides_provider_stub      = $this->createMock( Sensei_Home_Guides_Provider::class );

		// Mock provider
		$mocked_banner       = [ 'banner' ];
		$promo_provider_mock = $this->createMock( Sensei_Home_Promo_Banner_Provider::class );
		$promo_provider_mock->expects( $this->once() )
			->method( 'get' )
			->willReturn( $mocked_banner );

		// Do the actual call.
		$controller = new Sensei_REST_API_Home_Controller(
			'namespace',
			$quick_links_provider_stub,
			$help_provider_stub,
			$promo_provider_mock,
			$tasks_provider_stub,
			$news_provider_stub,
			$guides_provider_stub
		);
		$result     = $controller->get_data();

		// Assert 'promo_banner' is returned as received from the mapper.
		$this->assertArrayHasKey( 'promo_banner', $result );
		$this->assertSame( $mocked_banner, $result['promo_banner'] );
	}

	/**
	 * Check that Home Data contains Tasks section as generated by the provider.
	 */
	public function testHomeDataReturnsTasksGeneratedByProvider() {
		// Stubs
		$quick_links_provider_stub = $this->createMock( Sensei_Home_Quick_Links_Provider::class );
		$help_provider_stub        = $this->createMock( Sensei_Home_Help_Provider::class );
		$promo_provider_stub       = $this->createMock( Sensei_Home_Promo_Banner_Provider::class );
		$news_provider_stub        = $this->createMock( Sensei_Home_News_Provider::class );
		$guides_provider_stub      = $this->createMock( Sensei_Home_Guides_Provider::class );

		// Mock provider
		$mocked_tasks        = [ 'mocked-tasks' ];
		$tasks_provider_mock = $this->createMock( Sensei_Home_Tasks_Provider::class );
		$tasks_provider_mock->expects( $this->once() )
			->method( 'get' )
			->willReturn( $mocked_tasks );

		// Do the actual call.
		$controller = new Sensei_REST_API_Home_Controller(
			'namespace',
			$quick_links_provider_stub,
			$help_provider_stub,
			$promo_provider_stub,
			$tasks_provider_mock,
			$news_provider_stub,
			$guides_provider_stub
		);
		$result     = $controller->get_data();

		// Assert 'tasks' is returned as received from the mapper.
		$this->assertArrayHasKey( 'tasks', $result );
		$this->assertSame( $mocked_tasks, $result['tasks'] );
	}
}
