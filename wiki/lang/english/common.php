<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 09 19
 * @since       PHPBoost 1.6 - 2007 10 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['wiki.module.title'] = 'Wiki';

$lang['wiki.page.views.number'] = 'This page has been seen %d times';
$lang['wiki.contribution']      = 'Contribute';
$lang['wiki.tools']             = 'Tools';
$lang['wiki.author']            = 'Author';
$lang['wiki.summary.menu']      = 'Table of contents';
$lang['wiki.random.page']       = 'Random page';
$lang['wiki.restriction.level'] = 'Permission level';
$lang['wiki.define.status']     = 'Define status';
$lang['wiki.last.items.list']   = 'Last updated articles :';
$lang['wiki.categories.list']   = 'List of main categories';
$lang['wiki.items.in.category'] = 'Articles of this category';
$lang['wiki.sub.categories']    = 'Categories contained by this category :';
$lang['wiki.no.sub.item']       = 'No existing sub-article';
$lang['wiki.no.item']       	= 'No article';

// Archives
$lang['wiki.history']         = 'History';
$lang['wiki.full.history']    = 'Wiki history';
$lang['wiki.item.history']    = 'History of article %s';
$lang['wiki.history.seo']     = 'All history of article %s';
$lang['wiki.versions'] 	      = 'Versions';
$lang['wiki.version.date']    = 'Version date';
$lang['wiki.current.version'] = 'Current version';
$lang['wiki.item.unexists']   = 'The article you want to read doesn\'t exist, if you want to create it you can do it on this page.';
$lang['wiki.consult']         = 'Read';
$lang['wiki.restore.version'] = 'Restore this version';
$lang['wiki.actions']         = 'Possible actions';
$lang['wiki.no.action']       = 'No action possible';

// Categories
$lang['wiki.current.category']         = 'Current category';
$lang['wiki.select.category']          = 'Select a category';
$lang['wiki.selected.category']        = 'Selected category';
$lang['wiki.no.category']              = 'No existing category';
$lang['wiki.no.selected.category']     = 'No category selected';
$lang['wiki.no.existing.category']     = 'No existing category';
$lang['wiki.no.existing.sub.category'] = 'No existing sub-category';

// Configuration
$lang['wiki.config.module.title'] 		 = 'Wiki module configuration';
$lang['wiki.config.name']                = 'Wiki name';
$lang['wiki.config.sticky.summary']      = 'Display articles summary in fixed position.';
$lang['wiki.config.enable.views.number'] = 'Display views number in articles';
$lang['wiki.config.index']               = 'Wiki home';
$lang['wiki.config.display.categories']  = 'Show principal categories list in home';
$lang['wiki.config.hide']                = 'Don\'t show';
$lang['wiki.config.show']                = 'Show';
$lang['wiki.config.last.items']          = 'Last articles number to show in home';
$lang['wiki.config.last.items.clue']     = '0 to deactivate';
$lang['wiki.config.description']         = 'Home text';
	// Authorizations
$lang['wiki.authorizations']                  = 'Permissions management in the wiki';
$lang['wiki.authorizations.clue']             = 'You can configure here everything concerning authorizations. You can attribute authorizations to a level and specials persmissions to a group.';
$lang['wiki.authorizations.read']             = 'Read articles';
$lang['wiki.authorizations.write']            = 'Create an article';
$lang['wiki.authorizations.create.category']  = 'Create a category';
$lang['wiki.authorizations.restore.archive']  = 'Restore an archive';
$lang['wiki.authorizations.delete.archive']   = 'Delete an archive';
$lang['wiki.authorizations.edit']             = 'Edit an article';
$lang['wiki.authorizations.delete']           = 'Delete an article';
$lang['wiki.authorizations.rename']           = 'Rename an article';
$lang['wiki.authorizations.redirect']         = 'Manage redirection to an article';
$lang['wiki.authorizations.move']             = 'Move an article';
$lang['wiki.authorizations.status']           = 'Edit an article status';
$lang['wiki.authorizations.comment']          = 'Comment an article';
$lang['wiki.authorizations.restriction']      = 'Edit restrictions level of an article';
$lang['wiki.authorizations.restriction.clue'] = 'It is advised to keep it for moderators only';
	// Default install
