<pre class="language-php"><code class="language-php">$tabs_form = new HTMLForm('HTMLFormID');
$tabs_form->set_css_class('tabs-container fieldset-content');
<br />
$fieldset_tab_menu = new FormFieldMenuFieldset('tabmenulistID', '');
$tabs_form->add_fieldset($fieldset_tab_menu);
<br />
$fieldset_tab_menu->add_field(new FormFieldMultitabsLinkList('tabitemlistID',
    array(
        //new FormFieldMultitabsLinkElement(ItemTitle, 'tabs', 'HTMLFormID_targetID', 'fa-icon', 'picture_url', 'active_module'),
        new FormFieldMultitabsLinkElement($this->lang['Pannel 01 tabitem'], 'tabs', 'HTMLFormID_targetID-01'),
        new FormFieldMultitabsLinkElement($this->lang['multitabs.tabs.title.link'], 'tabs', 'HTMLFormID_targetID-02'),
        new FormFieldMultitabsLinkElement($this->lang['multitabs.tabs.title.link'] . ' 03', 'tabs', 'HTMLFormID_targetID-03'),
    )
));
<br />
$fieldset_tab_one = new FormFieldsetMultitabsHTML('targetID-01', $this->lang['multitabs.panel.title'] . ' 01', array('css_class' => 'tabs tabs-animation first-tab'));
$tabs_form->add_fieldset($fieldset_tab_one);
<em>// content of pannel 01</em>
...
// subtitle separator inside a fieldset
$fieldset_tab_one->add_field(new FormFieldSubTitle('subtitleID', $this->lang['multitabs.form.subtitle'],''));
...
$fieldset_tab_two = new FormFieldsetMultitabsHTML('targetID-02', $this->lang['multitabs.panel.title'] . ' 02', array('css_class' => 'tabs tabs-animation'));
$tabs_form->add_fieldset($fieldset_tab_two);
<em>// content of pannel 02</em>
...
$fieldset_tab_three = new FormFieldsetMultitabsHTML('targetID-03', $this->lang['multitabs.panel.title'] . ' 03', array('css_class' => 'tabs tabs-animation'));
$tabs_form->add_fieldset($fieldset_tab_three);
<em>// content of pannel  03</em>
...
</code></pre>
