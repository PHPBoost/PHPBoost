<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 01 26
 * @since       PHPBoost 3.0 - 2011 10 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CLIUserManagementCommand extends CLIMultipleGoalsCommand
{
    private static string $name = 'user';
    private static array $goals = [
        'add' => 'CLIAddUserCommand',
        'delete' => 'CLIDeleteUserCommand'
    ];

    public function __construct()
    {
        parent::__construct(self::$name, self::$goals);
    }

    public function short_description(): string
    {
        return 'manages the phpboost users';
    }
}
?>
