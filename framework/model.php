<?php

require_once '../framework/config.php';

class Model
{
    protected $tableName;
    public $fields=array();
    public $belongsTo=array();
    public $hasMany=array();

    private $link;

    public function __construct()
    {
        $this->link=mysql_connect(Config::SQL_SERVER, Config::SQL_USER, Config::SQL_PASSWORD) or die("Errore di connessione al db");
        mysql_select_db( Config::SQL_DATABASE, $this->link);
    }

    private function _getFields($allFields=false)
    {
        if ($allFields == true || count($this->fields)==0) {
            $fields = mysql_list_fields(Config::SQL_DATABASE, $this->tableName);
        } else {
            return $this->fields;
        }

        return $fields;
    }

    private function _fetchAllRows($res, $prKey)
    {
        while ($row=mysql_fetch_assoc($res)) {
            $result[$row[$prKey]] = $row;
        }

        return $result;
    }

    private function getPrimaryKey()
    {
        $resource=mysql_query("SHOW COLUMNS FROM " . $this->tableName) or die("Errore nella query");
         while ($row = mysql_fetch_assoc($resource)) {
            if ($row['Key'] == "PRI") {
                return $row['Field'];
            }
        }
    }

    private function _formatValueAsType($fieldValue, $fieldType='string')
    {
        //TODO realizzare corretta conversione tra tipi
        return "'$fieldValue'";
    }

    public function getLastID()
    {
        $id = mysql_fetch_row(mysql_query("SELECT LAST_INSERT_ID()", $this->link));

        return $id[0];
    }

    public function fetch($where='')
    {
        if (is_array($where)) {
            $whereStr = "";
            foreach ($where as $key => $condition) {
                $v = $condition['value'];
                if (!is_numeric($v)) {
                    $v = "'" . addslashes($v) . "'";
                }
                $whereStr .= $condition['column'] . "=" . $v . " AND ";
            }
            $whereStr = substr($whereStr, 0, -4);

            return $this->fetch($whereStr);
        }

        if (count($this->fields)==0) {
            $query= "SELECT * FROM $this->tableName ";
        } else {
            $query= toSeparatedString($this->fields, ", ", "SELECT ", " FROM $this->tableName ");
        }

        if ($where != '') {
            $query .= " WHERE $where ;";
        } else {
            $query .= ";";
        }

        $prKey = $this->getPrimaryKey();

        $resource=mysql_query($query) or die("Errore nella query");

        $rows = $this->_fetchAllRows($resource, $prKey);
        mysql_free_result($resource);

        $cycleDet = RelationCycleDetector::getInstance();

        if (!empty($this->belongsTo)) {
            $otherModelName = ucfirst($this->belongsTo['model']);
            $joinOn = $this->belongsTo['joinOn'];
            $className = $otherModelName . "Model";
            $cycle = $cycleDet->addModelAndCheck($className);
            if (!$cycle) {
                $cl = ClassLoader::getInstance();
                $cl->loadClass($className);
                $otherModel = new $className();
                $otherResult = $otherModel->fetch();

                foreach ($rows as $rowKey => $row) {

                    $matchId = $row[$joinOn];
                    $rows[$rowKey][$otherModelName] = $otherResult[$matchId];
                }
            }

        }

        if (!empty($this->hasMany)) {
            $otherModelName = ucfirst($this->hasMany['model']);
            $joinOn = $this->hasMany['joinOn'];
            $className = $otherModelName . "Model";
            $cycle = $cycleDet->addModelAndCheck($className);
            if (!$cycle) {
                $cl = ClassLoader::getInstance();
                $cl->loadClass($className);
                $otherModel = new $className();
                $otherResult = $otherModel->fetch();

                foreach ($otherResult as $otherKey => $otherValue) {
                    $matchId = $otherValue[$joinOn];
                    $rows[$matchId][$otherModelName][] = $otherValue;
                }
            }
        }

        return $rows;
    }

    public function newRow($allFields=false)
    {
        $fields=$this->_getFields($allFields);
        $row = array();
        for ($i=0; $i < mysql_num_fields($fields); $i++) {
            $row[mysql_field_name($fields,$i)]='';
        }

        return $row;
    }

    public function insert($row)
    {
        $fields = $this->_getFields(true);

        $query = "INSERT INTO $this->tableName (";
        foreach ($row as $key => $value) {
            $query .= " $key, ";
        }
        $query = substr($query, 0, strlen($query)-2);
        $query .= ") VALUES (";

        for ($i=0; $i < mysql_num_fields($fields); $i++) {
            if (strcmp($row[mysql_field_name($fields,$i)], "")== 0) {
                $query .= " NULL, ";
            } else {
                $query .= " " . $this->_formatValueAsType($row[mysql_field_name($fields,$i)], mysql_field_type($fields, $i)) . ", ";
            }
        }
        $query = substr($query, 0, strlen($query)-2);

        $query .= ");";

        mysql_query($query) or die("Errore nella query");
    }

    public function update($values, $where='')
    {
        $query = "UPDATE $this->tableName SET ";

        foreach ($values as $key => $value) {

            $query .= " $key = " . $this->_formatValueAsType($value, 'string') . ", ";

        }
        $query = substr($query, 0, strlen($query)-2);

        if ($where != '') {
            $query .= " WHERE $where ;";
        } else {
            $query .= ";";
        }

        mysql_query($query) or die("Errore nella query");
    }

    public function closeConnection()
    {
        mysql_close($this->link);
    }

}
