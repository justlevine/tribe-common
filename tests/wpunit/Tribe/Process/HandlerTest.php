<?php

namespace Tribe\Process;

include_once codecept_data_dir( 'classes/Dummy_Process_Handler.php' );

use Tribe\Common\Tests\Dummy_Process_Handler as Handler;
use Tribe\Tests\Traits\Cron_Assertions;
use Tribe__Feature_Detection as Feature_Detection;

class HandlerTest extends \Codeception\TestCase\WPTestCase {
	use Cron_Assertions;

	/**
	 * A prophecy of the feature detection class.
	 *
	 * @var Feature_Detection
	 */
	protected $feature_detection;

	/**
	 * It should fallback to use cron-based processing if AJAX based is not supported.
	 *
	 * @test
	 */
	public function should_fallback_to_use_cron_based_processing_if_ajax_based_is_not_supported() {
		$this->feature_detection->supports_async_process()->willReturn( false );

		$handler = $this->make_instance();
		$handler->dispatch();

		$this->assert_cron_event_exists( $handler->get_healthcheck_cron_hook_id() );
	}

	/**
	 * Builds and returns an instance of the Dummy handler injecting the
	 * feature detection object

	 *
	 * @return Handler
	 */
	protected function make_instance(): Handler {
		$handler = new Handler;
		$handler->set_feature_detection( $this->feature_detection->reveal() );

		return $handler;
	}

	/**
	 * It should not schedule a single cron event if async processing is supported
	 *
	 * @test
	 */
	public function should_not_schedule_a_single_cron_event_if_async_processing_is_supported() {
		$this->feature_detection->supports_async_process()->willReturn( true );
		$handler = $this->make_instance();
		$handler->dispatch();

		$this->assert_cron_event_not_exists( $handler->get_healthcheck_cron_hook_id() );
	}

	/**
	 * It should hook on its cron hook identifier on build if async process is not supported
	 *
	 * @test
	 */
	public function should_hook_on_its_cron_hook_identifier_on_build_if_async_process_is_not_supported() {
		$this->feature_detection->supports_async_process()->willReturn( false );
		$handler = $this->make_instance();

		$this->assertNotEmpty( has_action( $handler->get_healthcheck_cron_hook_id(), [ $handler, 'maybe_handle' ] ) );
	}

	/**
	 * It should hook on its cron hook identifier on build if async process is supported
	 *
	 * @test
	 */
	public function should_hook_on_its_cron_hook_identifier_on_build_if_async_process_is_supported() {
		$this->feature_detection->supports_async_process()->willReturn( true );
		$handler = $this->make_instance();

		$this->assertNotEmpty( has_action( $handler->get_healthcheck_cron_hook_id(), [ $handler, 'maybe_handle' ] ) );
	}

	/**
	 * It should act once on cron hook if async process is not supported
	 *
	 * @test
	 */
	public function should_act_once_on_cron_hook_if_async_process_is_not_supported() {
		$this->feature_detection->supports_async_process()->willReturn( false );

		$handler                  = $this->make_instance();
		$handler->handle_callback = function () {
			$current = (int) get_option( 'dummy_counter', 0 );
			update_option( 'dummy_counter', ++ $current );
		};
		$handler->dispatch();

		$this->assertEmpty( get_option( 'dummy_counter', false ) );

		do_action( $handler->get_healthcheck_cron_hook_id() );

		$this->assertEquals( '1', get_option( 'dummy_counter', false ) );

		do_action( $handler->get_healthcheck_cron_hook_id() );

		$this->assertEquals( '1', get_option( 'dummy_counter', false ) );

		do_action( $handler->get_healthcheck_cron_hook_id() );

		$this->assertEquals( '1', get_option( 'dummy_counter', false ) );
	}

	/**
	 * Runs before each test method to set up a common fixture.
	 */
	public function setUp() {
		parent::setUp(); // TODO: Change the autogenerated stub
		/** @var Feature_Detection $feature_detection */
		$this->feature_detection = $this->prophesize( Feature_Detection::class );
	}
}