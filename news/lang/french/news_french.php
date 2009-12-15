<?php
/*##################################################
 *                             news_french.php
 *                            -------------------
 *   begin                :  June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis, Roguelon Geoffrey
 *   email                : crowkait@phpboost.com, liaght@gmail.com
 *
 *
 ###################################################
 *
 *   This program is free software, you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program, if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


####################################################
#                     French                       #
####################################################

global $NEWS_LANG;

$LANG['e_unexist_news'] = 'La news que vous demandez n\'existe pas !';
$LANG['e_unexist_cat_news'] = 'La catégorie que vous demandez n\'existe pas !';

$NEWS_LANG = array(
	'activ_com_n' => 'Activer les commentaires des news',
	'activ_edito' => 'Activer l\'édito',
	'activ_icon_n' => 'Afficher les icônes de catégories des news',
	'activ_news_block' => 'Activer les news en bloc',
	'activ_pagination' => 'Activer la pagination',
	'add_category' => 'Ajouter une catégorie',
	'add_news' => 'Ajouter une news',
	'alert_delete_news' => 'Supprimer cette News ?',
	'archive' => 'Archives',
	'auth_contribute' => 'Permissions de contribution',
	'auth_moderate' => 'Permissions de modération des contributions',
	'auth_read' => 'Permissions de lecture',
	'auth_write' => 'Permissions d\'écriture',

	'cat_news' => 'Catégorie de la news',
	'category_desc' => 'Description de la catégorie',
	'category_image' => 'Image de la catégorie',
	'category_location' => 'Emplacement de la catégorie',
	'category_name' => 'Nom de la catégorie',
	'category_news' => 'Gestion des catégories',
	'configuration_news' => 'Configuration des news',
	'confirm_del_news' => 'Supprimer cette news ?',
	'contribution_confirmation' => 'Confirmation de contribution',
	'contribution_confirmation_explain' => '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>',
	'contribution_counterpart' => 'Complément de contribution',
	'contribution_counterpart_explain' => 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer cette news au site). Ce champ est facultatif.',
	'contribution_entitled' => '[News] %s',
	'contribution_success' => 'Votre contribution a bien été enregistrée.',

	'delete_category_and_its_content' => 'Supprimer la catégorie et tout son contenu',
	'desc_extend_news' => 'News étendue',
	'desc_news' => 'News',
	'display_archive' => 'Afficher les archives',
	'display_news_author' => 'Afficher l\'auteur de la news',
	'display_news_date' => 'Afficher la date de la news',

	'edit_news' => 'Éditer la news',
	'edito_where' => 'Message visible de tous en haut de l\'accueil',
	'explain_removing_category' => 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (fichiers et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de son catégorie. <strong>Attention, cette action est irréversible !</strong>',
	'extend_contents' => 'Lire la suite...',

	'global_auth' => 'Permissions globales',
	'global_auth_explain' => 'Vous définissez ici les permissions globales du module. Vous pourrez changer ces permissions localement sur chaque catégorie',

	'img_desc' => 'Description image',
	'img_link' => 'Adresse de la photo',
	'img_management' => 'Interface image',
	'infinite_loop' => 'Vous voulez déplacer la catégorie dans une de ses catégories filles ou dans elle-même, ce qui n\'a pas de sens. Merci de choisir une autre catégorie',

	'last_news' => 'Dernières news',

	'move_category_content' => 'Déplacer son contenu dans :',

	'news' => 'News',
	'news_date' => 'Date de la news',
	'nbr_arch_p' => 'Nombre d\'archives par pages',
	'nbr_news_column' => 'Nombre de colonnes pour afficher les news',
	'nbr_news_p' => 'Nombre de news par pages',
	'new_cat_does_not_exist' => 'La catégorie cible n\'existe pas',
	'news_management' => 'Gestion des news',
	'news_suggested' => 'News suggérées :',
	'no_news_available' => 'Aucune news disponible pour le moment',
	'notice_contribution' => 'Vous n\'êtes pas autorisé à créer une news, cependant vous pouvez proposer une news. Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.',

	'on' => 'Le : %s',

	'preview_image' => 'Aperçu image',
	'preview_image_explain' => 'Par défaut à droite',

	'release_date' => 'Date de parution',
	'removing_category' => 'Suppression d\'une catégorie',
	'require_cat' => 'Veuillez choisir une catégorie !',
	'required_fields_empty' => 'Des champs requis n\'ont pas été renseignés, merci de renouveler l\'opération correctement',

	'special_auth' => 'Permissions spéciales',
	'special_auth_explain' => 'Par défaut la catégorie aura la configuration générale du module. Vous pouvez lui appliquer des permissions particulières.',
	'successful_operation' => 'L\'opération que vous avez demandée a été effectuée avec succès',

	'title_news' => 'Titre de la news',

	'unexisting_category' => 'La catégorie que vous avez sélectionnée n\'existe pas',

	'until_1' => '(Jusqu\'au %s)',
	'until_2' => '(%s jusqu\'au %s)',

	'waiting_news' => 'News en attente',

	'xml_news_desc' => 'Actualités - ',
);

?>