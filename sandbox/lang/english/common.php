<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 01 14
 * @since   	PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

// --- Titre du module
$lang['module.title'] = 'Sandbox';
$lang['config.authorizations.read']  = 'Read authorizations';

// --- Mini module
$lang['mini.module.title'] = 'Sandbox';
$lang['mini.config.title'] = 'Configuration of the mini module';
$lang['mini.superadmin.enabled'] = 'Limit the access to only one administrator';
$lang['mini.superadmin.id'] = 'Choose the administrator';
$lang['mini.open.menu'] = 'Open the menu on the :';
$lang['mini.open.menu.left'] = 'left';
$lang['mini.open.menu.right'] = 'right';
$lang['is.not.admin'] = 'The member is not an administrator or don\'t exists';

$lang['mini.close'] = 'Close the menu';
$lang['mini.version.pbt'] = 'PHPBoost version';
$lang['mini.version.php'] = 'PHP server version';
$lang['mini.version.sql'] = 'MySql version';
$lang['mini.version.date'] = 'Installation date of the site';
$lang['mini.viewport.v'] = 'Height of viewport';
$lang['mini.viewport.h'] = 'Width of viewport';
$lang['mini.tools'] = 'Tools';
$lang['mini.errors'] = 'Archived errors';
$lang['mini.404'] = '404 errors';
$lang['mini.coms'] = 'Comments';
$lang['mini.database'] = 'Database';
$lang['mini.enable.css.cache'] = 'Enable the css cache';
$lang['mini.disable.css.cache'] = 'Disable the css cache';
$lang['mini.clean.css.cache'] = 'Refresh the css cache';
$lang['mini.clean.tpl.cache'] = 'Refresh the site cache';
$lang['mini.clean.rss.cache'] = 'Refresh the syndication cache';

$lang['mini.personalization'] = 'Personnalization';
$lang['mini.menus'] = 'Menus';
$lang['mini.enable.left.col'] = 'Enable the left column';
$lang['mini.disable.left.col'] = 'Disable the left column';
$lang['mini.enable.right.col'] = 'Enable the right column';
$lang['mini.disable.right.col'] = 'Disable the right column';
$lang['mini.general.config'] = 'General';
$lang['mini.advanced.config'] = 'Advanced';
$lang['mini.user'] = 'Users';
$lang['mini.theme'] = 'Themes';
$lang['mini.mod'] = 'Modules';

$lang['mini.sandbox.mod'] = 'Sandbox';
$lang['mini.sandbox.form'] = 'Forms';
$lang['mini.sandbox.css'] = 'Css framework';
$lang['mini.sandbox.bbcode'] = 'BBCode framework';
$lang['mini.sandbox.table'] = 'Tables';
$lang['mini.sandbox.menu'] = 'CssMenu';

$lang['mini.themes.switcher'] = 'Switch template';
$lang['mini.default.theme'] = 'Default template';

$lang['mini.config'] = 'Configuration';
$lang['mini.manage'] = 'Management';
$lang['mini.add'] = 'Add';

// --- Page d'accueil
$lang['title.config'] = 'Configuration';
$lang['title.form.builder'] = 'Form builder';
$lang['title.css'] = 'CSS';
$lang['title.plugins'] = 'Plugins';
$lang['title.bbcode'] = 'BBCode';
$lang['title.menu'] = 'Cssmenu menus ';
$lang['title.icons'] = 'Font-Awesome icons ';
$lang['title.table.builder'] = 'Table builder';
$lang['title.mail.sender'] = 'Email sending';
$lang['title.string.template'] = 'Template generator';

