<?php
class NewFriend extends SingleRecordOperator
{
    public function __construct($input = null, $skipAutoIncColumn = true)
    {
        $this->table = 'new_friend';
        parent::__construct($input, $skipAutoIncColumn);
    }
}
?>
