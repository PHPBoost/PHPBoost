<pre>

$wizard_form = new <span style="color: #ff8800">WizardHTMLForm</span>('HTMLFormID');
$wizard_form->set_css_class('<span style="color: #ff8800">wizard-container</span> fieldset-content');
<br />
$fieldset_tab_menu = new <span style="color: #ff8800">FormFieldMenuFieldset</span>('tabmenulistID', '');
$wizard_form->add_fieldset($fieldset_tab_menu);
<br />
$fieldset_tab_menu->add_field(new FormFieldActionLinkList('tabitemlistID',
    array(
        //new FormFieldActionLinkElement(ItemTitle, '#HTMLFormID_targetID', 'fa-icon', 'picture_url', 'active_module'),
        new FormFieldActionLinkElement($this->lang['Pannel 01 tabitem'], '#HTMLFormID_targetID-01'),
        new FormFieldActionLinkElement($this->lang['plugins.tabs.title.link'], '#HTMLFormID_targetID-02'),
        new FormFieldActionLinkElement($this->lang['plugins.tabs.title.link'] . ' 03', '#HTMLFormID_targetID-03'),
    )
));
<br />
$fieldset_tab_one = new FormFieldsetHTML('id', 'fieldset title');
$wizard_form->add_fieldset($fieldset_tab_one);
<em>// content of pannel 01</em>
...
$fieldset_tab_one->add_field(new <span style="color: #ff8800">FormFieldSubTitle</span>('id','subtitle',''));
...
$fieldset_tab_two = new FormFieldsetHTML('id', 'fieldset title');
$wizard_form->add_fieldset($fieldset_tab_two);
<em>// content of pannel 02</em>
...
$fieldset_tab_three = new FormFieldsetHTML('id', 'fieldset title');
$wizard_form->add_fieldset($fieldset_tab_three);
<em>// content of pannel  03</em>
...
<pre>