$lang['wiki.name'] = 'Wiki ' . GeneralConfig::load()->get_site_name();
$lang['wiki.index.text'] = '
	Welcome on the wiki module!
	<p>Here are some tips for a good start with this module :</p>
	<ul class="formatter-ul">
		<li class="formatter-li">To configure your module, go to the <a class="offload" href="' . WikiUrlBuilder::configuration()->relative() . '">administration module</a></li>
		<li class="formatter-li">To create categories, <a class="offload" href="' . WikiUrlBuilder::add_category()->relative() . '">click here</a></li>
		<li class="formatter-li">To create articles, <a class="offload" href="' . WikiUrlBuilder::add()->relative() . '">click here</a></li>
	</ul><br /><br />
	To customize the home page of this module, <a class="offload" href="' . WikiUrlBuilder::configuration()->relative() . '">click here</a>.<br /><br />
	For more information about the features of this module, feel free to ask questions on the <a class="offload" href="https://www.phpboost.com/forum/">support forum</a>.
';

//Hooks
$lang['wiki.specific_hook.wiki_change_status'] = 'Page status change';
$lang['wiki.specific_hook.wiki_delete_archive'] = 'Archive delete';
$lang['wiki.specific_hook.wiki_restore_archive'] = 'Archive restoration';

// Changing reason
$lang['wiki.changing.reason.label'] = 'Reason for the change (optional 100 car max)';
$lang['wiki.changing.reason']       = 'Reason for the change';
$lang['wiki.item.init']             = 'Initialization';

// Explorer
$lang['wiki.explorer']        = 'Wiki explorer';
$lang['wiki.explorer.short']  = 'Explorer';
$lang['wiki.explorer.seo']    = 'Explorer to navigate the tree of different pages of the wiki.';
$lang['wiki.root']            = 'Wiki root';
$lang['wiki.content']         = 'Content';
$lang['wiki.categories.tree'] = 'Category tree';

// Post | edit
$lang['wiki.create.item']     = 'Create an article';
$lang['wiki.create.category'] = 'Create a category';
$lang['wiki.warning.update']  = 'This article has been updated, you are now consulting an old release of this article!';
$lang['wiki.contribute']      = 'Contribute to the wiki';
$lang['wiki.edit.item']       = 'Edition of the article';
$lang['wiki.category.edit']   = 'Edition of the category';
	// js_tools
$lang['wiki.bbcode.wiki.icon']  = 'Wiki BBCode';
$lang['wiki.warning.link.name'] = 'Please enter a link name';
$lang['wiki.insert.link']       = 'Insert a link into the article';
$lang['wiki.link.title']        = 'Article title';
$lang['wiki.no.js.insert.link'] = '
	If you want to insert a link to an article you can use the link tag :
	[link=a]b[/link] where a is the title of the article in which you want to create a link (enough special characters) and b represents the name of the link.
';
$lang['wiki.paragraph']              = 'Insert a paragraph of level %d';
$lang['wiki.help.tags']              = 'Know more about wiki specific tags';
$lang['wiki.warning.paragraph.name'] = 'Please enter a paragraph name';
$lang['wiki.paragraph.name']         = 'Paragraph title';

// Properties
	// Comments
$lang['wiki.comments.management'] = 'Discussion about the article %s';
$lang['wiki.comments']            = 'Discussion';
$lang['wiki.comments.seo']        = 'All discussions for article %s';
	//Delete
$lang['wiki.confirm.delete.archive']  = 'Are you sure you want to delete this version of the article?';
$lang['wiki.remove.category']         = 'Delete a category';
$lang['wiki.remove.category.choice']  = 'Type of deletion ';
$lang['wiki.remove.category.clue']    = 'You want to delete this category. You can delete all its content, or move its content somewhere else.The article will be delete anyway.';
$lang['wiki.remove.all.contents']     = 'Delete all its content (irreversible actions)';
$lang['wiki.move.all.contents']       = 'Move all the content in this folder:';
$lang['wiki.future.category']         = 'Category in which you want to move these element:';
$lang['wiki.confirm.remove.category'] = 'Are you sure you want to delete this category? (definitive)';
$lang['wiki.no.valid.category']       = 'You have not selected a valid category!';
	// Move
