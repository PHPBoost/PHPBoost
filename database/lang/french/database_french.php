<?php
/*##################################################
 *                              database_french.php
 *                            -------------------
 *   begin                : Februar 02, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
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
#            French                                 #
 ####################################################

//Administration
$LANG['database'] = 'Base de données';
$LANG['creation_date'] = 'Date de création';
$LANG['database_management'] = 'Gestion base de données';
$LANG['db_sql_queries'] = 'Requêtes SQL';
$LANG['db_explain_actions'] = 'Ce panneau vous permet de gérer votre base de données. Vous pouvez y voir la liste des tables utilisées par PHPBoost, leurs propriétés, ainsi que divers outils qui vous permettront de faire quelques opérations basiques sur certaines tables. Vous pouvez aussi effectuer une sauvegarde de votre base de données, ou de seulement quelques tables que vous sélectionnerez ici.';
$LANG['db_explain_actions.question'] = 'L\'optimisation de la base de données permet de réorganiser la structure de la table afin de faciliter les opérations au serveur SQL. Cette opération est effectuée automatiquement sur chaque table une fois par jour. Vous pouvez optimiser les tables manuellement via ce panneau d\'administration.
<br />
La réparation n\'est normalement pas à envisager mais en cas de problème elle peut s\'avérer utile. Le support vous dira de l\'effectuer quand cela sera nécessaire.
<br />
<strong>Attention : </strong>C\'est une opération lourde, elle consomme beaucoup de ressources, il est donc conseillé d\'éviter de réparer les tables si ce n\'est pas utile !';
$LANG['db_restore_from_server'] = 'Vous pouvez utiliser les fichiers que vous n\'aviez pas supprimé lors de restaurations antérieures.';
$LANG['db_view_file_list'] = 'Voir la liste des fichiers disponibles (<em>cache/backup</em>)';
$LANG['import_file_explain'] = 'Vous pouvez restaurer votre base de données par un fichier que vous possédez sur votre ordinateur. Si votre fichier dépasse la taille maximale autorisée par votre serveur, c\'est-à-dire %s, vous devez utiliser l\'autre méthode en envoyant par FTP votre fichier dans le répertoire <em>cache/backup</em>.';
$LANG['db_restore'] = 'Restaurer';
$LANG['db_table_list'] = 'Liste des tables';
$LANG['db_table_name'] = 'Nom de la table';
$LANG['db_table_rows'] = 'Enregistrements';
$LANG['db_table_rows_format'] = 'Format';
$LANG['db_table_engine'] = 'Type';
$LANG['db_table_structure'] = 'Structure';
$LANG['db_table_collation'] = 'Interclassement';
$LANG['db_table_data'] = 'Taille';
$LANG['db_table_index'] = 'Index';
$LANG['db_table_field'] = 'Champ';
$LANG['db_table_attribute'] = 'Attribut';
$LANG['db_table_null'] = 'Null';
$LANG['db_table_value'] = 'Valeur';
$LANG['db_table_extra'] = 'Extra';
$LANG['db_autoincrement'] = 'Suivant autoindex';
$LANG['db_table_free'] = 'Perte';
$LANG['db_selected_tables'] = 'Sélectionner';
$LANG['db_select_all'] = 'Sélectionner toutes les tables';
$LANG['db_for_selected_tables'] = 'Actions à réaliser sur la sélection de tables';
$LANG['db_optimize'] = 'Optimiser';
$LANG['db_repair'] = 'Réparer';
$LANG['db_insert'] = 'Insérer';
$LANG['db_backup'] = 'Sauvegarder';
$LANG['db_download'] = 'Télécharger';
$LANG['db_succes_repair_tables'] = 'La sélection de tables (<em>%s</em>) a été réparée avec succès';
$LANG['db_succes_optimize_tables'] = 'La sélection de tables (<em>%s</em>) a été optimisée avec succès';
$LANG['db_backup_database'] = 'Sauvegarder la base de données';
$LANG['db_backup_explain'] = 'Vous pouvez encore modifier la liste des tables que vous souhaitez sélectionner dans le formulaire.
<br />
Ensuite vous devez choisir ce que vous souhaitez sauvegarder.';
$LANG['db_backup_all'] = 'Données et structure';
$LANG['db_backup_struct'] = 'Structure seulement';
$LANG['db_backup_data'] = 'Données seulement';
$LANG['db_backup_success'] = 'Votre base de données a été correctement sauvegardée. Vous pouvez la télécharger en cliquant sur le lien suivant : <a href="admin_database.php?read_file=%s">%s</a>';
$LANG['db_execute_query'] = 'Exécuter une requête dans la base de données';
$LANG['db_tools'] = 'Outils de gestion de la base de données';
$LANG['db_query_explain'] = 'Vous pouvez dans ce panneau d\'administration exécuter des requêtes dans la base de données. Cette interface ne devrait servir que lorsque le support vous demande d\'exécuter une requête dans la base de données qui vous sera communiquée.<br />
<strong>Attention :</strong> si cette requête n\'a pas été proposée par le support vous êtes responsable de son exécution et des pertes de données qu\'elle pourrait provoquer. Il est donc fortement déconseillé d\'utiliser ce module si vous ne maîtrisez pas complètement la structure des tables de PHPBoost.';
$LANG['db_submit_query'] = 'Exécuter';
$LANG['db_query_result'] = 'Résultat';
$LANG['db_executed_query'] = 'Requête SQL';
$LANG['db_confirm_query'] = 'Voulez-vous vraiment exécuter la requête suivante : ';
$LANG['db_file_list'] = 'Liste des fichiers';
$LANG['db_confirm_restore'] = 'Etes-vous sûr de vouloir restaurer votre base de données à partir de la sauvegarde sélectionnée ?';
$LANG['db_restore_file'] = 'Cliquez sur le fichier que vous voulez restaurer.';
$LANG['db_restore_success'] = 'La restauration de la base de données a été effectuée avec succès';
$LANG['db_restore_failure'] = 'Une erreur est survenue pendant la restauration de la base de données';
$LANG['db_upload_failure'] = 'Une erreur est survenue lors du transfert du fichier à partir duquel vous souhaitez importer votre base de données';
$LANG['db_file_already_exists'] = 'Un fichier du répertoire cache/backup porte le même nom que celui que vous souhaitez importer. Merci de renommer un des deux fichiers pour pouvoir l\'importer.';
$LANG['db_unlink_success'] = 'Le fichier a été supprimé avec succès !';
$LANG['db_unlink_failure'] = 'Le fichier n\'a pas pu être supprimé';
$LANG['db_confirm_delete_file'] = 'Etes-vous sûr de vouloir supprimer ce fichier ?';
$LANG['db_confirm_delete_table'] = 'Etes-vous sûr de vouloir supprimer cette table ?';
$LANG['db_confirm_truncate_table'] = 'Etes-vous sûr de vouloir vider cette table ?';
$LANG['db_confirm_delete_entry'] = 'Etes-vous sûr de vouloir supprimer cette entrée ?';
$LANG['db_file_does_not_exist'] = 'Le fichier que vous souhaitez supprimer n\'existe pas ou n\'est pas un fichier SQL';
$LANG['db_empty_dir'] = 'Le dossier est vide';
$LANG['db_file_name'] = 'Nom du fichier';
$LANG['db_file_weight'] = 'Taille du fichier';

?>
