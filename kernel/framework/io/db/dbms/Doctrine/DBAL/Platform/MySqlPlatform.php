<?php
/**
 * The MySqlPlatform provides the behavior, features and SQL dialect of the
 * MySQL database platform. This platform represents a MySQL 5.0 or greater platform that
 * uses the InnoDB storage engine.
 * @package     Doctrine
 * @subpackage  DBAL\Plateform
 * @license     https://www.gnu.org/licenses/lgpl-2.1.fr.html LGPL 2.1
 * @link        https://www.doctrine-project.org
 * @author      Roman BORSCHEL <roman@code-factory.org>
 * @version     PHPBoost 6.0 - last update: 2018 08 27
 * @since       PHPBoost 4.0 - 2013 01 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

/**
 *
 * @since 2.0
 * @author Roman Borschel <roman@code-factory.org>
 */
class MySqlPlatform extends AbstractPlatform
{
    /**
     * Creates a new MySqlPlatform instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the character used for identifier quoting.
     *
     * @return string
     * @override
     */
    public function getIdentifierQuoteCharacter()
    {
        return '`';
    }

    /**
     * Returns the regular expression operator.
     *
     * @return string
     * @override
     */
    public function getRegexpExpression()
    {
        return 'RLIKE';
    }

    /**
     * return string to call a function to get random value inside an SQL statement
     *
     * @return string to generate float between 0 and 1
     */
    public function getRandomExpression()
    {
        return 'RAND()';
    }

    /**
     * Builds a pattern matching string.
     *
     * EXPERIMENTAL
     *
     * WARNING: this function is experimental and may change signature at
     * any time until labelled as non-experimental.
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
                    $match = $field.'LIKE ';
                    break;
                // case sensitive
                case 'LIKE':
                    $match = $field.'LIKE BINARY ';
                    break;
                default:
                    throw DoctrineException::operatorNotSupported($operator);
            }
        }
        $match.= "'";
        foreach ($pattern as $key => $value) {
            if ($key % 2) {
                $match .= $value;
            } else {
                $match .= $this->conn->escapePattern($this->conn->escape($value));
            }
        }
        $match.= "'";
        $match.= $this->patternEscapeString();

        return $match;
    }

    /**
     * Returns global unique identifier
     *
     * @return string to get global unique identifier
     * @override
     */
    public function getGuidExpression()
    {
        return 'UUID()';
    }

    /**
     * Returns a series of strings concatinated
     *
     * concat() accepts an arbitrary number of parameters. Each parameter
     * must contain an expression or an array with expressions.
     *
     * @param string|array(string) strings that will be concatinated.
     * @override
     */
    public function getConcatExpression()
    {
        $args = func_get_args();
        return 'CONCAT(' . join(', ', (array) $args) . ')';
    }

    public function getListDatabasesSql()
    {
        return 'SHOW DATABASES';
    }

    public function getListSequencesSql($database)
    {
        $query = 'SHOW TABLES';
        if ( ! is_null($database)) {
            $query .= ' FROM ' . $database;
        }
        return $query;
    }

    public function getListTableConstraintsSql($table)
    {
        return 'SHOW INDEX FROM ' . $table;
    }

    public function getListTableIndexesSql($table)
    {
        return 'SHOW INDEX FROM ' . $table;
    }

    public function getListUsersSql()
    {
        return "SELECT * FROM mysql.user WHERE user != '' GROUP BY user";
    }

    public function getListViewsSql($database = null)
    {
        if (is_null($database)) {
            return 'SELECT * FROM information_schema.VIEWS';
        } else {
            return "SHOW FULL TABLES FROM " . $database . " WHERE Table_type = 'VIEW'";
        }
    }

    public function getListTableForeignKeysSql($table, $database = null)
    {
        $sql = "SELECT column_name, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.key_column_usage WHERE table_name = '" . $table . "'";

        if ( ! is_null($database)) {
            $sql .= " AND table_schema = '$database'";
        }

        $sql .= " AND REFERENCED_COLUMN_NAME is not NULL";

        return $sql;
    }

