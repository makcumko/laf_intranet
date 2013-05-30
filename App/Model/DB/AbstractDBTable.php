<?php
namespace App\Model\DB;

abstract class AbstractDBTable
{
    protected $tableName;

    protected $dbcode = "lafintranet";
    /** @var AbstractDBAdapter */
    protected $db;
    protected $user;
    /** @var \App\Model\Request */
    protected $request;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $this->db = \App\Model\Registry::Get($this->dbcode);
        $this->user = \App\Model\Registry::Get("user");
        $this->request = \App\Model\Registry::Get("request");
    }

    /**
     * Получаем одну запись
     */
    public function read($id) {
        $sql = "SELECT * FROM ".$this->db->escape($this->tableName, true)." WHERE id=:0:";
        return $this->db->query($sql, $id, AbstractDBAdapter::FETCH_LINE);
    }

    /**
     * Вставляет новую строку в таблицу
     */
    public function insert(Array $params) {
        if (!isset($params['ctime'])) $params['ctime'] = date("Y-m-d H:i:s");
        if (!isset($params['atime'])) $params['atime'] = date("Y-m-d H:i:s");

        //Сборка запроса
        $fields = $values = [];
        foreach($params as $field => $value)
        {
            $fields[] = $this->db->escape($field, true);
            $values[] = $this->db->escape($value);
        };

        //Запуск запроса
        $sql = "INSERT INTO ".$this->db->escape($this->tableName, true)."
				(".implode(", ", $fields).")
				VALUES (".implode(", ", $values).")";

        $this->db->query($sql, [], AbstractDBAdapter::FETCH_NONE);
        return $this->db->lastInsertId();
    }


    // Апдейт записей
    public function update($id, array $params)
    {
        if (!isset($params['atime'])) $params['atime'] = date("Y-m-d H:i:s");

        // Подготовка запроса
        $set = [];
        foreach($params as $cell => $value)
        {
            $set[] = $this->db->escape($cell, true).' = '.$this->db->escape($value);
        }

        // Формирование запроса
        $sql = "UPDATE ".$this->db->escape($this->tableName, true)."
                SET ".implode(", ", $set)."
                WHERE id = ".$this->db->escape($id);

        // Запуск запроса
        $this->db->query($sql, [], AbstractDBAdapter::FETCH_NONE);

        return $this->db->affectedRows();
    }

    public function delete($id)
    {
        return $this->update($id, ['flag_deleted' => 1]);
    }


    public function filter(Array $params, $order = null) {
        $where = ["flag_deleted = 0"];
        $orderby = [];

        foreach($params as $cell => $value) {
            $where[] = $this->db->escape($cell, true).' = '.$this->db->escape($value);
        }

        if ($order) {
            if (!is_array($order)) $order = [$order => "ASC"];

            foreach ($order as $field => $dir) $orderby[] = $this->db->escape($field, true)." ".(strtolower($dir) == "desc" ? "DESC" : "ASC");
        }

        $sql = "SELECT *
                FROM ".$this->db->escape($this->tableName, true)."
                WHERE ".implode(" AND ", $where);
        if (!empty($orderby)) $sql .= " ORDER BY ".implode(", ", $orderby);

        return $this->db->query($sql, [], AbstractDBAdapter::FETCH_ASSOC);
    }

    public function filterPaged(Array $params, $order = null, $limit = 20, $offset = 0) {
        $where = ["flag_deleted = 0"];
        $orderby = [];

        foreach($params as $cell => $value) {
            $where[] = $this->db->escape($cell, true).' = '.$this->db->escape($value);
        }

        if ($order) {
            if (!is_array($order)) $order = [$order => "ASC"];

            foreach ($order as $field => $dir) $orderby[] = $this->db->escape($field, true)." ".(strtolower($dir) == "desc" ? "DESC" : "ASC");
        }

        $sql = "SELECT *
                FROM ".$this->db->escape($this->tableName, true)."
                WHERE ".implode(" AND ", $where);
        if (!empty($orderby)) $sql .= " ORDER BY ".implode(", ", $orderby);

        if ($limit) $sql .= " LIMIT ".intval($limit);
        if ($offset) $sql .= " OFFSET ".intval($offset);

        $items = $this->db->query($sql, [], AbstractDBAdapter::FETCH_ASSOC);

        $sql = "SELECT COUNT(*)
                FROM ".$this->db->escape($this->tableName, true)."
                WHERE ".implode(" AND ", $where);
        $total = $this->db->query($sql, [], AbstractDBAdapter::FETCH_VALUE);

        return [
            'items' => $items,
            'count' => sizeof($items),
            'total' => $total,
            'pages' => ceil($total / $limit),
            'currentPage' => ceil($offset / sizeof($items)) + 1
        ];
    }


}
