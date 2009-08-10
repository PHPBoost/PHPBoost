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
#                                                           French                                                                               #
####################################################

//Erreurs
$LANG['error'] = 'Erreur';
$LANG['unknow_error'] = 'Erreur inconnue';
$LANG['e_auth'] = 'Vous n\'avez pas le niveau requis !';
$LANG['e_unexist_module'] = 'Le module associé n\'existe pas !';
$LANG['e_uninstalled_module'] = 'Ce module n\'est pas installé !';
$LANG['e_unactivated_module'] = 'Ce module n\'est pas activé !';
$LANG['e_incomplete'] = 'Tous les champs obligatoires doivent être remplis !';
$LANG['e_auth_post'] = 'Vous devez être inscrit pour poster !';
$LANG['e_readonly'] = 'Vous ne pouvez exécuter cette action, car vous avez été placé en lecture seule !';
$LANG['e_unexist_cat'] = 'La catégorie que vous demandez n\'existe pas !';
$LANG['e_unexist_file'] = 'Le fichier que vous avez demandé n\'existe pas !';
$LANG['e_unexist_page'] = 'La page que vous demandez n\'existe pas !';
$LANG['e_mail_format'] = 'Mail invalide !';
$LANG['e_unexist_member'] = 'Ce pseudo n\'existe pas !';
$LANG['e_unauthorized'] = 'Vous n\'êtes pas autorisé à poster !';
$LANG['e_flood'] = 'Vous ne pouvez pas encore poster, réessayez dans quelques instants';
$LANG['e_l_flood'] = 'Nombre maximum de lien(s) internet autorisé(s) dans votre message : %d';
$LANG['e_link_pseudo'] = 'Vous ne pouvez pas mettre de lien dans votre pseudo';
$LANG['e_php_version_conflict'] = 'Version PHP inadaptée';

//Cache
$LANG['e_cache_modules'] = 'Cache -> La génération du fichier de cache des modules a échoué !';

//Upload
$LANG['e_upload_max_dimension'] = 'Dimensions maximales du fichier dépassées';
$LANG['e_upload_max_weight'] = 'Poids maximum du fichier dépassé';
$LANG['e_upload_invalid_format'] = 'Format du fichier invalide';
$LANG['e_upload_error'] = 'Erreur lors de l\'upload du fichier';
$LANG['e_unlink_disabled'] = 'Fonction de suppression des fichiers désactivée sur votre serveur';
$LANG['e_upload_failed_unwritable'] = 'Upload impossible, interdiction d\'écriture dans ce dossier';
$LANG['e_upload_already_exist'] = 'Le fichier existe déjà, écrasement non autorisé';
$LANG['e_max_data_reach'] = 'Taille maximale atteinte, supprimez d\'anciens fichiers';

//Membres
$LANG['e_pass_mini'] = 'Longueur minimale du nouveau password : 6 caractères';
$LANG['e_pass_same'] = 'Les mots de passe doivent être identiques';
$LANG['e_pseudo_auth'] = 'Le pseudo entré est déjà utilisé !';
$LANG['e_mail_auth'] = 'Le mail entré est déjà utilisé !';
$LANG['e_mail_invalid'] = 'Le mail entré est invalide !';
$LANG['e_unexist_member'] = 'Aucun membre trouvé avec ce pseudo !';
$LANG['e_member_ban'] = 'Vous avez été banni! Vous pourrez vous reconnecter dans';
$LANG['e_member_ban_w'] = 'Vous avez été banni pour un comportement abusif! Contactez l\'administrateur s\'il s\'agit d\'une erreur.';
$LANG['e_unactiv_member'] = 'Votre compte n\'a pas encore été activé !';
$LANG['e_test_connect'] = 'Il vous reste %d essai(s) restant(s) après cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10 minutes pour 5) !';
$LANG['e_nomore_test_connect'] = 'Vous avez épuisé tous vos essais de connexion, votre compte est verrouillé pendant 5 minutes';

//Champs supplémentaires
$LANG['e_exist_field'] = 'Un champ portant ce nom existe déjà!';

//Groupes
$LANG['e_already_group'] = 'Le membre appartient déjà au groupe';

//Oublié
$LANG['e_mail_forget'] = 'Le mail entré ne correspond pas à celui de l\'utilisateur !';
$LANG['e_forget_mail_send'] = 'Un mail vient de vous être envoyé, avec une clé d\'activation pour confirmer !';
$LANG['e_forget_confirm_change'] = 'Mot de passe changé avec succès!<br />Vous pouvez désormais vous enregistrer avec le nouveau mot de passe qui vous a été transmis par email.';
$LANG['e_forget_echec_change'] = 'Echec le mot de passe ne peut être changé';

//Register
$LANG['e_incorrect_verif_code'] = 'Le code de vérification entré est incorrect !';

//Mps
$LANG['e_pm_full'] = 'Votre boite de messages privés est pleine, vous avez <strong>%d</strong> conversation(s) en attente, pour pouvoir la/les lire supprimez d\'anciennes conversations.';
$LANG['e_pm_full_post'] = 'Votre boite de messages privés est pleine, supprimez d\'anciennes conversations pour pouvoir en envoyer de nouvelles.';
$LANG['e_unexist_user'] = 'L\'utilisateur sélectionné n\'existe pas !';
$LANG['e_pm_del'] = 'Le destinataire a supprimé la conversation, vous ne pouvez plus poster';
$LANG['e_pm_noedit'] = 'Le destinataire a déjà lu votre message, vous ne pouvez plus l\'éditer';
$LANG['e_pm_nodel'] = 'Le destinataire a déjà lu votre message, vous ne pouvez plus le supprimer';

//Gestionnaire d'erreur php
$LANG['e_notice'] = 'Suggestion';
$LANG['e_warning'] = 'Avertissement';
$LANG['e_unknow'] = 'Inconnue';
$LANG['infile'] = 'dans le fichier';
$LANG['atline'] = 'à la ligne';

// Too Many Connections
$LANG['too_many_connections'] = 'Trop de connexions';
$LANG['too_many_connections_explain'] = 'Le nombre maximum de connexions simultanées à la base de données à été atteint.<br />Veuillez réessayer dans quelques secondes.';
?>
