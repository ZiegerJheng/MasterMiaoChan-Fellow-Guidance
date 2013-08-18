<?php
class PagingOperator
{
    private $numRowsInPage = null;
    private $listObjName = null;

    public function __construct($listObjName, $numRowsInPage)
    {
        $this->numRowsInPage = $numRowsInPage;
        $this->listObjName = $listObjName;
    }

    public function getListInPage($pageNum, $whereColumns = null)
    {
        $rowStart = $this->numRowsInPage * ($pageNum - 1);

        return new $this->listObjName($whereColumns, array($rowStart, $this->numRowsInPage));
    }
}
?>
