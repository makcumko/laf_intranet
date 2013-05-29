<?php

namespace App\Model\DB;

abstract class AbstractDBAdapter
{
    protected $connection;
    protected $resource;

    const FETCH_NONE = 0;
    const FETCH_ASSOC = 1;
    const FETCH_LINE = 2;
    const FETCH_VALUE = 3;

    public function __construct(\App\Model\Data\DatabaseData $config)
    {
        $this->config = $config;
    }

    /* close connection here */
    public function __destruct() {
    }

    /* create connection here */
    abstract public function getConnection();

    /* simple query saving result to $resource */
    abstract public function query($sql, $params = null, $fetchType = self::FETCH_ASSOC);

    abstract public function fetchAssoc();

    abstract public function fetchLine();

    abstract public function fetchValue();

    abstract public function lastInsertId();

    abstract public function affectedRows();

    abstract public function escape($var, $isFieldName = false);

    public function prepareParams($sql, $params = null) {
        if (is_array($params) && empty($params)) return $sql;

        // if single value, convert to array and treat properly
        if (!is_array($params)) $params = [$params];

        foreach ($params as $field => $value) {
            $sql = str_replace(":{$field}:", $this->escape($value), $sql);
        }

        return $sql;
    }
}
