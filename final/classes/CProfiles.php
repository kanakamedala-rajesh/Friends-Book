<?php

define('PROFILE_TIMEOUT', 5); // 5 mins

// Profiles class
class CProfiles {

    // constructor
    function CProfiles() {
    }

    // profile registration
    function registerProfile() {
        $sUsername = $GLOBALS['MySQL']->escape($_POST['username']);
        $sFirstname = $GLOBALS['MySQL']->escape($_POST['firstname']);
        $sLastname = $GLOBALS['MySQL']->escape($_POST['lastname']);
        $sEmail = $GLOBALS['MySQL']->escape($_POST['email']);
        $sPassword = $GLOBALS['MySQL']->escape($_POST['password']);

        if ($sUsername && $sEmail && $sPassword) {
            // check if already exist
            $aProfile = $GLOBALS['MySQL']->getRow("SELECT * FROM `cs_profiles` WHERE `email`='{$sEmail}'");
            if ($aProfile['id'] > 0) {
                $sErrors = '<h2>Another profile with same email already exist</h2>';
            } else {
                // generate Salt and Cached password
                $sSalt = $this->getRandCode();
                $sPass = sha1(md5($sPassword) . $sSalt);

                // add new member into database
                $sSQL = "
                    INSERT INTO `cs_profiles` SET 
                    `name` = '{$sUsername}',
                    `first_name` = '{$sFirstname}',
                    `last_name` = '{$sLastname}',
                    `email` = '{$sEmail}',
                    `password` = '{$sPass}',
                    `salt` = '{$sSalt}',
                    `status` = 'active',
                    `role` = '1',
                    `date_reg` = NOW();
                ";
                $GLOBALS['MySQL']->res($sSQL);

                // autologin
                $GLOBALS['CLogin']->performLogin($sUsername, $sPassword);
            }
        }
    }

    // get random code (for salt)
    function getRandCode($iLen = 8) {
        $sRes = '';

        $sChars = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
        for ($i = 0; $i < $iLen; $i++) {
            $z = rand(0, strlen($sChars) -1);
            $sRes .= $sChars[$z];
        }
        return $sRes;
    }

    // get profiles block
    function getProfilesBlock($iLim = 10, $bOnlineOnly = false) {
        $iPLimit = PROFILE_TIMEOUT;
        $sOnlineSQL = ($bOnlineOnly) ? 'AND (`date_nav` > SUBDATE(NOW(), INTERVAL ' . $iPLimit . ' MINUTE))' : '';
        $sSQL = "
            SELECT `cs_profiles`.*,
            if (`date_nav` > SUBDATE(NOW(), INTERVAL {$iPLimit} MINUTE ), 1, 0) AS `is_online`
            FROM `cs_profiles`
            WHERE `status` = 'active'
            {$sOnlineSQL}
            ORDER BY `date_reg` DESC
            LIMIT {$iLim} 
        ";
        $aProfiles = $GLOBALS['MySQL']->getAll($sSQL);

        $bCanChat = ($_SESSION['member_id'] && $_SESSION['member_status'] == 'active' && $_SESSION['member_role']);

        // create list of messages
        $sCode = '';
        foreach ($aProfiles as $i => $aProfile) {
            $sName = ($aProfile['first_name'] && $aProfile['last_name']) ? $aProfile['first_name'] . ' ' . $aProfile['last_name'] : $aProfile['name'];
            $sSName = (strlen($sName) > 32) ? mb_substr($sName, 0, 28) . '...' : $sName;
            $iPid = $aProfile['id'];
            $sAvatar = $this->getProfileAvatar($iPid);

            $sOnline = ($aProfile['is_online'] == 1) ? '<img alt="" src="images/online.png" class="status_img" />' : '';
            $sChat = ($bCanChat /*&& $aProfile['is_online'] == 1*/) ? '<img id="'.$iPid.'" alt="chat" src="images/chat.png" class="pchat" title="'.$sName.'" />' : '';
            $sCode .= '<div id="'.$iPid.'" title="'.$sName.'"><a href="profile.php?id='.$iPid.'"><img src="'.$sAvatar.'" alt="'.$sName.'"><p>'.$sSName.$sChat.'</p>'.$sOnline.'</a></div>';
        }

        $sClass = ($bOnlineOnly) ? 'profiles online_profiles' : 'profiles';
        return '<div class="'.$sClass.'">' . $sCode . '</div>';
    }

