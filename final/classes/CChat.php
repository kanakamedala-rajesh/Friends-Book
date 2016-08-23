<?php

class CChat {

    // constructor
    function CChat() {}

    // add a message to database
    function acceptMessage($iPostRoom = 0) {
        $sName = $GLOBALS['MySQL']->escape($_SESSION['member_name']);
        $iPid = (int)$_SESSION['member_id'];
        $sMessage = $GLOBALS['MySQL']->escape($_POST['message']);

        if ($iPid && $sName != '' && $sMessage != '') {
            $sSQL = "
                SELECT `id`
                FROM `cs_messages`
                WHERE `sender` = '{$iPid}' AND UNIX_TIMESTAMP( ) - `when` < 5
                    AND `room` = '{$iPostRoom}'
                LIMIT 1
            ";
            $iLastId = $GLOBALS['MySQL']->getOne($sSQL);
            if ($iLastId) return 2; // as protection from very often messages

            $bRes = $GLOBALS['MySQL']->res("INSERT INTO `cs_messages` SET `sender` = '{$iPid}', `message` = '{$sMessage}', `when` = UNIX_TIMESTAMP(), `room` = '{$iPostRoom}'");
            return ($bRes) ? 1 : 3;
        }
    }

    // add a private message to database
    function acceptPrivMessage() {
        $sName = $GLOBALS['MySQL']->escape($_SESSION['member_name']);
        $iPid = (int)$_SESSION['member_id'];
        $iRecipient = (int)$_POST['recipient'];
        $sMessage = $GLOBALS['MySQL']->escape($_POST['priv_message']);

        if ($iPid && $iRecipient && $sName != '' && $sMessage != '') {
            $sSQL = "
                SELECT `id`
                FROM `cs_messages`
                WHERE `sender` = '{$iPid}' AND `recipient` = '{$iRecipient}' AND UNIX_TIMESTAMP( ) - `when` < 5
                    AND `room` = 0
                LIMIT 1
            ";
            $iLastId = $GLOBALS['MySQL']->getOne($sSQL);
            if ($iLastId) return 2; // as protection from very often messages

            $bRes = $GLOBALS['MySQL']->res("INSERT INTO `cs_messages` SET `sender` = '{$iPid}', `recipient` = '{$iRecipient}', `message` = '{$sMessage}', `when` = UNIX_TIMESTAMP()");
            return ($bRes) ? 1 : 3;
        }
    }

    // return input text form
    function getInputForm($iRoom = 0) {
        $aKeys = array(
            '{room}' => $iRoom
        );
        return strtr(file_get_contents('templates/chat.html'), $aKeys);
    }

    // get last 10 messages
    function getMessages($iRecipient = 0, $iRoom = 0) {
        $sRecipientSQL = 'WHERE `recipient` = 0';
        if ($iRecipient > 0) {
            $iPid = (int)$_SESSION['member_id'];
            $sRecipientSQL = "WHERE (`sender` = '{$iRecipient}' && `recipient` = '{$iPid}') || (`recipient` = '{$iRecipient}' && `sender` = '{$iPid}')";
        }

        $sRecipientSQL .= " AND `room` = '{$iRoom}'";

        $sSQL = "
            SELECT `a` . * , `cs_profiles`.`name`,  `cs_profiles`.`id` as 'pid' , UNIX_TIMESTAMP( ) - `a`.`when` AS 'diff'
            FROM `cs_messages` AS `a`
            INNER JOIN `cs_profiles` ON `cs_profiles`.`id` = `a`.`sender`
            {$sRecipientSQL}
            ORDER BY `a`.`id` DESC
            LIMIT 10 
        ";
        $aMessages = $GLOBALS['MySQL']->getAll($sSQL);
        asort($aMessages);

        // create list of messages
        $sMessages = '';
        foreach ($aMessages as $i => $aMessage) {
            $sExStyles = $sExJS = '';
            $iDiff = (int)$aMessage['diff'];
            if ($iDiff < 7) { // less than 7 seconds
                $sExStyles = 'style="display:none;"';
                $sExJS = "<script> $('#message_{$aMessage['id']}').fadeIn('slow'); </script>";
            }

            $sWhen = date("H:i:s", $aMessage['when']);
            $sAvatar = $GLOBALS['CProfiles']->getProfileAvatar($aMessage['pid']);
            $sMessages .= '<div class="message" id="message_'.$aMessage['id'].'" '.$sExStyles.'><b><a href="profile.php?id='.$aMessage['pid'].'" target="_blank"><img src="'. $sAvatar .'">' . $aMessage['name'] . ':</a></b> ' . $aMessage['message'] . '<span>(' . $sWhen . ')</span></div>' . $sExJS;
        }
        return $sMessages;
    }

