<?php

class CLogin {

    // constructor
    function CLogin() {
        session_start();
    }

    // get login box function
    function getLoginBox() {
        if (isset($_GET['logout'])) { // logout processing
            if (isset($_SESSION['member_name']) && isset($_SESSION['member_pass']))
                $this->performLogout();
        }

        if ($_POST && $_POST['Login'] == 'Login' && $_POST['username'] && $_POST['password']) { // login processing
            if ($this->checkLogin($_POST['username'], $_POST['password'], false)) { // successful login
                $this->performLogin($_POST['username'], $_POST['password']);
                header( "Location:{$_SERVER['REQUEST_URI']}" );
                exit;
            } else { // wrong login
                return file_get_contents('templates/login_form.html') . '<h2>Username or Password is incorrect</h2>';
            }
        } else { // in case if we already logged (on refresh page):
            if (isset($_SESSION['member_name']) && $_SESSION['member_name'] && $_SESSION['member_pass']) {
                $aReplaces = array(
                    '{name}' => $_SESSION['member_name'],
                    '{status}' => $_SESSION['member_status'],
                    '{role}' => $_SESSION['member_role'],
                );
                return strtr(file_get_contents('templates/logout_form.html'), $aReplaces);
            }

            // otherwise - draw login form
            return file_get_contents('templates/login_form.html');
        }
    }

    // perform login
    function performLogin($sName, $sPass) {
        $this->performLogout();

        // make variables safe
        $sName = $GLOBALS['MySQL']->escape($sName);

        $aProfile = $GLOBALS['MySQL']->getRow("SELECT * FROM `cs_profiles` WHERE `name`='{$sName}'");
        // $sPassEn = $aProfile['password'];
        $iPid = $aProfile['id'];
        $sSalt = $aProfile['salt'];
        $sStatus = $aProfile['status'];
        $sRole = $aProfile['role'];

        $sPass = sha1(md5($sPass) . $sSalt);

        $_SESSION['member_id'] = $iPid;
        $_SESSION['member_name'] = $sName;
        $_SESSION['member_pass'] = $sPass;
        $_SESSION['member_status'] = $sStatus;
        $_SESSION['member_role'] = $sRole;
    }

    // perform logout
    function performLogout() { 
        unset($_SESSION['member_id']);
        unset($_SESSION['member_name']);
        unset($_SESSION['member_pass']);
        unset($_SESSION['member_status']);
        unset($_SESSION['member_role']);
    }

    // check login
    function checkLogin($sName, $sPass, $isHash = true) {
        // make variables safe
        $sName = $GLOBALS['MySQL']->escape($sName);
        $sPass = $GLOBALS['MySQL']->escape($sPass);

        $aProfile = $GLOBALS['MySQL']->getRow("SELECT * FROM `cs_profiles` WHERE `name`='{$sName}'");
        $sPassEn = $aProfile['password'];

        if ($sName && $sPass && $sPassEn) {
            if (! $isHash) {
                $sSalt = $aProfile['salt'];
                $sPass = sha1(md5($sPass) . $sSalt);
            }
            return ($sPass == $sPassEn);
        }
        return false;
    }
}

$GLOBALS['CLogin'] = new CLogin();
