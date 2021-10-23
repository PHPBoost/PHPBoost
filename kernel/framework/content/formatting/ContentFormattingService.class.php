<?php
/**
 * This class contains the default content formatting factory that must be used if you want
 * a formatting factory having the default settings.
 * @package     Content
 * @subpackage  Formatting
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
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
		$editor_lang = LangLoader::get('editor-lang');
		return array(
			'b'              => $editor_lang['editor.bold'],
			'i'              => $editor_lang['editor.italic'],
			'u'              => $editor_lang['editor.underline'],
			's'              => $editor_lang['editor.strike'],
			'title'          => $editor_lang['editor.title'],
			'style'          => $editor_lang['editor.style'],
			'url'            => $editor_lang['editor.url'],
			'img'            => $editor_lang['editor.img'],
			'quote'          => $editor_lang['editor.quote'],
			'hide'           => $editor_lang['editor.hide'],
			'list'           => $editor_lang['editor.list'],
			'color'          => $editor_lang['editor.color'],
			'bgcolor'        => $editor_lang['editor.bgcolor'],
			'font'           => $editor_lang['editor.font'],
			'size'           => $editor_lang['editor.size'],
			'align'          => $editor_lang['editor.align'],
			'float'          => $editor_lang['editor.float'],
			'sup'            => $editor_lang['editor.sup'],
			'sub'            => $editor_lang['editor.sub'],
			'indent'         => $editor_lang['editor.indent'],
			'pre'            => $editor_lang['editor.pre'],
			'table'          => $editor_lang['editor.table'],
			'swf'            => $editor_lang['editor.flash'],
			'movie'          => $editor_lang['editor.movie'],
			'sound'          => $editor_lang['editor.sound'],
			'code'           => $editor_lang['editor.code'],
			'math'           => $editor_lang['editor.math'],
			'anchor'         => $editor_lang['editor.anchor'],
			'acronym'        => $editor_lang['editor.acronym'],
			'block'          => $editor_lang['editor.block'],
			'fieldset'       => $editor_lang['editor.fieldset'],
			'mail'           => $editor_lang['editor.email'],
			'line'           => $editor_lang['editor.line'],
			'wikipedia'      => $editor_lang['editor.wikipedia'],
			'html'           => $editor_lang['editor.html'],
			'feed'           => $editor_lang['editor.feed'],
			'youtube'        => $editor_lang['editor.youtube'],
			'lightbox'       => $editor_lang['editor.lightbox'],
			'charmap'        => $editor_lang['editor.charmap'],
			'insertdatetime' => $editor_lang['editor.date.time'],
			'fa'             => $editor_lang['editor.fa'],
			'p'              => $editor_lang['editor.paragraph']
		);
	}
}
?>
