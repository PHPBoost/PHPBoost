<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 01 14
 * @since       PHPBoost 4.0 - 2013 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

// --- Module Titles
$lang['sandbox.module.title'] = 'Sandbox';

$lang['title.config'] = 'Configuration';
$lang['title.admin.form'] = 'Form in administration';
$lang['title.form.builder'] = 'Form builder';
$lang['title.css'] = 'CSS';
$lang['title.plugins'] = 'Plugins';
$lang['title.bbcode'] = 'BBCode';
$lang['title.menu'] = 'Cssmenu menus ';
$lang['title.icons'] = 'Font-Awesome icons ';
$lang['title.table.builder'] = 'Table builder';
$lang['title.mail.sender'] = 'Email sending';
$lang['title.string.template'] = 'Template generator';

// Home page
$lang['welcome.message'] = '<p>Welcome to the Sandbox module.</p>
<br />
<p>You can try here several part of the PHPBoost framework :</p>
<ul class="sandbox-home-list">
<li><i class="fa fa-fw fa-asterisk"></i> Rendering of the different fields of the <a href="' . SandboxUrlBuilder::form()->absolute() . '">form builder</a></li>
<li><i class="fab fa-fw fa-css3"></i> Rendering of the main <a href="' . SandboxUrlBuilder::css()->absolute() . '">CSS classes</a></li>
<li><i class="fa fa-fw fa-cube"></i> Rendering of the <a href="' . SandboxUrlBuilder::plugins()->absolute() . '">jQuery plugins</a> integrated in PHPBoost.</li>
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

$lang['lorem.short.content'] = 'Etiam hendrerit, tortor et faucibus dapibus, eros orci porta eros, in facilisis ipsum ipsum at nisl';
$lang['lorem.medium.content'] = 'Fusce vitae consequat nisl. Fusce vestibulum porta ipsum ac consectetur. Duis finibus mauris eu feugiat congue.
Aenean aliquam accumsan ipsum, ac dapibus dui ultricies non. In hac habitasse platea dictumst. Aenean mi nibh, varius vel lacus at, tincidunt luctus eros.
In hac habitasse platea dictumst. Vestibulum luctus lorem nisl, et hendrerit lectus dapibus ut. Phasellus sit amet nisl tortor.
Aenean pulvinar tellus nulla, sit amet mattis nisl semper eu. Phasellus efficitur nisi a laoreet dignissim. Aliquam erat volutpat.';
$lang['lorem.large.content'] = ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut hendrerit odio urna, blandit pharetra elit
scelerisque tempor. Nulla dapibus felis orci, at consectetur orci auctor eget. Donec eros lectus, mollis eget auctor vel, convallis ac mauris.
Cras imperdiet, erat ac semper volutpat, libero orci varius mi, et ullamcorper quam urna vitae augue. Maecenas maximus vitae diam vel porta.
Pellentesque dignissim dolor eu neque aliquet viverra. Maecenas tincidunt, mi non gravida tincidunt, lectus elit gravida massa,
sed viverra tortor diam pretium metus. In hac habitasse platea dictumst. Ut velit turpis, sollicitudin non risus et, pretium efficitur leo.
Integer elementum faucibus finibus. Nullam et felis sit amet felis blandit iaculis. Vestibulum massa arcu, finibus id enim ac, commodo aliquam metus.
Vestibulum feugiat urna nunc, et eleifend velit posuere ac. Vestibulum sagittis tempus nunc, sit amet dignissim ipsum sollicitudin eget.';

//
$lang['sandbox.summary'] = 'Summary';
$lang['sandbox.source.code'] = 'See the source code';

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
