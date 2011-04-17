<?php
/*##################################################
 *                                common.php
 *                            -------------------
 *   begin                : January 4, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 ###################################################
 *
 *   This program is a free software. You can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/
  
 ####################################################
 #                     French                      	#
 ####################################################
 
$lang = array();
 
 //Actions 
$lang['actions.add'] = 'Ajouter';
$lang['actions.edit'] = 'Modifier';
$lang['actions.delete'] = 'Supprimer';
$lang['actions.enabled'] = 'Activé';
$lang['actions.disabled'] = 'Désactivé';
$lang['actions.yes'] = 'Oui';
$lang['actions.no'] = 'Non';
$lang['actions.display'] = 'Afficher';
$lang['actions.accept'] = 'Accepter';
$lang['actions.refuse'] = 'Refuser';

//Ranks
$lang['ranks.rank'] = 'Rang';
$lang['ranks.ranks'] = 'Rangs';
$lang['ranks.guest'] = 'Visiteur';
$lang['ranks.member'] = 'Membre';
$lang['ranks.moderator'] = 'Modérateur';
$lang['ranks.administrator'] = 'Administrateur';
$lang['ranks.guests'] = 'Visiteurs';
$lang['ranks.members'] = 'Membres';
$lang['ranks.moderators'] = 'Modérateurs';
$lang['ranks.administrators'] = 'Administrateurs';

//Syndication
$lang['syndication.syndication'] = 'Syndication';
$lang['syndication.rss'] = 'RSS';
$lang['syndication.atom'] = 'ATOM';

//Sort
$lang['sort.asc'] = 'Croissant';
$lang['sort.desc'] = 'Decroissant';

//Contribution
$lang['contribution.contribution-panel'] = 'Panneau de contribution';
$lang['contribution.contribution'] = 'Contribution';
$lang['contribution.contribution-status-unread'] = 'Non traitée';
$lang['contribution.contribution-status-being-processed'] = 'En cours';
$lang['contribution.contribution-status-processed'] = 'Traitée';
$lang['contribution.contribution-entitled'] = 'Intitulé';
$lang['contribution.contribution-description'] = 'Description';
$lang['contribution.contribution-edition'] = 'Edition d\'une contribution';
$lang['contribution.contribution-status'] = 'Statut';
$lang['contribution.contributor'] = 'Contributeur';
$lang['contribution.contribution-creation-date'] = 'Date de création';
$lang['contribution.contribution-fixer'] = 'Responsable';
$lang['contribution.contribution-fixing-date'] = 'Date de clôture';
$lang['contribution.contribution-module'] = 'Module';
$lang['contribution.process-contribution'] = 'Traiter la contribution';
$lang['contribution.confirm-delete-contribution'] = 'Etes-vous sûr de vouloir supprimer cette contribution ?';
$lang['contribution.no-contribution'] = 'Aucune contribution à afficher';
$lang['contribution.contribution-list'] = 'Liste des contributions';
$lang['contribution.contribute'] = 'Contribuer';
$lang['contribution.contribute-in-modules-explain'] = 'Les modules suivants permettent aux utilisateurs de contribuer. Cliquez sur un module pour vous rendre dans son interface de contribution.';
$lang['contribution.contribute-in-module-name'] = 'Contribuer dans le module %s';
$lang['contribution.no-module-to-contribute'] = 'Aucun module dans lequel vous pouvez contribuer n\'est installé.';

//Sex
$lang['sex.sex'] = 'Sexe';
$lang['sex.male'] = 'Homme';
$lang['sex.female'] = 'Femme';

//Captcha
$lang['captcha.captcha'] = 'Code de vérification';

//Read
$lang['read.read'] = 'Lu';
$lang['read.not-read'] = 'Non lu';

//Errors
$lang['errors.question'] = 'Question';
$lang['errors.notice'] = 'Remarque';
$lang['errors.warning'] = 'Attention';
$lang['errors.success'] = 'Succès';

//Date
$lang['date.date'] = 'Date';
$lang['date.day'] = 'Jour';
$lang['date.days'] = 'Jours';
$lang['date.month'] = 'Mois';
$lang['date.months'] = 'Mois';
$lang['date.year'] = 'An';
$lang['date.years'] = 'Ans';
$lang['date.on'] = 'Le';
$lang['date.at'] = 'à';
$lang['date.and'] = 'et';
$lang['date.by'] = 'Par';
$lang['date.january'] = 'Janvier';
$lang['date.february'] = 'Février';
$lang['date.march'] = 'Mars';
$lang['date.april'] = 'Avril';
$lang['date.may'] = 'Mai';
$lang['date.june'] = 'Juin';
$lang['date.july'] = 'Juillet';
$lang['date.august'] = 'Août';
$lang['date.september'] = 'Septembre';
$lang['date.october'] = 'Octobre';
$lang['date.november'] = 'Novembre';
$lang['date.december'] = 'Décembre';
$lang['date.monday'] = 'Lundi';
$lang['date.tuesday'] = 'Mardi';
$lang['date.wenesday'] = 'Mercredi';
$lang['date.thursday'] = 'Jeudi';
$lang['date.friday'] = 'Vendredi';
$lang['date.saturday'] = 'Samedi';
$lang['date.sunday']	= 'Dimanche';

//Footer
$lang['footer.powered_by'] = 'Boosté par';
$lang['footer.phpboost_right'] = '';
$lang['footer.sql_request'] = 'Requêtes';
$lang['footer.achieved'] = 'Exécuté en';

global $LANG;
$LANG = array_merge($LANG, $lang);
 ?>