    public function getCreateViewSql($name, $sql)
    {
        return 'CREATE VIEW ' . $name . ' AS ' . $sql;
    }

    public function getDropViewSql($name)
    {
        return 'DROP VIEW '. $name;
    }

    /**
     * Gets the SQL snippet used to declare a VARCHAR column on the MySql platform.
     *
     * @params array $field
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
                : ($length ? 'VARCHAR(' . $length . ')' : 'VARCHAR(255)');
    }

    /** @override */
    public function getClobTypeDeclarationSql(array $field)
    {
        if ( ! empty($field['length'])) {
            $length = $field['length'];
            if ($length <= 255) {
                return 'TINYTEXT';
            } else if ($length <= 65532) {
                return 'TEXT';
            } else if ($length <= 16777215) {
                return 'MEDIUMTEXT';
            }
        }
        return 'LONGTEXT';
    }

    /**
     * Obtain DBMS specific SQL code portion needed to set the CHARACTER SET
     * of a field declaration to be used in statements like CREATE TABLE.
     *
     * @param string $charset   name of the charset
     * @return string  DBMS specific SQL code portion needed to set the CHARACTER SET
     *                 of a field declaration.
     */
    public function getCharsetFieldDeclaration($charset)
    {
        return 'CHARACTER SET ' . $charset;
    }

