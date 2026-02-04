<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 04
 * @since       PHPBoost 3.0 - 2011 10 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CLIHtaccessCommand extends CLIMultipleGoalsCommand
{
    private static string $name = 'htaccess';
    private static array $goals = ['content' => 'CLIHtaccessContentCommand', 'rewriting' => 'CLIHtaccessRewritingCommand'];

    public function __construct()
    {
        parent::__construct(self::$name, self::$goals);
    }

    public function short_description(): string
    {
        return 'manages the htaccess file';
    }
}
?>
