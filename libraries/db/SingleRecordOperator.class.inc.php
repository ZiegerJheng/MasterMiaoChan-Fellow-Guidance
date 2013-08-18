<?php
abstract class SingleRecordOperator
{
    protected $table                   = null;
    protected $priKeys                 = array();
    protected $autoIncColumn           = null;
    protected $columns                 = array();

    protected $initType                = null;

    protected $dbErrorInfo             = array();

    public function __construct($input = null, $skipAutoIncColumn = true)
    {
        if(false == is_null($input)){
            $this->initColumnStruct();

            if(false == is_array($input) || 0 === count(array_diff_key($this->priKeys, $input))){
                $this->initColumnValueFromDB($input);
            }
            else{
                $this->initColumnValueFromInput($input, $skipAutoIncColumn);
            }
        }
    }

    private function initColumnStruct()
    {
        $sql = sprintf('DESCRIBE `%s`', $this->table);
        $rs = Database::query($sql);
        $rs->setFetchMode(PDO::FETCH_ASSOC);
        $tableStruct = $rs->fetchAll();

        foreach($tableStruct as $column){
            $this->columns[$column['Field']] = array(
                    'value' => null,
                    'updated' => false
                    );

            if('PRI' == $column['Key']){
                $this->priKeys[] = $column['Field'];
            }

            if(false !== strstr($column['Extra'], 'auto_increment')){
                $this->autoIncColumn = $column['Field'];
            }
        }
    }

    private function initColumnValueFromDB($priKeyValue)
    {
        if(false == is_array($priKeyValue)){
            $kName = $this->priKeys[0];
            $priKeyValue = array($kName => $priKeyValue);
        }

        if(count($this->priKeys) != count($priKeyValue)){
            return false;
        }

        $sql = sprintf('SELECT * FROM `%s` WHERE ', $this->table);
        $keyValuePair = array();
        $isFirst = true;
        foreach($this->priKeys as $priKeyName){
            if(true == $isFirst){
                $isFirst = false;
            }
            else{
                $sql .= ' AND ';
            }

            $sql .= sprintf('`%s` = :%s', $priKeyName, $priKeyName);

            $kName = ':' . $priKeyName;
            $keyValuePair[$kName] = $priKeyValue[$priKeyName];
        }

        $sth = Database::prepare($sql);
        $sth->execute($keyValuePair);

        $recordCount = $sth->rowCount();
        if($recordCount != 1){
            return false;
        }

        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $data = $sth->fetchAll();
        foreach($this->columns as $colName => $colContent){
            $this->columns[$colName]['value'] = $data[0][$colName];
        }

        $this->initType = 'db';

        return true;
    }

    public function initColumnValueFromInput($input, $skipAutoIncColumn = true)
    {
        if(false == is_array($input)){
            return false;
        }

        if(count($input) < count($this->columns) - count($this->priKeys)){
            return false;
        }

        foreach($this->columns as $colName => $colValue){
            if($colName == $this->autoIncColumn && true == $skipAutoIncColumn){
                continue;
            }

            if(true == array_key_exists($colName, $input)){
                $this->columns[$colName]['value'] = $input[$colName];
                $this->columns[$colName]['updated'] = true;
            }
            else{
                return false;
            }
        }

        $this->initType = 'function';

        return true;
    }

    public function __get($colName)
    {
        if(true == array_key_exists($colName, $this->columns)){
            return $this->columns[$colName]['value'];
        }
        else{
            return null;
        }
    }

    public function __set($colName, $newValue)
    {
        if(true == array_key_exists($colName, $this->columns) && $colName != $this->autoIncColumn){
            $this->columns[$colName]['value'] = $newValue;
            $this->columns[$colName]['updated'] = true;
        }
    }

    public function toArray()
    {
        $array = array();
        foreach($this->columns as $colName => $colContent){
            $array[$colName] = $colContent['value'];
        }

        return $array;
    }

