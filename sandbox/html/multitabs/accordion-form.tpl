<pre>

$accordion_form = new HTMLForm('HTMLFormID');
$accordion_form->set_css_class('accordion-container siblings fieldset-content');
<br />
$fieldset_accordion_controls = new FormFieldsetAccordionControls('accordion_controls', '');
$accordion_form->add_fieldset($fieldset_accordion_controls);
<br />
$fieldset_tab_menu = new FormFieldMenuFieldset('tabmenulistID', '');
$accordion_form->add_fieldset($fieldset_tab_menu);
<br />
$fieldset_tab_menu->add_field(new FormFieldMultitabsLinkList('tabitemlistID',
    array(
        //new FormFieldMultitabsLinkElement(ItemTitle, 'accordion', 'HTMLFormID_targetID', 'fa-icon', 'picture_url', 'active_module'),
        new FormFieldMultitabsLinkElement($this->lang['Pannel 01 tabitem'], 'accordion', 'HTMLFormID_targetID-01'),
        new FormFieldMultitabsLinkElement($this->lang['multitabs.accordion.title.link'], 'accordion', 'HTMLFormID_targetID-02'),
        new FormFieldMultitabsLinkElement($this->lang['multitabs.accordion.title.link'] . ' 03', 'accordion', 'HTMLFormID_targetID-03'),
    )
));
<br />
$fieldset_tab_one = new FormFieldsetMultitabsHTML('targetID-01', $this->lang['multitabs.panel.title'] . ' 01', array('css_class' => 'accordion accordion-animation'));
$accordion_form->add_fieldset($fieldset_tab_one);
<em>// content of pannel 01</em>
...
// subtitle separator inside a fieldset
$fieldset_tab_one->add_field(new FormFieldSubTitle('subtitleID', $this->lang['multitabs.form.subtitle'],''));
...
$fieldset_tab_two = new FormFieldsetMultitabsHTML('targetID-02', $this->lang['multitabs.panel.title'] . ' 02', array('css_class' => 'accordion accordion-animation'));
$accordion_form->add_fieldset($fieldset_tab_two);
<em>// content of pannel 02</em>
...
$fieldset_tab_three = new FormFieldsetMultitabsHTML('targetID-03', $this->lang['multitabs.panel.title'] . ' 03', array('css_class' => 'accordion accordion-animation'));
$accordion_form->add_fieldset($fieldset_tab_three);
<em>// content of pannel  03</em>
...
<pre>
