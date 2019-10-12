<pre>

$tabs_form = new HTMLForm('HTMLFormID');
$tabs_form->set_css_class('<span style="color: #ff8800">tabs-container</span> fieldset-content');
<br />
$fieldset_tab_menu = new <span style="color: #ff8800">FormFieldMenuFieldset</span>('tabmenulistID', '');
$tabs_form->add_fieldset($fieldset_tab_menu);
<br />
$fieldset_tab_menu->add_field(new <span style="color: #ff8800">FormFieldMultitabsLinkList</span>('tabitemlistID',
    array(
        //new <span style="color: #ff8800">FormFieldMultitabsLinkElement</span>(ItemTitle, 'HTMLFormID_targetID', 'fa-icon', 'picture_url', 'active_module'),
        new <span style="color: #ff8800">FormFieldMultitabsLinkElement</span>($this->lang['Pannel 01 tabitem'], 'HTMLFormID_targetID-01'),
        new <span style="color: #ff8800">FormFieldMultitabsLinkElement</span>($this->lang['multitabs.tabs.title.link'], 'HTMLFormID_targetID-02'),
        new <span style="color: #ff8800">FormFieldMultitabsLinkElement</span>($this->lang['multitabs.tabs.title.link'] . ' 03', 'HTMLFormID_targetID-03'),
    )
));
<br />
$fieldset_tab_one = new <span style="color: #ff8800">FormFieldsetMultitabsHTML</span>('<span style="color: #ff8800">targetID-01</span>', $this->lang['multitabs.panel.title'] . ' 01'<span style="color: #ff8800">, array('css_class' => 'tabs tabs-animation')</span>);
$tabs_form->add_fieldset($fieldset_tab_one);
<em>// content of pannel 01</em>
...
// subtitle separator inside a fieldset
$fieldset_tab_one->add_field(new <span style="color: #ff8800">FormFieldSubTitle</span>('subtitleID', $this->lang['multitabs.form.subtitle'],''));
...
$fieldset_tab_two = new <span style="color: #ff8800">FormFieldsetMultitabsHTML</span>('targetID-02', $this->lang['multitabs.panel.title'] . ' 02'<span style="color: #ff8800">, array('css_class' => 'tabs tabs-animation')</span>);
$tabs_form->add_fieldset($fieldset_tab_two);
<em>// content of pannel 02</em>
...
$fieldset_tab_three = new <span style="color: #ff8800">FormFieldsetMultitabsHTML</span>('targetID-03', $this->lang['multitabs.panel.title'] . ' 03'<span style="color: #ff8800">, array('css_class' => 'tabs tabs-animation')</span>);
$tabs_form->add_fieldset($fieldset_tab_three);
<em>// content of pannel  03</em>
...
<pre>