    /**
     * @override
     */
    public function getDateTimeTypeDeclarationSql(array $fieldDeclaration)
    {
        if ($fieldDeclaration['version']) {
            return 'TIMESTAMP';
        } else {
            return 'DATETIME';
        }
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
    public function getBooleanTypeDeclarationSql(array $field)
    {
        return 'TINYINT(1)';
    }

    /**
     * Obtain DBMS specific SQL code portion needed to set the COLLATION
     * of a field declaration to be used in statements like CREATE TABLE.
     *
     * @param string $collation   name of the collation
     * @return string  DBMS specific SQL code portion needed to set the COLLATION
     *                 of a field declaration.
     */
    public function getCollationFieldDeclaration($collation)
    {
        return 'COLLATE ' . $collation;
    }

    /**
     * Whether the platform prefers identity columns for ID generation.
     * MySql prefers "autoincrement" identity columns since sequences can only
     * be emulated with a table.
     *
     * @return boolean
     * @override
     */
    public function prefersIdentityColumns()
    {
        return true;
    }

    /**
     * Whether the platform supports identity columns.
     * MySql supports this through AUTO_INCREMENT columns.
     *
     * @return boolean
     * @override
     */
    public function supportsIdentityColumns()
    {
        return true;
    }

    /**
     * Whether the platform supports savepoints. MySql does not.
     *
     * @return boolean
     * @override
     */
    public function supportsSavepoints()
    {
        return false;
    }

    public function getShowDatabasesSql()
    {
        return 'SHOW DATABASES';
    }

    public function getListTablesSql()
    {
        return 'SHOW TABLES';
    }

    public function getListTableColumnsSql($table)
    {
        return 'DESCRIBE ' . $table;
    }

    /**
     * create a new database
     *
     * @param string $name name of the database that should be created
     * @return string
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
     * @return string
     * @override
     */
    public function getDropDatabaseSql($name)
    {
        return 'DROP DATABASE ' . $name;
    }

    /**
     * create a new table
     *
     * @param string $name   Name of the database that should be created
     * @param array $fields  Associative array that contains the definition of each field of the new table
     *                       The indexes of the array entries are the names of the fields of the table an
     *                       the array entry values are associative arrays like those that are meant to be
     *                       passed with the field definitions to get[Type]Declaration() functions.
     *                          array(
     *                              'id' => array(
     *                                  'type' => 'integer',
     *                                  'unsigned' => 1
     *                                  'notnull' => 1
     *                                  'default' => 0
     *                              ),
     *                              'name' => array(
     *                                  'type' => 'text',
     *                                  'length' => 12
     *                              ),
     *                              'password' => array(
     *                                  'type' => 'text',
     *                                  'length' => 12
     *                              )
     *                          );
     * @param array $options  An associative array of table options:
     *                          array(
     *                              'comment' => 'Foo',
     *                              'charset' => 'utf8',
     *                              'collate' => 'utf8_unicode_ci',
     *                              'type'    => 'innodb',
     *                          );
     *
     * @return void
     * @override
     */
    public function getCreateTableSql($name, array $fields, array $options = array())
    {
        if ( ! $name) {
            throw DoctrineException::missingTableName();
        }
        if (empty($fields)) {
            throw DoctrineException::missingFieldsArrayForTable($name);
        }
        $queryFields = $this->getColumnDeclarationListSql($fields);

        if (isset($options['uniqueConstraints']) && ! empty($options['uniqueConstraints'])) {
            foreach ($options['uniqueConstraints'] as $uniqueConstraint) {
                $queryFields .= ', UNIQUE(' . implode(', ', array_values($uniqueConstraint)) . ')';
            }
        }

        // add all indexes
        if (isset($options['indexes']) && ! empty($options['indexes'])) {
            foreach($options['indexes'] as $index => $definition) {
                $queryFields .= ', ' . $this->getIndexDeclarationSql($index, $definition);
            }
        }

        // attach all primary keys
        if (isset($options['primary']) && ! empty($options['primary'])) {
            $keyColumns = array_unique(array_values($options['primary']));
            $queryFields .= ', PRIMARY KEY(' . implode(', ', $keyColumns) . ')';
        }

        $query = 'CREATE ';
        if (!empty($options['temporary'])) {
            $query .= 'TEMPORARY ';
        }
        $query.= 'TABLE ' . $name . ' (' . $queryFields . ')';

        $optionStrings = array();

        if (isset($options['comment'])) {
            $optionStrings['comment'] = 'COMMENT = ' . $this->quote($options['comment'], 'text');
        }
        if (isset($options['charset'])) {
            $optionStrings['charset'] = 'DEFAULT CHARACTER SET ' . $options['charset'];
            if (isset($options['collate'])) {
                $optionStrings['charset'] .= ' COLLATE ' . $options['collate'];
            }
        }

        // get the type of the table
        if (isset($options['engine'])) {
            $optionStrings[] = 'ENGINE = ' . $options['engine'];
        } else {
            // default to innodb
//            $optionStrings[] = 'ENGINE = InnoDB';
            $optionStrings[] = 'ENGINE = MyISAM';
        }

        if ( ! empty($optionStrings)) {
            $query.= ' '.implode(' ', $optionStrings);
        }
        $sql[] = $query;

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
	 * Obtain DBMS specific SQL code portion needed to declare a generic type
	 * field to be used in statements like CREATE TABLE.
	 *
	 * @param string $name   name the field to be declared.
	 * @param array  $field  associative array with the name of the properties
	 *      of the field being declared as array indexes. Currently, the types
	 *      of supported field properties are as follows:
	 *
	 *      length
	 *          Integer value that determines the maximum length of the text
	 *          field. If this argument is missing the field should be
	 *          declared to have the longest length allowed by the DBMS.
	 *
	 *      default
	 *          Text value to be used as default for this field.
	 *
	 *      notnull
	 *          Boolean flag that indicates whether this field is constrained
	 *          to not be set to null.
	 *      charset
	 *          Text value with the default CHARACTER SET for this field.
	 *      collation
	 *          Text value with the default COLLATION for this field.
	 *      unique
	 *          unique constraint
	 *      check
	 *          column check constraint
	 *
	 * @return string  DBMS specific SQL code portion that should be used to declare the column.
	 */
	public function getColumnDeclarationSql($name, array $field)
	{
		$default = $this->getDefaultValueDeclarationSql($field);

		$charset = (isset($field['charset']) && $field['charset']) ?
                ' ' . $this->getColumnCharsetDeclarationSql($field['charset']) : '';

		$collation = (isset($field['collation']) && $field['collation']) ?
                ' ' . $this->getColumnCollationDeclarationSql($field['collation']) : '';

		$notnull = (isset($field['notnull']) && $field['notnull']) ? ' NOT NULL' : '';

		$unique = (isset($field['unique']) && $field['unique']) ?
                ' ' . $this->getUniqueFieldDeclarationSql() : '';

		$check = (isset($field['check']) && $field['check']) ?
                ' ' . $field['check'] : '';

		$typeDecl = Type::getType($field['type'])->getSqlDeclaration($field, $this);

		return '`' . $name . '` ' . $typeDecl . $charset . $default . $notnull . $unique . $check . $collation;
	}

    /**
     * Gets the SQL to alter an existing table.
     *
     * @param string $name The name of the table that is intended to be changed.
     * @param array $changes Associative array that contains the details of each type
     *                             of change that is intended to be performed. The types of
     *                             changes that are currently supported are defined as follows:
     *
     *                             name
     *
     *                                New name for the table.
     *
     *                            add
     *
     *                                Associative array with the names of fields to be added as
     *                                 indexes of the array. The value of each entry of the array
     *                                 should be set to another associative array with the properties
     *                                 of the fields to be added. The properties of the fields should
     *                                 be the same as defined by the Metabase parser.
     *
     *
     *                            remove
     *
     *                                Associative array with the names of fields to be removed as indexes
     *                                 of the array. Currently the values assigned to each entry are ignored.
     *                                 An empty array should be used for future compatibility.
     *
     *                            rename
     *
     *                                Associative array with the names of fields to be renamed as indexes
     *                                 of the array. The value of each entry of the array should be set to
     *                                 another associative array with the entry named name with the new
     *                                 field name and the entry named Declaration that is expected to contain
     *                                 the portion of the field declaration already in DBMS specific SQL code
     *                                 as it is used in the CREATE TABLE statement.
     *
     *                            change
     *
     *                                Associative array with the names of the fields to be changed as indexes
     *                                 of the array. Keep in mind that if it is intended to change either the
     *                                 name of a field and any other properties, the change array entries
     *                                 should have the new names of the fields as array indexes.
     *
     *                                The value of each entry of the array should be set to another associative
     *                                 array with the properties of the fields to that are meant to be changed as
     *                                 array entries. These entries should be assigned to the new values of the
     *                                 respective properties. The properties of the fields should be the same
     *                                 as defined by the Metabase parser.
     *
     *                            Example
     *                                array(
     *                                    'name' => 'userlist',
     *                                    'add' => array(
     *                                        'quota' => array(
     *                                            'type' => 'integer',
     *                                            'unsigned' => 1
     *                                        )
     *                                    ),
     *                                    'remove' => array(
     *                                        'file_limit' => array(),
     *                                        'time_limit' => array()
     *                                    ),
     *                                    'change' => array(
     *                                        'name' => array(
     *                                            'length' => '20',
     *                                            'definition' => array(
     *                                                'type' => 'text',
     *                                                'length' => 20,
     *                                            ),
     *                                        )
     *                                    ),
     *                                    'rename' => array(
     *                                        'sex' => array(
     *                                            'name' => 'gender',
     *                                            'definition' => array(
     *                                                'type' => 'text',
     *                                                'length' => 1,
     *                                                'default' => 'M',
     *                                            ),
     *                                        )
     *                                    )
     *                                )
     *
     * @param boolean $check     indicates whether the function should just check if the DBMS driver
     *                           can perform the requested table alterations if the value is true or
     *                           actually perform them otherwise.
     * @return boolean
     * @override
     */
    public function getAlterTableSql($name, array $changes, $check = false)
    {
        if ( ! $name) {
            throw DoctrineException::missingTableName();
        }

        foreach ($changes as $changeName => $change) {
            switch ($changeName) {
                case 'add':
                case 'remove':
                case 'change':
                case 'rename':
                case 'name':
                    break;
                default:
                    throw DoctrineException::alterTableChangeNotSupported($changeName);
            }
        }

        if ($check) {
            return true;
        }

        $query = '';
        if ( ! empty($changes['name'])) {
            $query .= 'RENAME TO ' . $changes['name'];
        }

        if ( ! empty($changes['add']) && is_array($changes['add'])) {
            foreach ($changes['add'] as $fieldName => $field) {
                if ($query) {
                    $query.= ', ';
                }
                $query.= 'ADD ' . $this->getColumnDeclarationSql($fieldName, $field);
            }
        }

        if ( ! empty($changes['remove']) && is_array($changes['remove'])) {
            foreach ($changes['remove'] as $fieldName => $field) {
                if ($query) {
                    $query .= ', ';
                }
                $query .= 'DROP ' . $fieldName;
            }
        }

        $rename = array();
        if ( ! empty($changes['rename']) && is_array($changes['rename'])) {
            foreach ($changes['rename'] as $fieldName => $field) {
                $rename[$field['name']] = $fieldName;
            }
        }

        if ( ! empty($changes['change']) && is_array($changes['change'])) {
            foreach ($changes['change'] as $fieldName => $field) {
                if ($query) {
                    $query.= ', ';
                }
                if (isset($rename[$fieldName])) {
                    $oldFieldName = $rename[$fieldName];
                    unset($rename[$fieldName]);
                } else {
                    $oldFieldName = $fieldName;
                }
                $query .= 'CHANGE ' . $oldFieldName . ' '
                        . $this->getColumnDeclarationSql($fieldName, $field['definition']);
            }
        }

        if ( ! empty($rename) && is_array($rename)) {
            foreach ($rename as $renameName => $renamedField) {
                if ($query) {
                    $query.= ', ';
                }
                $field = $changes['rename'][$renamedField];
                $query .= 'CHANGE ' . $renamedField . ' '
                        . $this->getColumnDeclarationSql($field['name'], $field['definition']);
            }
        }

        if ( ! $query) {
            return false;
        }

        return 'ALTER TABLE ' . $name . ' ' . $query;
    }

    /**
     * Obtain DBMS specific SQL code portion needed to declare an integer type
     * field to be used in statements like CREATE TABLE.
     *
     * @param string  $name   name the field to be declared.
     * @param string  $field  associative array with the name of the properties
     *                        of the field being declared as array indexes.
     *                        Currently, the types of supported field
     *                        properties are as follows:
     *
     *                       unsigned
     *                        Boolean flag that indicates whether the field
     *                        should be declared as unsigned integer if
     *                        possible.
     *
     *                       default
     *                        Integer value to be used as default for this
     *                        field.
     *
     *                       notnull
     *                        Boolean flag that indicates whether this field is
     *                        constrained to not be set to null.
     * @return string  DBMS specific SQL code portion that should be used to
     *                 declare the specified field.
     * @override
     */
    public function getIntegerTypeDeclarationSql(array $field)
    {
        return 'INT' . $this->_getCommonIntegerTypeDeclarationSql($field);
    }

    /** @override */
    public function getBigIntTypeDeclarationSql(array $field)
    {
        return 'BIGINT' . $this->_getCommonIntegerTypeDeclarationSql($field);
    }

    /** @override */
    public function getSmallIntTypeDeclarationSql(array $field)
    {
        return 'SMALLINT' . $this->_getCommonIntegerTypeDeclarationSql($field);
    }

    /** @override */
    protected function _getCommonIntegerTypeDeclarationSql(array $columnDef)
    {
        $length = '';
        if (isset($columnDef['length'])) {
            $length = '(' . $columnDef['length'] . ')';
        }
        $autoinc = '';
        if ( ! empty($columnDef['autoincrement'])) {
            $autoinc = ' AUTO_INCREMENT';
        }
        $unsigned = (isset($columnDef['unsigned']) && $columnDef['unsigned']) ? ' UNSIGNED' : '';

        return $length . $unsigned . $autoinc;
    }

    /**
     * Obtain DBMS specific SQL code portion needed to set an index
     * declaration to be used in statements like CREATE TABLE.
     *
     * @param string $charset       name of the index
     * @param array $definition     index definition
     * @return string  DBMS specific SQL code portion needed to set an index
     * @pacthed by PHPBoost
     * @override
     */
    public function getIndexDeclarationSql($name, array $definition)
    {
        $type = '';
        if (isset($definition['type'])) {
            switch (TextHelper::strtolower($definition['type'])) {
                case 'fulltext':
                    $type = 'FULLTEXT KEY';
                    break;
                case 'unique':
                    $type = 'UNIQUE INDEX';
                    break;
                case 'key':
                    $type = 'KEY';
                    break;
                default:
                    throw DoctrineException::invalidIndexType($definition['type']);
            }
        }

        if ( ! isset($definition['fields'])) {
            throw DoctrineException::indexFieldsArrayRequired();
        }
        if ( ! is_array($definition['fields'])) {
            $definition['fields'] = array($definition['fields']);
        }

        $query = $type . ' `' . $name . '`';

        $query .= ' (' . $this->getIndexFieldDeclarationListSql($definition['fields']) . ')';

        return $query;
    }

    /**
     * Obtain DBMS specific SQL code portion needed to set an index
     * declaration to be used in statements like CREATE TABLE.
     *
     * @return string
     * @override
     */
    public function getIndexFieldDeclarationListSql(array $fields)
    {
        $declFields = array();

        foreach ($fields as $fieldName => $field) {
            $fieldString = '`' . $fieldName . '`';

            if (is_array($field)) {
                if (isset($field['length'])) {
                    $fieldString .= '(' . $field['length'] . ')';
                }

                if (isset($field['sorting'])) {
                    $sort = TextHelper::strtoupper($field['sorting']);
                    switch ($sort) {
                        case 'ASC':
                        case 'DESC':
                            $fieldString .= ' ' . $sort;
                            break;
                        default:
                            throw DoctrineException::unknownIndexSortingOption($sort);
                    }
                }
            } else {
                $fieldString = '`' . $field . '`';
            }
            $declFields[] = $fieldString;
        }
        return implode(', ', $declFields);
    }

    /**
     * Return the FOREIGN KEY query section dealing with non-standard options
     * as MATCH, INITIALLY DEFERRED, ON UPDATE, ...
     *
     * @param array $definition
     * @return string
     * @override
     */
    public function getAdvancedForeignKeyOptionsSql(array $definition)
    {
        $query = '';
        if ( ! empty($definition['match'])) {
            $query .= ' MATCH ' . $definition['match'];
        }
        if ( ! empty($definition['onUpdate'])) {
            $query .= ' ON UPDATE ' . $this->getForeignKeyReferentialActionSql($definition['onUpdate']);
        }
        if ( ! empty($definition['onDelete'])) {
            $query .= ' ON DELETE ' . $this->getForeignKeyReferentialActionSql($definition['onDelete']);
        }
        return $query;
    }

    /**
     * Gets the SQL to drop an index of a table.
     *
     * @param string    $table          name of table that should be used in method
     * @param string    $name           name of the index to be dropped
     * @override
     */
    public function getDropIndexSql($table, $name)
    {
        return 'DROP INDEX ' . $name . ' ON ' . $table;
    }

    /**
     * Gets the SQL to drop a table.
     *
     * @param string $table The name of table to drop.
     * @override
     */
    public function getDropTableSql($table)
    {
        return 'DROP TABLE ' . $table;
    }

    public function getSetTransactionIsolationSql($level)
    {
        return 'SET SESSION TRANSACTION ISOLATION LEVEL ' . $this->_getTransactionIsolationLevelSql($level);
    }

    /**
     * Get the platform name for this instance.
     *
     * @return string
     */
    public function getName()
    {
        return 'mysql';
    }
}
