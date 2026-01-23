<?php
/**
 * This class is used to represent the result of a validation process.
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 01 16
*/

class ValidationResult
{
    /**
     * @var string The validation result title
     */
    private string $title;

    /**
     * @var array The list of errors
     */
    private array $errors = [];

    /**
     * Constructs a new ValidationResult.
     *
     * @param string $title The title of the validation result
     */
    public function __construct(string $title = 'Validation result')
    {
        $this->title = $title;
    }

    /**
     * Gets the validation result title.
     *
     * @return string The validation result title
     */
    public function get_title(): string
    {
        return $this->title;
    }

    /**
     * Adds an error message.
     *
     * @param string $error_msg The error message to add
     */
    public function add_error(string $error_msg): void
    {
        $this->errors[] = $error_msg;
    }

    /**
     * Gets the list of error messages.
     *
     * @return array The list of error messages
     */
    public function get_errors_messages(): array
    {
        return $this->errors;
    }

    /**
     * Checks if there are any errors.
     *
     * @return bool True if there are errors, false otherwise
     */
    public function has_errors(): bool
    {
        return !empty($this->errors);
    }
}
?>
