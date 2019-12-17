<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 20
 * @since       PHPBoost 1.6 - 2007 08 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

//Généralités
$LANG['pages'] = 'Pages';

$LANG['page_hits'] = 'Cette page a été consultée %d fois';

//Administration
$LANG['pages_count_hits_activated'] = 'Compter le nombre de fois que la page est consultée';
$LANG['pages_count_hits_explain'] = 'Peut-être choisi au cas par cas pour chaque page';
$LANG['pages_auth_read'] = 'Lecture des pages';
$LANG['pages_auth_edit'] = 'Modification de page';
$LANG['pages_auth_read_com'] = 'Utilisation des commentaires';
$LANG['pages_auth'] = 'Autorisations';
$LANG['select_all'] = 'Tout sélectionner';
$LANG['select_none'] = 'Tout désélectionner';
$LANG['ranks'] = 'Rangs';
$LANG['groups'] = 'Groupes';
$LANG['pages_config'] = 'Configuration des pages';
$LANG['pages_management'] = 'Gestion des pages';
$LANG['pages_manage'] = 'Gérer les pages';

//Création / édition d'une page
$LANG['pages_edition'] = 'Modification d\'une page';
$LANG['pages_creation'] = 'Création d\'une page';
$LANG['pages_edit_page'] = 'Modification de la page <em>%s</em>';
$LANG['page_title'] = 'Titre de la page';
$LANG['page_contents'] = 'Contenu de la page';
$LANG['pages_edit'] = 'Modifier cette page';
$LANG['pages_delete'] = 'Supprimer cette page';
$LANG['pages_create'] = 'Créer une page';
$LANG['pages_comments_activated'] = 'Activer les commentaires';
$LANG['pages_display_print_link'] = 'Afficher le lien d\'impression';
$LANG['pages_own_auth'] = 'Mettre des autorisations particulières à la page';
$LANG['pages_is_cat'] = 'Cette page est une catégorie';
$LANG['pages_parent_cat'] = 'Catégorie parente';
$LANG['pages_page_path'] = 'Emplacement';
$LANG['pages_properties'] = 'Propriétés';
$LANG['pages_no_selected_cat'] = 'Aucune catégorie sélectionnée';
$LANG['explain_select_multiple'] = 'Maintenez ctrl puis cliquez dans la liste pour faire plusieurs choix';
$LANG['pages_previewing'] = 'Prévisualisation :';
$LANG['pages_contents_part'] = 'Contenu de la page';
$LANG['pages_delete_success'] = 'La page a été supprimée avec succès';
$LANG['pages_delete_failure'] = 'La page n\'a pas pu être supprimée';
$LANG['pages_confirm_delete'] = 'Etes-vous sur de vouloir supprimer cette page ?';

//Divers
$LANG['pages_links_list'] = 'Outils';
$LANG['pages_com'] = 'Commentaires';
$LANG['pages_explorer'] = 'Explorateur';
$LANG['pages_explorer_seo'] = 'Explorateur permettant de naviguer dans l\'arborescence des différentes pages du site.';
$LANG['pages_root'] = 'Racine';
$LANG['pages_cats_tree'] = 'Arborescence des pages';
$LANG['pages_display_coms'] = 'Commentaires (%d)';
$LANG['pages_post_com'] = 'Poster un commentaire';
$LANG['pages_page_com'] = 'Commentaires de la page %s';
$LANG['pages_page_com_seo'] = 'Tous les commentaires de la page %s';

