<pre>
$modal_form = new HTMLForm('modal_form');
$modal_form->set_css_class('modal-container fieldset-content');

// Modal triggers
$fieldset_modal_menu = new FormFieldMenuFieldset('modal_menu', '');
$modal_form->add_fieldset($fieldset_modal_menu);

$fieldset_modal_menu->add_field(new FormFieldMultitabsLinkList('modal_menu_list',
    array(
        <!-- new FormFieldMultitabsLinkElement('itemTitle', 'modal', 'HTMLFormID_targetID', 'fa-icon'), -->
        new FormFieldMultitabsLinkElement($this->lang['multitabs.menu.title'] . ' modal', 'modal', 'modal_form_modal-10', 'fa-cog'),
    )
));

// Modal window
$fieldset_modal_one = new FormFieldsetMultitabsHTML('modal-10', $this->lang['multitabs.panel.title'] . ' 10', array('css_class' => 'modal modal-animation', 'modal' => true));
$modal_form->add_fieldset($fieldset_modal_one);

$fieldset_modal_one->set_description($this->common_lang['lorem.large.content']);
<pre>
