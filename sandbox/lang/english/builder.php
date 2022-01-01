<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

$lang['sandbox.builder.description'] = '
    <p>This page shows the render of form elements.</p>
    <p>Built with the PHP constructor, you can also <a class="pinned bgc link-color offload" href="#markup-view">build them with html <i class="fa fa-caret-down"></i></a>.</p>
    <p>To get the php declaration, please read <aclass="offload"  href="https://github.com/PHPBoost/PHPBoost/blob/master/sandbox/controllers/SandboxBuilderController.class.php#L110">the file</a> of the module, or the A.P.I. documentation of the <a href="https://www.phpboost.com/api"><i class="fa iboost fa-iboost-phpboost"></i> PHPHBoost</a> website</p>
';
$lang['sandbox.builder.title']   = 'Form';
$lang['sandbox.builder.preview'] = 'Preview';

$lang['sandbox.builder.text.fields']     = 'Text fields';
$lang['sandbox.builder.textarea']        = 'Textarea';
$lang['sandbox.builder.checked.choices'] = 'Radio / checkbox';
$lang['sandbox.builder.selects']         = 'Selects';
$lang['sandbox.builder.buttons']         = 'Buttons';
$lang['sandbox.builder.title.upload']    = 'Upload';
$lang['sandbox.builder.gmap']            = 'Google Maps';
$lang['sandbox.builder.dates']           = 'Dates';

// Text fields
$lang['sandbox.builder.text.field']                       = 'Text field';
$lang['sandbox.builder.text.field.clue']                  = 'Constraints: letters, numbers & underscore';
$lang['sandbox.builder.text.field.lorem']                 = 'Lorem ipsum';
$lang['sandbox.builder.text.field.disabled']              = 'Disabed fiels';
$lang['sandbox.builder.text.field.disabled.clue']         = 'Disabed';
$lang['sandbox.builder.text.field.readonly']              = 'Read only field';
$lang['sandbox.builder.url.field']                        = 'Website';
$lang['sandbox.builder.url.field.clue']                   = 'Valid url';
$lang['sandbox.builder.url.field.placeholder']            = 'https://www.phpboost.com';
$lang['sandbox.builder.email.field']                      = 'Email';
$lang['sandbox.builder.email.field.clue']                 = 'Valid email';
$lang['sandbox.builder.email.field.placeholder']          = 'lorem@phpboost.com';
$lang['sandbox.builder.email.field.multiple']             = 'Multiple email';
$lang['sandbox.builder.email.field.multiple.clue']        = 'Valid email, comma separated';
$lang['sandbox.builder.email.field.multiple.placeholder'] = 'lorem@phpboost.com,ipsum@phpboost.com';
$lang['sandbox.builder.phone.field']                      = 'Phone number';
$lang['sandbox.builder.phone.field.clue']                 = 'Valid phone number';
$lang['sandbox.builder.phone.field.placeholder']          = '01234567890';
$lang['sandbox.builder.text.field.required']              = 'Required field';
$lang['sandbox.builder.text.field.required.filled']       = 'Filled required field';
$lang['sandbox.builder.text.field.required.empty']        = 'Empty required field';
$lang['sandbox.builder.number.field']                     = 'Number';
$lang['sandbox.builder.number.field.clue']                = 'interval: from 10 to 100';
$lang['sandbox.builder.number.field.placeholder']         = '20';
$lang['sandbox.builder.number.field.decimal']             = 'Decimal number';
$lang['sandbox.builder.number.field.decimal.clue']        = 'Use comma';
$lang['sandbox.builder.number.field.decimal.placeholder'] = '5.5';
$lang['sandbox.builder.slider.field']                     = 'Slider';
$lang['sandbox.builder.slider.field.clue']                = 'Drag it';
$lang['sandbox.builder.slider.field.placeholder']         = '4';
$lang['sandbox.builder.password.field']                   = 'Password';
$lang['sandbox.builder.password.field.clue']              = ' characters at least';
$lang['sandbox.builder.password.field.placeholder']       = 'aaaaaa';
$lang['sandbox.builder.password.field.confirm']           = 'Confirm password';

// Textareas
$lang['sandbox.builder.multiline.medium']      = 'Medium multiline text field ';
$lang['sandbox.builder.multiline']             = 'Multiline text field';
$lang['sandbox.builder.multiline.clue']        = 'Description';
$lang['sandbox.builder.multiline.lorem']       = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut tempor lacus.';
$lang['sandbox.builder.rich.text']             = 'Text field with editor';
$lang['sandbox.builder.rich.text.placeholder'] = 'Lorem ipsum dolor sit <strong>amet</strong>';

