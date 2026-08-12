<?php

/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Database configuration class.
 *
 * You can specify multiple configurations for production, development and testing.
 *
 * datasource => The name of a supported datasource; valid options are as follows:
 *  Database/Mysql - MySQL 4 & 5,
 *  Database/Sqlite - SQLite (PHP5 only),
 *  Database/Postgres - PostgreSQL 7 and higher,
 *  Database/Sqlserver - Microsoft SQL Server 2005 and higher
 *
 * You can add custom database datasources (or override existing datasources) by adding the
 * appropriate file to app/Model/Datasource/Database. Datasources should be named 'MyDatasource.php',
 *
 *
 * persistent => true / false
 * Determines whether or not the database should use a persistent connection
 *
 * host =>
 * the host you connect to the database. To add a socket or port number, use 'port' => #
 *
 * prefix =>
 * Uses the given prefix for all the tables in this database. This setting can be overridden
 * on a per-table basis with the Model::$tablePrefix property.
 *
 * schema =>
 * For Postgres/Sqlserver specifies which schema you would like to use the tables in.
 * Postgres defaults to 'public'. For Sqlserver, it defaults to empty and use
 * the connected user's default schema (typically 'dbo').
 *
 * encoding =>
 * For MySQL, Postgres specifies the character encoding to use when connecting to the
 * database. Uses database default not specified.
 *
 * unix_socket =>
 * For MySQL to connect via socket specify the `unix_socket` parameter instead of `host` and `port`
 *
 * settings =>
 * Array of key/value pairs, on connection it executes SET statements for each pair
 * For MySQL : http://dev.mysql.com/doc/refman/5.6/en/set-statement.html
 * For Postgres : http://www.postgresql.org/docs/9.2/static/sql-set.html
 * For Sql Server : http://msdn.microsoft.com/en-us/library/ms190356.aspx
 */
class DATABASE_CONFIG
{
    //v2
    public $local = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'samed',
        'encoding' => 'utf8'
    );

    public $local_proativa_robo = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'desenvol_proativa_robo',
        'encoding' => 'utf8'
    );



    public $pro_digihost = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'desenvol_admin',
        'password' => 'pnl8000',
        'database' => 'desenvol_samed',
        'encoding' => 'utf8'
    );

    public $pro = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'samed_pro.vpshost9932.mysql.dbaas.com.br',
        'login' => 'samed_pro',
        'password' => 'pnl8000@S',
        'database' => 'samed_pro',
        'encoding' => 'utf8'
    );

    public $pro_proativa_robo = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'desenvol_admin',
        'password' => 'pnl8000',
        'database' => 'desenvol_proativa_robo',
        'encoding' => 'utf8'
    );


    public $default = array();
    public $default_pro_robo = array();

    function __construct()
    {

        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $uri  = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        if ($host == '') {
            $this->default = $this->local;
            $this->default_pro_robo = $this->local_proativa_robo;
        } else {
            $localhost = explode(':', $_SERVER['HTTP_HOST']);

            if (in_array($localhost[0], array('localhost'))) {
                $this->default = $this->local;
                $this->default_pro_robo = $this->local_proativa_robo;
            } else {
                $this->default = $this->pro;
                $this->default_pro_robo = $this->pro_proativa_robo;
            }

            #krumo($this->default);
            #krumo($this->default_pro_robo);
            #MULTI CONEXÃO
            $arrRequest = explode('/', $_SERVER['REQUEST_URI']);

            $nameController = ($localhost[0] == 'localhost') ? @$arrRequest[3] : @$arrRequest[2]; //PEGA CONTROLLER
            $nameAction = ($localhost[0] == 'localhost') ? @$arrRequest[4] : @$arrRequest[3]; //PEGA CONTROLLER   

            if ($nameController == 'ws' && $nameAction == 'call_dw') {
                #$this->default = $this->pro_proativa;
            }
        }
    }
}
