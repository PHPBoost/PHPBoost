<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 04 03
 * @since       PHPBoost 4.1 - 2015 09 30
*/

####################################################
#                    French                        #
####################################################

$lang['module_title'] = 'Base de données';
$lang['module_config_title'] = 'Configuration du module base de données';

$lang['database.actions.database_management'] = 'Gestion base de données';
$lang['database.actions.db_sql_queries'] = 'Requêtes SQL';

// Configuration
$lang['config.database-tables-optimization-enabled'] = 'Activer l\'optimisation automatique des tables de la base de données';
$lang['config.database-tables-optimization-day'] = 'Jour de l\'optimisation';
$lang['config.database-tables-optimization-day.explain'] = 'Optimisation exécutée dans la nuit';



// Management
$lang['database'] = 'Base de données';
$lang['creation_date'] = 'Date de création';
$lang['database_management'] = 'Gestion base de données';
$lang['db_sql_queries'] = 'Requêtes SQL';
$lang['db_explain_actions'] = 'Ce panneau vous permet de gérer votre base de données. Vous pouvez y voir la liste des tables utilisées par PHPBoost, leurs propriétés, ainsi que divers outils qui vous permettront de faire quelques opérations basiques sur certaines tables. Vous pouvez aussi effectuer une sauvegarde de votre base de données, ou de seulement quelques tables que vous sélectionnerez ici.';
$lang['db_explain_actions.question'] = 'L\'optimisation de la base de données permet de réorganiser la structure de la table afin de faciliter les opérations au serveur SQL. Cette opération peut être effectuée automatiquement si l\'option est cochée dans l\'administration du module. Vous pouvez optimiser les tables manuellement via ce panneau d\'administration.
<br />
La réparation n\'est normalement pas à envisager mais en cas de problème elle peut s\'avérer utile. Le support vous dira de l\'effectuer quand cela sera nécessaire.
<br />
<strong>Attention : </strong>C\'est une opération lourde, elle consomme beaucoup de ressources, il est donc conseillé d\'éviter de réparer les tables si ce n\'est pas utile !';
$lang['db_restore_from_server'] = 'Vous pouvez utiliser les fichiers que vous n\'aviez pas supprimé lors de restaurations antérieures.';
$lang['db_view_file_list'] = 'Voir la liste des fichiers disponibles (<em>cache/backup</em>)';
$lang['import_file_explain'] = 'Vous pouvez restaurer votre base de données par un fichier que vous possédez sur votre ordinateur. Si votre fichier dépasse la taille maximale autorisée par votre serveur, c\'est-à-dire %s, vous devez utiliser l\'autre méthode en envoyant par FTP votre fichier dans le répertoire <em>cache/backup</em>.';
$lang['db_restore'] = 'Restaurer';
$lang['db_table_list'] = 'Liste des tables';
$lang['db_table_name'] = 'Nom de la table';
$lang['db_table_rows'] = 'Enregistrements';
$lang['db_table_rows_format'] = 'Format';
$lang['db_table_engine'] = 'Type';
$lang['db_table_structure'] = 'Structure';
$lang['db_table_collation'] = 'Interclassement';
$lang['db_table_data'] = 'Taille';
$lang['db_table_index'] = 'Index';
$lang['db_table_field'] = 'Champ';
$lang['db_table_attribute'] = 'Attribut';
$lang['db_table_null'] = 'Null';
$lang['db_table_value'] = 'Valeur';
$lang['db_table_extra'] = 'Extra';
$lang['db_autoincrement'] = 'Suivant autoindex';
$lang['db_table_free'] = 'Perte';
$lang['db_selected_tables'] = 'Sélectionner';
$lang['db_select_all'] = 'Sélectionner toutes les tables';
$lang['db_select_db_for_restore'] = 'Sélectionner la base de donnée à restaurer';
$lang['db_for_selected_tables'] = 'Actions à réaliser sur la sélection de tables';
$lang['db_optimize'] = 'Optimiser';
$lang['db_repair'] = 'Réparer';
$lang['db_insert'] = 'Insérer';
$lang['db_backup'] = 'Sauvegarder';
$lang['database.compress.file'] = 'Compresser le fichier';
$lang['db_download'] = 'Télécharger';
$lang['db_succes_repair_tables'] = 'La sélection de tables (<em>%s</em>) a été réparée avec succès';
$lang['db_succes_optimize_tables'] = 'La sélection de tables (<em>%s</em>) a été optimisée avec succès';
$lang['db_backup_database'] = 'Sauvegarder la base de données';
$lang['db_backup_explain'] = 'Vous pouvez encore modifier la liste des tables que vous souhaitez sélectionner dans le formulaire.
<br />
Ensuite vous devez choisir ce que vous souhaitez sauvegarder.';
$lang['db_backup_all'] = 'Données et structure';
$lang['db_backup_struct'] = 'Structure seulement';
$lang['db_backup_data'] = 'Données seulement';
$lang['db_backup_success'] = 'Votre base de données a été correctement sauvegardée. Vous pouvez la télécharger en cliquant sur le lien suivant : <a href="admin_database.php?read_file=%s">%s</a>';
$lang['db_execute_query'] = 'Requête SQL';
$lang['db_tools'] = 'Gestion base de données';
$lang['db_query_explain'] = 'Vous pouvez dans ce panneau d\'administration exécuter des requêtes dans la base de données. Cette interface ne devrait servir que lorsque le support vous demande d\'exécuter une requête dans la base de données qui vous sera communiquée.<br />
<strong>Attention :</strong> si cette requête n\'a pas été proposée par le support vous êtes responsable de son exécution et des pertes de données qu\'elle pourrait provoquer. Il est donc fortement déconseillé d\'utiliser ce module si vous ne maîtrisez pas complètement la structure des tables de PHPBoost.';
$lang['db_submit_query'] = 'Exécuter';
$lang['db_query_result'] = 'Résultat';
$lang['db_executed_query'] = 'Requête SQL';
$lang['db_confirm_query'] = 'Voulez-vous vraiment exécuter la requête suivante : ';
$lang['db_file_list'] = 'Liste des fichiers';
$lang['db_confirm_restore'] = 'Etes-vous sûr de vouloir restaurer votre base de données à partir de la sauvegarde sélectionnée ?';
$lang['db_restore_file'] = 'Cliquez sur le fichier que vous voulez restaurer.';
$lang['db_restore_success'] = 'La restauration de la base de données a été effectuée avec succès';
$lang['db_restore_failure'] = 'Une erreur est survenue pendant la restauration de la base de données';
$lang['db_upload_failure'] = 'Une erreur est survenue lors du transfert du fichier à partir duquel vous souhaitez importer votre base de données';
$lang['db_file_already_exists'] = 'Un fichier du répertoire cache/backup porte le même nom que celui que vous souhaitez importer. Merci de renommer un des deux fichiers pour pouvoir l\'importer.';
$lang['db_no_sql_file'] = 'Le fichier à importer n\'est pas la sauvegarde d\'une base de données, merci de fournir un fichier correct à restaurer.';
$lang['db_backup_not_from_this_site'] = 'Le fichier à importer n\'est pas la sauvegarde de ce site, restauration impossible.';
$lang['db_unlink_success'] = 'Le fichier a été supprimé avec succès !';
$lang['db_unlink_failure'] = 'Le fichier n\'a pas pu être supprimé';
$lang['db_confirm_delete_file'] = 'Etes-vous sûr de vouloir supprimer ce fichier ?';
$lang['db_confirm_delete_table'] = 'Etes-vous sûr de vouloir supprimer cette table ?';
$lang['db_confirm_truncate_table'] = 'Etes-vous sûr de vouloir vider cette table ?';
$lang['db_confirm_delete_entry'] = 'Etes-vous sûr de vouloir supprimer cette entrée ?';
$lang['db_file_does_not_exist'] = 'Le fichier que vous souhaitez supprimer n\'existe pas ou n\'est pas un fichier SQL';
$lang['db_empty_dir'] = 'Le dossier est vide';
$lang['db_file_name'] = 'Nom du fichier';
$lang['db_file_weight'] = 'Taille du fichier';

?>
