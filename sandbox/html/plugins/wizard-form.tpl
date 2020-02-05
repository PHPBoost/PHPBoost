<pre class="language-php"><code class="language-php">$wizard_form = new WizardHTMLForm('HTMLFormID');
$wizard_form->set_css_class('wizard-container fieldset-content');
<br />
$fieldset_tab_menu = new FormFieldMenuFieldset('tabmenulistID', '');
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
$fieldset_tab_one->add_field(new FormFieldSubTitle('id','subtitle',''));
...
$fieldset_tab_two = new FormFieldsetHTML('id', 'fieldset title');
$wizard_form->add_fieldset($fieldset_tab_two);
<em>// content of pannel 02</em>
...
$fieldset_tab_three = new FormFieldsetHTML('id', 'fieldset title');
$wizard_form->add_fieldset($fieldset_tab_three);
<em>// content of pannel  03</em>
...
</code></pre>
