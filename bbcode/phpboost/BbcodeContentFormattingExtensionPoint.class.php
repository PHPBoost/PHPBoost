<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 09
 * @since       PHPBoost 3.0 - 2011 10 11
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BbcodeContentFormattingExtensionPoint extends AbstractContentFormattingExtensionPoint
{
	public function get_name(): string
	{
		return 'bbcode';
	}

	public function get_parser(): FormattingParser
	{
		$parser = new BbcodeParser();
		$parser->set_forbidden_tags($this->get_forbidden_tags());
		$parser->set_html_auth($this->get_html_auth());
		return $parser;
	}

	public function get_unparser(): FormattingParser
	{
		return new BbcodeUnparser();
	}

	public function get_second_parser(): FormattingParser
	{
		return new ContentSecondParser();
	}

	public function get_editor(): BbcodeEditor
	{
		$editor = new BbcodeEditor();
		$editor->set_forbidden_tags($this->get_forbidden_tags());
		return $editor;
	}
}
?>
