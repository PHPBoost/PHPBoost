<?php
/**
 * This class provides an interface editor for contents.
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 09
 * @since       PHPBoost 2.0 - 2008 07 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TinyMCEEditor extends ContentEditor
{
	private static $js_included = false;
	private $array_tags = array('undo' => 'undo', 'redo' => 'redo', '|1' => '|', 'b' => 'bold', 'i' => 'italic', 'u' => 'underline', 's' => 'strikethrough', '|2' => '|', 'color1' => 'forecolor', 'color2' => 'backcolor', '|3' => '|',
	'align1' => 'alignleft', 'align2' => 'aligncenter', 'align3' => 'alignright', 'align4' => 'alignjustify', '|4' => '|', 'size' => 'fontsizeselect', 'font' => 'fontselect', '|5' => '|', 'list1' => 'bullist', 'list2' => 'numlist', '|6' => '|',
	'indent1' => 'outdent', 'indent2' => 'indent', 'quote' => 'blockquote', '|7' => '|', 'cut' => 'cut', 'copy' => 'copy', 'paste' => 'paste', '_search' => 'searchreplace', 
	'|8' => '|', 'url1' => 'link', 'url2' => 'unlink', 'img' => 'image', 'movie' => 'media', 'insertfile' => 'insertfile', '|9' => '|', 'emotions' => 'emoticons', 'table' => 'table', '|10' => '|', 'title' => 'formatselect', 'style' => 'styleselect', '|11' => '|', 'sub' => 'subscript', 'sup' => 'superscript', 'line' => 'hr',
	'|12' => '|', 'anchor' => 'anchor', 'charmap' => 'charmap', 'removeformat' => 'removeformat', 'visualchars' => 'visualchars', 'visualblocks' => 'visualblocks');

	public function get_template()
	{
		if (!is_object($this->template) || !($this->template instanceof Template))
		{
			$this->template = new FileTemplate('TinyMCE/tinymce_editor.tpl');
			$this->template->add_lang(LangLoader::get_all_langs());
		}
		return $this->template;
	}

	/**
	 * @desc Display the editor
	 * @return string Formated editor.
	 */
	public function display()
	{
		$template = $this->get_template();

		$displayed_icons_number = 0;
		$toolbar = array();
		foreach ($this->array_tags as $tag => $tinymce_tag) //Balises autorisées.
		{
			$tag = preg_replace('`[0-9]`u', '', $tag);
			if (!in_array($tag, $this->forbidden_tags))
			{
				if ($tag != 'insertfile' || ($tag == 'insertfile' && AppContext::get_current_user()->check_auth(FileUploadConfig::load()->get_authorization_enable_interface_files(), FileUploadConfig::AUTH_FILES_BIT)))
				{
					$toolbar[] = $tinymce_tag;

					$displayed_icons_number++;
				}
			}
		}
		$toolbar = implode(' ', $toolbar);

		$language = TextHelper::substr(AppContext::get_current_user()->get_locale(), 0, 2);
		switch ($language) {
			case 'fr' : $language = 'fr_FR';
						break;

			case 'en' : $language = 'en_GB';
						break;

			default :	break;
		}

		$form_field_infos = explode('_', $this->identifier);
		$form_name = $form_field_infos[0];
		unset($form_field_infos[0]);
		$field_name = implode('_', $form_field_infos);

		$template->put_all(array(
			'C_NOT_JS_INCLUDED' => self::$js_included,
			'C_HTMLFORM'        => !empty($form_name) && !empty($field_name),
			'PAGE_PATH'         => $_SERVER['PHP_SELF'],
			'FIELD'             => $this->identifier,
			'FORM_NAME'         => $form_name,
			'FIELD_NAME'        => $field_name,
			'FORBIDDEN_TAGS'    => implode(',', $this->forbidden_tags),
			'C_TOOLBAR'         => !empty($toolbar),
			'TOOLBAR'           => $toolbar,
			'LANGUAGE'          => $language
		));

		self::$js_included = true;

		return $template->render();
	}
}
?>
