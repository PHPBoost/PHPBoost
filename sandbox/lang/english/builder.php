<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 09
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

$lang['builder.clueription'] = '
    <p>This page shows the render of form elements.</p>
    <p>Built with the PHP constructor, you can also <a class="pinned bgc link-color offload" href="#markup-view">build them with html <i class="fa fa-caret-down"></i></a>.</p>
    <p>To get the php declaration, please read <aclass="offload"  href="https://github.com/PHPBoost/PHPBoost/blob/master/sandbox/controllers/SandboxBuilderController.class.php#L110">the file</a> of the module, or the A.P.I. documentation of the <a href="https://www.phpboost.com/api"><i class="fa iboost fa-iboost-phpboost"></i> PHPHBoost</a> website</p>
';
$lang['builder.title']   = 'Form';
$lang['builder.preview'] = 'Preview';

$lang['builder.text.fields']     = 'Text fields';
$lang['builder.textarea']        = 'Textarea';
$lang['builder.checked.choices'] = 'Radio / checkbox';
$lang['builder.selects']         = 'Selects';
$lang['builder.buttons']         = 'Buttons';
$lang['builder.title.upload']    = 'Upload';
$lang['builder.gmap']            = 'Google Maps';
$lang['builder.dates']           = 'Dates';

// Text fields
$lang['builder.text.field']                       = 'Text field';
$lang['builder.text.field.clue']                  = 'Constraints: letters, numbers & underscore';
$lang['builder.text.field.lorem']                 = 'Lorem ipsum';
$lang['builder.text.field.disabled']              = 'Disabed fiels';
$lang['builder.text.field.disabled.clue']         = 'Disabed';
$lang['builder.text.field.readonly']              = 'Read only field';
$lang['builder.url.field']                        = 'Website';
$lang['builder.url.field.clue']                   = 'Valid url';
$lang['builder.url.field.placeholder']            = 'https://www.phpboost.com';
$lang['builder.email.field']                      = 'Email';
$lang['builder.email.field.clue']                 = 'Valid email';
$lang['builder.email.field.placeholder']          = 'lorem@phpboost.com';
$lang['builder.email.field.multiple']             = 'Multiple email';
$lang['builder.email.field.multiple.clue']        = 'Valid email, comma separated';
$lang['builder.email.field.multiple.placeholder'] = 'lorem@phpboost.com,ipsum@phpboost.com';
$lang['builder.phone.field']                      = 'Phone number';
$lang['builder.phone.field.clue']                 = 'Valid phone number';
$lang['builder.phone.field.placeholder']          = '01234567890';
$lang['builder.text.field.required']              = 'Required field';
$lang['builder.text.field.required.filled']       = 'Filled required field';
$lang['builder.text.field.required.empty']        = 'Empty required field';
$lang['builder.number.field']                     = 'Number';
$lang['builder.number.field.clue']                = 'interval: from 10 to 100';
$lang['builder.number.field.placeholder']         = '20';
$lang['builder.number.field.decimal']             = 'Decimal number';
$lang['builder.number.field.decimal.clue']        = 'Use comma';
$lang['builder.number.field.decimal.placeholder'] = '5.5';
$lang['builder.slider.field']                     = 'Slider';
$lang['builder.slider.field.clue']                = 'Drag it';
$lang['builder.slider.field.placeholder']         = '4';
$lang['builder.password.field']                   = 'Password';
$lang['builder.password.field.clue']              = ' characters at least';
$lang['builder.password.field.placeholder']       = 'aaaaaa';
$lang['builder.password.field.confirm']           = 'Confirm password';

// Textareas
$lang['builder.multiline.medium']      = 'Medium multiline text field ';
$lang['builder.multiline']             = 'Multiline text field';
$lang['builder.multiline.clue']        = 'Description';
$lang['builder.multiline.lorem']       = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut tempor lacus.';
$lang['builder.rich.text']             = 'Text field with editor';
$lang['builder.rich.text.placeholder'] = 'Lorem ipsum dolor sit <strong>amet</strong>';

// Choices
$lang['builder.checkbox']                = 'Checkboxes';
$lang['builder.multiple.checkbox']       = 'Multiple checkboxes';
$lang['builder.radio']                   = 'Radio buttons';
$lang['builder.select']                  = 'Select';
$lang['builder.select.to.list']          = 'Select with icons/pictures';
$lang['builder.multiple.select']         = 'Multiple select';
$lang['builder.multiple.select.to.list'] = 'Multiple select with icons/pictures';
$lang['builder.choice']                  = 'Choice ';
$lang['builder.choice.group']            = 'Group ';
$lang['builder.timezone']                = 'TimeZone';
$lang['builder.user.completion']         = 'Users autocompletion';

// Miscellaneaous
$lang['builder.clue']                 = 'This is a description';
$lang['builder.spacer']               = 'This is a line breack, it can be display text free';
$lang['builder.subtitle']             = 'Form subtitle';
$lang['builder.hidden']               = 'Hidden field';
$lang['builder.free.html']            = 'Free field';
$lang['builder.date']                 = 'Date';
$lang['builder.date.hm']              = 'Date/hour/minutes';
$lang['builder.possible.values']      = 'Add possible values options';
$lang['builder.sources']              = 'Add sources';
$lang['builder.color']                = 'Color';
$lang['builder.search']               = 'Search';
$lang['builder.file.picker']          = 'File';
$lang['builder.multiple.file.picker'] = 'Several files';
$lang['builder.thumbnail.picker']     = 'Thumbnail';
$lang['builder.file.upload']          = 'Link to file';
$lang['builder.captcha']              = 'You have to be disconnected to see this field on the bottom of this page, before the list of source codes.';

// Links
$lang['builder.links.menu'] = 'Links menu';
$lang['builder.links.list'] = 'Links list';
$lang['builder.link.icon']  = 'Item with icon';
$lang['builder.link.img']   = 'Item with picture';
$lang['builder.link']       = 'List item';
$lang['builder.modal.menu'] = 'Modal menu';
$lang['builder.tabs.menu']  = 'Tabs menu';
$lang['builder.panel']      = 'Panel';

// Googlemap
$lang['builder.googlemap']                  = 'field from GoogleMaps module';
$lang['builder.googlemap.simple.address']   = 'Single address';
$lang['builder.googlemap.map.address']      = 'Address with map';
$lang['builder.googlemap.simple.marker']    = 'Marker';
$lang['builder.googlemap.multiple.markers'] = 'Multiple markers';

// Authorizations
$lang['builder.authorization']        = 'Authorization';
$lang['builder.authorization.1']      = 'Action 1';
$lang['builder.authorization.1.clue'] = 'Authorizations for action 1';
$lang['builder.authorization.2']      = 'Action 2';

// Orientations
$lang['builder.vertical.clue']   = 'Vertical form';
$lang['builder.horizontal.clue'] = 'Horizontal form';

// Buttons
$lang['builder.all.buttons']           = 'All buttons must contain the .button class.<br /><br />';
$lang['builder.send.button']           = 'Send';
$lang['builder.button']                = 'Button';
$lang['builder.button.sizes']          = 'With size';
$lang['builder.button.colors']         = 'With color';
$lang['builder.button.link']           = 'With link';
$lang['builder.button.picture']        = 'With picture';
$lang['builder.button.icon']           = 'With icon';
$lang['builder.button.confirm']        = 'With confirm';
$lang['builder.button.confirm.alert']  = 'This link will redirect to official website.';
$lang['builder.button.alternate.send'] = 'Alternate send button';
?>
