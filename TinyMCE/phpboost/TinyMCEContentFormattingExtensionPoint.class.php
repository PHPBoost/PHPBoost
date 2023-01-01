<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 11
*/

class TinyMCEContentFormattingExtensionPoint extends AbstractContentFormattingExtensionPoint
{
	public function get_name()
	{
		return 'TinyMCE';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_parser()
	{
		$parser = new TinyMCEParser();
		$parser->set_forbidden_tags($this->get_forbidden_tags());
		$parser->set_html_auth($this->get_html_auth());
		return $parser;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_unparser()
	{
		return new TinyMCEUnparser();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_second_parser()
	{
		return new ContentSecondParser();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_editor()
	{
		$editor = new TinyMCEEditor();
		$editor->set_forbidden_tags($this->get_forbidden_tags());
		return $editor;
	}
}
?>
