<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 08 25
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

$lang['builder.explain'] = '
    <p>This page shows the render of form elements.</p>
    <p>Built with the PHP constructor, you can also <a class="pinned bgc link-color" href="#markup-view">build them with html <i class="fa fa-caret-down"></i></a>.</p>
    <p>To get the php declaration, please read <a href="https://github.com/PHPBoost/PHPBoost/blob/master/sandbox/controllers/SandboxBuilderController.class.php#L110">the file</a> of the module, or the A.P.I. documentation of the <a href="https://www.phpboost.com/api"><i class="fa iboost fa-iboost-phpboost"></i> PHPHBoost</a> website</p>
';
$lang['builder.title'] = 'Form';
$lang['builder.preview'] = 'Preview';

$lang['builder.title.inputs'] = 'Text fields';
$lang['builder.title.textarea'] = 'Textarea';
$lang['builder.title.choices'] = 'Radio / checkbox';
$lang['builder.title.select'] = 'Selects';
$lang['builder.title.buttons'] = 'Buttons';
$lang['builder.title.upload'] = 'Upload';
$lang['builder.title.gmap'] = 'Google Maps';
$lang['builder.title.date'] = 'Date';
$lang['builder.title.authorization'] = 'Authorization';
$lang['builder.title.orientation'] = 'Orientation';

// Text fields
$lang['builder.input.text'] = 'Text field';
$lang['builder.input.text.desc'] = 'Constraints: letters, numbers & underscore';
$lang['builder.input.text.lorem'] = 'Lorem ipsum';
$lang['builder.input.text.disabled'] = 'Disabed fiels';
$lang['builder.input.text.disabled.desc'] = 'Disabed';
$lang['builder.input.text.readonly'] = 'Read only field';
$lang['builder.input.url'] = 'Website';
$lang['builder.input.url.desc'] = 'Valid url';
$lang['builder.input.url.placeholder'] = 'https://www.phpboost.com';
$lang['builder.input.email'] = 'Email';
$lang['builder.input.email.desc'] = 'Valid email';
$lang['builder.input.email.placeholder'] = 'lorem@phpboost.com';
$lang['builder.input.email.multiple'] = 'Multiple email';
$lang['builder.input.email.multiple.desc'] = 'Valid email, comma separated';
$lang['builder.input.email.multiple.placeholder'] = 'lorem@phpboost.com,ipsum@phpboost.com';
$lang['builder.input.phone'] = 'Phone number';
$lang['builder.input.phone.desc'] = 'Valid phone number';
$lang['builder.input.phone.placeholder'] = '01234567890';
$lang['builder.input.text.required'] = 'Required field';
$lang['builder.input.text.required.filled'] = 'Filled required field';
$lang['builder.input.text.required.empty'] = 'Empty required field';
$lang['builder.input.number'] = 'Number';
$lang['builder.input.number.desc'] = 'interval: from 10 to 100';
$lang['builder.input.number.placeholder'] = '20';
$lang['builder.input.number.decimal'] = 'Decimal number';
$lang['builder.input.number.decimal.desc'] = 'Use comma';
$lang['builder.input.number.decimal.placeholder'] = '5.5';
$lang['builder.input.length'] = 'Slider';
$lang['builder.input.length.desc'] = 'Drag it';
$lang['builder.input.length.placeholder'] = '4';
$lang['builder.input.password'] = 'Password';
$lang['builder.input.password.desc'] = ' characters at least';
$lang['builder.input.password.placeholder'] = 'aaaaaa';
$lang['builder.input.password.confirm'] = 'Confirm password';

// Textareas
$lang['builder.input.multiline.medium'] = 'Medium multiline text field ';
$lang['builder.input.multiline'] = 'Multiline text field';
$lang['builder.input.multiline.desc'] = 'Description';
$lang['builder.input.multiline.lorem'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut tempor lacus.';
$lang['builder.input.rich.text'] = 'Text field with editor';
$lang['builder.input.rich.text.placeholder'] = 'Lorem ipsum dolor sit <strong>amet</strong>';

// Choices
$lang['builder.input.checkbox'] = 'Checkboxes';
$lang['builder.input.multiple.checkbox'] = 'Multiple checkboxes';
$lang['builder.input.radio'] = 'Radio buttons';
$lang['builder.input.select'] = 'Select';
$lang['builder.input.fake.select'] = 'Fake select with icons/pictures';
$lang['builder.input.multiple.select'] = 'Multiple select';
$lang['builder.input.choice'] = 'Choice ';
$lang['builder.input.choice.group'] = 'Group ';
$lang['builder.input.timezone'] = 'TimeZone';
$lang['builder.input.user.completion'] = 'Users autocompletion';

// Miscellaneaous
$lang['builder.title.miscellaneous'] = 'Miscellaneaous';

$lang['builder.desc'] = 'This is a description';
$lang['builder.spacer'] = 'This is a line breack, it can be display text free';
$lang['builder.subtitle'] = 'Form subtitle';
$lang['builder.input.hidden'] = 'Hidden field';
$lang['builder.free.html'] = 'Free field';
$lang['builder.date'] = 'Date';
$lang['builder.date.hm'] = 'Date/hour/minutes';
$lang['builder.possible.values'] = 'Add possible values options';
$lang['builder.sources'] = 'Add sources';
$lang['builder.color'] = 'Color';
$lang['builder.search'] = 'Search';
$lang['builder.file.picker'] = 'File';
$lang['builder.multiple.file.picker'] = 'Several files';
$lang['builder.thumbnail.picker'] = 'Thumbnail';
$lang['builder.file.upload'] = 'Link to file';
$lang['builder.captcha'] = 'You have to be disconnected to see this field on the bottom of this page, before the list of source codes.';

// Links
$lang['builder.links.menu'] = 'Links menu';
$lang['builder.links.list'] = 'Links list';
$lang['builder.link.icon'] = 'Item with icon';
$lang['builder.link.img'] = 'Item with picture';
$lang['builder.link'] = 'List item';
$lang['builder.modal.menu'] = 'Modal menu';
$lang['builder.tabs.menu'] = 'Tabs menu';
$lang['builder.panel'] = 'Panel';

// Googlemap
$lang['builder.googlemap'] = 'field from GoogleMaps module';
$lang['builder.googlemap.simple_address'] = 'Single address';
$lang['builder.googlemap.map_address'] = 'Address with map';
$lang['builder.googlemap.simple_marker'] = 'Marker';
$lang['builder.googlemap.multiple_markers'] = 'Multiple markers';

// Authorizations
$lang['builder.authorization'] = 'Authorization';
$lang['builder.authorization.1'] = 'Action 1';
$lang['builder.authorization.1.desc'] = 'Authorizations for action 1';
$lang['builder.authorization.2'] = 'Action 2';

// Orientations
$lang['builder.vertical.desc'] = 'Vertical form';
$lang['builder.horizontal.desc'] = 'Horizontal form';

// Buttons
$lang['builder.all.buttons'] = 'All buttons must contain the .button class.<br /><br />';
$lang['builder.send.button'] = 'Send';
$lang['builder.button'] = 'Button';
$lang['builder.button.sizes'] = 'With size';
$lang['builder.button.colors'] = 'With color';
$lang['builder.button.link'] = 'With link';
$lang['builder.button.picture'] = 'With picture';
$lang['builder.button.icon'] = 'With icon';
$lang['builder.button.confirm'] = 'With confirm';
$lang['builder.button.confirm.alert'] = 'This link will redirect to official website.';
$lang['builder.button.alternate.send'] = 'Alternate send button';
$lang['builder.buttons'] = 'Buttons';
?>
