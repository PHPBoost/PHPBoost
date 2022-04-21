<?php
/**
 * This class contains the default content formatting factory that must be used if you want
 * a formatting factory having the default settings.
 * @package     Content
 * @subpackage  Formatting
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 21
 * @since       PHPBoost 3.0 - 2009 12 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContentFormattingService
{
	/**
	 * @var AbstractContentFormattingFactory
	 */
	private $default_factory;

	/**
	 * Returns the content formatting factory corresponding to the default configuration
	 * @return ContentFormattingFactory
	 */
	public function get_default_factory()
	{
		if ($this->default_factory === null)
		{
			$this->default_factory = $this->create_factory($this->get_user_editor());
		}
		return $this->default_factory;
	}

	/**
	 * Creates a factory for the given language
	 * @param string $language
	 * @return ContentFormattingFactory
	 */
	public function create_factory($language = '')
	{
		$editor = $this->get_existing_editor($language);
		return ContentFormattingProvidersService::create_factory($editor);
	}

	/**
	 * Returns the name of the editor of the current user (chosen in its profile).
	 * @return string The editor used by the current user.
	 */
	public function get_user_editor()
	{
		return AppContext::get_current_user()->get_editor();
	}

	/**
	 * Returns the parser to use in the default configuration
	 * @return FormattingParser
	 */
	public function get_default_parser()
	{
		return $this->get_default_factory()->get_parser();
	}

	/**
	 * Returns the unparser to use in the default configuration
	 * @return FormattingParser
	 */
	public function get_default_unparser()
	{
		return $this->get_default_factory()->get_unparser();
	}

	/**
	 * Returns the second parser to use in the default configuration
	 * @return FormattingParser
	 */
	public function get_default_second_parser()
	{
		return $this->get_default_factory()->get_second_parser();
	}

	/**
	 * Returns the editor displayer that you have to display beside the associated HTML textarea
	 * if you use the default configuration.
	 * @return ContentEditor
	 */
	public function get_default_editor()
	{
		return $this->get_default_factory()->get_editor();
	}

	/**
	 * @param string $editor
	 * @return string
	 */
	private function get_existing_editor($editor)
	{
		if (in_array($editor, self::get_editors_identifier()))
		{
			return $editor;
		}
		else
		{
			return ContentFormattingConfig::load()->get_default_editor();
		}
	}

	public function get_editors_identifier()
	{
		return array_keys(ContentFormattingProvidersService::get_editors());
	}

	public function get_available_editors()
	{
		$available_editors = array();
		foreach (ContentFormattingProvidersService::get_editors() as $id => $provider)
		{
			$available_editors[$id] = $provider->get_name();
		}
		return $available_editors;
	}

	/**
	 * @param string $id_module
	 */
	public function uninstall_editor($id_module)
	{
		$editors = $this->get_available_editors();

		if (in_array($id_module, $editors))
		{
			if (count($editors) > 1)
			{
				$default_editor = ContentFormattingConfig::load()->get_default_editor();

				if ($default_editor !== $id_module)
				{
					PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('editor' => $default_editor),
						'WHERE editor=:old_editor', array('old_editor' => $id_module
					));
				}
				else
					return LangLoader::get_message('warning.is.default.editor', 'warning-lang');
			}
			else
				return LangLoader::get_message('warning.last.editor.installed', 'warning-lang');
		}
	}

	/**
	 * Returns the map of all the formatting types supported by the PHPBoost formatting editors and parsers.
	 * The keys of the map are the tags identifiers and the values the tags names.
	 * @return string[] The map
	 */
	public function get_available_tags()
	{
		$lang = LangLoader::get_all_langs();
		return array(
			'b'              => $lang['editor.bold'],
			'i'              => $lang['editor.italic'],
			'u'              => $lang['editor.underline'],
			's'              => $lang['editor.strike'],
			'color'          => $lang['editor.color'],
			'bgcolor'        => $lang['editor.bgcolor'],
			'size'           => $lang['editor.size'],
			'font'           => $lang['editor.font'],
			'align'          => $lang['editor.align'],
			'float'          => $lang['editor.float'],
			'indent'         => $lang['editor.indent'],
			'sup'            => $lang['editor.sup'],
			'sub'            => $lang['editor.sub'],
			'title'          => $lang['editor.title'],
			'list'           => $lang['editor.list'],
			'table'          => $lang['editor.table'],
			'p'              => $lang['editor.paragraph'],
			'block'          => $lang['editor.block'],
			'container'      => $lang['editor.container'],
			'fieldset'       => $lang['editor.fieldset'],
			'abbr'           => $lang['editor.abbr'],
			'quote'          => $lang['editor.quote'],
			'hide'           => $lang['editor.hide'],
			'member'         => $lang['editor.member'],
			'teaser'         => $lang['editor.teaser'],
			'moderator'      => $lang['editor.moderator'],
			'style'          => $lang['editor.style'],
			'line'           => $lang['editor.line'],
			'url'            => $lang['editor.url'],
			'mail'           => $lang['editor.email'],
			'wikipedia'      => $lang['editor.wikipedia'],
			'feed'           => $lang['editor.feed'],
			'anchor'         => $lang['editor.anchor'],
			'sound'          => $lang['editor.sound'],
			'movie'          => $lang['editor.movie'],
			'lightbox'       => $lang['editor.lightbox'],
			'img'            => $lang['editor.img'],
			'upload'         => $lang['editor.upload'],
			'smileys'        => $lang['editor.smileys'],
			'emoji'          => $lang['editor.emoji'],
			'fa'             => $lang['editor.fa'],
			'code'           => $lang['editor.code'],
			'math'           => $lang['editor.math'],
			'html'           => $lang['editor.html'],
			'charmap'        => $lang['editor.charmap'],
			'insertdatetime' => $lang['editor.date.time'],
		);
	}
}
?>
