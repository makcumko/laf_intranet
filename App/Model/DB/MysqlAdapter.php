<?php

namespace App\Model\DB;

// TODO: use mysqli

class MysqlAdapter extends AbstractDBAdapter
{
    public function __construct(\App\Model\Data\DatabaseData $config)
    {
        $this->config = $config;
    }

    public function __destruct() {
        if (isset($this->connection)) mysql_close($this->connection);
    }

    public function getConnection()
    {
        if (!isset($this->connection))
        {
//            echo "getting connection";
//            var_dump($this->config);
            try {
                $this->connection = mysql_connect($this->config['host'], $this->config['user'], $this->config['password'], true);
                mysql_select_db($this->config['db'], $this->connection);
                mysql_query("SET NAMES utf8", $this->connection);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage(), \App\Model\Errors::ERR_DB_CONNECT);
            }
        }
    }

    public function query($sql, $params = null, $fetchType = self::FETCH_ASSOC)
    {
        $this->getConnection();

        try {
            $sql = $this->prepareParams($sql, $params);

            $this->resource = mysql_query($sql, $this->connection);

            if ($err = mysql_error($this->connection))
                throw new \Exception($err, mysql_errno($this->connection));
        } catch (\Exception $e) {
//            print_r($this->config);
//            echo "MYSQL ERROR OLOLO ".$e->getMessage();
//            debug_print_backtrace(0, 4);
            throw new \Exception($e->getMessage(), \App\Model\Errors::ERR_DB_QUERY);
        }

        if ($fetchType == self::FETCH_ASSOC) return $this->fetchAssoc();
        elseif ($fetchType == self::FETCH_LINE) return $this->fetchLine();
        elseif ($fetchType == self::FETCH_VALUE) return $this->fetchValue();
    }

    public function fetchAssoc() {
        $result = [];
        if (empty($this->resource)) {
            throw new \Exception("Cant fetch query result, resourse type invalid", \App\Model\Errors::ERR_DB_BAD_RESOURSE);
        }
        if (is_resource($this->resource)) {
            while ($row = mysql_fetch_assoc($this->resource)) {
                $result[] = $row;
            }
        }
        return $result;
    }

    public function fetchLine() {
        if (empty($this->resource)) {
            throw new \Exception("Cant fetch query result, resourse type invalid", \App\Model\Errors::ERR_DB_BAD_RESOURSE);
        }
        if (mysql_num_rows($this->resource) > 0) return mysql_fetch_assoc($this->resource);
        else return [];
    }

    public function fetchValue() {
        if (empty($this->resource)) {
            throw new \Exception("Cant fetch query result, resourse type invalid", \App\Model\Errors::ERR_DB_BAD_RESOURSE);
        }
        if (mysql_num_rows($this->resource) > 0) return current(mysql_fetch_array($this->resource));
        else return NULL;
    }

    public function lastInsertId() {
        return mysql_insert_id($this->connection);
    }

    public function affectedRows() {
        return mysql_affected_rows($this->connection);
    }

    public function escape($var, $isFieldName = false)
    {
        $this->getConnection();

        if (is_int($var) || is_float($var)) return $var;
        elseif (!$isFieldName && ($var === 'null' || $var === 'NULL' || $var === null)) return 'NULL';
        elseif (!$isFieldName && is_bool($var) )
        {
            if ($var === true || $var === 'true') return "TRUE";
            if ($var === false || $var === 'false') return "FALSE";
        }
        else
        {
            return ($isFieldName) ? mysql_real_escape_string(trim($var)) : "'". mysql_real_escape_string(trim($var)) ."'";
        }
        return $var;
    }

}
