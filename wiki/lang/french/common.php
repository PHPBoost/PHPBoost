<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 09 19
 * @since       PHPBoost 1.6 - 2006 12 02
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['wiki.module.title'] = 'Wiki';

$lang['wiki.page.views.number'] = 'Cette page a été vue %d fois';
$lang['wiki.contribution']      = 'Contribuer';
$lang['wiki.tools']             = 'Outils';
$lang['wiki.author']            = 'Auteur';
$lang['wiki.summary.menu']      = 'Table des matières';
$lang['wiki.random.page']       = 'Page aléatoire';
$lang['wiki.restriction.level'] = 'Niveau de restriction';
$lang['wiki.define.status']     = 'Définir un statut';
$lang['wiki.last.items.list']   = 'Derniers articles mis à jour :';
$lang['wiki.categories.list']   = 'Liste des catégories principales :';
$lang['wiki.items.in.category'] = 'Articles présents dans cette catégorie';
$lang['wiki.sub.categories']    = 'Catégories contenues par cette catégorie :';
$lang['wiki.no.sub.item']       = 'Aucun sous article existant';
$lang['wiki.no.item']       	= 'Aucun article';

// Archives
$lang['wiki.history']         = 'Historique';
$lang['wiki.full.history']    = 'Historique du wiki';
$lang['wiki.item.history']    = 'Historique l\'article %s';
$lang['wiki.history.seo']     = 'Tout l\'historique de l\'article %s';
$lang['wiki.versions'] 	      = 'Versions';
$lang['wiki.version.date']    = 'Date de version';
$lang['wiki.current.version'] = 'Version courante';
$lang['wiki.item.unexists']   = 'L\'article que vous demandez n\'existe pas, vous pouvez le créer ici.';
$lang['wiki.consult']         = 'Consulter';
$lang['wiki.restore.version'] = 'Restaurer cette version';
$lang['wiki.actions']         = 'Actions possibles';
$lang['wiki.no.action']       = 'Aucune action possible';

// Categories
$lang['wiki.current.category']         = 'Catégorie courante';
$lang['wiki.select.category']          = 'Sélectionner une catégorie';
$lang['wiki.selected.category']        = 'Catégorie sélectionnée';
$lang['wiki.no.category']              = 'Aucune catégorie';
$lang['wiki.no.selected.category']     = 'Aucune catégorie sélectionnée';
$lang['wiki.no.existing.category']     = 'Aucune catégorie existante';
$lang['wiki.no.existing.sub.category'] = 'Aucune sous-catégorie existante';

// Configuration
$lang['wiki.config.module.title'] 		 = 'Configuration du module Wiki';
$lang['wiki.config.name']                = 'Nom du wiki';
$lang['wiki.config.sticky.summary']      = 'Afficher le sommaire des articles en position fixe.';
$lang['wiki.config.enable.views.number'] = 'Afficher le nombre vues dans les articles';
$lang['wiki.config.index']               = 'Accueil du wiki';
$lang['wiki.config.display.categories']  = 'Afficher la liste des catégories principales sur l\'accueil';
$lang['wiki.config.hide']                = 'Ne pas afficher';
$lang['wiki.config.show']                = 'Afficher';
$lang['wiki.config.last.items']          = 'Nombre des derniers articles à afficher sur l\'accueil';
$lang['wiki.config.last.items.clue']     = '0 pour désactiver';
$lang['wiki.config.description']         = 'Texte de l\'accueil';
	// Authorizations
$lang['wiki.authorizations']                  = 'Gestion des autorisations dans le module Wiki';
$lang['wiki.authorizations.clue']             = 'Vous pouvez paramétrer ici tout ce qui concerne les autorisations. Vous pouvez attribuer des autorisations à un niveau mais aussi des autorisations spéciales à un groupe.';
$lang['wiki.authorizations.read']             = 'Lire les articles';
$lang['wiki.authorizations.write']            = 'Créer un article';
$lang['wiki.authorizations.create.category']  = 'Créer une catégorie';
$lang['wiki.authorizations.restore.archive']  = 'Restaurer une archive';
$lang['wiki.authorizations.delete.archive']   = 'Supprimer une archive';
$lang['wiki.authorizations.edit']             = 'Editer un article';
$lang['wiki.authorizations.delete']           = 'Supprimer un article';
$lang['wiki.authorizations.rename']           = 'Renommer un article';
$lang['wiki.authorizations.redirect']         = 'Gérer les redirections vers un article';
$lang['wiki.authorizations.move']             = 'Déplacer un article';
$lang['wiki.authorizations.status']           = 'Modifier le statut d\'un article';
$lang['wiki.authorizations.comment']          = 'Commenter un article';
$lang['wiki.authorizations.restriction']      = 'Modifier le niveau de restrictions d\'un article';
$lang['wiki.authorizations.restriction.clue'] = 'Il est conseillé de le laisser aux modérateurs uniquement';
	// Default install