$lang['wiki.moving.management']          = 'Moving of an article';
$lang['wiki.change.category']            = 'Change category';
$lang['wiki.category.contains.category'] = 'You have attempted to move this category in its sub-category or in itself, that\'s impossible!';
 	// Redirection
$lang['wiki.redirections.management']  = 'Redirections management';
$lang['wiki.redirection.management']   = 'Redirection to the article';
$lang['wiki.redirecting.from']         = 'Redirect from %s';
$lang['wiki.remove.redirection']       = 'Delete the redirection';
$lang['wiki.redirections']             = 'Redirections';
$lang['wiki.edit.redirection']         = 'Edit an redirection';
$lang['wiki.redirection.name']         = 'Title of the redirection';
$lang['wiki.redirection.delete']       = 'Delete the redirection';
$lang['wiki.alert.delete.redirection'] = 'Are you sure you want to delete this redirection?';
$lang['wiki.no.redirection']           = 'They have no redirection to this page';

$lang['wiki.create.redirection.management'] = 'Create a redirection to the article';
$lang['wiki.create.redirection']            = 'Create a redirection to this page';
	// Rename
$lang['wiki.renaming.management']  = 'Rename an article';
$lang['wiki.renaming.new.title']   = 'New title of the article';
$lang['wiki.renaming.clue']        = 'You will rename this article. Be careful, all links to this article will be broken. But you can ask to put a redirection for this article, so the links will not be broken.';
$lang['wiki.renaming.redirection'] = 'Create an automatic redirection from the old article to the new one';
$lang['wiki.title.already.exists'] = 'The title you want to choose already exists. Please choose another one';
	// Restrictions
$lang['wiki.authorizations.management']   = 'Permissions management';
$lang['wiki.default.authorizations.clue'] = 'Don\'t take into account any particular restriction to this article; permissions of this article will be global permissions.';
$lang['wiki.default.authorizations']      = 'Default permissions';
	// Status
$lang['wiki.status.management'] = 'Articles status management';
$lang['wiki.defined.status']    = 'Defined status';
$lang['wiki.undefined.status']  = 'Personalized status';
$lang['wiki.no.status']         = 'No status';
$lang['wiki.current.status']    = 'Current status';
$lang['wiki.status.list'] = array(
	array('Quality article', '<span class="message-helper bgc notice">This article is very good.</span>'),
	array('Unachieved article', '<span class="message-helper bgc question">This article lacks sources. <br />Your knowlegde is welcome to complete it.</span>'),
	array('Article in transformation', '<span class="message-helper bgc notice">This article is not complete, you can use your knowledge to complete it.</span>'),
	array('Article to remake', '<span class="message-helper bgc warning">This article must be writen again. Its content is not reliable.</span>'),
	array('Article discussion', '<span class="message-helper bgc error">This article was a subject of discussion and its content seems to be incorrect. You can eventually read discussions and maybe use your knowledge to complete it.</span>')
);

// RSS
$lang['wiki.rss.category']   = 'Last articles of the category: %s';
$lang['wiki.rss.last.items'] = '%s : last articles';

// Tools menu
$lang['wiki.update.index'] = 'Edit wiki index';
$lang['wiki.move']         = 'Move';
$lang['wiki.rename']       = 'Rename';

// Tracked items
$lang['wiki.tracked.items']             = 'Favorites';
$lang['wiki.tracked.items.seo']         = 'List of favorite wiki articles.';
$lang['wiki.untrack']                   = 'Don\'t track anymore';
$lang['wiki.track']                     = 'Track this topic';
$lang['wiki.already.favorite']          = 'The topic you want to put in your favorites is already in it';
$lang['wiki.article.is.not.a.favorite'] = 'The topic you want to delete of your favorites is not in your favorites';
$lang['wiki.no.tracked.items']          = 'No topic in favorites';
$lang['wiki.confirm.untrack']           = 'Are you sure you want to delete this article from your favorites?';

// Tree links
$lang['wiki.item.add']     = 'Add an article';
$lang['wiki.category.add'] = 'Add a categorie';

?>
