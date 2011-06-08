<?php
/*##################################################
 *                                errors.php
 *                            -------------------
 *   begin                : June 27, 2006
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : mickaelhemri@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
#                        French                     #
 ####################################################

$lang = array();

//Erreurs
$lang['error'] = 'Erreur';
$lang['unknow_error'] = 'Erreur inconnue';
$lang['e_auth'] = 'Vous n\'avez pas le niveau requis !';
$lang['e_unexist_module'] = 'Le module associé n\'existe pas !';
$lang['e_uninstalled_module'] = 'Ce module n\'est pas installé !';
$lang['e_unactivated_module'] = 'Ce module n\'est pas activé !';
$lang['e_incomplete'] = 'Tous les champs obligatoires doivent être remplis !';
$lang['e_auth_post'] = 'Vous devez être inscrit pour poster !';
$lang['e_readonly'] = 'Vous ne pouvez exécuter cette action, car vous avez été placé en lecture seule !';
$lang['e_unexist_cat'] = 'La catégorie que vous demandez n\'existe pas !';
$lang['e_unexist_file'] = 'Le fichier que vous avez demandé n\'existe pas !';
$lang['e_unexist_page'] = 'La page que vous demandez n\'existe pas !';
$lang['e_mail_format'] = 'Mail invalide !';
$lang['e_unexist_member'] = 'Ce pseudo n\'existe pas !';
$lang['e_unauthorized'] = 'Vous n\'êtes pas autorisé à poster !';
$lang['e_flood'] = 'Vous ne pouvez pas encore poster, réessayez dans quelques instants';
$lang['e_l_flood'] = 'Nombre maximum de lien(s) internet autorisé(s) dans votre message : %d';
$lang['e_link_pseudo'] = 'Vous ne pouvez pas mettre de lien dans votre pseudo';
$lang['e_php_version_conflict'] = 'Version PHP inadaptée';

//Cache
$lang['e_cache_modules'] = 'Cache -> La génération du fichier de cache des modules a échoué !';

//Upload
$lang['e_upload_max_dimension'] = 'Dimensions maximales du fichier dépassées';
$lang['e_upload_max_weight'] = 'Poids maximum du fichier dépassé';
$lang['e_upload_invalid_format'] = 'Format du fichier invalide';
$lang['e_upload_php_code'] = 'Contenu du fichier invalide, le code php est interdit';
$lang['e_upload_error'] = 'Erreur lors de l\'upload du fichier';
$lang['e_unlink_disabled'] = 'Fonction de suppression des fichiers désactivée sur votre serveur';
$lang['e_upload_failed_unwritable'] = 'Upload impossible, interdiction d\'écriture dans ce dossier';
$lang['e_upload_already_exist'] = 'Le fichier existe déjà, écrasement non autorisé';
$lang['e_max_data_reach'] = 'Taille maximale atteinte, supprimez d\'anciens fichiers';

//Membres
$lang['e_pass_mini'] = 'Longueur minimale du nouveau password : 6 caractères';
$lang['e_pass_same'] = 'Les mots de passe doivent être identiques';
$lang['e_pseudo_auth'] = 'Le pseudo entré est déjà utilisé !';
$lang['e_mail_auth'] = 'Le mail entré est déjà utilisé !';
$lang['e_mail_invalid'] = 'Le mail entré est invalide !';
$lang['e_unexist_member'] = 'Aucun membre trouvé avec ce pseudo !';
$lang['e_member_ban'] = 'Vous avez été banni! Vous pourrez vous reconnecter dans';
$lang['e_member_ban_w'] = 'Vous avez été banni pour un comportement abusif! Contactez l\'administrateur s\'il s\'agit d\'une erreur.';
$lang['e_unactiv_member'] = 'Votre compte n\'a pas encore été activé !';
$lang['e_test_connect'] = 'Il vous reste %d essai(s) restant(s) après cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10 minutes pour 5) !';
$lang['e_nomore_test_connect'] = 'Vous avez épuisé tous vos essais de connexion, votre compte est verrouillé pendant 5 minutes';

//Groupes
$lang['e_already_group'] = 'Le membre appartient déjà au groupe';

//Oublié
$lang['e_mail_forget'] = 'Le mail entré ne correspond pas à celui de l\'utilisateur !';
$lang['e_forget_mail_send'] = 'Un mail vient de vous être envoyé, avec une clé d\'activation pour changer votre mot de passe';
$lang['e_forget_confirm_change'] = 'Mot de passe changé avec succès !<br />Vous pouvez désormais vous connecter avec le nouveau mot de passe que vous avez choisi.';
$lang['e_forget_echec_change'] = 'Echec le mot de passe ne peut être changé';

//Register
$lang['e_incorrect_verif_code'] = 'Le code de vérification entré est incorrect !';

//Mps
$lang['e_pm_full'] = 'Votre boite de messages privés est pleine, vous avez <strong>%d</strong> conversation(s) en attente, pour pouvoir la/les lire supprimez d\'anciennes conversations.';
$lang['e_pm_full_post'] = 'Votre boite de messages privés est pleine, supprimez d\'anciennes conversations pour pouvoir en envoyer de nouvelles.';
$lang['e_unexist_user'] = 'L\'utilisateur sélectionné n\'existe pas !';
$lang['e_pm_del'] = 'Le destinataire a supprimé la conversation, vous ne pouvez plus poster';
$lang['e_pm_noedit'] = 'Le destinataire a déjà lu votre message, vous ne pouvez plus l\'éditer';
$lang['e_pm_nodel'] = 'Le destinataire a déjà lu votre message, vous ne pouvez plus le supprimer';

//Gestionnaire d'erreur php
$lang['e_fatal'] = 'Fatale';
$lang['e_notice'] = 'Suggestion';
$lang['e_warning'] = 'Avertissement';
$lang['e_unknow'] = 'Inconnue';
$lang['infile'] = 'dans le fichier';
$lang['atline'] = 'à la ligne';

// Too Many Connections
$lang['too_many_connections'] = 'Trop de connexions';
$lang['too_many_connections_explain'] = 'Le nombre maximum de connexions simultanées à la base de données à été atteint.<br />Veuillez réessayer dans quelques secondes.';

// DEPRECATED
global $LANG;
$LANG = array_merge($LANG, $lang);
?>
