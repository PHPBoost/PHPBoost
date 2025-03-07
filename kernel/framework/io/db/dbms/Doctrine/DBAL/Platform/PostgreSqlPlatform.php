<?php
/**
 * PostgreSqlPlatform.
 * @package     Doctrine
 * @subpackage  DBAL\Plateform
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @author      Roman BORSCHEL <roman@code-factory.org>
 * @author      Lukas Smith <smith@pooteeweet.org> (PEAR MDB2 library)
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 4.0 - 2013 01 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PostgreSqlPlatform extends AbstractPlatform
{
	/**
	 * Constructor.
	 * Creates a new PostgreSqlPlatform.
	 */
	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * Returns the md5 sum of a field.
	 *
	 * Note: Not SQL92, but common functionality
	 *
	 * md5() works with the default PostgreSQL 8 versions.
	 *
	 * If you are using PostgreSQL 7.x or older you need
	 * to make sure that the digest procedure is installed.
	 * If you use RPMS (Redhat and Mandrake) install the postgresql-contrib
	 * package. You must then install the procedure by running this shell command:
	 * <code>
	 * psql [dbname] < /usr/share/pgsql/contrib/pgcrypto.sql
	 * </code>
	 * You should make sure you run this as the postgres user.
	 *
	 * @return string
	 * @override
	 */
	public function getMd5Expression($column)
	{
		if ($this->_version > 7) {
			return 'MD5(' . $column . ')';
		} else {
			return 'encode(digest(' . $column .', md5), hex)';
		}
	}

	/**
	 * Returns part of a string.
	 *
	 * Note: Not SQL92, but common functionality.
	 *
	 * @param string $value the target $value the string or the string column.
	 * @param int $from extract from this characeter.
	 * @param int $len extract this amount of characters.
	 * @return string sql that extracts part of a string.
	 * @override
	 */
	public function getSubstringExpression($value, $from, $len = null)
	{
		if ($len === null) {
			return 'SUBSTR(' . $value . ', ' . $from . ')';
		} else {
			return 'SUBSTR(' . $value . ', ' . $from . ', ' . $len . ')';
		}
	}

	/**
	 * PostgreSQLs AGE(<timestamp1> [, <timestamp2>]) function.
	 *
	 * @param string $timestamp1 timestamp to subtract from NOW()
	 * @param string $timestamp2 optional; if given: subtract arguments
	 * @return string
	 */
	public function getAgeExpression($timestamp1, $timestamp2 = null)
	{
		if ( $timestamp2 == null ) {
			return 'AGE(' . $timestamp1 . ')';
		}
		return 'AGE(' . $timestamp1 . ', ' . $timestamp2 . ')';
	}

	/**
	 * PostgreSQLs DATE_PART( <text>, <time> ) function.
	 *
	 * @param string $text what to extract
	 * @param string $time timestamp or interval to extract from
	 * @return string
	 */
	public function getDatePartExpression($text, $time)
	{
		return 'DATE_PART(' . $text . ', ' . $time . ')';
	}

	/**
	 * PostgreSQLs TO_CHAR( <time>, <text> ) function.
	 *
	 * @param string $time timestamp or interval
	 * @param string $text how to the format the output
	 * @return string
	 */
	public function getToCharExpression($time, $text)
	{
		return 'TO_CHAR(' . $time . ', ' . $text . ')';
	}

	/**
	 * Returns the SQL string to return the current system date and time.
	 *
	 * @return string
	 */
	public function getNowExpression()
	{
		return 'LOCALTIMESTAMP(0)';
	}

	/**
	 * regexp
	 *
	 * @return string           the regular expression operator
	 * @override
	 */
	public function getRegexpExpression()
	{
		return 'SIMILAR TO';
	}

	/**
	 * return string to call a function to get random value inside an SQL statement
	 *
	 * @return return string to generate float between 0 and 1
	 * @access public
	 * @override
	 */
	public function getRandomExpression()
	{
		return 'RANDOM()';
	}

	/**
	 * build a pattern matching string
	 *
	 * EXPERIMENTAL
	 *
	 * WARNING: this function is experimental and may change signature at
	 * any time until labelled as non-experimental
	 *
	 * @access public
	 *
	 * @param array $pattern even keys are strings, odd are patterns (% and _)
	 * @param string $operator optional pattern operator (LIKE, ILIKE and maybe others in the future)
	 * @param string $field optional field name that is being matched against
	 *                  (might be required when emulating ILIKE)
	 *
	 * @return string SQL pattern
	 * @override
	 */
	public function getMatchPatternExpression($pattern, $operator = null, $field = null)
	{
		$match = '';
		if ( ! is_null($operator)) {
			$field = is_null($field) ? '' : $field.' ';
			$operator = TextHelper::strtoupper($operator);
			switch ($operator) {
				// case insensitive
				case 'ILIKE':
					$match = $field.'ILIKE ';
					break;
					// case sensitive
				case 'LIKE':
					$match = $field.'LIKE ';
					break;
				default:
					throw DoctrineException::operatorNotSupported($operator);
			}
		}
		$match.= "'";
		foreach ($pattern as $key => $value) {
			if ($key % 2) {
				$match.= $value;
			} else {
				$match.= $this->conn->escapePattern($this->conn->escape($value));
			}
		}
		$match.= "'";
		$match.= $this->patternEscapeString();

		return $match;
	}

	/**
	 * parses a literal boolean value and returns
	 * proper sql equivalent
	 *
	 * @param string $value     boolean value to be parsed
	 * @return string           parsed boolean value
	 */
	/*public function parseBoolean($value)
	{
	return $value;
	}*/

	/**
	 * Whether the platform supports sequences.
	 * Postgres has native support for sequences.
	 *
	 * @return boolean
	 */
	public function supportsSequences()
	{
		return true;
	}

	/**
	 * Whether the platform supports database schemas.
	 *
	 * @return boolean
	 */
	public function supportsSchemas()
	{
		return true;
	}

	/**
	 * Whether the platform supports identity columns.
	 * Postgres supports these through the SERIAL keyword.
	 *
	 * @return boolean
	 */
	public function supportsIdentityColumns()
	{
		return true;
	}

	/**
	 * Whether the platform prefers sequences for ID generation.
	 *
	 * @return boolean
	 */
	public function prefersSequences()
	{
		return true;
	}

	public function getListDatabasesSql()
	{
		return 'SELECT datname FROM pg_database';
	}

	public function getListFunctionsSql()
	{
		return "SELECT
                    proname
                FROM
                    pg_proc pr, pg_type tp
                WHERE
                    tp.oid = pr.prorettype
                AND pr.proisagg = FALSE
                AND tp.typname <> 'trigger'
                AND pr.pronamespace IN
                    (SELECT oid FROM pg_namespace
                    WHERE nspname NOT LIKE 'pg_%' AND nspname != 'information_schema'";
	}

	public function getListSequencesSql($database)
	{
		return "SELECT
                    relname
                FROM
					pg_class
                WHERE relkind = 'S' AND relnamespace IN
                    (SELECT oid FROM pg_namespace
                        WHERE nspname NOT LIKE 'pg_%' AND nspname != 'information_schema')";
	}

	public function getListTablesSql()
	{
		return "SELECT
                    c.relname AS table_name
                FROM pg_class c, pg_user u
                WHERE c.relowner = u.usesysid
                    AND c.relkind = 'r'
                    AND NOT EXISTS (SELECT 1 FROM pg_views WHERE viewname = c.relname)
                    AND c.relname !~ '^(pg_|sql_)'
                UNION
                SELECT c.relname AS table_name
                FROM pg_class c
                WHERE c.relkind = 'r'
                    AND NOT EXISTS (SELECT 1 FROM pg_views WHERE viewname = c.relname)
                    AND NOT EXISTS (SELECT 1 FROM pg_user WHERE usesysid = c.relowner)
                    AND c.relname !~ '^pg_'";
	}

	public function getListViewsSql()
	{
		return 'SELECT viewname, definition FROM pg_views';
	}

	public function getListTriggersSql($table = null)
	{
		$sql = 'SELECT trg.tgname AS trigger_name
                    FROM pg_trigger trg,
						pg_class tbl
					WHERE trg.tgrelid = tbl.oid';

		if ( ! is_null($table)) {
			$sql .= " AND tbl.relname = " . $table;
		}

		return $sql;
	}

	public function getListUsersSql()
	{
		return 'SELECT usename, passwd FROM pg_user';
	}

	public function getListTableForeignKeysSql($table, $database = null)
	{
		return "SELECT pg_catalog.pg_get_constraintdef(oid, true) as condef
				FROM pg_catalog.pg_constraint r
				WHERE r.conrelid =
				(
					SELECT c.oid
					FROM pg_catalog.pg_class c
					LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
					WHERE c.relname = '" . $table . "' AND pg_catalog.pg_table_is_visible(c.oid)
				)
				AND r.contype = 'f'";
	}

	public function getCreateViewSql($name, $sql)
	{
		return 'CREATE VIEW ' . $name . ' AS ' . $sql;
	}

	public function getDropViewSql($name)
	{
		return 'DROP VIEW '. $name;
	}

	public function getListTableConstraintsSql($table)
	{
		return "SELECT
                    relname
                FROM
                    pg_class
                WHERE oid IN (
                    SELECT indexrelid
                    FROM pg_index, pg_class
                    WHERE pg_class.relname = '$table'
                        AND pg_class.oid = pg_index.indrelid
                        AND (indisunique = 't' OR indisprimary = 't')
                        )";
	}

	public function getListTableIndexesSql($table)
	{
		return "SELECT
                    relname,
                    (
                        SELECT indisunique
                        FROM pg_index
                        WHERE oid = indexrelid
                    ) as unique
                FROM
                    pg_class
                WHERE oid IN (
                    SELECT indexrelid
                    FROM pg_index, pg_class
                    WHERE pg_class.relname = '$table'
                        AND pg_class.oid=pg_index.indrelid
                        AND indisprimary != 't'
                )";
	}

	public function getListTableColumnsSql($table)
	{
		return "SELECT
                    a.attnum,
                    a.attname AS field,
                    t.typname AS type,
                    format_type(a.atttypid, a.atttypmod) AS complete_type,
                    a.attnotnull AS isnotnull,
                    (SELECT 't'
						FROM pg_index
						WHERE c.oid = pg_index.indrelid
                        AND pg_index.indkey[0] = a.attnum
                        AND pg_index.indisprimary = 't'
                    ) AS pri,
                    (SELECT pg_attrdef.adsrc
						FROM pg_attrdef
						WHERE c.oid = pg_attrdef.adrelid
                        AND pg_attrdef.adnum=a.attnum
                    ) AS default
                    FROM pg_attribute a, pg_class c, pg_type t
                    WHERE c.relname = '$table'
                        AND a.attnum > 0
                        AND a.attrelid = c.oid
                        AND a.atttypid = t.oid
                    ORDER BY a.attnum";
	}

	/**
	 * create a new database
	 *
	 * @param string $name name of the database that should be created
	 * @throws PDOException
	 * @return void
	 * @override
	 */
	public function getCreateDatabaseSql($name)
	{
		return 'CREATE DATABASE ' . $name;
	}

	/**
	 * drop an existing database
	 *
	 * @param string $name name of the database that should be dropped
	 * @throws PDOException
	 * @access public
	 */
	public function getDropDatabaseSql($name)
	{
		return 'DROP DATABASE ' . $name;
	}

	/**
	 * getAdvancedForeignKeyOptions
	 * Return the FOREIGN KEY query section dealing with non-standard options
	 * as MATCH, INITIALLY DEFERRED, ON UPDATE, ...
	 *
	 * @param array $definition         foreign key definition
	 * @return string
	 * @override
	 */
	public function getAdvancedForeignKeyOptionsSql(array $definition)
	{
		$query = '';
		if (isset($definition['match'])) {
			$query .= ' MATCH ' . $definition['match'];
		}
		if (isset($definition['onUpdate'])) {
			$query .= ' ON UPDATE ' . $definition['onUpdate'];
		}
		if (isset($definition['onDelete'])) {
			$query .= ' ON DELETE ' . $definition['onDelete'];
		}
		if (isset($definition['deferrable'])) {
			$query .= ' DEFERRABLE';
		} else {
			$query .= ' NOT DEFERRABLE';
		}
		if (isset($definition['feferred'])) {
			$query .= ' INITIALLY DEFERRED';
		} else {
			$query .= ' INITIALLY IMMEDIATE';
		}
		return $query;
	}

	/**
	 * generates the sql for altering an existing table on postgresql
	 *
	 * @param string $name          name of the table that is intended to be changed.
	 * @param array $changes        associative array that contains the details of each type      *
	 * @param boolean $check        indicates whether the function should just check if the DBMS driver
	 *                              can perform the requested table alterations if the value is true or
	 *                              actually perform them otherwise.
	 * @see Doctrine_Export::alterTable()
	 * @return array
	 * @override
	 */
	public function getAlterTableSql($name, array $changes, $check = false)
	{
		foreach ($changes as $changeName => $change) {
			switch ($changeName) {
				case 'add':
				case 'remove':
				case 'change':
				case 'name':
				case 'rename':
					break;
				default:
					throw DoctrineException::alterTableChangeNotSupported($changeName);
			}
		}

		if ($check) {
			return true;
		}

		$sql = array();

		if (isset($changes['add']) && is_array($changes['add'])) {
			foreach ($changes['add'] as $fieldName => $field) {
				$query = 'ADD ' . $this->getColumnDeclarationSql($fieldName, $field);
				$sql[] = 'ALTER TABLE ' . $name . ' ' . $query;
			}
		}

		if (isset($changes['remove']) && is_array($changes['remove'])) {
			foreach ($changes['remove'] as $fieldName => $field) {
				$query = 'DROP ' . $fieldName;
				$sql[] = 'ALTER TABLE ' . $name . ' ' . $query;
			}
		}

		if (isset($changes['change']) && is_array($changes['change'])) {
			foreach ($changes['change'] as $fieldName => $field) {
				if (isset($field['type'])) {
					$serverInfo = $this->getServerVersion();

					if (is_array($serverInfo) && $serverInfo['major'] < 8) {
						throw DoctrineException::changeColumnRequiresPostgreSQL8OrAbove($field['type']);
					}
					$query = 'ALTER ' . $fieldName . ' TYPE ' . $this->getTypeDeclarationSql($field['definition']);
					$sql[] = 'ALTER TABLE ' . $name . ' ' . $query;
				}
				if (array_key_exists('default', $field)) {
					$query = 'ALTER ' . $fieldName . ' SET DEFAULT ' . $this->quote($field['definition']['default'], $field['definition']['type']);
					$sql[] = 'ALTER TABLE ' . $name . ' ' . $query;
				}
				if ( ! empty($field['notnull'])) {
					$query = 'ALTER ' . $fieldName . ' ' . ($field['definition']['notnull'] ? 'SET' : 'DROP') . ' NOT NULL';
					$sql[] = 'ALTER TABLE ' . $name . ' ' . $query;
				}
			}
		}

		if (isset($changes['rename']) && is_array($changes['rename'])) {
			foreach ($changes['rename'] as $fieldName => $field) {
				$sql[] = 'ALTER TABLE ' . $name . ' RENAME COLUMN ' . $fieldName . ' TO ' . $field['name'];
			}
		}

		if (isset($changes['name'])) {
			$sql[] = 'ALTER TABLE ' . $name . ' RENAME TO ' . $changes['name'];
		}

		return $sql;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return string
	 * @override
	 */
	public function getCreateSequenceSql($sequenceName, $start = 1, $allocationSize = 1)
	{
		return 'CREATE SEQUENCE ' . $sequenceName
		. ' INCREMENT BY ' . $allocationSize . ' START ' . $start;
	}

	/**
	 * drop existing sequence
	 *
	 * @param string $sequenceName name of the sequence to be dropped
	 * @override
	 */
	public function getDropSequenceSql($sequenceName)
	{
		return 'DROP SEQUENCE ' . $sequenceName;
	}

	/**
	 * Gets the SQL used to create a table.
	 *
	 * @param unknown_type $name
	 * @param array $fields
	 * @param array $options
	 * @return unknown
	 */
	public function getCreateTableSql($name, array $fields, array $options = array())
	{
		/* @PATH BEGIN PHPBoost */
		$sql = array();
		if (isset($options['primary']) && count($options['primary']) === 1) {
			$pk_name = $options['primary'][0];
			if (isset($fields[$pk_name]) && isset($fields[$pk_name]['autoincrement']) &&
			$fields[$pk_name]['autoincrement']) {
				$seq_name = $name . '_pk_seq';
				$sql[] = 'CREATE SEQUENCE ' . $seq_name;
				$fields[$pk_name]['nextval'] = 'nextval(\'' . $seq_name . '\')';
				//CREATE TABLE tablename (
				//    colname integer DEFAULT nextval('tablename_colname_seq') NOT NULL
				//);
				$fields[$pk_name]['autoincrement'] = false;
			}
		}
		/* @PATH END PHPBoost */

		$queryFields = $this->getColumnDeclarationListSql($fields);

		if (isset($options['primary']) && ! empty($options['primary'])) {
			$keyColumns = array_unique(array_values($options['primary']));
			$queryFields .= ', PRIMARY KEY(' . implode(', ', $keyColumns) . ')';
		}

		$query = 'CREATE TABLE ' . $name . ' (' . $queryFields . ')';

		$sql[] = $query;

		if (isset($options['indexes']) && ! empty($options['indexes'])) {
			foreach($options['indexes'] as $index => $definition) {
				$sql[] = $this->getCreateIndexSql($name, $index, $definition);
			}
		}

		if (isset($options['foreignKeys'])) {
			foreach ((array) $options['foreignKeys'] as $k => $definition) {
				if (is_array($definition)) {
					$sql[] = $this->getCreateForeignKeySql($name, $definition);
				}
			}
		}

		return $sql;
	}

	/**
	 * Postgres wants boolean values converted to the strings 'true'/'false'.
	 *
	 * @param array $item
	 * @override
	 */
	public function convertBooleans($item)
	{
		if (is_array($item)) {
			foreach ($item as $key => $value) {
				if (is_bool($value) || is_numeric($item)) {
					$item[$key] = ($value) ? 'true' : 'false';
				}
			}
		} else {
			if (is_bool($item) || is_numeric($item)) {
				$item = ($item) ? 'true' : 'false';
			}
		}
		return $item;
	}

	public function getSequenceNextValSql($sequenceName)
	{
		return "SELECT NEXTVAL('" . $sequenceName . "')";
	}

	public function getSetTransactionIsolationSql($level)
	{
		return 'SET SESSION CHARACTERISTICS AS TRANSACTION ISOLATION LEVEL '
		. $this->_getTransactionIsolationLevelSql($level);
	}

	/**
	 * @override
	 */
	public function getBooleanTypeDeclarationSql(array $field)
	{
		return 'BOOLEAN';
	}

	/**
	 * @override
	 */
	public function getIntegerTypeDeclarationSql(array $field)
	{
		if ( ! empty($field['autoincrement'])) {
			return 'SERIAL';
		}
		// @PATCH PHPBoost
		elseif ( ! empty($field['nextval'])) {
			return 'integer DEFAULT ' . $field['nextval'];
		}
		// @PATCH END PHPBoost

		return 'INT';
	}

	// @PATCH PHPBoost
	/**
	* @override
	*/
	public function getDefaultValueDeclarationSql($field)
	{
		if ( ! empty($field['nextval'])) {
			return ' NOT NULL';
		} else {
			return parent::getDefaultValueDeclarationSql($field);
		}
	}
	// @PATCH END PHPBoost

	/**
	 * @override
	 */
	public function getBigIntTypeDeclarationSql(array $field)
	{
		if ( ! empty($field['autoincrement'])) {
			return 'BIGSERIAL';
		}
		return 'BIGINT';
	}

	/**
	 * @override
	 */
	public function getSmallIntTypeDeclarationSql(array $field)
	{
		return 'SMALLINT';
	}

	/**
	 * @override
	 */
	public function getDateTimeTypeDeclarationSql(array $fieldDeclaration)
	{
		return 'TIMESTAMP(0) WITH TIME ZONE';
	}

	/**
	 * @override
	 */
	public function getDateTypeDeclarationSql(array $fieldDeclaration)
	{
		return 'DATE';
	}

	/**
	 * @override
	 */
	protected function _getCommonIntegerTypeDeclarationSql(array $columnDef)
	{
		return '';
	}

	/**
	 * Gets the SQL snippet used to declare a VARCHAR column on the MySql platform.
	 *
	 * @params array $field
	 * @override
	 */
	public function getVarcharTypeDeclarationSql(array $field)
	{
		if ( ! isset($field['length'])) {
			if (array_key_exists('default', $field)) {
				$field['length'] = $this->getVarcharMaxLength();
			} else {
				$field['length'] = false;
			}
		}

		$length = ($field['length'] <= $this->getVarcharMaxLength()) ? $field['length'] : false;
		$fixed = (isset($field['fixed'])) ? $field['fixed'] : false;

		return $fixed ? ($length ? 'CHAR(' . $length . ')' : 'CHAR(255)')
		: ($length ? 'VARCHAR(' . $length . ')' : 'TEXT');
	}

	/** @override */
	public function getClobTypeDeclarationSql(array $field)
	{
		return 'TEXT';
	}

	/**
	 * Get the platform name for this instance
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'postgresql';
	}

	/**
	 * Gets the character casing of a column in an SQL result set.
	 *
	 * PostgreSQL returns all column names in SQL result sets in lowercase.
	 *
	 * @param string $column The column name for which to get the correct character casing.
	 * @return string The column name in the character casing used in SQL result sets.
	 */
	public function getSqlResultCasing($column)
	{
		return TextHelper::strtolower($column);
	}

	public function getDateTimeFormatString()
	{
		return 'Y-m-d H:i:sO';
	}

	/**
	 * Get the insert sql for an empty insert statement
	 *
	 * @param string $tableName
	 * @param string $identifierColumnName
	 * @return string $sql
	 */
	public function getEmptyIdentityInsertSql($quotedTableName, $quotedIdentifierColumnName)
	{
		return 'INSERT INTO ' . $quotedTableName . ' (' . $quotedIdentifierColumnName . ') VALUES (DEFAULT)';
	}
}
