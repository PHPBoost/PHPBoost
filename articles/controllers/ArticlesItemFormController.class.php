<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 19
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesItemFormController extends DefaultItemFormController
{
	protected function build_post_content_fields(FormFieldset $fieldset)
	{
		$fieldset->add_field(new FormFieldActionLink('add_page', $this->lang['articles.add.page'] , 'javascript:bbcode_page();', '', '', '', 'fa fa-pagebreak'));

		// Put the cursor on the good page when we edit an article with several pages
		if (!$this->is_new_item)
		{
			$current_page = AppContext::get_request()->get_getstring('page', '');

			$this->view->put('C_PAGE', !empty($current_page));

			if (!empty($current_page))
			{
				$content = $this->get_item()->get_content();

				//If article doesn't begin with a page, we insert one
				if (TextHelper::substr(trim($content), 0, 6) != '[page]')
				{
					$content = '[page]&nbsp;[/page]' . $content;
				}

				//Retrieving pages
				preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $content, $array_page);

				$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';

				$this->view->put('PAGE', TextHelper::to_js_string($page_name));
			}
		}

		parent::build_post_content_fields($fieldset);
	}
}
?>
