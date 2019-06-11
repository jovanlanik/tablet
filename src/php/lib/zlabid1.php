<?php namespace Uzlabina;
/**
 * Uzlabina user login checker for student projects
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   UzlabinaID
 * @author    Jan Klepal <jan@klepal.cz>
 * @copyright 2016 SPŠE V Úžlabině
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @version   GIT: $Id$
 * @link      https://git.uzlabina.cz/core/zlabid
 */

if (!function_exists('sha1')) {
    die('PHP sha1 function is required for ZlabID!');
}

if (!function_exists('hex2bin')) {
    /**
     * Decodes a hexadecimally encoded binary string
     *
     * @param str $str hexadecimally encoded binary string
     *
     * @return str decoded string
     */
    function hex2bin($str) {
        $bin = "";
        $len = strlen($str);
        for ($i=0; $i<$len; $i+=2) {
            $bin .= pack('H*', substr($str, $i, 2));
        }
        return $bin;
    }
}

class ZlabID {
    public $isValid = false; // set to true when login server validates user
    public $isInvalid = false; // set to true when username or password is invalid
    public $result = null; // account type [student|ucitel] or error message
    public $username = null; // validated username
    public $firstname = null; // user first name
    public $lastname = null; // user last name
    public $class = null; // class
    public $sid = null; // used for session checking

    public $errors = array( // error messages localization
        'SERVER_ERROR' => 'Chyba přihlašovacího serveru',
        'NO_USERNAME' => 'Nebylo předáno uživatelské jméno',
        'NO_USERNAME' => 'Nebylo předáno heslo',
        'INVALID_CREDENTIALS' => 'Neplatné jméno nebo heslo',
        'INVALID_SID' => 'Přihlášení vypršelo',
        'INVALID_REFERRER' => 'Neplatný referrer',
    );
    public $unknownError = 'Neznámá chyba'; // in case server return error which is not in errors

    /**
     * Check reponse from login server and set attributes.
     *
     * @param str  $secret        secret associated with project
     * @param bool $checkSid      pass session ID to login server and check it in response
     * @param bool $checkReferrer check for HTTP referrer header to avoid bookmarking
     * @param str  $sessionStore  name of key where login information are stored in session
     * @param int  $sessionExpire number of seconds successful login is cached between requests
     */
    public function __construct($secret, $checkSid = true, $checkReferrer = true, $sessionStore = 'ZlabID', $sessionExpire = 300) {
        $this->sessionStore = $sessionStore;
        $this->sessionExpire = $sessionExpire;
        if (!isset($_SESSION)) {
            session_start();
        }
        if ($checkSid) {
            $this->sid = md5(session_id());
        }
        // try to load login from session
        if ($this->restoreResult()) {
            return;
        }
        foreach (array('result', 'username', 'salt', 'response') as $key) {
            // no required parameters from server in URL, so show login form
            if (!array_key_exists($key, $_GET) || !is_string($_GET[$key])) {
                return;
            }
        }

        if (!is_null($this->sid) && (!array_key_exists('sid', $_GET) || $this->sid != $_GET['sid'])) {
            // this is used to check validity of stored URL result as support or an alternative for
            // referrer checking
            $this->setResult('INVALID_SID');
            return;
        }

        if ($checkReferrer && !array_key_exists('HTTP_REFERER', $_SERVER)) {
            // if user bookmarks valid response URL from login server then no future checks are made
            // HTTP referrer header is not set for bookmarks
            $this->setResult('INVALID_REFERRER');
            return;
        }

        $this->username = $_GET['username'];
        $salt = hex2bin($_GET['salt']);
        if ($_GET['response'] != sha1($secret.$salt.$this->username.$_GET['result'])) {
            $this->isInvalid = true;
            $this->setResult($_GET['result']);
            return;
        }

        $this->isValid = true;
        $this->result = $_GET['result'];
        if (array_key_exists('firstname', $_GET)) {
            $this->firstname = $_GET['firstname'];
        }
        if (array_key_exists('lastname', $_GET)) {
            $this->lastname = $_GET['lastname'];
        }
        if (array_key_exists('class', $_GET)) {
            $this->class = $_GET['class'];
        }

        $_SESSION[$sessionStore] = array(
            'result' => $this->result,
            'username' => $this->username,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'class' => $this->class,
            'expire' => time() + $sessionExpire,
        );
    }

    /**
     * Remove cached login information from session.
     *
     * @return bool wheter session existed
     */
    public function logout() {
        $this->isValid = false;
        $this->isInvalid = false;
        $this->result = null;
        $this->username = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->class = null;
        if (!array_key_exists($this->sessionStore, $_SESSION)) {
            return false;
        }
        unset($_SESSION[$this->sessionStore]);
        return true;
    }

    /**
     * Convert error to human readable text and set as result.
     *
     * @param str $error code to be converted
     *
     * @return void
     */
    private function setResult($error) {
        if (array_key_exists($error, $this->errors)) {
            $this->result = $this->errors[$error];
        } else {
            $this->result = $this->unknownError;
        }
    }

    /**
     * Restore cached login from session and set attributes.
     *
     * @return bool wheter cached data are still valid
     */
    private function restoreResult() {
        if (!array_key_exists($this->sessionStore, $_SESSION)) {
            return false;
        }
        foreach (array('result', 'username', 'lastname', 'firstname', 'class', 'expire') as $key) {
            if (!array_key_exists($key, $_SESSION[$this->sessionStore])) {
                unset($_SESSION[$this->sessionStore]);
                return false;
            }
        }
        $now = time();
        if ($now >= $_SESSION[$this->sessionStore]['expire']) {
            // cached information are expired
            unset($_SESSION[$this->sessionStore]);
            return false;
        }
        $_SESSION[$this->sessionStore]['expire'] = $now + $this->sessionExpire;
        $this->isValid = true;
        $this->result = $_SESSION[$this->sessionStore]['result'];
        $this->username = $_SESSION[$this->sessionStore]['username'];
        $this->lastname = $_SESSION[$this->sessionStore]['lastname'];
        $this->firstname = $_SESSION[$this->sessionStore]['firstname'];
        $this->class = $_SESSION[$this->sessionStore]['class'];
        return true;
    }
}
