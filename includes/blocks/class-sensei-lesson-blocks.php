<?php
/**
 * File containing the class Sensei_Lesson_Blocks.
 *
 * @package sensei
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Sensei_Lesson_Blocks
 */
class Sensei_Lesson_Blocks extends Sensei_Blocks_Initializer {
	/**
	 * Sensei_Blocks constructor.
	 */
	public function __construct() {
		parent::__construct( 'lesson' );

		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
		add_action( 'init', [ $this, 'register_lesson_post_metas' ] );
	}

	/**
	 * Enqueue frontend and editor assets.
	 *
	 * @access private
	 */
	public function enqueue_block_assets() {
		if ( 'lesson' !== get_post_type() ) {
			return;
		}

		Sensei()->assets->enqueue(
			'sensei-single-lesson-blocks-style',
			'blocks/single-lesson-style.css',
			[ 'sensei-shared-blocks-style' ]
		);

		if ( ! is_admin() ) {
			Sensei()->assets->enqueue_script( 'sensei-blocks-frontend' );
		}
	}

	/**
	 * Enqueue editor assets.
	 *
	 * @access private
	 */
	public function enqueue_block_editor_assets() {
		if ( 'lesson' !== get_post_type() ) {
			return;
		}

		Sensei()->assets->enqueue(
			'sensei-single-lesson-blocks',
			'blocks/single-lesson.js',
			[ 'sensei-shared-blocks' ],
			true
		);
		Sensei()->assets->enqueue(
			'sensei-single-lesson-blocks-editor-style',
			'blocks/single-lesson-style-editor.css',
			[ 'sensei-shared-blocks-editor-style', 'sensei-editor-components-style' ]
		);
	}

	/**
	 * Initializes the blocks.
	 */
	public function initialize_blocks() {
		new Sensei_Lesson_Actions_Block();
		new Sensei_Next_Lesson_Block();
		new Sensei_Complete_Lesson_Block();
		new Sensei_Reset_Lesson_Block();
		new Sensei_View_Quiz_Block();
		new Sensei_Block_Contact_Teacher();

		$post_type_object = get_post_type_object( 'lesson' );

		$post_type_object->template = [
			[ 'sensei-lms/button-contact-teacher' ],
			[
				'core/paragraph',
				[ 'placeholder' => __( 'Write lesson content...', 'sensei-lms' ) ],
			],
			[ 'sensei-lms/lesson-actions' ],
		];
	}

	/**
	 * Register lesson post metas.
	 *
	 * @access private
	 */
	public function register_lesson_post_metas() {
		register_post_meta(
			'lesson',
			'_needs_template',
			[
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'boolean',
				'auth_callback' => function() {
					return current_user_can( 'manage_sensei' );
				},
			]
		);
	}
}