//Accueil
$LANG['pages_explain'] = 'Vous êtes sur le panneau de contrôle des pages. Vous pouvez ici gérer l\'ensemble de vos pages.<br /><br />
<p>Vous utilisez l\'éditeur que vous avez choisi dans votre profil pour mettre en forme les pages. Pour insérer du code HTML, utilisez la balise BBCode suivante :<br /><div class="code">[html]code html[/html]</div></p>
<p>La balise est la même que vous utilisiez l\'éditeur BBCode ou TinyMCE.</p><br />
<p>Pour faire des liens entre les différentes pages, il suffit d\'utiliser la balise BBCode suivante :<br /><div class="code">[link=titre-de-la-page]Lien vers la page[/link]</div></p>
<p>Cette balise existe uniquement sur les modules pages et wiki.</p>
<div class="message-helper bgc warning">Pour des raisons de sécurité il est interdit d\'insérer du code PHP dans les pages.</div>';
$LANG['pages_redirections'] = 'Gestion des redirections';
$LANG['pages_num_pages'] = '%d page(s) existante(s)';
$LANG['pages_num_coms'] = '%d commentaire(s) sur l\'ensemble des pages soit %1.1f commentaire par page';
$LANG['pages_stats'] = 'Statistiques';
$LANG['pages_tools'] = 'Outils';

//Redirections et renommer
$LANG['pages_rename'] = 'Renommer';
$LANG['pages_redirection_management'] = 'Gestion des redirections';
$LANG['pages_redirection_manage'] = 'Gérer les redirections';
$LANG['pages_rename_page'] = 'Renommer la page <em>%s</em>';
$LANG['pages_new_title'] = 'Nouveau titre de la page';
$LANG['pages_create_redirection'] = 'Créer une redirection depuis l\'ancien titre vers le nouveau';
$LANG['pages_explain_rename'] = 'Vous êtes sur le point de renommer la page. Vous devez savoir que tous les liens qui mènent vers cet article seront rompus. C\'est pourquoi vous avez la possibilité de créer une redirection depuis l\'ancien nom vers le nouveau afin de pouvoir continuer à utiliser ces liens';
$LANG['pages_confirm_delete_redirection'] = 'Etes-vous sur de vouloir supprimer cette redirection ?';
$LANG['pages_delete_redirection'] = 'Supprimer cette redirection';
$LANG['pages_redirected_from'] = 'Redirigé depuis <em>%s</em>';
$LANG['pages_redirection_title'] = 'Titre de la redirection';
$LANG['pages_redirection_target'] = 'Cible de la redirection';
$LANG['pages_redirection_actions'] = 'Actions';
$LANG['pages_manage_redirection'] = 'Voir les redirections menant à la même page';
$LANG['pages_no_redirection'] = 'Aucune redirection existante';
$LANG['pages_create_redirection'] = 'Créer une redirection vers cette page';
$LANG['pages_creation_redirection'] = 'Création d\'une redirection';
$LANG['pages_creation_redirection_title'] = 'Création d\'une redirection vers la page : %s';
$LANG['pages_redirection_title'] = 'Nom de la redirection';
$LANG['pages_remove_this_cat'] = 'Suppression de la catégorie : <em>%s</em>';
$LANG['pages_remove_all_contents'] = 'Supprimer tout son contenu (action irréversible)';
$LANG['pages_move_all_contents'] = 'Déplacer tout son contenu dans le dossier suivant :';
$LANG['pages_future_cat'] = 'Catégorie dans laquelle vous souhaitez déplacer ces éléments';
$LANG['pages_change_cat'] = 'Changer de catégorie';
$LANG['pages_delete_cat'] = 'Suppression d\'une catégorie';
$LANG['pages_confirm_remove_cat'] = 'Etes-vous sur de vouloir supprimer cette catégorie ?';

//Erreurs
$LANG['page_alert_title'] = 'Vous devez entrer un titre';
$LANG['page_alert_contents'] = 'Vous devez entrer le contenu de votre page';
$LANG['pages_already_exists'] = 'Le titre que vous avez choisi pour la page existe déjà. Vous devez en choisir un autre, chaque page étant repérée uniquement par son titre, celui-ci doit être unique.';
$LANG['pages_cat_contains_cat'] = 'La catégorie que vous avez sélectionnée pour placer cette catégorie est contenue par cette même catégorie, ce qui n\'est pas possible. Merci de choisir une autre catégorie';
$LANG['pages_notice_previewing'] = 'Vous êtes en train de prévisualiser ce que vous avez entré. Aucune modification n\'a été apportée dans la base de données, vous devez valider votre page afin que les modifications soient prises en compte';

$LANG['pages_rss_desc'] = 'Pages actualités';

?>
