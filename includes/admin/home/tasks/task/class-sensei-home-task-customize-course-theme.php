<?php
/**
 * File containing the Sensei_Home_Task_Customize_Course_Theme class.
 *
 * @package sensei-lms
 * @since $$next-version
 */

/**
 * Sensei_Home_Task_Customize_Course_Theme class.
 *
 * @since $$next-version
 */
class Sensei_Home_Task_Customize_Course_Theme implements Sensei_Home_Task {
	const CUSTOMIZED_COURSE_THEME_OPTION_KEY = 'sensei_home_task_visited_course_theme_customizer';

	/**
	 * The ID for the task.
	 *
	 * @return string
	 */
	public static function get_id(): string {
		return 'customize-course-theme';
	}

	/**
	 * Number used to sort in frontend.
	 *
	 * @return int
	 */
	public function get_priority(): int {
		return 400;
	}

	/**
	 * Task title.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return __( 'Customize the Course theme', 'sensei-lms' );
	}

	/**
	 * Task url.
	 *
	 * @return string
	 */
	public function get_url(): ?string {
		// TODO
		return '';
		// return admin_url( 'admin.php?page=wc-admin' );
	}

	/**
	 * Whether the task is completed or not.
	 *
	 * @return bool
	 */
	public function is_completed(): bool {
		return (bool) get_option( self::CUSTOMIZED_COURSE_THEME_OPTION_KEY, false );
	}

	/**
	 * Mark the task as completed.
	 *
	 * @return void
	 */
	public static function mark_completed() {
		update_option( self::CUSTOMIZED_COURSE_THEME_OPTION_KEY, true, false );
	}

	/**
	 * Test if this task should be active or not.
	 *
	 * @return bool Whether the task should be active or not.
	 */
	public static function is_active() {
		// TODO: return true if Course Theme is active.
		return true;
		// if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		// 	return false;
		// }
		// $features = Sensei_Setup_Wizard::instance()->get_wizard_user_data( 'features' );
		// return in_array( 'woocommerce', $features['selected'], true );
	}
}