$lang['welcome.message'] = '<p>Welcome to the Sandbox module.</p>
<br />
<p>You can try here several part of the PHPBoost framework :</p>
<ul class="sandbox-home-list">
<li><i class="fa fa-fw fa-asterisk"></i> Rendering of the different fields of the <a href="' . SandboxUrlBuilder::form()->absolute() . '">form builder</a></li>
<li><i class="fab fa-fw fa-css3"></i> Rendering of the main <a href="' . SandboxUrlBuilder::css()->absolute() . '">CSS classes</a></li>
<li><i class="far fa-fw fa-file-code"></i> Rendering of the specific styles from the <a href="' . SandboxUrlBuilder::bbcode()->absolute() . '">BBCode</a></li>
<li><i class="fab fa-fw fa-font-awesome-flag"></i> A tutorial about using icons from the <a href="' . SandboxUrlBuilder::icons()->absolute() . '">Font Awesome library</a></li>
<li><i class="fa fa-fw fa-list"></i> Rendering of the <a href="' . SandboxUrlBuilder::menu()->absolute() . '">Cssmenu navigation menus</a>.</li>
<li><i class="fa fa-fw fa-table"></i> The dynamic <a href="' . SandboxUrlBuilder::table()->absolute() . '">table generation</a></li>
<li><i class="fa fa-fw fa-at"></i> <a href="' . SandboxUrlBuilder::mail()->absolute() . '">Emails sending</a></li>
<li><i class="fa fa-fw fa-code"></i> <a href="' . SandboxUrlBuilder::template()->absolute() . '">Template generation</a> with or without cache</li>
</ul>
<br />
';

// --- Framework lorem

$lang['framework.lorem.mini'] = 'Etiam hendrerit, tortor et faucibus dapibus, eros orci porta eros, in facilisis ipsum ipsum at nisl';
$lang['framework.lorem.medium'] = 'Fusce vitae consequat nisl. Fusce vestibulum porta ipsum ac consectetur. Duis finibus mauris eu feugiat congue.
Aenean aliquam accumsan ipsum, ac dapibus dui ultricies non. In hac habitasse platea dictumst. Aenean mi nibh, varius vel lacus at, tincidunt luctus eros.
In hac habitasse platea dictumst. Vestibulum luctus lorem nisl, et hendrerit lectus dapibus ut. Phasellus sit amet nisl tortor.
Aenean pulvinar tellus nulla, sit amet mattis nisl semper eu. Phasellus efficitur nisi a laoreet dignissim. Aliquam erat volutpat.';
$lang['framework.lorem.large'] = ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut hendrerit odio urna, blandit pharetra elit
scelerisque tempor. Nulla dapibus felis orci, at consectetur orci auctor eget. Donec eros lectus, mollis eget auctor vel, convallis ac mauris.
Cras imperdiet, erat ac semper volutpat, libero orci varius mi, et ullamcorper quam urna vitae augue. Maecenas maximus vitae diam vel porta.
Pellentesque dignissim dolor eu neque aliquet viverra. Maecenas tincidunt, mi non gravida tincidunt, lectus elit gravida massa,
sed viverra tortor diam pretium metus. In hac habitasse platea dictumst. Ut velit turpis, sollicitudin non risus et, pretium efficitur leo.
Integer elementum faucibus finibus. Nullam et felis sit amet felis blandit iaculis. Vestibulum massa arcu, finibus id enim ac, commodo aliquam metus.
Vestibulum feugiat urna nunc, et eleifend velit posuere ac. Vestibulum sagittis tempus nunc, sit amet dignissim ipsum sollicitudin eget.';

//
$lang['sandbox.summary'] = 'Summary';
$lang['sandbox.source.code'] = 'See the source code';

// --- Formulaires

