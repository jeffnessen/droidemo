<?php

/*
 * This library will be used to start, set and verify that a browser
 * has been authorized to access our software via email verification
 */
$salt="Pr0g3n7u5";

function checkCookie () {
    $emailHash = $_COOKIE["verification"];
    if (strlen($emailHash) < 32) {
           echo "Your browser appears to not be authenticated to use this service.<br />";
           echo "Enter your email in the form below to authorize your browser.<br />";
           echo "You will receive an email with a link to verify your address.<br />";
           echo "Your email address will be verified against the list of authorized emails.<br /><br />";
           echo "<form method=post action=verifyemail.php>";
           echo "Email address: <input type=text name=email><br />";
           echo "I am a current user and this is for a new computer<input type='checkbox' value='1' name='cuonc'><br />";
           echo "<input type=submit>";
           echo "</form>";
           return (FALSE);
    }
    else {
        return ($emailHash);
    }
} // checkCookie

function hashEmailAddress ($salt, $emailAddress) {
    if (!isset($salt)) die ("Unable to verify encryption, exiting");
    if (!isset($emailAddress)) die ("No email address to confirm, exiting");
    $saltedemail = $salt.$emailAddress;
    $emailhash = md5($saltedemail);
    return($emailhash);
} //hashEmailAddress

function setCookieHash($emailhash) {
    $result = setrawcookie("verification", $emailhash, time()+3600*24*90, "/SOAR/", "www.automadoc.com") or die("unable to set cookie, make sure cookies are enabled for this site");
    return ($result);
} //setCookieHash

function verifyAuthorizedEmail($formid, $dblink, $emailAddress) {
    $whitelist = getEmailWhitelist($formid, $dblink);
    if (in_array($emailAddress, $whitelist)) {
        return (TRUE);
    }
    else {
        return (FALSE);
    }
} //verifyAuthorizedEmail

function unhashCookie($salt, $emailHash, $emailAddress) {
    if ($emailHash == md5($salt.$emailAddress)) {
        return (TRUE);
    }
    else {
        return (FALSE);
    }
} //unhashCookie

?>