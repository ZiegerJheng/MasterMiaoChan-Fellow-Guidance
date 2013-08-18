<?php
abstract class ListOperator
{
    protected $table = null;
    public $list = array();

    protected $singleRecordOperatorName = null;

    public function __construct($whereColumns = null, $limit = null, $orderby = null)
    {
        $this->initColumnValueFromDB($whereColumns, $limit, $orderby);
    }

    private function initColumnValueFromDB($whereColumns = null, $limit = null, $orderby = null)
    {
        if(false == empty($whereColumns)){
            $forPrepare = array();

            $sql = sprintf('SELECT * FROM `%s` WHERE ', $this->table);
            $isFirst = true;
            foreach($whereColumns as $colName => $colValue){
                if(true == $isFirst){
                    $isFirst = false;
                }
                else{
                    $sql .= ' AND ';
                }
                
                if(false == is_array($colValue)){
                    $sql .= sprintf('`%s` = ?', $colName);

                    $forPrepare[] = $colValue;
                }
                else{
                    if(true == in_array($colValue[0], array('=', '!=', 'LIKE'))){
                        $sql .= sprintf('`%s` %s ?', $colName, $colValue[0]);

                        $forPrepare[] = $colValue[1];
                    }
                    else if(true == in_array($colValue[0], array('BETWEEN'))){
                        $sql .= sprintf('`%s` %s ? AND ?', $colName, $colValue[0]);

                        list($val1, $val2) = explode('<=>', $colValue[1]);
                        $forPrepare[] = $val1;
                        $forPrepare[] = $val2;
                    }
                    else{
                        return false;
                    }
                }
            }

            if(true == is_array($orderby)){
                $sql .= $this->genOrderbySQL($orderby);
                if(false === $sql){
                    return false;
                }
            }

            if(false == empty($limit)){
                $sql .= $this->genLimitSQL($limit);
                if(false === $sql){
                    return false;
                }
            }

            $sth = Database::prepare($sql);
            $execResult = $sth->execute($forPrepare);
        }
        else{
            $sql = sprintf('SELECT * FROM `%s`', $this->table);

            if(true == is_array($orderby)){
                $sql .= $this->genOrderbySQL($orderby);
                if(false === $sql){
                    return false;
                }
            }

            if(false == empty($limit)){
                $sql .= $this->genLimitSQL($limit);
                if(false === $sql){
                    return false;
                }
            }

            $sth = Database::query($sql);
            $execResult = (false === $sth) ? false : true;
        }

        $rowCount = $sth->rowCount();

        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $data = $sth->fetchAll();

        if(true == $execResult && $rowCount > 0){
            foreach($data as $single){
                $this->list[] = array(
                    'content' => new $this->singleRecordOperatorName($single, false),
                    'updated' => false
                );
            }

            return true;
        }
        else{
            return false;
        }
    }

    private function genOrderbySQL($orderby)
    {
        $sql = ' ORDER BY ';

        $isFirst = true;
        foreach($orderby as $key => $orderpair){
            if(true == $isFirst){
                if(true == in_array($orderpair[1], array('ASC', 'DESC'))){
                    $sql .= sprintf('%s %s', $orderpair[0], $orderpair[1]);
                }
                else{
                    return false;
                }

                $isFirst = false;
            }
            else{
                if(true == in_array($orderpair[1], array('ASC', 'DESC'))){
                    $sql .= sprintf(', %s %s', $orderpair[0], $orderpair[1]);
                }
                else{
                    return false;
                }
            }
        }

        return $sql;
    }

    private function genLimitSQL($limit)
    {
        if(true == is_array($limit)){
            $sql = sprintf(' LIMIT %d,%d', $limit[0], $limit[1]);
        }
        else{
            $sql = sprintf(' LIMIT %d', $limit);
        }

        return $sql;
    }

    public function toArray()
    {
        $array = array();
        foreach($this->list as $key => $listContent){
            $array[] = $listContent['content']->toArray();
        }

        return $array;
    }

    public function save()
    {
        foreach($this->list as $key => $listContent){
            if(false == $this->list[$key]['updated']){
                continue;
            }

            $this->list[$key]['content']->save();
            $this->list[$key]['updated'] = false;
        }
    }

    public function del()
    {
        foreach($this->list as $key => $listContent){
            $this->list[$key]['content']->del();
            $this->list[$key]['updated'] = false;
        }
    }

    public function numOfRows()
    {
        return count($this->list);
    }
}
?>