$lang['form.title'] = 'Form';
$lang['form.title.inputs'] = 'Text fields';
$lang['form.title.textarea'] = 'Textarea';
$lang['form.title.radio'] = 'Radio / checkbox';
$lang['form.title.upload'] = 'Upload';
$lang['form.title.gmap'] = 'Google Maps';
$lang['form.title.select'] = 'Select';
$lang['form.title.date'] = 'Date';
$lang['form.title.authorization'] = 'Authorization';
$lang['form.title.orientation'] = 'Orientation';
$lang['form.desc'] = 'This is a description';
$lang['form.input.text'] = 'Text field';
$lang['form.input.text.desc'] = 'Constraints: letters, numbers and underscore';
$lang['form.input.text.lorem'] = 'Lorem ipsum';
$lang['form.input.text.disabled'] = 'Disabled field';
$lang['form.input.text.disabled.desc'] = 'Disabled';
$lang['form.input.url'] = 'Web site';
$lang['form.input.url.desc'] = 'Valid url';
$lang['form.input.url.placeholder'] = 'https://www.phpboost.com';
$lang['form.input.email'] = 'Email';
$lang['form.input.email.desc'] = 'Valid email';
$lang['form.input.email.placeholder'] = 'lorem@phpboost.com';
$lang['form.input.email.multiple'] = 'Multiple email';
$lang['form.input.email.multiple.desc'] = 'Valid emails, separated by comma';
$lang['form.input.email.multiple.placeholder'] = 'lorem@phpboost.com,ipsum@phpboost.com';
$lang['form.input.phone'] = 'Phone number';
$lang['form.input.phone.desc'] = 'Valid phone number';
$lang['form.input.phone.placeholder'] = '0123456789';
$lang['form.input.text.required'] = 'Required field';
$lang['form.input.text.required.filled'] = 'Required field filled';
$lang['form.input.text.required.empty'] = 'Required field empty';
$lang['form.input.number'] = 'Number';
$lang['form.input.number.desc'] = 'interval: from 10 to 100';
$lang['form.input.number.placeholder'] = '20';
$lang['form.input.number.decimal'] = 'Decimal number';
$lang['form.input.number.decimal.desc'] = 'Use comma';
$lang['form.input.number.decimal.placeholder'] = '5.5';
$lang['form.input.length'] = 'Slider';
$lang['form.input.length.desc'] = 'Clic and drag';
$lang['form.input.length.placeholder'] = '4';
$lang['form.input.password'] = 'Password';
$lang['form.input.password.desc'] = ' characters minimum';
$lang['form.input.password.placeholder'] = 'aaaaaa';
$lang['form.input.password.confirm'] = 'Confirm password';
$lang['form.input.multiline.medium'] = 'Medium multiline text field';
$lang['form.input.multiline'] = 'Multiline text field';
$lang['form.input.multiline.desc'] = 'Description';
$lang['form.input.multiline.lorem'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut tempor lacus.';
$lang['form.input.rich.text'] = 'Text field with editor';
$lang['form.input.rich.text.placeholder'] = 'Create a website <strong>easily</strong>';
$lang['form.input.checkbox'] = 'Checkbox';
$lang['form.input.multiple.checkbox'] = 'Multiple checkbox';
$lang['form.input.radio'] = 'Radio buttons';
$lang['form.input.select'] = 'Select';
$lang['form.input.multiple.select'] = 'Select with serveral choices';
$lang['form.input.choice.1'] = 'Choice 1';
$lang['form.input.choice.2'] = 'Choice 2';
$lang['form.input.choice.3'] = 'Choice 3';
$lang['form.input.choice.4'] = 'Choice 4';
$lang['form.input.choice.5'] = 'Choice 5';
$lang['form.input.choice.6'] = 'Choice 6';
$lang['form.input.choice.7'] = 'Choice 7';
$lang['form.input.choice.group.1'] = 'Group 1';
$lang['form.input.choice.group.2'] = 'Group 2';
$lang['form.input.timezone'] = 'TimeZone';
$lang['form.input.user.completion'] = 'User completion';
$lang['form.send.button'] = 'Send';

$lang['form.title.2'] = 'Form 2';
$lang['form.input.hidden'] = 'hidden field';
$lang['form.free.html'] = 'Free field';
$lang['form.date'] = 'Date';
$lang['form.date.hm'] = 'Date/hour/minutes';
$lang['form.color'] = 'color';
$lang['form.search'] = 'Search';
$lang['form.file.picker'] = 'File picker';
$lang['form.multiple.file.picker'] = 'Multiple file picker';
$lang['form.file.upload'] = 'Link to file';
$lang['form.action.link.list'] = 'List of links';
$lang['form.action.link.1'] = 'Link item 1';
$lang['form.action.link.2'] = 'Link item 2';
$lang['form.action.link.3'] = 'Link item 3';
$lang['form.action.link.4'] = 'Link item 4';

$lang['form.googlemap'] = 'GoogleMaps modue fields';
$lang['form.googlemap.simple_address'] = 'Simple Address';
$lang['form.googlemap.map_address'] = 'Address with map';
$lang['form.googlemap.simple_marker'] = 'Marker';
$lang['form.googlemap.multiple_markers'] = 'Multiples markers';

$lang['form.authorization'] = 'Authorization';
$lang['form.authorization.1'] = 'Action 1';
$lang['form.authorization.1.desc'] = 'Authorizations for l\'action 1';
$lang['form.authorization.2'] = 'Action 2';

$lang['form.vertical.desc'] = 'Vertical form';
$lang['form.horizontal.desc'] = 'Horizontal form';

$lang['form.preview'] = 'Preview';
$lang['form.button'] = 'Button';

// --- CSS

// Pages
$lang['css.page.title'] = 'Page title';
$lang['css.more'] = 'Author | Creation date | Number of comments ...';
$lang['css.picture'] = 'Picture';

$lang['css.class'] = 'class';
$lang['css.form'] = 'form';
$lang['css.options.file.title'] = 'Informations on files';
$lang['css.options'] = '.options';
$lang['css.options.sort_by'] = 'Order by';
$lang['css.options.sort_by.alphabetical'] = 'Alphabetical';
$lang['css.options.sort_by.size'] = 'Size';
$lang['css.options.sort_by.date'] = 'Date';
$lang['css.options.sort_by.popularity'] = 'Views';
$lang['css.options.sort_by.note'] = 'Note';
$lang['css.options.link'] = 'Link';
$lang['css.options.option.title'] = 'Module option';
$lang['css.options.option.com'] = 'No comment';
$lang['css.modules_menus.direction.up'] = 'Up';
$lang['css.modules_menus.direction.down'] = 'Down';

// Typogrphie
$lang['css.title.framework'] = 'Layout';
$lang['css.title.typography'] = 'Typography';
$lang['css.titles'] = 'Titles';
$lang['css.title'] = 'Title';

$lang['css.styles'] = 'Styles';
$lang['css.text_bold'] = 'Bold text';
$lang['css.text_italic'] = 'Italic text';
$lang['css.text_underline'] = 'Underline text';
$lang['css.text_strike'] = 'Strike text';

$lang['css.title.sizes'] = 'Text Sizes';
$lang['css.text'] = 'Text';
$lang['css.text.smaller'] = 'Smaller text';
$lang['css.text.small'] = 'Small text';
$lang['css.text.big'] = 'Big text';
$lang['css.text.bigger'] = 'Bigger text';
$lang['css.text.biggest'] = 'Biggest text';
$lang['css.link'] = 'Hypertext link';

$lang['css.rank_color'] = 'User color ranking';
$lang['css.admin'] = 'Administrator';
$lang['css.modo'] = 'Moderator';
$lang['css.member'] = 'Member';

// Divers
$lang['css.miscellaneous'] = 'Miscellaneous';
$lang['css.main_actions_icons'] = 'Main actions icons';
$lang['css.rss_feed'] = 'RSS feed';
$lang['css.edit'] = 'Edit';
$lang['css.delete'] = 'Delete';
$lang['css.delete.confirm'] = 'Delete (automatic JS control with delete confirmation)';
$lang['css.delete.confirm.custom'] = 'Delete (automatic JS control with custom confirmation)';
$lang['css.delete.custom_message'] = 'Custom message';

$lang['css.lists'] = 'Lists';
$lang['css.element'] = 'Element';

$lang['css.progress_bar'] = 'Progress bar';
$lang['css.progress_bar.util_infos'] = 'Usefull informations';
$lang['css.progress_bar.votes'] = '3 votes';

$lang['css.explorer'] = 'Explorer';
$lang['css.root'] = 'Root';
$lang['css.tree'] = 'Tree';
$lang['css.cat'] = 'Category';
$lang['css.file'] = 'File';

$lang['css.button'] = 'Buttons';
$lang['css.button.other'] = 'Other buttons';

$lang['css.notation'] = 'Notation';
$lang['css.notation.possible.values'] = 'Possible values';
$lang['css.notation.example'] = 'Example for a rating of 2.4 on 5';

$lang['css.sortable'] = 'Drag & Drop Sortable';
$lang['css.static.sortable'] = 'Spoted sortable';
$lang['css.moving.sortable'] = 'Moving sortable';
$lang['css.dropzone'] = 'Spot here';
$lang['css.sortable.move'] = 'Moving';

//Blockquote
$lang['css.quote'] = 'Quote';
$lang['css.code'] = 'Code';
$lang['css.code.php'] = 'PHP code';
$lang['css.hidden'] = 'Hidden text';

//Pagination
$lang['css.pagination'] = 'Pagination';

//Tables
$lang['css.table'] = 'Tables';
$lang['css.table.description'] = 'Description';
$lang['css.table.description.content'] = 'Table description';
$lang['css.table.name'] = 'Name';
$lang['css.table.caption'] = 'This is a table';
$lang['css.table.caption.no.header'] = 'This is a table with no header';
$lang['css.table.author'] = 'Author';
$lang['css.table.test'] = 'Test';
$lang['css.table.header'] = 'Header';
$lang['css.table.sort.up'] = 'Sort up';
$lang['css.table.sort.down'] = 'Sort down';

//Messages
$lang['css.messages.and.coms'] = 'Messages et comments';
$lang['css.messages.login'] = 'admin';
$lang['css.messages.level'] = 'Administrator';
$lang['css.messages.date'] = '09/05/2016 at 03h37 pm';
$lang['css.messages.content'] = 'This is a comment';

$lang['css.alert.messages'] = 'Alert messages';
$lang['css.message.success'] = 'This is a success message';
$lang['css.message.notice'] = 'This is a notice message';
$lang['css.message.warning'] = 'This is a warning message';
$lang['css.message.error'] = 'This is a error message';
$lang['css.message.question'] = 'This is a question:<br /> is the two-lines display working correctly?';
$lang['css.message.member'] = 'This is a message limited to members';
$lang['css.message.modo'] = 'This is a message limited to moderators';
$lang['css.message.admin'] = 'This is a message limited to administrators';
$lang['css.message.float-unlimited'] = 'This is a floating message without time limit';
$lang['css.message.float-limited'] = 'This is a floating message with time limit';
$lang['css.message.float-display'] = 'Display floating messages';

//Blocs
$lang['css.blocks'] = 'Blocks';
$lang['css.block.title'] = 'Article title';
$lang['css.blocks.per.line'] = 'per row';

// --- Styles spécifiques du BBCode

$lang['bbcode.title.typography'] = 'Typography';

$lang['bbcode.titles'] = 'Titles';
$lang['bbcode.title_1'] = 'Title 1';
$lang['bbcode.title_2'] = 'Title 2';
$lang['bbcode.title_3'] = 'Title 3';
$lang['bbcode.title_4'] = 'Title 4';
$lang['bbcode.title_5'] = 'Title 5';

$lang['bbcode.title.lists'] = 'Lists';
$lang['bbcode.element'] = 'Element';
$lang['bbcode.element_1'] = 'Element 1';
$lang['bbcode.element_2'] = 'Element 2';
$lang['bbcode.element_3'] = 'Element 3';

$lang['bbcode.title.blocks'] = 'Blocks';
$lang['bbcode.paragraph'] = 'Paragraph';
$lang['bbcode.block'] = 'Block';
$lang['bbcode.fieldset'] = 'Fieldset';
$lang['bbcode.legend'] = 'Fieldset legend';

$lang['bbcode.title.media'] = 'Media';
$lang['bbcode.image'] = 'Picture';
$lang['bbcode.lightbox'] = 'Lightbox';
$lang['bbcode.youtube'] = 'Youtube';
$lang['bbcode.movie'] = 'Movie';
$lang['bbcode.flash'] = 'SWF';
$lang['bbcode.flash.alert'] = 'Your browser doesn\'t support Adobe Flash';
$lang['bbcode.audio'] = 'Sound';

$lang['bbcode.title.code'] = 'Blocquote';
$lang['bbcode.quote'] = 'Quote';
$lang['bbcode.hidden'] = 'Hidden';
$lang['bbcode.code.php'] = 'Code';

$lang['bbcode.title.table'] = 'Table';
$lang['bbcode.table.header'] = 'Header';
$lang['bbcode.table.name'] = 'Name';
$lang['bbcode.table.description'] = 'Description';

// --- Wiki
$lang['wiki.not'] = 'The Wiki is not installed and/or activated';
$lang['wiki.conditions'] = 'You must set the wiki in your template to get your modifications active.';
$lang['wiki.module'] = 'Wiki module';
$lang['wiki.table.of.contents'] = 'Summary';
$lang['wiki.contents'] = 'Wiki content';

// --- Icônes Font-Awesome

$lang['iconfa.sample'] = 'Some examples';
$lang['iconfa.social'] = 'Social networks';
$lang['iconfa.screen'] = 'Screen';
$lang['iconfa.icon'] = 'Icon';
$lang['iconfa.name'] = 'Name';
$lang['iconfa.code'] = 'Code';
$lang['iconfa.list'] = 'The complete list of icons and their own code : ';

$lang['iconfa.howto'] = 'How to ?';
$lang['iconfa.howto.explain'] = 'Font-Awesome is an icon-font, a font to simply display icons';
$lang['iconfa.howto.update'] = 'It\'s integrated since the 4.1 PHPBoost version. Each update of Font-Awesome is integrated in the next PHPBoost update..';
$lang['iconfa.howto.html'] = 'Html way';
$lang['iconfa.howto.html.class'] = 'Set the icon name as a class : ';
$lang['iconfa.howto.html.class.result.i'] = 'That gives the "edit" icon following by : ';
$lang['iconfa.howto.html.class.result.a'] = 'That gives the link with ths "globe" icon first : ';
$lang['iconfa.howto.html.class.result.all'] = 'You can play with all html tags.';
$lang['iconfa.howto.css'] = 'CSS way';
$lang['iconfa.howto.css.class'] = 'Set your class, then the icon code as content of ::before or ::after of the class :';
$lang['iconfa.howto.css.css.code'] = 'CSS :';
$lang['iconfa.howto.css.html.code'] = 'HTML :';
$lang['iconfa.howto.bbcode'] = 'BBCode way';
$lang['iconfa.howto.bbcode.some.icons'] = 'The icons mainly used in PHPBoost are already setted un the bbcode menu bar. You can select them by clicking on the flag icon menu:';
$lang['iconfa.howto.bbcode.tag'] = 'If the icon you want is not in the list, you can use the [fa] tag like this:';
$lang['iconfa.howto.bbcode.icon.name'] = '[fa]Icon name[/fa]';
$lang['iconfa.howto.bbcode.icon.test'] = 'E.g., [fa]cubes[/fa] gives:';
$lang['iconfa.howto.bbcode.icon.variants'] = 'Variants (see next paragraph) are usable with the BBCode and are explained in the PHPBoost documentation.';
$lang['iconfa.howto.variants'] = 'Variants';
$lang['iconfa.howto.variants.explain'] = 'Font-Awesome is setted with some variants like sizing the icon, animation, rotation, stacking and somelse.';
$lang['iconfa.howto.variants.list'] = 'All variants are explained here : ';
$lang['iconfa.howto.variants.spinner'] = 'That gives the "spinner" icon, setted in rotation and twice bigger than its initial size : ';

// --- Cssmenu

$lang['cssmenu.h1'] = 'Navigation menu';
$lang['cssmenu.site.slogan'] = 'Sandbox - Cssmenu Design';
$lang['cssmenu.breadcrumb.index'] = 'Home';
$lang['cssmenu.breadcrumb.sandbox'] = 'Sandbox';
$lang['cssmenu.breadcrumb.cssmenu'] = 'cssmenu';
$lang['cssmenu.h2'] = 'All the cssmenu menus';
$lang['cssmenu.long.element'] = 'Menu item very large to refine the cssmenu design';
$lang['cssmenu.element'] = 'Menu item';
$lang['cssmenu.sub.element'] = 'Submenu';
$lang['cssmenu.horizontal.top'] = 'Horizontal header menu';
$lang['cssmenu.horizontal.sub.header'] = 'Menu in sub-header';
$lang['cssmenu.sub.admin'] = 'Administration';
$lang['cssmenu.horizontal.scrolling'] = 'Scrolling horizontal menu';
$lang['cssmenu.vertical.scrolling'] = 'Scrolling vertical menu';
$lang['cssmenu.vertical.img'] = 'Menu avec images';
$lang['cssmenu.vertical.scrolling.left'] = 'Scroll vert left menu';
$lang['cssmenu.vertical.scrolling.right'] = 'Scroll vert right menu';
$lang['cssmenu.actionslinks.sandbox'] = 'Sandbox';
$lang['cssmenu.actionslinks.index'] = 'Home';
$lang['cssmenu.actionslinks.form'] = 'Form';
$lang['cssmenu.actionslinks.css'] = 'CSS';
$lang['cssmenu.actionslinks.bbcode'] = 'BBCode';
$lang['cssmenu.actionslinks.menu'] = 'Cssmenu';
$lang['cssmenu.actionslinks.icons'] = 'Font-Awesome Icons';
$lang['cssmenu.actionslinks.table'] = 'Table';
$lang['cssmenu.actionslinks.mail'] = 'Email sending';
$lang['cssmenu.actionslinks.template'] = 'Templates generation';
$lang['cssmenu.actionslinks'] = 'Modules options menu';
$lang['cssmenu.group'] = 'Group menu';
$lang['cssmenu.static'] = 'Static menu';
$lang['cssmenu.static.footer'] = 'Footer static menu';

$lang['cssmenu.warning'] = 'This page has a specific structure to display all kind of menus regarding to all places they can be setted.
It could be ugly if you don\'t use the rules of the Base template.';


// --- Mail

$lang['mail.title'] = 'Email';
$lang['mail.sender_mail'] = 'Sender email';
$lang['mail.sender_name'] = 'Sender name';
$lang['mail.recipient_mail'] = 'Recipient email';
$lang['mail.recipient_name'] = 'Recipient name';
$lang['mail.subject'] = 'Email subject';
$lang['mail.content'] = 'Content';
$lang['mail.smtp_config'] = 'SMTP configuration';
$lang['mail.smtp_config.explain'] = 'If you want to use a direct SMTP connection to send the email, check the box.';
$lang['mail.use_smtp'] = 'Use SMTP';
$lang['mail.smtp_configuration'] = 'SMTP parameters configuration';
$lang['mail.smtp.host'] = 'Host name';
$lang['mail.smtp.port'] = 'Port';
$lang['mail.smtp.login'] = 'Login';
$lang['mail.smtp.password'] = 'Password';
$lang['mail.smtp.secure_protocol'] = 'Secure protocol';
$lang['mail.smtp.secure_protocol.none'] = 'None';
$lang['mail.smtp.secure_protocol.tls'] = 'TLS';
$lang['mail.smtp.secure_protocol.ssl'] = 'SSL';
$lang['mail.success'] = 'The email has been sent';

//Template
$lang['string_template.result'] = 'Template generation duration without cache : :non_cached_time seconds<br />Template generation duration with cache: :cached_time seconds<br />Lenght of the parsed string: :string_length chars.';

?>