// Choices
$lang['sandbox.builder.checkbox']                = 'Checkboxes';
$lang['sandbox.builder.multiple.checkbox']       = 'Multiple checkboxes';
$lang['sandbox.builder.radio']                   = 'Radio buttons';
$lang['sandbox.builder.select']                  = 'Select';
$lang['sandbox.builder.select.to.list']          = 'Select with icons/pictures';
$lang['sandbox.builder.multiple.select']         = 'Multiple select';
$lang['sandbox.builder.multiple.select.to.list'] = 'Multiple select with icons/pictures';
$lang['sandbox.builder.choice']                  = 'Choice ';
$lang['sandbox.builder.choice.group']            = 'Group ';
$lang['sandbox.builder.timezone']                = 'TimeZone';
$lang['sandbox.builder.user.completion']         = 'Users autocompletion';

// Miscellaneaous
$lang['sandbox.builder.clue']                 = 'This is a description';
$lang['sandbox.builder.spacer']               = 'This is a line breack, it can be display text free';
$lang['sandbox.builder.subtitle']             = 'Form subtitle';
$lang['sandbox.builder.hidden']               = 'Hidden field';
$lang['sandbox.builder.free.html']            = 'Free field';
$lang['sandbox.builder.date']                 = 'Date';
$lang['sandbox.builder.date.hm']              = 'Date/hour/minutes';
$lang['sandbox.builder.possible.values']      = 'Add possible values options';
$lang['sandbox.builder.sources']              = 'Add sources';
$lang['sandbox.builder.color']                = 'Color';
$lang['sandbox.builder.search']               = 'Search';
$lang['sandbox.builder.file.picker']          = 'File';
$lang['sandbox.builder.multiple.file.picker'] = 'Several files';
$lang['sandbox.builder.thumbnail.picker']     = 'Thumbnail';
$lang['sandbox.builder.file.upload']          = 'Link to file';
$lang['sandbox.builder.captcha']              = 'You have to be disconnected to see this field on the bottom of this page, before the list of source codes.';

// Links
$lang['sandbox.builder.links.menu'] = 'Links menu';
$lang['sandbox.builder.links.list'] = 'Links list';
$lang['sandbox.builder.link.icon']  = 'Item with icon';
$lang['sandbox.builder.link.img']   = 'Item with picture';
$lang['sandbox.builder.link']       = 'List item';
$lang['sandbox.builder.modal.menu'] = 'Modal menu';
$lang['sandbox.builder.tabs.menu']  = 'Tabs menu';
$lang['sandbox.builder.panel']      = 'Panel';

// Googlemap
$lang['sandbox.builder.googlemap']                  = 'field from GoogleMaps module';
$lang['sandbox.builder.googlemap.simple.address']   = 'Single address';
$lang['sandbox.builder.googlemap.map.address']      = 'Address with map';
$lang['sandbox.builder.googlemap.simple.marker']    = 'Marker';
$lang['sandbox.builder.googlemap.multiple.markers'] = 'Multiple markers';

// Authorizations
$lang['sandbox.builder.authorization']        = 'Authorization';
$lang['sandbox.builder.authorization.1']      = 'Action 1';
$lang['sandbox.builder.authorization.1.clue'] = 'Authorizations for action 1';
$lang['sandbox.builder.authorization.2']      = 'Action 2';

// Orientations
$lang['sandbox.builder.vertical.clue']   = 'Vertical form';
$lang['sandbox.builder.horizontal.clue'] = 'Horizontal form';

// Buttons
$lang['sandbox.builder.all.buttons']           = 'All buttons must contain the .button class.<br /><br />';
$lang['sandbox.builder.send.button']           = 'Send';
$lang['sandbox.builder.button']                = 'Button';
$lang['sandbox.builder.button.sizes']          = 'With size';
$lang['sandbox.builder.button.colors']         = 'With color';
$lang['sandbox.builder.button.link']           = 'With link';
$lang['sandbox.builder.button.picture']        = 'With picture';
$lang['sandbox.builder.button.icon']           = 'With icon';
$lang['sandbox.builder.button.confirm']        = 'With confirm';
$lang['sandbox.builder.button.confirm.alert']  = 'This link will redirect to official website.';
$lang['sandbox.builder.button.alternate.send'] = 'Alternate send button';
?>