    // get profile info
    function getProfileInfo($i) {
        $sSQL = "
            SELECT * 
            FROM `cs_profiles`
            WHERE `id` = '{$i}'
        ";
        $aInfos = $GLOBALS['MySQL']->getAll($sSQL);
        return $aInfos[0];
    }

    // get role name
    function getRoleName($i) {
        $sRet = 'Ordinary member';
        switch ($i) {
            case 4:
                $sRet = 'Moderator';
                break;
            case 5:
                $sRet = 'Administrator';
                break;
        }
        return $sRet;
    }

    // get profile avatar block
    function getProfileAvatarBlock() {
        if ($_SESSION['member_id']) {
            $aInfo = $this->getProfileInfo((int)$_SESSION['member_id']);
            if (is_array($aInfo) && count($aInfo)) {
                $sName = ($aInfo['first_name'] && $aInfo['last_name']) ? $aInfo['first_name'] . ' ' . $aInfo['last_name'] : $aInfo['name'];

                $aKeys = array(
                    '{id}' => $aInfo['id'],
                    '{image}' => $this->getProfileAvatar($aInfo['id']),
                    '{name}' => $sName
                );
                return strtr(file_get_contents('templates/avatar.html'), $aKeys);
            }
        }
    }

    function getProfileAvatar($i) {
        $sPath = 'data/' . $i . '.jpg';
        return (file_exists($sPath)) ? $sPath : 'images/member.png';
    }

    // add avatar (upload image)
    function addAvatar() {
        $iPid = (int)$_SESSION['member_id'];
        if ($iPid) {
            $sFileTmpName = $_FILES['avatar']['tmp_name'];
            $sDstFilename = 'data/' . $iPid . '.jpg';

            if (file_exists($sFileTmpName) && filesize($sFileTmpName) > 0) {
                $aSize = getimagesize($sFileTmpName);
                if (! $aSize) {
                    @unlink($sFileTmpName);
                    return;
                }

                $bGDInstalled = extension_loaded('gd');

                // resize if GD is installed
                if (! $bGDInstalled)
                    return;

                $iWidth = $iHeight = 48; // width and height for avatar image
                define('IMAGE_TYPE_GIF', 1);
                define('IMAGE_TYPE_JPG', 2);
                define('IMAGE_TYPE_PNG', 3);

                $gdInfoArray = gd_info();
                switch($aSize[2]) {
                    case IMAGE_TYPE_GIF:
                        if (! $gdInfoArray['GIF Read Support'] || ! $gdInfoArray['GIF Create Support'])
                            return;
                        $vSrcImage = @imagecreatefromgif($sFileTmpName);
                        break;
                    case IMAGE_TYPE_JPG:
                        if (! $gdInfoArray['JPG Support'] && ! $gdInfoArray['JPEG Support'])
                            return;
                        $vSrcImage = @imagecreatefromjpeg($sFileTmpName);
                        break;
                    case IMAGE_TYPE_PNG:
                        if (! $gdInfoArray['PNG Support'])
                            return;
                        $vSrcImage = @imagecreatefrompng($sFileTmpName);
                        break;
                    default:
                        return;
                }
                if (! $vSrcImage)
                    return;

                // calculate destination rate and sizes
                $fSrcRate = (float)($aSize[0] / $aSize[1]);
                $fDstRate = (float)($iWidth / $iHeight);
                $fResizeRate = ($fSrcRate > $fDstRate) ? (float)($iWidth / $aSize[0]) : (float)($iHeight / $aSize[1]);
                $iDstWidth = (int)($fResizeRate * $aSize[0]);
                $iDstHeight = (int)($fResizeRate * $aSize[1]);

                if (function_exists('imagecreatetruecolor') && $aSize[2] != IMAGE_TYPE_GIF) {
                    // resize if need (if size is larger than needed)
                    if ($aSize[0] > $iWidth || $aSize[1] > $iHeight) {
                        $vDstImage = imagecreatetruecolor($iDstWidth, $iDstHeight);
                        $vConvRes = imagecopyresampled($vDstImage, $vSrcImage, 0, 0, 0, 0, $iDstWidth, $iDstHeight, $aSize[0], $aSize[1]);
                    } else {
                        $vDstImage = $vSrcImage;
                        $vConvRes = true;
                    }
                } else { // for old GD versions and for GIF images
                    if ($aSize[0] > $iWidth || $aSize[1] > $iHeight) {
                        $vDstImage = imagecreate( $iDstWidth, $iDstHeight );
                        $vConvRes = imagecopyresized($vDstImage, $vSrcImage, 0, 0, 0, 0, $iDstWidth, $iDstHeight, $aSize[0], $aSize[1]);
                    } else {
                        $vDstImage = $vSrcImage;
                        $vConvRes = true;
                    }
                }

                if (! $vConvRes)
                    return;

                $bRes = imagejpeg($vDstImage, $sDstFilename);

                // memory cleanup
                if ($vDstImage != $vSrcImage) {
                    imagedestroy($vSrcImage);
                    imagedestroy($vDstImage);
                } else {
                    imagedestroy($vSrcImage);
                }
                return ($bRes && file_exists($sDstFilename)) ? 1 : '';
            }
        }
    }

