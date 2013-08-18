<?php
class FellowGroup
{
    public function getFellowList()
    {
        $dbFellows = new DBFellowList();
        $fellowsArray = $dbFellows->toArray();

        return $fellowsArray;
    }

    public function getDailyShareReport($date)
    {
        $dbDailyShare = new DBDailyShare(array(
            'report_date' => $date
        ));

        $dbShareEvent = new DBShareEvent(array(
            'last_report_date' => $date
        ));

        $dbNewComing = new DBNewComing(array(
            'last_report_date' => $date
        ));

        $dailyShareReport = array('DailyShare' => array(), 'ShareEvent' => array(), 'NewComing' => array());

        foreach($dbDailyShare->list as $dailyShareRecord){
            $newFriendName = $dailyShareRecord->getNewFriendName();
            $sharerName = $dailyShareRecord->getSharerName();
            $relationship = $dailyShareRecord->relationship;
            $note = $dailyShareRecord->note;

            $dailyShareReport['DailyShare'][$sharerName] = array(
                'NewFriendName' => $newFriendName,
                'Relationship' => $relationship,
                'Note' => $note
            );
        }

        foreach($dbShareEvent->list as $shareEventRecord){
            $newFriendName = $shareEventRecord->getNewFriendName();
            $inviterName = $shareEventRecord->getInviterName();
            $relationship = $shareEventRecord->relationship;
            $attendingDate = $shareEventRecord->attending_date;
            $attendingPlace = $shareEventRecord->getAttendingPlaceName();
            $note = $shareEventRecord->note;
            $status = $shareEventRecord->status;

            $dailyShareReport['ShareEvent'][] = array(
                'NewFriendName' => $newFriendName,
                'InviterName' => $inviterName,
                'Relationship' => $relationship,
                'AttendingDate' => $attendingDate,
                'AttendingPlace' => $attendingPlace,
                'Note' => $note,
                'Status' => $status
            );
        }

        foreach($dbNewComing->list as $newComingRecord){
            $newFellowName = $newComingRecord->getNewFellowName();
            $inviterName = $newComingRecord->getInviterName();
            $relationship = $newComingRecord->relationship;
            $attendingDate = $newComingRecord->attending_date;
            $attendingPlace = $newComingRecord->getAttendingPlaceName();
            $note = $newComingRecord->note;
            $status = $newComingRecord->status;

            $dailyShareReport['NewComing'][] = array(
                'NewFellowName' => $newFellowName,
                'InviterName' => $inviterName,
                'Relationship' => $relationship,
                'AttendingDate' => $attendingDate,
                'AttendingPlace' => $attendingPlace,
                'Note' => $note,
                'Status' => $status
            );
        }

        return $dailyShareReport;
    }

    public function getFellowDailyShareList($sDate, $eDate)
    {
        $dbFellowDailyShareList = new DBDailyShareList(array(
            'report_date' => array('BETWEEN', ($sDate . '<=>' . $eDate)) 
        ));

        $fellowDailyShareList = array();
        foreach($dbFellowDailyShareList->list as $fellowDailyShareRecord){
            $sharerName = $fellowDailyShareRecord->getSharerName();

            if(true == array_key_exists($sharerName, $fellowDailyShareList)){
                $fellowDailyShareList[$sharerName] += 1;
            }
            else{
                $fellowDailyShareList[$sharerName] = 1;
            }
        }
    }

    public function getFellowShareEventList($sDate, $eDate)
    {
        $dbFellowShareEventList = new DBShareEventList(array(
            'attending_date' => array('BETWEEN', ($sDate . '<=>' . $eDate)),
            'status' => array('LIKE', '確認參加%')
        ));

        $fellowShareEventList = array();
        foreach($dbFellowShareEventList->list as $fellowShareEventRecord){
            $inviterName = $fellowShareEventRecord->getInviterName();

            if(true == array_key_exists($inviterName, $fellowShareEventList)){
                $fellowShareEventList[$inviterName] += 1;
            }
            else{
                $fellowShareEventList[$inviterName] = 1;
            }
        }

        return $fellowShareEventList;
    }

    public function getFellowNewComingReport($sDate, $eDate)
    {
        $dbFellowNewComingList = new DBNewComingList(array(
            'attending_date' => array('BETWEEN', ($sDate . '<=>' . $eDate)),
            'status' => '確認入門'
        ));

        $fellowNewComingList = array();
        foreach($dbFellowNewComingList->list as $fellowNewComingRecord){
            $inviterName = $fellowNewComingRecord->getInviterName();

            if(true == array_key_exists($inviterName, $fellowNewComingList)){
                $fellowNewComingList[$inviterName] += 1;
            }
            else{
                $fellowNewComingList[$inviterName] = 1;
            }
        }

        return $fellowNewComingList;
    }

    public function getGroupShareReport($sDate, $eDate)
    {
        $groupShareReport = array('DailyShare' => array(), 'ShareEvent' => array(), 'NewComing' => array());

        $dbFellowDailyShareList = new DBDailyShareList(array(
            'report_date' => array('BETWEEN', ($sDate . '<=>' . $eDate)) 
        ));

        $dailyShareReport = array();
        foreach($dbFellowDailyShareList->list as $fellowDailyShareRecord){
            $reportDate = $fellowDailyShareRecord->report_date;

            if(true == array_key_exists($reportDate, $dailyShareReport)){
                $dailyShareReport[$reportDate] += 1;
            }
            else{
                $dailyShareReport[$reportDate] = 1;
            }
        }
        $groupShareReport['DailyShare'] = fillZeroRecordToReport($dailyShareReport, $sDate, $eDate);

        $dbFellowShareEventList = new DBShareEventList(array(
            'attending_date' => array('BETWEEN', ($sDate . '<=>' . $eDate))
        ));

        $shareEventReport = array();
        foreach($dbFellowShareEventList->list as $fellowShareEventRecord){
            $attendingDate = $fellowShareEventRecord->attending_date;

            if(true == array_key_exists($attendingDate, $shareEventReport)){
                $shareEventReport[$attendingDate] += 1;
            }
            else{
                $shareEventReport[$attendingDate] = 1;
            }
        }
        $groupShareReport['ShareEvent'] = fillZeroRecordToReport($shareEventReport, $sDate, $eDate);

        $dbFellowNewComingList = new DBNewComingList(array(
            'attending_date' => array('BETWEEN', ($sDate . '<=>' . $eDate))
        ));

        $newComingReport = array();
        foreach($dbFellowNewComingList->list as $fellowNewComingList){
            $attendingDate = $fellowNewComingList->attending_date;

            if(true == array_key_exists($attendingDate, $newComingReport)){
                $newComingReport[$attendingDate] += 1;
            }
            else{
                $newComingReport[$attendingDate] = 1;
            }
        }
        $groupShareReport['NewComing'] = fillZeroRecordToReport($newComingReport, $sDate, $eDate);

        return $groupShareReport;
    }
}
?>
