<pre>

$tabs_form = new HTMLForm('HTMLFormID');
$tabs_form->set_css_class('<span style="color: #ff8800">tab-container</span> fieldset-content');
<br />
$fieldset_tab_menu = new <span style="color: #ff8800">FormFieldMenuFieldset</span>('tabmenulistID', '');
$tabs_form->add_fieldset($fieldset_tab_menu);
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
$fieldset_tab_one = new FormFieldsetHTML('<span style="color: #ff8800">targetID-01</span>', $this->lang['plugins.form.title'] . ' 01');
$tabs_form->add_fieldset($fieldset_tab_one);
<em>// content of pannel 01</em>
...
$fieldset_tab_one->add_field(new <span style="color: #ff8800">FormFieldSubTitle</span>('subtitleID', $this->lang['plugins.form.subtitle'],''));
...
$fieldset_tab_two = new FormFieldsetHTML('targetID-02', $this->lang['plugins.form.title'] . ' 02');
$tabs_form->add_fieldset($fieldset_tab_two);
<em>// content of pannel 02</em>
...
$fieldset_tab_three = new FormFieldsetHTML('targetID-03', $this->lang['plugins.form.title'] . ' 03');
$tabs_form->add_fieldset($fieldset_tab_three);
<em>// content of pannel  03</em>
...
<pre>
