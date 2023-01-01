<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 01 03
 * @since       PHPBoost 1.5 - 2006 06 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

// Errors
$lang['e_incomplete'] = 'Tous les champs obligatoires doivent être remplis !';
$lang['e_readonly']   = 'Vous ne pouvez exécuter cette action, car vous avez été placé en lecture seule !';
$lang['e_flood']      = 'Vous ne pouvez pas encore poster, réessayez dans quelques instants';
$lang['e_l_flood']    = 'Nombre maximum de lien(s) internet autorisé(s) dans votre message : %d';

// Upload
$lang['e_upload_max_dimension']     = 'Dimensions maximales du fichier dépassées';
$lang['e_upload_max_weight']        = 'Poids maximum du fichier dépassé';
$lang['e_upload_invalid_format']    = 'Format du fichier invalide';
$lang['e_upload_php_code']          = 'Contenu du fichier invalide, le code php est interdit';
$lang['e_upload_error']             = 'Erreur lors de l\'upload du fichier';
$lang['e_unlink_disabled']          = 'Fonction de suppression des fichiers désactivée sur votre serveur';
$lang['e_upload_failed_unwritable'] = 'Upload impossible, interdiction d\'écriture dans ce dossier';
$lang['e_upload_already_exist']     = 'Le fichier existe déjà, écrasement non autorisé';
$lang['e_upload_no_selected_file']  = 'Aucun fichier n\'a été sélectionné';
$lang['e_max_data_reach']           = 'Taille maximale atteinte, supprimez d\'anciens fichiers';

// Members
$lang['e_display_name_auth'] = 'Le nom d\'affichage entré est déjà utilisé !';
$lang['e_pseudo_auth']       = 'Le pseudo entré est déjà utilisé !';
$lang['e_mail_auth']         = 'L\'adresse email entrée est déjà utilisée !';
$lang['e_member_ban']        = 'Vous avez été banni! Vous pourrez vous reconnecter dans';
$lang['e_member_ban_w']      = 'Vous avez été banni pour un comportement abusif! Contactez l\'administrateur s\'il s\'agit d\'une erreur.';
$lang['e_unactiv_member']    = 'Votre compte n\'a pas encore été activé !';

// Groups
$lang['e_already_group'] = 'Le membre appartient déjà au groupe';

// PM
$lang['e_pm_full']      = 'Votre boite de messages privés est pleine, vous avez <strong>%d</strong> conversation(s) en attente, supprimez d\'anciennes conversations pour pouvoir la/les lire.';
$lang['e_pm_full_post'] = 'Votre boite de messages privés est pleine, supprimez d\'anciennes conversations pour pouvoir en envoyer de nouvelles.';
$lang['e_unexist_user'] = 'L\'utilisateur sélectionné n\'existe pas !';
$lang['e_pm_del']       = 'Le destinataire a supprimé la conversation, vous ne pouvez plus poster';
$lang['e_pm_noedit']    = 'Le destinataire a déjà lu votre message, vous ne pouvez plus l\'éditer';
$lang['e_pm_nodel']     = 'Le destinataire a déjà lu votre message, vous ne pouvez plus le supprimer';

?>
