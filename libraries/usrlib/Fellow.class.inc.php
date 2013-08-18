<?php
class Fellow
{
    public $id = null;
    public $name = null;

    ##
    # 這個就單純新增資料
    ##
    public function newDailyShare($reportDate, $newFriendName, $relationship, $note, $shareEvent, $newComing)
    {
        // 如果新朋友名字已經有了就直接取ID
        if(true == NewFriend::isExist(array('name' => $newFriendName))){
            $addNewFriend = new NewFriend(array('name' => $newFriendName));
            $addNewFriend->save();
            $newFriendID = $addNewFriend->id;
        }
        // 如果新朋友名字還沒有就新增，接著取新增的ID
        else{
            $findFriend = new NewFriendList(array('name' => $newFriendName));
            $newFriendID = $findFriend->list[0]->id;
        }

        // 新增每日分享記錄
        $addDailyShare = new DailyShare(
            'new_friend' => $newFriendID,
            'sharer' => $this->id,
            'relationship' => $relationship,
            'note' => $note,
            'report_date' => $reportDate
        );
        $addDailyShare->save();

        // 若有預計參加分享會資料的話就新增
        if(null != $shareEvent){
            $addShareEvent = new ShareEvent(array(
                'new_friend' => $newFriendID,
                'inviter' => $this->id,
                'relationship' => $relationship,
                'attending_date' => $shareEvent['date'],
                'attending_place' => $shareEvent['place'],
                'note' => $note,
                'last_report_date' => date('Y-m-d')
            ));
            $addShareEvent->save();
        }

        // 若有預計入門資料的話就新增
        if(null != $newComing){
            $addNewComing = new NewComing(array(
                'new_fellow' => $newFriendID,
                'inviter' => $this->id,
                'relationship' => $relationship,
                'attending_date' => $newComing['date'],
                'attending_place' => $newComing['place'],
                'note' => $note,
                'last_report_date' => date('Y-m-d')
            ));
            $addNewComing->save();
        }
    }

    public function editShareEventDate($shareEventID, $newAttendingDate)
    {
        $editShareEvent = new ShareEvent($shareEventID);

        $oldAttendingTS = strtotime($editShareEvent->attending_date);
        $newAttendingTS = strtotime($newAttendingDate);
        if($newAttendingTS > $oldAttendingTS){
            $editShareEvent->postpone_count += 1;
        }

        $editShareEvent->attending_date = $newAttendingDate;
        $editShareEvent->save();
    }

    public function editShareEventStatus($shareEventID, $newStatus, $newComingInfo = null)
    {
        $editShareEvent = new ShareEvent($shareEventID);
        $editShareEvent->status = $newStatus;
        $editShareEvent->save();

        if('確認參加要入門' == $newStatus && null !== $newComingInfo){
            $addNewComing = new NewComing(array(
                'new_fellow' => $editShareEvent->new_friend,
                'inviter' => $this->id,
                'relationship' => $editShareEvent->relationship,
                'attending_date' => $newComingInfo['attendingDate'],
                'attending_place' => $newComingInfo['attendingPlace'],
                'note' => $newComingInfo['note'],
                'last_report_date' => date('Y-m-d')
            ));
            $addNewComing->save();
        }
    }

    public function editShareEventNote($shareEventID, $newNote)
    {
        $editShareEvent = new ShareEvent($shareEventID);
        $editShareEvent->note = $newNote;
        $editShareEvent->save();
    }

    public function editNewComingDate($newComingID, $newAttendingDate)
    {
        $editNewComing = new NewComing($newComingID);

        $oldAttendingTS = strtotime($editNewComing->attending_date);
        $newAttendingTS = strtotime($newAttendingDate);
        if($newAttendingTS > $oldAttendingTS){
            $editNewComing->postpone_count += 1;
        }

        $editNewComing->attending_date = $newAttendingDate;
        $editNewComing->save();
    }

    public function editNewComingStatus()
    {
        $editNewComing = new NewComing($newComingID);
        $editNewComing->status = $newStatus;
        $editNewComing->save();
    }

    public function editNewComingNote()
    {
        $editNewComing = new NewComing($newComingID);
        $editNewComing->note = $newNote;
        $editNewComing->save();
    }

    public function getDailyShareList($sDate, $eDate)
    {
        $dailyShareList = new DailyShareList(array(
            'sharer' => $this->id,
            'report_date' => array('BETWEEN', ($sDate . '<=>' . $eDate))
        ));

        $rDailyShareList = array();
        $dailyShareListArray = $dailyShareList->toArray();
        foreach($dailyShareListArray as $dailyShareRecord){
            $reportDate = $dailyShareRecord['report_date'];
            
            if(true == array_key_exists($reportDate, $rDailyShareList)){
                $rDailyShareList[$reportDate] += 1;
            }
            else{
                $rDailyShareList[$reportDate] = 1;
            }
        }

        $rDailyShareList = fillZeroRecordToReport($rDailyShareList, $sDate, $eDate);

        return $rDailyShareList;
    }

    public function getShareEventList($sDate, $eDate)
    {
        $shareEventList = new ShareEventList(array(
            'inviter' => $this->id,
            'attending_date' => array('BETWEEN', ($sDate . '<=>' . $eDate)),
            'status' => array('LIKE', '確認參加%')
        ));

        $rShareEventList = array();
        $shareEventListArray = $shareEventList->toArray();
        foreach($shareEventListArray as $shareEventRecord){
            $attendingDate = $shareEventListArray['attending_date'];

            if(true == array_key_exists($attendingDate, $rShareEventList){
                $rShareEventList[$attendingDate] += 1;
            }
            else{
                $rShareEventList[$attendingDate] = 1;
            }
        }

        $rShareEventList = fillZeroRecordToReport($rShareEventList, $sDate, $eDate);

        return $rShareEventList;
    }

    public function getNewComingList($sDate, $eDate)
    {
        $newComingList = new NewComingList(array(
            'inviter' => $this->id,
            'attending_date' => array('BETWEEN', ($sDate . '<=>' . $eDate)),
            'status' => '確認入門'
        ));

        $newComingListArray = $newComingList->toArray();
        foreach($newComingListArray as $newComingRecord){
            $attendingDate = $newComingRecord['attending_date'];

            if(true == array_key_exists($attendingDate, $rNewComingList)){
                $rNewComingList[$attendingDate] += 1;
            }
            else{
                $rNewComingList[$attendingDate] = 1;
            }

            $rNewComingList = fillZeroRecordToReport($rNewComingList, $sDate, $eDate);

            return $rNewComingList;
        }
    }

    public function getShareStatisticsReport($sDate, $eDate)
    {
        $report = array();

        $report['DailyShare'] = $this->getDailyShareList($sDate, $eDate);
        $report['ShareEvent'] = $this->getShareEventList($sDate, $eDate);
        $report['NewComing'] = $this->getNewComingList($sDate, $eDate);

        return $report;
    }
}
?>