    public function save()
    {
        $forPrepare = array();
        
        if('function' == $this->initType){
            $sql = sprintf('INSERT INTO `%s` ( ', $this->table);
            $isFirst = true;
            $counter = 0;
            foreach($this->columns as $colName => $colContent){
                if($colName == $this->autoIncColumn){
                    continue;
                }

                if(true == $isFirst){
                    $isFirst = false;
                }
                else{
                    $sql .= ', ';
                }

                $sql .= sprintf('`%s`', $colName);

                $forPrepare[] = $colContent['value'];

                $counter++;
            }
            $sql .= ' ) VALUES ( ';

            $isFirst = true;
            for($i = 0; $i < $counter; $i++){
                if(true == $isFirst){
                    $isFirst = false;
                }
                else{
                    $sql .= ', ';
                }

                $sql .= '?';
            }
            $sql .= ' )';
        }
        else if('db' == $this->initType){
            $sql = sprintf('UPDATE `%s` SET ', $this->table);
            $isFirst = true;
            foreach($this->columns as $colName => $colContent){
                if($colName == $this->autoIncColumn){
                    continue;
                }

                if(true == $isFirst){
                    $isFirst = false;
                }
                else{
                    $sql .= ', ';
                }

                $sql .= sprintf('`%s` = ?', $colName);

                $forPrepare[] = $colContent['value'];
            }

            $sql .= ' WHERE ';

            $isFirst = true;
            foreach($this->priKeys as $priKeyName){
                if(true == $isFirst){
                    $isFirst = false;
                }
                else{
                    $sql .= ' AND ';
                }

                $sql .= sprintf('`%s` = ?', $priKeyName);

                $forPrepare[] = $this->columns[$priKeyName]['value'];
            }
            //var_dump($sql); var_dump($forPrepare);
        }

        $sth = Database::prepare($sql);
        $execResult = $sth->execute($forPrepare);
        if(true == $execResult){
            if('function' == $this->initType){
                $this->columns[$this->autoIncColumn]['value'] = Database::lastInsertId();
            }
            $this->resetColUpdated();

            return true;
        }
        else{
            $this->dbErrorInfo = $sth->errorInfo();
            return false;
        }
    }

    public function del()
    {
        if(null != $this->initType){
            $forPrepare = array();
            
            $sql = sprintf('DELETE FROM `%s` WHERE ', $this->table);
            $isFirst = true;
            foreach($this->priKeys as $priKeyName){
                if(true == $isFirst){
                    $isFirst = false;
                }
                else{
                    $sql .= ' AND ';
                }

                $sql .= sprintf('`%s` = ?', $priKeyName);

                $forPrepare[] = $this->columns[$priKeyName]['value'];
            }

            $sth = Database::prepare($sql);
            $execResult = $sth->execute($forPrepare);
            if(true == $execResult){
                $this->resetColumns();

                return true;
            }
            else{
                $this->dbErrorInfo = $sth->errorInfo();
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function getErrorInfo()
    {
        return $this->dbErrorInfo;
    }

    public function getTable()
    {
        return $this->table;
    }

    private function resetColUpdated()
    {
        foreach($this->columns as $colName => $colContent){
            $this->columns[$colName]['updated'] = false;
        }
    }

    private function resetColumns()
    {
        foreach($this->columns as $colName => $colContent){
            $this->columns[$colName]['value'] = null;
            $this->columns[$colName]['updated'] = false;
        }
    }

    public static function isExist($whereColumns)
    {
        $calledClassName = get_called_class();
        $calledClass = new $calledClassName(null);
        $table = $calledClass->getTable();

        $forPrepare = array();

        $sql = sprintf('SELECT * FROM `%s` WHERE ', $table);
        $isFirst = true;
        foreach($whereColumns as $colName => $colValue){
            if(true == $isFirst){
                $isFirst = false;
            }
            else{
                $sql .= ' AND ';
            }
            
            $sql .= sprintf('`%s` = ?', $colName);

            $forPrepare[] = $colValue;
        }

        $sth = Database::prepare($sql);
        $execResult = $sth->execute($forPrepare);
        $rowCount = $sth->rowCount();
        if(true == $execResult && $rowCount > 0){
            return true;
        }
        else{
            return false;
        }
    }
}
?>
