<?php

// set error reporting level
if (version_compare(phpversion(), '5.3.0', '>=') == 1)
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
else
  error_reporting(E_ALL & ~E_NOTICE);

require_once('classes/CMySQL.php');
require_once('classes/CLogin.php');
require_once('classes/CProfiles.php');

// for logged-in members only
if ($_SESSION['member_id'] && $_SESSION['member_status'] == 'active') {
    if (in_array($_SESSION['member_role'], array(4, 5))) { // for moderators and admins only
        if ($_POST['action'] == 'block') { // Block action
            $iRes = $GLOBALS['CProfiles']->blockMember((int)$_POST['pid']);
            header('Content-Type: text/html; charset=utf-8');
            echo $iRes;
            exit;
        }
    }

    if ($_POST['action'] == 'put_vote') { // Put vote action
        $iPid = (int)$_POST['id'];
        $iVote = (int)$_POST['vote'];
        $sIp = getVisitorIP();

        // we can vote once per week (protection)
        $iOldId = $GLOBALS['MySQL']->getOne("SELECT `pid` FROM `cs_profiles_vote_track` WHERE `pid` = '{$iPid}' AND `ip` = '{$sIp}' AND (`date` >= NOW() - INTERVAL 7 DAY) LIMIT 1");
        if (! $iOldId) {
            $GLOBALS['MySQL']->res("INSERT INTO `cs_profiles_vote_track` SET `pid` = '{$iPid}', `ip` = '{$sIp}', `date` = NOW()");
            $GLOBALS['MySQL']->res("UPDATE `cs_profiles` SET `rate` = `rate` + {$iVote}, `rate_count` = `rate_count` + 1 WHERE `id` = '{$iPid}'");
            header('Content-Type: text/html; charset=utf-8');
            echo 1;
            exit;
        }
    }
}

function getVisitorIP() {
    $ip = "0.0.0.0";
    if( ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) && ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif( ( isset( $_SERVER['HTTP_CLIENT_IP'])) && (!empty($_SERVER['HTTP_CLIENT_IP'] ) ) ) {
        $ip = explode(".",$_SERVER['HTTP_CLIENT_IP']);
        $ip = $ip[3].".".$ip[2].".".$ip[1].".".$ip[0];
    } elseif((!isset( $_SERVER['HTTP_X_FORWARDED_FOR'])) || (empty($_SERVER['HTTP_X_FORWARDED_FOR']))) {
        if ((!isset( $_SERVER['HTTP_CLIENT_IP'])) && (empty($_SERVER['HTTP_CLIENT_IP']))) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }
    return $ip;
}