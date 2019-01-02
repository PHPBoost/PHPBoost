<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @category    Framework
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 31
*/

class FormButtonAjax extends AbstractFormButton
{
    public function __construct($label, AjaxRequest $request, $img = '', array $fields, $condition = null)
    {
    	$full_label = '';
    	if (!empty($img))
    	{
    		$full_label = '<img src="' . $img . '" alt="' . $label . '" title="' . $label . '" />';
    	}
    	else
    	{
    		$full_label = $label;
    	}
        parent::__construct('button', $full_label, '', $this->build_ajax_request($request, $fields, $condition), !empty($img) ? 'image' : '');
    }

    private function build_ajax_request(AjaxRequest $request, array $fields, $condition)
    {
    	if (is_array($fields))
    	{
	    	foreach ($fields as $field)
	    	{
	    		$request->add_param($field->get_id(), '$FF(\'' . $field->get_id() . '\').getValue()');
	    	}
    	}
    	if (!empty($condition))
    	{
    		return 'if (' . $condition . '){' . $request->render() . '}';
    	}
    	return $request->render();
    }
}
?>