$lang['wiki.name'] = 'Wiki ' . GeneralConfig::load()->get_site_name();
$lang['wiki.index.text'] = '
	Bienvenue sur le module wiki !
	<p>Voici quelques conseils pour bien débuter sur ce module.</p>
	<ul class="formatter-ul">
		<li class="formatter-li">Pour configurer votre module, rendez vous dans l\'<a class="offload" href="' . WikiUrlBuilder::configuration()->relative() . '">administration du module</a></li>
		<li class="formatter-li">Pour créer des catégories, <a class="offload" href="' . WikiUrlBuilder::add_category()->relative() . '">cliquez ici</a></li>
		<li class="formatter-li">Pour créer des articles, rendez vous <a class="offload" href="' . WikiUrlBuilder::add()->relative() . '">ici</a></li>
	</ul><br /><br />
	Pour personnaliser l\'accueil de ce module, <a class="offload" href="' . WikiUrlBuilder::configuration()->relative() . '">cliquez ici</a><br /><br />
	Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a class="offload" href="https://www.phpboost.com/forum/">PHPBoost</a>.
';

//Hooks
$lang['wiki.specific_hook.wiki_change_status'] = 'Changement du statut d\'une page';
$lang['wiki.specific_hook.wiki_delete_archive'] = 'Suppression d\'une archive';
$lang['wiki.specific_hook.wiki_restore_archive'] = 'Restauration d\'une archive';

// Changing reason
$lang['wiki.changing.reason.label'] = 'Nature de la modification (facultatif, 100 caractères max)';
$lang['wiki.changing.reason']       = 'Nature de la modification';
$lang['wiki.item.init']             = 'Initialisation';

// Explorer
$lang['wiki.explorer']        = 'Explorateur du wiki';
$lang['wiki.explorer.short']  = 'Explorateur';
$lang['wiki.explorer.seo']    = 'Explorateur permettant de naviguer dans l\'arborescence des différentes pages du wiki.';
$lang['wiki.root']            = 'Racine du wiki';
$lang['wiki.content']         = 'Contenu';
$lang['wiki.categories.tree'] = 'Arborescence';

// Post | edit
$lang['wiki.create.item']     = 'Créer un article';
$lang['wiki.create.category'] = 'Créer une catégorie';
$lang['wiki.warning.update']  = 'Cet article a été mis à jour, vous consultez ici une archive de cet article!';
$lang['wiki.contribute']      = 'Contribuer au wiki';
$lang['wiki.edit.item']       = 'Edition de l\'article';
$lang['wiki.category.edit']   = 'Edition de la catégorie';
	// js_tools
$lang['wiki.bbcode.wiki.icon']  = 'BBCode wiki';
$lang['wiki.warning.link.name'] = 'Veuillez entrer un nom de lien';
$lang['wiki.insert.link']       = 'Insérer un lien vers un article';
$lang['wiki.link.title']        = 'Titre de l\'article';
$lang['wiki.no.js.insert.link'] = '
	Pour insérer un lien vers un article veuillez utiliser la balise link :
	[link=$a]$b[/link] où $a représente le titre de l\'article (sans caractères spéciaux, tel qu\'il apparaît dans l\'adresse) et $b représente le nom du lien
';
$lang['wiki.paragraph']              = 'Insérer un paragraphe de niveau %d';
$lang['wiki.help.tags']              = 'En savoir plus sur les balises du BBCode spécifiques au wiki';
$lang['wiki.warning.paragraph.name'] = 'Veuillez entrer le titre du paragraphe';
$lang['wiki.paragraph.name']         = 'Titre du paragraphe';

// Properties
	// Comments
$lang['wiki.comments.management'] = 'Discussion à propos de l\'article';
$lang['wiki.comments']            = 'Discussion';
$lang['wiki.comments.seo']        = 'Toutes les discussions sur l\'article %s';
	// Delete
$lang['wiki.confirm.delete.archive']  = 'Etes-vous sûr de vouloir supprimer cette version de l\'article ?';
$lang['wiki.remove.category']         = 'Suppression d\'une catégorie';
$lang['wiki.remove.category.choice']  = 'Type de suppression';
$lang['wiki.remove.category.clue']    = 'Vous souhaitez supprimer cette catégorie. Vous pouvez supprimer tout son contenu ou transférer son contenu ailleurs. La description associée à cette catégorie sera quant à elle obligatoirement supprimé.';
$lang['wiki.remove.all.contents']     = 'Supprimer tout son contenu (action irréversible)';
$lang['wiki.move.all.contents']       = 'Déplacer tout son contenu dans le dossier suivant :';
$lang['wiki.future.category']         = 'Catégorie dans laquelle vous souhaitez déplacer ses éléments';
$lang['wiki.confirm.remove.category'] = 'Etes-vous sûr de vouloir supprimer cette catégorie (définitif) ?';
$lang['wiki.no.valid.category']       = 'Vous n\'avez pas sélectionné de catégorie valide !';
	// Move
