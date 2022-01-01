<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 20
 * @since       PHPBoost 4.0 - 2013 12 05
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['category.category']       = 'Catégorie';
$lang['category.categories']     = 'Catégories';
$lang['category.a.category']     = 'Une catégorie';
$lang['category.all.categories'] = 'Toutes les catégories';
$lang['category.see.category']   = 'Voir la catégorie';

$lang['category.sub.category']   = 'Sous-catégorie';
$lang['category.sub.categories'] = 'Sous-catégories';

// Management
$lang['category.categories.management'] = 'Gestion des catégories';
$lang['category.categories.manage']     = 'Gérer les catégories';
$lang['category.location']              = 'À placer dans la catégorie';
$lang['category.add']                   = 'Ajouter une catégorie';
$lang['category.edit']                  = 'Modifier une catégorie';
$lang['category.delete']                = 'Supprimer une catégorie';

// Form
$lang['category.form.authorizations.clue'] = 'Les autorisations générales du module s\'appliquent par défaut. Vous pouvez appliquer des permissions particulières.';

// Delete category
$lang['category.content.management'] = 'Gestion du contenu de la catégorie';
$lang['category.delete.description'] = 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous : soit déplacer l\'ensemble de son contenu (éléments et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de la catégorie. <strong>Attention, cette action est irréversible !</strong>';
$lang['category.delete.all.content'] = 'Supprimer tout le contenu';
$lang['category.move.to']            = 'Déplacer le contenu vers : ';

// Messages
$lang['category.no.element']          = 'Aucune catégorie';
$lang['category.success.add']         = 'La catégorie <b>:name</b> a été ajoutée';
$lang['category.success.edit']        = 'La catégorie <b>:name</b> a été modifiée';
$lang['category.success.delete']      = 'La catégorie <b>:name</b> a été supprimée';
$lang['category.delete.confirmation'] = 'Voulez-vous vraiment supprimer la catégorie :name ?';

// Default root description
$lang['category.default.created.elements.number']   = ':elements_number ont été créés pour vous montrer comment fonctionne ce module.';
$lang['category.default.root.category.description'] = '
	Bienvenue dans le module :module_name du site.
	<br />
	:created_elements_number Voici quelques conseils pour bien débuter sur ce module.
	<br />
	<ul class="formatter-ul">
		<li class="formatter-li"> Pour configurer ou personnaliser l\'accueil de votre module, rendez-vous dans l\'<a class="offload" href=":configuration_link">administration du module</a></li>
		<li class="formatter-li"> Pour créer des catégories, <a class="offload" href=":add_category_link">cliquez ici</a> </li>
		<li class="formatter-li"> Pour ajouter des :items, <a class="offload" href=":add_item_link">cliquez ici</a></li>
	</ul>
	Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a class="offload" href=":documentation_link">PHPBoost</a>.
';
?>
