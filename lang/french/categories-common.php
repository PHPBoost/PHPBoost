<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 08
 * @since       PHPBoost 4.0 - 2013 12 05
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['category'] = 'Catégorie';
$lang['categories'] = 'Catégories';
$lang['a.category'] = 'Une catégorie';
$lang['see.category'] = 'Voir la catégorie';

$lang['sub_category'] = 'Sous-catégorie';
$lang['sub_categories'] = 'Sous-catégories';

//Management
$lang['categories.management'] = 'Gestion des catégories';
$lang['categories.manage'] = 'Gérer les catégories';
$lang['category.add'] = 'Ajouter une catégorie';
$lang['category.edit'] = 'Modifier une catégorie';
$lang['category.delete'] = 'Supprimer une catégorie';

//Form
$lang['category.form.authorizations.description'] = 'Les autorisations générales du module s\'appliquent par défaut. Vous pouvez appliquer des permissions particulières.';

//Delete category
$lang['delete.description'] = 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous : soit déplacer l\'ensemble de son contenu (éléments et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de la catégorie. <strong>Attention, cette action est irréversible !</strong>';
$lang['delete.category_and_content'] = 'Supprimer tout le contenu';
$lang['delete.move_in_other_cat'] = 'Déplacer le contenu dans :';

//Messages
$lang['category.message.success.add'] = 'La catégorie <b>:name</b> a été ajoutée';
$lang['category.message.success.edit'] = 'La catégorie <b>:name</b> a été modifiée';
$lang['category.message.success.delete'] = 'La catégorie <b>:name</b> a été supprimée';
$lang['category.message.delete_confirmation'] = 'Voulez-vous vraiment supprimer la catégorie :name ?';

//Default root description
$lang['default.created.elements.number'] = ':elements_number ont été créés pour vous montrer comment fonctionne ce module.';
$lang['default.root.category.description'] = 'Bienvenue dans le module :module_name du site.
<br />
:created_elements_number Voici quelques conseils pour bien débuter sur ce module.
<br />
<ul class="formatter-ul">
	<li class="formatter-li"> Pour configurer ou personnaliser l\'accueil de votre module, rendez-vous dans l\'<a href=":configuration_link">administration du module</a></li>
	<li class="formatter-li"> Pour créer des catégories, <a href=":add_category_link">cliquez ici</a> </li>
	<li class="formatter-li"> Pour ajouter des :items, <a href=":add_item_link">cliquez ici</a></li>
</ul>
Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href=":documentation_link">PHPBoost</a>.
';
?>
