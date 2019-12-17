<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 26
 * @since       PHPBoost 3.0 - 2012 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

####################################################
#                     French                       #
####################################################

$lang['success'] = 'Succès';
$lang['error'] = 'Erreur';

$lang['error.fatal'] = 'Fatale';
$lang['error.notice'] = 'Suggestion';
$lang['error.warning'] = 'Avertissement';
$lang['error.question'] = 'Question';
$lang['error.unknow'] = 'Inconnue';

$lang['message.close_ephemeral_message'] = 'Fermer le message';

//PHPBoost errors
$lang['error.auth'] = 'Vous n\'avez pas le niveau requis !';
$lang['error.auth.guest'] = 'Le contenu de cette page est protégé. Veuillez vous inscrire ou vous connecter sur le site pour y accéder.';
$lang['error.auth.registration_disabled'] = 'L\'inscription de nouveaux membres est désactivée sur le site.';
$lang['error.page.forbidden'] = 'L\'accès à ce dossier est interdit !';
$lang['error.page.unexist'] = 'La page que vous demandez n\'existe pas !';
$lang['error.action.unauthorized'] = 'Action non autorisée !';
$lang['error.module.uninstalled'] = 'Ce module n\'est pas installé !';
$lang['error.module.unactivated'] = 'Ce module n\'est pas activé !';
$lang['error.invalid_archive_content'] = 'Le contenu de l\'archive est incorrect !';
$lang['error.404.message'] = 'Il semblerait qu\'une tornade soit passée par ici.<br />Il ne reste malheureusement plus rien à voir.';
$lang['error.403.message'] = 'Il semblerait qu\'une tornade soit passée par ici.<br />L\'accès est interdit au public.';

$lang['csrf_invalid_token'] = 'Jeton de session invalide. Veuillez essayer de recharger la page car l\'opération n\'a pas pu être effectuée.';

//Element
$lang['element.already_exists'] = 'L\'élément existe déjà.';
$lang['element.unexist'] = 'L\'élément que vous demandez n\'existe pas.';
$lang['element.not_visible'] = 'Cet élément n\'est pas encore ou n\'est plus approuvé, il n\'est pas affiché pour les autres utilisateurs du site.';

$lang['misfit.php'] = 'Version PHP inadaptée';
$lang['misfit.phpboost'] = 'Version de PHPBoost inadaptée';

//Process
$lang['process.success'] = 'L\'opération s\'est déroulée avec succès';
$lang['process.error'] = 'Une erreur s\'est produite lors de l\'opération';

$lang['confirm.delete'] = 'Voulez-vous vraiment supprimer cet élément ?';
$lang['confirm.delete.elements'] = 'Voulez-vous vraiment supprimer ces éléments ?';

$lang['message.success.config'] = 'La configuration a été modifiée';
$lang['message.success.position.update'] = 'Les éléments ont été repositionnés';

$lang['message.download.file.error'] = 'Echec lors du téléchargement du fichier :filename';

$lang['message.delete_install_and_update_folders'] = 'Par mesure de sécurité nous vous conseillons fortement de supprimer les dossiers <b>install</b> et <b>update</b> et tout ce qu\'ils contiennent, des personnes mal intentionnées pourraient relancer le script d\'installation et écraser certaines de vos données !';
$lang['message.delete_install_or_update_folders'] = 'Par mesure de sécurité nous vous conseillons fortement de supprimer le dossier <b>:folder</b> et tout ce qu\'il contient, des personnes mal intentionnées pourraient relancer le script d\'installation et écraser certaines de vos données !';

//Captcha
$lang['captcha.validation_error'] = 'Le champ de vérification visuel n\'a pas été saisi correctement !';
$lang['captcha.is_default'] = 'Le captcha que vous souhaitez désinstaller ou désactiver est défini sur le site, veuillez d\'abord sélectionner un autre captcha dans la configuration du contenu.';
$lang['captcha.last_installed'] = 'Dernier captcha, vous ne pouvez pas le supprimer ou le désactiver. Veuillez d\'abord en installer un autre.';

