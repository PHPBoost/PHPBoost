<?php
/*##################################################
 *                           user-common.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
$lang['profile_of'] = 'Profil de :name';
$lang['profile.edit'] = 'Edition du profil';
$lang['messages'] = 'Messages de l\'utilisateur';
$lang['maintain'] = 'Maintenance';
$lang['welcome'] = 'Bienvenue';

$lang['members-list'] = 'Liste des membres';
$lang['member-management'] = 'Gestion du membre';
$lang['punishment-management'] = 'Gestion des sanctions';

$lang['profile.edit.password.error'] = 'Le mot de passe que vous avez entré n\'est pas correct';
$lang['external-auth.account-exists'] = 'Pour associer votre compte avec la connexion externe vous devez vous connecter sur le site et vous rendre dans l\'édition de votre profil';
$lang['external-auth.email-not-found'] = 'L\'adresse email de votre compte n\'a pas pu être récupérée, votre compte ne peut pas être associé.';

//Contribution
$lang['contribution'] = 'Contribution';
$lang['contribution.explain'] = 'Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.';
$lang['contribution.description'] = 'Complément de contribution';
$lang['contribution.description.explain'] = 'Expliquez les raisons de votre contribution. Ce champ est facultatif mais il peut aider un approbateur à prendre sa décision.';
$lang['contribution.confirmed'] = 'Votre contribution a bien été enregistrée.';
$lang['contribution.confirmed.messages'] = '<p>Vous pourrez la suivre dans le <a href="' . UserUrlBuilder::contribution_panel()->rel() . '">panneau de contribution</a> 
et éventuellement discuter avec les validateurs si leur choix n\'est pas franc.</p><p>Merci d\'avoir participé à la vie du site !</p>';
$lang['contribution.pm.title'] = 'La contribution <strong>:title</strong> a été commentée';
$lang['contribution.pm.contents'] = ':author a ajouté un commentaire à la contribution <strong>:title</strong>.<br />
<br />
Commentaire :<br />
:comment<br />
<br />
<a href=":contribution_url">Accéder à la contribution</a>';

//User fields
$lang['display_name'] = 'Nom d\'affichage';
$lang['display_name.explain'] = 'Nom affiché sur chacun des éléments que vous ajoutez.';
$lang['login'] = 'Identifiant de connexion';
$lang['login.explain'] = 'Adresse email ou votre login personnalisé si vous en avez choisi un.';
$lang['login.custom'] = 'Choisir un identifiant de connexion';
$lang['login.custom.explain'] = 'Par défaut, vous devez vous connecter avec votre adresse email.';
$lang['password'] = 'Mot de passe';
$lang['password.new'] = 'Nouveau mot de passe';
$lang['password.old'] = 'Ancien mot de passe';
$lang['password.old.explain'] = 'Remplir seulement en cas de modification';
$lang['password.confirm'] = 'Confirmer le mot de passe';
$lang['password.explain'] = 'Longueur minimale du mot de passe : :number caractères';
$lang['email'] = 'Email';
$lang['email.hide'] = 'Cacher l\'email';
$lang['theme'] = 'Thème';
$lang['theme.preview'] = 'Prévisualiser le thème';
$lang['text-editor'] = 'Editeur de texte';
$lang['lang'] = 'Langue';
$lang['timezone.'] = 'Fuseau horaire';
$lang['timezone.choice'] = 'Choix du fuseau horaire';
$lang['timezone.choice.explain'] = 'Permet d\'ajuster l\'heure à votre localisation';
$lang['level'] = 'Rang';
$lang['approbation'] = 'Approbation';

$lang['registration_date'] = 'Date d\'inscription';
$lang['last_connection'] = 'Dernière connexion';
$lang['number-messages'] = 'Nombre de messages';
$lang['private_message'] = 'Message privé';
$lang['delete-account'] = 'Supprimer le compte';
$lang['avatar'] = 'Avatar';

//Groups
$lang['groups'] = 'Groupes';
$lang['groups.select'] = 'Sélectionner un groupe';
$lang['no_member'] = 'Aucun membre dans ce groupe';

//Other
$lang['caution'] = 'Avertissement';
$lang['readonly'] = 'Lecture seule';
$lang['banned'] = 'Banni';
$lang['connection'] = 'Connexion';
$lang['autoconnect'] = 'Connexion auto';
$lang['disconnect'] = 'Se déconnecter';
$lang['facebook-connect'] = 'Se connecter avec Facebook';
$lang['google-connect'] = 'Se connecter avec Google+';
$lang['twitter-connect'] = 'Se connecter avec Twitter';

$lang['internal_connection'] = 'Connexion interne';
$lang['create_internal_connection'] = 'Créer un compte interne';
$lang['fb_connection'] = 'Connexion par Facebook';
$lang['google_connection'] = 'Connexion par Google';
$lang['associate_account'] = 'Associer votre compte';
$lang['dissociate_account'] = 'Dissocier votre compte';

// Ranks
$lang['rank'] = 'Rang';
$lang['visitor'] = 'Visiteur';
$lang['member'] = 'Membre';
$lang['moderator'] = 'Modérateur';
$lang['administrator'] = 'Administrateur';

//Forget password
$lang['forget-password'] = 'Mot de passe oublié';
$lang['forget-password.select'] = 'Sélectionnez le champ que vous voulez renseigner (email ou pseudo)';
$lang['forget-password.success'] = 'Un email vous a été envoyé avec un lien pour changer votre mot de passe';
$lang['forget-password.error'] = 'Les informations fournies ne sont pas correctes, veuillez les rectifier et réessayer';
$lang['change-password'] = 'Changement de mot de passe';
$lang['forget-password.mail.content'] = 'Cher(e) :pseudo,

Vous recevez cet e-mail parce que vous (ou quelqu\'un qui prétend l\'être) avez demandé à ce qu\'un nouveau mot de passe vous soit envoyé pour votre compte sur :host. 
Si vous n\'avez pas demandé de changement de mot de passe, veuillez l\'ignorer. Si vous continuez à le recevoir, veuillez contacter l\'administrateur du site.

Pour changer de mot de passe, cliquez sur le lien fourni ci-dessous et suivez les indications sur le site.

:change_password_link

Si vous rencontrez des difficultés, veuillez contacter l\'administrateur du site.

:signature';

//Registration 
$lang['register'] = 'S\'inscrire';
$lang['registration'] = 'Inscription';

$lang['registration.validation.mail.explain'] = 'Vous devrez activer votre compte dans l\'email qui vous sera envoyé avant de pouvoir vous connecter';
$lang['registration.validation.administrator.explain'] = 'Un administrateur devra activer votre compte avant de pouvoir vous connecter';

$lang['registration.confirm.success'] = 'Votre compte a été validé avec succès';
$lang['registration.confirm.error'] = 'Un problème est survenue lors de votre activation, vérifier que votre clé est bien valide';

$lang['registration.success.administrator-validation'] = 'Vous vous êtes enregistré avec succès. Cependant un administrateur doit valider votre compte avant de pouvoir l\'utiliser';
$lang['registration.success.mail-validation'] = 'Vous vous êtes enregistré avec succès. Cependant il vous faudra cliquer sur le lien d\'activation contenu dans le mail qui vous a été envoyé';

$lang['registration.email.automatic-validation'] = 'Vous pouvez désormais vous connecter à votre compte directement sur le site.';
$lang['registration.email.mail-validation'] = 'Vous devez activer votre compte avant de pouvoir vous connecter en cliquant sur ce lien : :validation_link';
$lang['registration.email.administrator-validation'] = 'Attention : Votre compte devra être activé par un administrateur avant de pouvoir vous connecter. Merci de votre patience.';
$lang['registration.email.mail-administrator-validation'] = 'Cher(e) :pseudo,

Nous avons le plaisir de vous informer que votre compte sur :site_name vient d\'être validé par un administrateur.

Vous pouvez dès à présent vous connecter au site à l\'aide des identifiants fournis dans le précédent email.

:signature';

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

A bientôt sur :host

:signature';

$lang['agreement'] = 'Règlement';
$lang['agreement.agree'] = 'J\'accepte les conditions';
$lang['agreement.agree.required'] = 'Vous devez accepter le règlement pour vous inscrire';

//Messages
$lang['user.message.success.add'] = 'L\'utilisateur <b>:name</b> a été ajouté';
$lang['user.message.success.edit'] = 'Le profil a été modifié';
$lang['user.message.success.delete'] = 'L\'utilisateur <b>:name</b> a été supprimé';

############## Extended Field ##############

$lang['extended-field.field.sex'] = 'Sexe';
$lang['extended-field.field.sex-explain'] = '';

$lang['extended-field.field.pmtomail'] = 'Recevoir une notification par mail à la réception d\'un message privé';
$lang['extended-field.field.pmtomail-explain'] = '';

$lang['extended-field.field.date-birth'] = 'Date de naissance';
$lang['extended-field.field.date-birth-explain'] = '';

$lang['extended-field.field.avatar'] = 'Avatar';
$lang['extended-field.field.avatar-explain'] = '';
$lang['extended-field.field.avatar.current_avatar'] = 'Avatar actuel';
$lang['extended-field.field.avatar.upload_avatar'] = 'Uploader un avatar';
$lang['extended-field.field.avatar.upload_avatar-explain'] = 'Avatar directement hébergé sur le serveur';
$lang['extended-field.field.avatar.link'] = 'Lien avatar';
$lang['extended-field.field.avatar.link-explain'] = 'Adresse directe de l\'avatar';
$lang['extended-field.field.avatar.delete'] = 'Supprimer l\'avatar courant';
$lang['extended-field.field.avatar.no_avatar'] = 'Aucun avatar';

$lang['extended-field.field.location'] = 'Localisation';
$lang['extended-field.field.location-explain'] = '';

$lang['extended-field.field.job'] = 'Emploi';
$lang['extended-field.field.job-explain'] = '';

$lang['extended-field.field.entertainement'] = 'Loisirs';
$lang['extended-field.field.entertainement-explain'] = '';

$lang['extended-field.field.biography'] = 'Biographie';
$lang['extended-field.field.biography-explain'] = '';
?>
