<?php
/*##################################################
 *                              articles_french.php
 *                            -------------------
 *   begin                : November 21, 2006
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


####################################################
#                                                          French                                                                        #
####################################################

//Erreurs
$LANG['e_unexist_articles'] = 'L\'article que vous avez demandé n\'existe pas';


global $ARTICLES_LANG;
// contribution
$ARTICLES_LANG = array(
	'articles_management' => 'Gestion des articles',
	'recount' => 'Recompter',
	'explain_articles_count' => 'Recompter le nombre d\'articles par catégories',
	'nbr_articles_max' => 'Nombre maximum d\'articles affichés',
	'alert_delete_article' => 'Supprimer cet article ?',
	'select_page' => 'Sélectionnez une page',
	'summary' => 'Sommaire',
	'articles' => 'Articles',
	'title_articles' => 'Articles',
	'xml_articles_desc' => 'Derniers articles',
	'nbr_articles_info' => '%d article(s) dans la catégorie',
	'none_article' => 'Aucun article dans cette catégorie',
	'sub_categories' => 'Sous catégories',
	'written_by' => 'Ecrit par',
	'page_prompt' => 'Titre de la nouvelle page',
	'articles_add' => 'Ajouter un article',
	'article_icon' => 'Icône de l\'article',
	'cat_icon' => 'Icône de la catégorie',
	'articles_date' => 'Date de l\'article <span class="text_small">(jj/mm/aa)</span> <br />
	<span class="text_small">(Laisser vide pour mettre la date d\'aujourd\'hui)',
	'explain_page' => 'Insérer une nouvelle page',
	'contribution_confirmation' => 'Confirmation de contribution',
	'contribution_confirmation_explain' => '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>',
	'contribution_counterpart' => 'Complément de contribution',
	'contribution_counterpart_explain' => 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer cet article au site). Ce champ est facultatif.',
	'contribution_entitled' => '[Articles] %s',
	'contribution_success' => 'Votre contribution a bien été enregistrée.',
	'global_auth' => 'Permissions globales',
	'global_auth_explain' => 'Vous définissez ici les permissions globales du module. Vous pourrez changer ces permissions localement sur chaque catégorie',
	'auth_contribute' => 'Permissions de contribution',
	'auth_moderate' => 'Permissions de modération des contributions',
	'auth_read' => 'Permissions de lecture',
	'auth_write' => 'Permissions d\'écriture',
	'add_articles' => 'Ajouter un article',
	'release_date' => 'Date de parution',
	'removing_category' => 'Suppression d\'une catégorie',
	'require_cat' => 'Veuillez choisir une catégorie !',
	'articles_date' => 'Date de l\'article',
	'required_fields_empty' => 'Des champs requis n\'ont pas été renseignés, merci de renouveler l\'opération correctement',	
	'category_name' => 'Nom de la catégorie',
	'category_location' => 'Emplacement de la catégorie',
	'category_desc' => 'Description de la catégorie',
	'category_image' => 'Image de la catégorie',
	'special_auth' => 'Permissions spéciales',
	'special_auth_explain' => 'Par défaut la catégorie aura la configuration générale du module. Vous pouvez lui appliquer des permissions particulières.',
	'articles_management' => 'Gestion des articles',
	'add_category' => 'Ajouter une catégorie',
	'configuration_articles' => 'Configuration des articles',
	'category_articles' => 'Gestion des catégories',
	'required_fields_empty' => 'Des champs requis n\'ont pas été renseignés, merci de renouveler l\'opération correctement',
	'unexisting_category' => 'La catégorie que vous avez sélectionnée n\'existe pas',
	'new_cat_does_not_exist' => 'La catégorie cible n\'existe pas',
	'infinite_loop' => 'Vous voulez déplacer la catégorie dans une de ses catégories filles ou dans elle-même, ce qui n\'a pas de sens. Merci de choisir une autre catégorie',
	'successful_operation' => 'L\'opération que vous avez demandée a été effectuée avec succès',
	'explain_removing_category' => 'Vous êtes sur le point de supprimer la catégorie. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (articles et sous catégories) dans une autre catégorie soit supprimer l\'ensemble de son catégorie. <strong>Attention, cette action est irréversible !</strong>',
	'delete_category_and_its_content' => 'Supprimer la catégorie et tout son contenu',
	'move_category_content' => 'Déplacer son contenu dans :',
	'edit_articles' => 'Éditer l\'article',
	'contribution_confirmation' => 'Confirmation de contribution',
	'contribution_confirmation_explain' => '<p>Vous pourrez la suivre dans le <a href="' . url('../member/contribution_panel.php') . '">panneau de contribution de PHPBoost</a> et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>',
	'contribution_counterpart' => 'Complément de contribution',
	'contribution_counterpart_explain' => 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer cet article au site). Ce champ est facultatif.',
	'contribution_entitled' => '[Articles] %s',
	'contribution_success' => 'Votre contribution a bien été enregistrée.',
	'notice_contribution' => 'Vous n\'êtes pas autorisé à créer un article, cependant vous pouvez proposer un article. Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.',
	'use_tab'=>"Utilisation des onglets pour la pagination des articles",
	'or_direct_path' => 'Ou chemin direct',
	'waiting_articles' => 'Articles en attentes',
	'no_articles_available' => 'Aucun articles disponible pour le moment',
	
	'article_description'=>"Description de l'article",
	'no_articles_waiting'=>'Aucun articles en attente disponible pour le moment',
	'publicate_articles'=>'Articles publiés',
	'cat_tpl' => 'Templates de la catégorie',
	'articles_tpl' => 'Templates des articles',
	'tpl_explain' => 'Vous définissez ici des templates personnalisés à utiliser pour les articles et la catégories courante.',
	'tpl'=>'Templates personnalisés',
	'source'=>'Sources',
	'add_source'=>'Ajouter une source',
	'source_link'=>'URL de la source',
	'special_auth_explain_articles' => 'Par défaut l\'article aura la configuration générale de sa catégorie. Vous pouvez lui appliquer des permissions particulières.',
	'special_option_explain' => 'Par défaut l\'article aura la configuration générale de sa catégorie. Vous pouvez lui appliquer des options particulères.',
	'special_option' => 'Options spéciales',
	'articles_mini_config'=> 'Configuration du mini module',
	'nbr_articles_mini'=> 'Nombre d\'articles à afficher',		
	'mini_type'=> 'Type de classement',	
	'articles_best_note' => 'Articles les mieux notés',
	'articles_more_com' => 'Articles ayant le plus de commentaire',
	'articles_by_date' => 'Derniers articles',
	'articles_most_popular' => 'Articles les plus populaires',
	'author' => 'Auteur',
	'more_article' => 'Plus d\'article',
	'hide'=>'Cacher',
	'enable'=>'Activer',
	'desable'=>'Désactiver',
	'mail_articles'=>'Envoyer le lien de l\'article par mail',
	'mail_recipient'=>'E-mail du destinataire',
	'sender'=>'Expéditeur',
	'user_mail'=>'Votre adresse e-mail',
	'subject'=>'Sujet',
	'admin_invalid_email_error' => 'Mail invalide',
	'require_sender'=> 'Veuillez remplir le champs expéditeur',
	'require_subject'=> ' Veuillez remplir le champs sujet',
	'admin_email_error' => 'L\'adresse de courier électronique que vous avez entrée n\'a pas une forme correcte',
	'link_mail'=>'Envoyer ce lien à un ami',
	'admin_link_mail'=>'Autoriser l\'envoie du liens d\'un article par mail',
	'order_by'=>'Trier par ',
	'extend_field'=>'Champs suplémentaires',
	'extend_field_explain' => 'Vous pouvez déclarer ici des champs suplémentaires pour les articles de cette catégorie',
	'extend_field_name'=>'Nom du champ',
	'extend_field_type'=>'Type de champ',
	'extend_field_add'=>'Ajouter un champ',
	'successful_send_mail'=>'Votre mail a été envoyé avec succès',
	'error_send_mail'=>'Une erreur est survenue veuillez réessayer plutard',
	'text_link_mail' =>'Ceci est un e-mail de (%s) envoyé par %s (%s). Ce lien pourrait vous intéresser: %s %s',
);
?>