    function getRecentMessage($iPid) {
        if ($iPid) {
            $sSQL = "
                SELECT `a` . * , `cs_profiles`.`name`,  `cs_profiles`.`id` as 'pid' , UNIX_TIMESTAMP( ) - `a`.`when` AS 'diff'
                FROM `cs_messages` AS `a`
                INNER JOIN `cs_profiles` ON `cs_profiles`.`id` = `a`.`sender`
                WHERE `recipient` = '{$iPid}' AND `room` = 0
                ORDER BY `a`.`id` DESC
                LIMIT 1
            ";

            $aMessage = $GLOBALS['MySQL']->getRow($sSQL);
            $iDiff = (int)$aMessage['diff'];
            if ($iDiff < 7) { // less than 7 seconds, = new
                return (int)$aMessage['sender'];
            }
            return;
        }
    }

    function getRandColor() {
        $aColors = array('red', 'blue', 'green', 'orange', 'indigo', 'violet');
        shuffle($aColors);
        return $aColors[0];
    }

    function getRooms($iRoom = 0) {
        $sSQL = "
            SELECT * 
            FROM `cs_rooms`
            WHERE 1
        ";
        $aRooms = $GLOBALS['MySQL']->getAll($sSQL);

        $sRooms = '';
        foreach ($aRooms as $i => $aRoom) {
            $sActive = ($iRoom == $aRoom['id']) ? ' active' : '';
            $sColor = $this->getRandColor();
            $sRooms .= '<li><a class="'.$sColor.$sActive.'" href="index.php?room='.$aRoom['id'].'">'.$aRoom['title'].'</a></li>';
        }

        $sMainActive = ($iRoom == 0) ? ' active' : '';
        $sColor = $this->getRandColor();

        return <<<EOF
<div class="roomsHolder">
    <ul class="rooms">
        <li><a class="{$sColor}{$sMainActive}" href="index.php">Main</a></li>
        {$sRooms}
    </ul>
    <div class="shadow"></div>
</div>
<div class="clear"></div>
EOF;
    }

    function getRoomInfo($i) {
        $sSQL = "
            SELECT * 
            FROM `cs_rooms`
            WHERE `id` = '{$i}'
        ";
        $aInfos = $GLOBALS['MySQL']->getAll($sSQL);
        return $aInfos[0];
    }

    function addRoom($sTitleParam) {
        $sTitle = $GLOBALS['MySQL']->escape($sTitleParam);
        $iPid = (int)$_SESSION['member_id'];

        if ($iPid && $sTitle) {
            return $GLOBALS['MySQL']->res("INSERT INTO `cs_rooms` SET `title` = '{$sTitle}', `owner` = '{$iPid}', `when` = UNIX_TIMESTAMP()");
        }
    }
    function deleteRoom($sRoomParam) {
        $iRoom = (int)$sRoomParam;

        if ($iRoom) {
            $GLOBALS['MySQL']->res("DELETE FROM `cs_messages` WHERE `room` = '{$iRoom}'");
            return $GLOBALS['MySQL']->res("DELETE FROM `cs_rooms` WHERE `id` = '{$iRoom}' LIMIT 1");
        }
    }
}

$GLOBALS['MainChat'] = new CChat();
