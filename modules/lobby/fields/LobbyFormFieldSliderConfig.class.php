<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.0 - 2016 04 15
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

class LobbyFormFieldSliderConfig extends AbstractFormField
{
	private int $max_input = 20;

	public function __construct(string $id, string $label, array $value = [], array $field_options = [], array $constraints = [])
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	public function display()
	{
		$template = $this->get_template_to_use();

		$view = new FileTemplate('lobby/LobbyFormFieldSliderConfig.tpl');
		$view->add_lang(LangLoader::get_all_langs('lobby'));

		$view->put_all([
			'NAME'       => $this->get_html_id(),
			'ID'         => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled(),
		]);

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			$view->assign_block_vars('fieldelements', [
				'ID'          => $i,
				'U_PICTURE'   => $options['picture_url'],
				'DESCRIPTION' => $options['description'],
				'LINK'        => $options['link'],
			]);
			$i++;
		}

		if ($i == 0)
		{
			$view->assign_block_vars('fieldelements', [
				'ID'          => $i,
				'U_PICTURE'   => '',
				'DESCRIPTION' => '',
				'LINK'        => '',
			]);
		}

		$view->put_all([
			'MAX_INPUT'  => $this->max_input,
			'NBR_FIELDS' => $i == 0 ? 1 : $i,
		]);

		$template->assign_block_vars('fieldelements', [
			'ELEMENT' => $view->render(),
		]);

		return $template;
	}

	public function retrieve_value(): void
	{
		$request = AppContext::get_request();
		$values  = [];

		for ($i = 0; $i < $this->max_input; $i++)
		{
			$desc_id    = 'field_description_' . $this->get_html_id() . '_' . $i;
			$pic_id     = 'field_picture_url_' . $this->get_html_id() . '_' . $i;
			$link_id    = 'field_link_' . $this->get_html_id() . '_' . $i;

			if ($request->has_postparameter($pic_id) || $request->has_postparameter($desc_id) || $request->has_postparameter($link_id))
			{
				$description = $request->get_poststring($desc_id);
				$link        = $request->get_poststring($link_id);
				$picture_url = $request->get_poststring($pic_id);

				if (!empty($picture_url) || !empty($description) || !empty($link))
				{
					$values[] = [
						'description' => $description,
						'picture_url' => $picture_url,
						'link'        => $link,
					];
				}
			}
		}

		$this->set_value($values);
	}

	protected function compute_options(array &$field_options): void
	{
		foreach ($field_options as $attribute => $value)
		{
			if (strtolower($attribute) === 'max_input')
			{
				$this->max_input = $value;
				unset($field_options['max_input']);
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
