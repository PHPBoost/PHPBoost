<?php
/*##################################################
 *                           user-common.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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
 #                     French                       #
 ####################################################

$lang['user'] = 'Utilisateur';
$lang['users'] = 'Utilisateurs';
$lang['profile'] = 'Profil';
$lang['profile.edit'] = 'Edition du profil';
$lang['messages'] = 'Messages de l\'utilisateur';
$lang['maintain'] = 'Maintenance';
$lang['groups'] = 'Groupes';

$lang['profile.edit.success'] = 'Le profil a bien été modifié';
$lang['profile.edit.password.error'] = 'Le mot de passe que vous avez entré n\'est pas correct';

//Contribution
$lang['contribution.confirmed'] = 'Votre contribution a bien été enregistrée.';
$lang['contribution.confirmed.messages'] = '<p>Vous pourrez la suivre dans le <a href="' . UserUrlBuilder::contribution_panel()->absolute() . '">panneau de contribution</a> 
et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>';


//User fields
$lang['pseudo'] = 'Pseudo';
$lang['pseudo.explain'] = 'Longueur minimale du pseudo : 3 caractères';
$lang['password'] = 'Mot de passe';
$lang['password.new'] = 'Nouveau mot de passe';
$lang['password.old'] = 'Ancien mot de passe';
$lang['password.old.explain'] = 'Remplir seulement en cas de modification';
$lang['password.confirm'] = 'Confirmer le mot de passe';
$lang['password.explain'] = 'Longueur minimale du mot de passe : 6 caractères';
$lang['email'] = 'Email';
$lang['email.hide'] = 'Cache l\'email';
$lang['theme'] = 'Thème';
$lang['theme.preview'] = 'Prévisualiser le thème';
$lang['text-editor'] = 'Editeur de texte';
$lang['lang'] = 'Langue';
$lang['timezone.'] = 'Fuseau horaire';
$lang['timezone.choice'] = 'Choix du fuseau horaire';
$lang['timezone.choice.explain'] = 'Permet d\'ajuster l\'heure à votre localisation';
$lang['email.hide'] = 'Cache l\'email';
$lang['level'] = 'Rang';

$lang['registration_date'] = 'Date d\'inscription';
$lang['last_connection'] = 'Dernière connexion';
$lang['number-messages'] = 'Nombre de messages';
$lang['private_message'] = 'Message privé';
$lang['delete-account'] = 'Supprimer le compte';
$lang['avatar'] = 'Avatar';

$lang['groups'] = 'Groupes';
$lang['groups.select'] = 'Séléctionner un groupe';

//Other
$lang['banned'] = 'Banni';
$lang['connect'] = 'Se connecter';

// Ranks
$lang['visitor'] = 'Visiteur';
$lang['member'] = 'Membre';
$lang['moderator'] = 'Modérateur';
$lang['administrator'] = 'Administrateur';

//Forget password
$lang['forget-password'] = 'Mot de passe oublié';
$lang['forget-password.select'] = 'Sélectionnez le champ que vous voulez renseigner (email ou pseudo)';
$lang['forget-password.success'] = 'Un email vous a été envoyé avec un lien pour changer votre mot de passe';
$lang['forget-password.error'] = 'Les informations fournisent ne sont pas correct, veuillez les rectifier et réessayer';
$lang['change-password'] = 'Changement de mot de passe';
$lang['forget-password.mail.content'] = 'Cher(e) :pseudo,

Vous recevez cet e-mail parce que vous (ou quelqu\'un qui prétend l\'être) avez demandé à ce qu\'un nouveau mot de passe vous soit envoyé pour votre compte sur :host. 
Si vous n\'avez pas demandé de changement de mot de passe, veuillez l\'ignorer. Si vous continuez à le recevoir, veuillez contacter l\'administrateur du site.

Pour changer de mot de passe, cliquez sur le lien fourni ci-dessous et suivez les indications sur le site.

:change_password_link

Si vous rencontrez des difficultés, veuillez contacter l\'administrateur du site.

:signature';

//Registration 
$lang['registration'] = 'Inscription';
$lang['registration.validation.mail.explain'] = 'Vous devrez activer votre compte dans l\'email qui vous sera envoyé avant de pouvoir vous connecter';
$lang['registration.validation.administrator.explain'] = 'Un administrateur devra activer votre compte avant de pouvoir vous connecter';
$lang['registration.confirm.success'] = 'Votre compte a été validé avec succès';
$lang['registration.confirm.error'] = 'Un problème est survenue lors de votre activation, vérifier que votre clé est bien valide';
$lang['registration.success'] = 'Vous vous êtes bien enregistré et êtes dès à présent connecté';
$lang['registration.success.administrator-validation'] = 'Vous vous êtes enregistré avec succès. Cependant un administrateur doit valider votre compte avant de pouvoir l\'utiliser';
$lang['registration.success.mail-validation'] = 'Vous vous êtes enregistré avec succès. Cependant il vous faudra cliquer sur le lien d\'activation contenu dans le mail qui vous a été envoyé';
$lang['registration.pending-approval'] = 'Un nouveau membre s\'est inscrit. Son compte doit être approuvé avant de pouvoir être utilisé.';
$lang['registration.subject-mail'] = 'Confirmation d\'inscription sur :site_name';
$lang['registration.content-mail'] = 'Cher(e) :pseudo,

Tout d\'abord, merci de vous être inscrit sur :site_name. Vous faites parti dès maintenant des membres du site.
En vous inscrivant sur :site_name, vous obtenez un accès à la zone membre qui vous offre plusieurs avantages. Vous pourrez, entre autre, être reconnu automatiquement sur tout le site, pour poster des messages, modifier la langue et/ou le thème par défaut, éditer votre profil, accéder à des catégories réservées aux membres... Bref vous accédez à toute la communauté du site.

Pour vous connecter, il vous faudra retenir votre identifiant et votre mot de passe.

Nous vous rappelons vos identifiants.

Identifiant : :login
Mot de passe : :password

:accounts_validation_explain

:signature';
?>