$lang['wiki.moving.management']          = 'Déplacement d\'un article';
$lang['wiki.change.category']            = 'Changer de catégorie';
$lang['wiki.category.contains.category'] = 'Vous souhaitez placer cette catégorie dans une de ses catégories filles ou dans elle-même, ce qui est impossible!';
	// Redirection
$lang['wiki.redirections.management']  = 'Gestion des redirections';
$lang['wiki.redirection.management']   = 'Redirection menant à l\'article';
$lang['wiki.redirecting.from']         = 'Redirigé depuis %s';
$lang['wiki.remove.redirection']       = 'Supprimer la redirection';
$lang['wiki.redirections']             = 'Redirections';
$lang['wiki.edit.redirection']         = 'Edition d\'une redirection';
$lang['wiki.redirection.name']         = 'Titre de la redirection';
$lang['wiki.redirection.delete']       = 'Supprimer la redirection';
$lang['wiki.alert.delete.redirection'] = 'Etes-vous sur de vouloir supprimer cette redirection ?';
$lang['wiki.no.redirection']           = 'Il n\'y a aucune redirection vers cette page';

$lang['wiki.create.redirection.management'] = 'Créer une redirection vers l\'article';
$lang['wiki.create.redirection']            = 'Créer une redirection vers cet article';
	// Rename
$lang['wiki.renaming.management']  = 'Renommer un article';
$lang['wiki.renaming.new.title']   = 'Nouveau titre de l\'article';
$lang['wiki.renaming.clue']        = 'Vous êtes sur le point de renommer un article. Attention, vous devez savoir que tous les liens menant à cet article seront rompus. Cependant vous pouvez demander à laisser une redirection vers le nouvel article, ce qui permettra de ne pas briser les liens.';
$lang['wiki.renaming.redirection'] = 'Créer une redirection automatique depuis l\'ancien article vers le nouveau';
$lang['wiki.title.already.exists'] = 'Le titre que vous avez choisi existe déjà. Veuillez en choisir un autre';
	// Restrictions
$lang['wiki.authorizations.management']   = 'Gestion des niveaux d\'autorisation';
$lang['wiki.default.authorizations.clue'] = 'Ne pas considérer de restriction particulière pour cet article; les autorisations seront les autorisations globales du wiki';
$lang['wiki.default.authorizations']      = 'Autorisations par défaut';
	// Status
$lang['wiki.status.management'] = 'Gestion des statuts des articles';
$lang['wiki.defined.status']    = 'Statut prédéfini';
$lang['wiki.undefined.status']  = 'Statut personnalisé';
$lang['wiki.no.status']         = 'Aucun statut';
$lang['wiki.current.status']    = 'Statut courant';
$lang['wiki.status.list'] = array(
	array('Article de qualité', '<span class="message-helper bgc notice">Cet article est de grande qualité il est complet et fiable.</span>'),
	array('Article incomplet', '<span class="message-helper bgc question">Cet article manque de sources.<br />Vos connaissances sont les bienvenues afin de le compléter.</span>'),
	array('Article en cours de travaux', '<span class="message-helper bgc notice">Cet article est en cours de travaux, des modifications sont en cours de réalisation, revenez plus tard le reconsulter. Merci.</span>'),
	array('Article à refaire', '<span class="message-helper bgc warning">Cet article est à refaire, son contenu n\'est pas très fiable.</span>'),
	array('Article remis en cause', '<span class="message-helper bgc error">Cet article a été discuté et son contenu ne paraît pas correct. Vous pouvez éventuellement consulter les discussions à ce propos et peut-être y apporter vos connaissances.</span>')
);

// RSS
$lang['wiki.rss.category']   = 'Derniers articles de la catégorie %s';
$lang['wiki.rss.last.items'] = '%s : derniers articles';

// Tools menu
$lang['wiki.update.index'] = 'Modifier l\'accueil';
$lang['wiki.move']         = 'Déplacer';
$lang['wiki.rename']       = 'Renommer';

// Tracked items
$lang['wiki.tracked.items']             = 'Favoris';
$lang['wiki.tracked.items.seo']         = 'Liste des articles favoris du wiki.';
$lang['wiki.untrack']                   = 'Ne plus suivre';
$lang['wiki.track']                     = 'Suivre cet article';
$lang['wiki.already.favorite']          = 'Le sujet que vous désirez mettre en favoris est déjà en favoris';
$lang['wiki.article.is.not.a.favorite'] = 'L\'article que vous souhaitez supprimer de vos favoris ne figure pas parmi vos favoris';
$lang['wiki.no.tracked.items']          = 'Aucun article en favoris';
$lang['wiki.confirm.untrack']           = 'Etes-vous certain de vouloir supprimer cet article de vos favoris ?';

// Tree links
$lang['wiki.item.add']     = 'Ajouter un article';
$lang['wiki.category.add'] = 'Ajouter une catégorie';

?>