    function changeColor($sColor = '') {
        $iPid = (int)$_SESSION['member_id'];
        $sColor = $GLOBALS['MySQL']->escape($sColor);
        if ($iPid && $sColor) {
            if (strlen($sColor) == 4) {
                $sColor = '00' . $sColor;
            }
            $sSQL = "
                UPDATE `cs_profiles` SET 
                `color` = '{$sColor}'
                WHERE `id` = '{$iPid}'
            ";
            $GLOBALS['MySQL']->res($sSQL);
            return 1;
        }
        return;
    }

    // get block member action button
    function getBlockMemberAction($iPid) {
        if ($_SESSION['member_id'] != $iPid && $_SESSION['member_status'] == 'active' && in_array($_SESSION['member_role'], array(4, 5))) {
            $aMyInfo = $this->getProfileInfo($_SESSION['member_id']);
            $aInfo = $this->getProfileInfo($iPid);

            if ($aMyInfo['role'] > $aInfo['role']) {
                $sStatus = $aInfo['status'];
                $sDescDesc = ($sStatus == 'active') ? 'Block this member' : 'Unblock this member';

                return '<font style="float:right"><button id="block" pid="'.$iPid.'">'.$sDescDesc.'</button></font><script src="js/admin_utils.js"></script>';
            }
        }
    }

    // block member
    function blockMember($iPid) {
        if ($iPid) {
            $aInfo = $this->getProfileInfo($iPid);
            $sStatus = $aInfo['status'];
            $sUpStatus = ($sStatus == 'active') ? 'passive' : 'active';
            $sSQL = "
                UPDATE `cs_profiles` SET 
                `status` = '{$sUpStatus}'
                WHERE `id` = '{$iPid}'
            ";
            $GLOBALS['MySQL']->res($sSQL);
            return ($sStatus == 'active') ? 2 : 1;
        }
        return;
    }

    // get block member action button
    function getBlockRate($iPid) {
        if ($_SESSION['member_id'] != $iPid && $_SESSION['member_status'] == 'active') {
            // $aMyInfo = $this->getProfileInfo($_SESSION['member_id']);
            $aInfo = $this->getProfileInfo($iPid);

            // vote element
            $iIconSize = 64;
            $iMax = 5;
            $iRate = $aInfo['rate'];
            $iRateCnt = $aInfo['rate_count'];
            $fRateAvg = ($iRate && $iRateCnt) ? $iRate / $iRateCnt : 0;
            $iWidth = $iIconSize*$iMax;
            $iActiveWidth = round($fRateAvg*($iMax ? $iWidth/$iMax : 0));

            $sVot = '';
            for ($i=1 ; $i<=$iMax ; $i++) {
                $sVot .= '<a href="#" id="'.$i.'"><img class="votes_button" src="images/empty.gif" alt="" /></a>';
            }

            $aKeys = array(
                '{pid}' => $iPid,
                '{width}' => $iWidth,
                '{rate_cnt}' => $iRateCnt,
                '{rate_avg}' => $fRateAvg,
                '{votes}' => $sVot,
                '{act_width}' => $iActiveWidth,
            );
            return strtr(file_get_contents('templates/vote.html'), $aKeys);
        }
    }
}

$GLOBALS['CProfiles'] = new CProfiles();