//Form
$lang['form.explain_required_fields'] = 'Les champs marqués * sont obligatoires !';
$lang['form.doesnt_match_regex'] = 'La valeur saisie n\'est pas au bon format';
$lang['form.doesnt_match_date_regex'] = 'La valeur saisie doit être une date valide';
$lang['form.doesnt_match_url_regex'] = 'La valeur saisie doit être une url valide';
$lang['form.doesnt_match_mail_regex'] = 'La valeur saisie doit être un mail valide';
$lang['form.doesnt_match_tel_regex'] = 'La valeur saisie doit être un numéro de téléphone valide';
$lang['form.doesnt_match_number_regex'] = 'La valeur saisie doit être un nombre';
$lang['form.doesnt_match_authorized_extensions_regex'] = 'L\'extension du fichier n\'est pas autorisée. Extensions valides : :extensions.';
$lang['form.doesnt_match_picture_file_regex'] = 'La valeur saisie doit correspondre à une image';
$lang['form.doesnt_match_length_intervall'] = 'La valeur saisie ne respecte par la longueur définie (:lower_bound <= valeur <= :upper_bound)';
$lang['form.doesnt_match_length_min'] = 'La valeur saisie doit faire au moins :lower_bound caractères';
$lang['form.doesnt_match_length_max'] = 'La valeur saisie doit faire au maximum :upper_bound caractères';
$lang['form.doesnt_match_integer_intervall'] = 'La valeur saisie ne respecte pas l\'intervalle définie (:lower_bound <= valeur <= :upper_bound)';
$lang['form.doesnt_match_integer_min'] = 'La valeur saisie doit être supérieure ou égale à :lower_bound';
$lang['form.doesnt_match_integer_max'] = 'La valeur saisie doit être inférieure ou égale à :upper_bound';
$lang['form.doesnt_match_medium_password_regex'] = 'Le mot de passe doit comporter au moins une minuscule et une majuscule ou une minuscule et un chiffre';
$lang['form.doesnt_match_strong_password_regex'] = 'Le mot de passe doit comporter au moins une minuscule, une majuscule et un chiffre';
$lang['form.doesnt_match_very_strong_password_regex'] = 'Le mot de passe doit comporter au moins une minuscule, une majuscule, un chiffre et un caractère spécial';
$lang['form.invalid_url'] = 'L\'url n\'est pas valide';
$lang['form.invalid_picture'] = 'Le fichier indiqué n\'est pas une image';
$lang['form.unexisting_file'] = 'Le fichier n\'a pas été trouvé, son adresse doit être incorrecte';
$lang['form.has_to_be_filled'] = 'Le champ ":name" doit être renseigné';
$lang['form.validation_error'] = 'Veuillez corriger les erreurs du formulaire';
$lang['form.fields_must_be_equal'] = 'Les champs ":field1" et ":field2" doivent être égaux';
$lang['form.fields_must_not_be_equal'] = 'Les champs ":field1" et ":field2" doivent avoir des valeurs différentes';
$lang['form.first_field_must_be_inferior_to_second_field'] = 'Le champ ":field2" doit avoir une valeur inférieure au champ ":field1"';
$lang['form.first_field_must_be_superior_to_second_field'] = 'Le champ ":field2" doit avoir une valeur supérieure au champ ":field1"';
$lang['form.first_field_must_not_be_contained_in_second_field'] = 'La valeur champ ":field1" ne doit pas être contenue dans le champ ":field2"';
$lang['form.login_and_mail_must_not_be_contained_in_second_field'] = 'Votre email ou votre identifiant de connexion ne doivent pas être contenus dans le champ ":field2"';
$lang['form.doesnt_match_mail_authorized_domains_regex'] = 'Le nom de domaine de cette adresse n\'est pas autorisé sur le site, veuillez choisir une autre adresse email';

//User
$lang['user.not_authorized_during_maintain'] = 'Vous n\'avez pas l\'autorisation d\'accéder au site pendant la maintenance';
$lang['user.not_exists'] = 'L\'utilisateur n\'existe pas !';
$lang['user.auth.passwd_flood'] = 'Il vous reste :remaining_tries essai(s) après cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10min pour 5)!';
$lang['user.auth.passwd_flood_max'] = 'Vous avez épuisé tous vos essais de connexion, votre compte est verrouillé pendant 5 minutes.';

//Extended fields
$lang['extended_field.avatar_upload_invalid_format'] = 'Format du fichier invalide pour l\'avatar';
$lang['extended_field.avatar_upload_max_dimension'] = 'Dimensions maximales du fichier dépassées pour l\'avatar';

//BBcode
$lang['bbcode_member'] = 'Message destiné aux membres';
$lang['bbcode_moderator'] = 'Message destiné aux modérateurs';

//Locked content
$lang['content.is_locked.description'] = 'L\'élément est actuellement ouvert par :user_display_name, vous ne pouvez pas y accéder, réessayez plus tard.';
$lang['content.is_locked.another_user'] = 'un autre utilisateur';

//Upload
$lang['upload.success'] = 'L\'archive a été uploadée avec succès';
$lang['upload.invalid_format'] = 'Le format de l\'archive n\'est pas valide';
$lang['upload.error'] = 'Erreur lors de l\'upload du fichier';
$lang['upload.max_file_size_exceeded'] = 'La taille maximale du fichier ne doit pas dépasser :max_file_size.';

?>
