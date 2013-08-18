<?php
function fillZeroRecordToReport($inArray, $sDate, $eDate)
{
    $sTimeStamp = strtotime($sDate);
    $eTimeStamp = strtotime($eDate);

    $outArray = array();
    for($cTimeStamp = $sTimeStamp; $cTimeStamp <= $eTimeStamp; $cTimeStamp += (60 * 60 * 24)){
        $keyDate = date('Y-m-d', $cTimeStamp);

        if(true == array_key_exists($keyDate, $inArray)){
            $outArray[$keyDate] = $inArray[$keyDate];
        }
        else{
            $outArray[$keyDate] = 0;
        }
    }

    return $outArray;
}
?>
