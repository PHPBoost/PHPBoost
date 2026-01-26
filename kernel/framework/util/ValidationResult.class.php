<?php
/**
 * This class is done to time a process easily. You choose when to start and when to stop.
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 01 23
 * @since       PHPBoost 3.0 - 2010 01 16
*/

class ValidationResult
{
    /**
     * @var string the validation result title
     */
    private string $title;

    /**
     * @var string[]
     */
    private array $errors = [];

    /**
     * Constructor
     */
    public function __construct(string $title = 'Validation result')
    {
        $this->title = $title;
    }

    /**
     * Returns the title
     */
    public function get_title(): string
    {
        return $this->title;
    }

    /**
     * Adds an error message
     * @param string $error_msg the error message to add
     */
    public function add_error(string $error_msg): void
    {
        $this->errors[] = $error_msg;
    }

    /**
     * Returns the list of the errors messages
     * @return string[] errors messages
     */
    public function get_errors_messages(): array
    {
        return $this->errors;
    }

    /**
     * Returns true if there are errors
     * @return bool true if there are errors
     */
    public function has_errors(): bool
    {
        return !empty($this->errors);
    }
}
?>
