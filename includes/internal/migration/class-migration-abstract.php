<?php
/**
 * File containing the abstract class for migrations.
 *
 * @package sensei
 * @since $$next-version$$
 */

namespace Sensei\Internal\Migration;

/**
 * Migration abstract class.
 *
 * @since $$next-version$$
 */
abstract class Migration_Abstract {
	/**
	 * The errors that occurred during the migration.
	 *
	 * @var array
	 */
	private $errors = array();

	/**
	 * The targeted plugin version.
	 *
	 * @since $$next-version$$
	 *
	 * @return string
	 */
	abstract public function target_version(): string;

	/**
	 * Run the migration.
	 *
	 * @since $$next-version$$
	 *
	 * @param bool $dry_run Whether to run the migration in dry-run mode.
	 *
	 * @return int The number of rows migrated.
	 */
	abstract public function run( bool $dry_run = true );

	/**
	 * Return the errors that occurred during the migration.
	 *
	 * @since $$next-version$$
	 *
	 * @return array
	 */
	public function get_errors(): array {
		return $this->errors;
	}

	/**
	 * Add an error message to the errors list unless it's there already.
	 *
	 * @param string $error The error message to add.
	 */
	protected function add_error( string $error ): void {
		if ( ! in_array( $error, $this->errors, true ) ) {
			$this->errors[] = $error;
		}
	}
}

