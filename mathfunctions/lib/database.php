<?php

function opendb() {
	$SERVER = "cloud2.internal.progentus.com";
	$USER = "soar";
	$PASSWORD = "S0ar!";
	$DATABASE = "soar";

	$dblink = mysql_connect($SERVER, $USER, $PASSWORD) or die("Unable to connect to database server ".mysql_error());
	$testval = mysql_selectdb($DATABASE, $dblink) or die("Unable to connect to database ".mysql_error());
	
	return $dblink;
} //opendb

function getFieldList($formid, $dblink) {
	$query = "SELECT * FROM FieldMapping WHERE FormID='$formid'";
	$result = mysql_query($query, $dblink) or die("Unable to access Field Mapping Data");
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$fieldlist[$i] = $row;
		$i++;
	}
	return $fieldlist;
} //getFieldList

function getRequiredFields($formid, $dblink) {
	$query = "SELECT * FROM FormTextMap WHERE FormID='$formid'";
	$result = mysql_query($query, $dblink) or die("Unable to access Field Mapping Data");
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
            $textblockid = $row['TextBlockID'];
            $query2 = " SELECT TextBlockID, TextQuestionMap.QuestionID, DisplayPrecision, VarType, VariableName FROM TextQuestionMap LEFT JOIN Questions ON TextQuestionMap.QuestionID=Questions.QuestionID WHERE TextBlockID='$textblockid'";
            $result2 = mysql_query($query2, $dblink) or die("Unable to gather require field data");
            while ($row2 = mysql_fetch_assoc($result2)) {
                $fieldlist[$i] = $row2;
		$i++;
            }
        }
	return $fieldlist;
} //getFieldList

function getAssumptions($formid, $dblink) {
	$query = "SELECT * FROM Assumptions WHERE FormID='$formid'";
	$result = mysql_query($query, $dblink) or die("Unable to locate SOAR assumptions");
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$assumption[$i] = $row;
		$i++;
	}
	return $assumption;
} //getAssumptions

function getTextBlocks($formid, $dblink) {
	$query = "SELECT * FROM TextBlocks WHERE FormID='$formid' ORDER BY BlockOrder";
	$result = mysql_query($query, $dblink) or die("Unable to locate SOAR assumptions");
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$textblock[$i] = $row;
		$i++;
	}
	return $textblock;
} //getTextBlocks

function getFormulas($formid, $dblink) {
	$query = "SELECT * FROM Formulas WHERE FormID='$formid'";
	$result = mysql_query($query, $dblink) or die("Unable to locate SOAR assumptions");
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$formula[$i] = $row;
		$i++;
	}
	return $formula;
} //getFormulas

function getDocumentHead($formid, $dblink) {
	$query = "SELECT * FROM DocumentHeads WHERE FormID='$formid'";
	$result = mysql_query($query, $dblink) or die("Unable to locate SOAR assumptions");
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$dochead[$i] = $row;
		$i++;
	}
	return $dochead;
} // getDocumentHead

function getDocumentTail($formid, $dblink) {
	$query = "SELECT * FROM DocumentTails WHERE FormID='$formid'";
	$result = mysql_query($query, $dblink) or die("Unable to locate SOAR assumptions");
	$i = 0;
	while ($row = mysql_fetch_assoc($result)) {
		$doctail[$i] = $row;
		$i++;
	}
	return $doctail;
} //getDocumentTail

function getEmailWhitelist($formid, $dblink) {
    $query = "SELECT * FROM Whitelist WHERE FormID='$formid'";
    $result = mysql_query($query, $dblink) or die("Unable to locate email Whitelist");
    $i = 0;
    while ($row = mysql_fetch_assoc($result)) {
            $whitelist[$i] = $row;
            $i++;
    }
    return $whitelist;
}  //getEmailWhitelist

function verifyDomain ($email, $dblink) {
    $domain = strtolower(substr($email, stripos($email, "@")+1));
    $query = "SELECT * FROM AuthorizedDomains WHERE DomainName='$domain'";
//    echo "$query<br />";
    $result = mysql_query($query, $dblink) or die("Unable to obtain email validation");
    $row = mysql_fetch_assoc($result);
    $providerid = $row['ProviderID'];
    if ($providerid) { 
        return $providerid;
    } else {
    return NULL;
    } //if providerid
} //verifyDomain

function getAuthorizedForms ($emailHash, $dblink) {
    $query = "SELECT WhitelistEmails.FormID, ShortDescription FROM WhitelistEmails LEFT JOIN Forms on WhitelistEmails.FormID = Forms.FormID WHERE EmailHash='$emailHash'";
    $result = mysql_query($query, $dblink) or die("There was an error accessing your application list. ". mysql_error());
    $i = 0;
    while($row = mysql_fetch_assoc($result)) {
        $authorizedForm[$i] = $row;
        $i++;
    }
    return $authorizedForm;
}  //getAuthorizedForms

function getFormQuestions ($formid, $emailHash, $dblink) {
    $query = "SELECT TextBlockID FROM TextBlocks WHERE FormID='$formid'";
    $result = mysql_query($query, $dblink) or die("There was an error accessing the question to build you input form.");
    //echo "<p class=info>There were ".mysql_num_rows($result)." rows found in the question database</p>";
    $i = 0;
    while($row = mysql_fetch_assoc($result)) {
        $textblockid = $row['TextBlockID'];
        $query2 = "SELECT  DefaultValue, Qorder, QuestionText, Required, VariableName, AnswerLength, HelpText, VarType FROM TextQuestionMap LEFT JOIN Questions ON Questions.QuestionID=TextQuestionMap.QuestionID WHERE TextBlockID='$textblockid' ORDER BY Qorder";
        $result2 = mysql_query($query2, $dblink) or die("No questions found for your form");
        $i2 = 0;
        while ($row2 = mysql_fetch_assoc($result2)) {
            $questions[$i][$i2] = $row2;
            //echo "<p class=info>Question [$i][$i2]: ".$questions[$i][$i2]['QuestionText']."</p>";
            //echo "<p class=info>HelpText [$i][$i2]: ".$questions[$i][$i2]['HelpText']."</p>";
            $i2++;
            }
    $i++;
    }
    return $questions;
} //getFormQuestions

function addUser($email, $emailHash, $providerID, $dblink) {
    $query = "INSERT INTO People (EmailAddress, EmailHash, ProviderID) VALUES ('$email', '$emailHash', '$providerID')";
    $result = mysql_query($query, $dblink);
    if (!$result ) {
        return FALSE;
    } else {
        return TRUE;
    }
} //addUser

function addQuestion($post, $dblink) {
    $textblockid = mysql_escape_string($post['TextBlockID']);
    $questiontext = mysql_escape_string($post['QuestionText']);
    $helptext = mysql_escape_string($post['HelpText']);
    $required = $post['Required'];
    if ($required == 'on') $required = 1;
    $variablename = mysql_escape_string($post['VariableName']);
    $defaultvalue = mysql_escape_string($post['DefaultValue']);
    $answerlength = mysql_escape_string($post['AnswerLength']);
    $vartype = mysql_escape_string($post['VarType']);
    $query = "INSERT INTO Questions (QuestionText, VariableName, AnswerLength, VarType, Required, HelpText) VALUES ('$questiontext', '$variablename', '$answerlength', '$vartype', '$required', '$helptext')";
    $result = mysql_query($query, $dblink) or die(mysql_error());
    $questionid = mysql_insert_id($dblink) or die(mysql_error());
    $query2 = "INSERT INTO TextQuestionMap (TextBlockID, DefaultValue, QuestionID, Qorder) VALUES ('$textblockid', '$defaultvalue', '$questionid', '999')";
    $result2 = mysql_query($query2, $dblink) or die(mysql_error());
    return TRUE;
} // addQuestions

function getProviderInfo($emailHash, $dblink) {
    $query = "SELECT ProviderID FROM People WHERE EmailHash='$emailHash'";
    $result = mysql_query($query) or die("Unable to get your company information, some features will not work");
    $row = mysql_fetch_assoc($result);
    $providerID = $row['ProviderID'];
    $query2 = "SELECT ProviderID, Name, LogoFileName, EmailSignature FROM Providers WHERE ProviderID='$providerID'";
    $result2 = mysql_query($query2, $dblink) or die("Unable to get company specifics, some features will not work");
    $row2 = mysql_fetch_assoc($result2);
    return $row2;
} // getProviderInfo

function getRoles($emailHash, $dblink) {
    global $domainAdmin, $departmentAdmin;
    $query = "SELECT DepartmentAdmin, DomainAdmin FROM People WHERE EmailHash='$emailHash'";
    $result = mysql_query($query, $dblink) or die("Unable to determine your permissions");
    $row = mysql_fetch_assoc($result);
    $domainAdmin = $row['DomainAdmin'];
    $departmentAdmin = $row['DepartmentAdmin'];
} //getRoles

function grantForms($providerID, $emailHash, $email, $dblink) {
    $authorizedForms = getAuthorizedForms($emailHash, $dblink);
    $elements = count($authorizedForms);
    for ($i = 0; $i < $elements; $i++) {
        $alreadyauth[$i]=$authorizedForms[$i]['FormID'];
    }
    $query = "SELECT FormID FROM Forms WHERE ProviderID='$providerID' AND OpenToDomain='1'";
    $result = mysql_query($query, $dblink) or die("Unable to associate applications");
    while ($row = mysql_fetch_assoc($result)) {
        $formid = $row['FormID'];
        $iquery = "INSERT INTO WhitelistEmails (EmailHash, EmailAddress, FormID) VALUES ('$emailHash', '$email', '$formid')";  
        if (!in_array($formid, $alreadyauth)) {
            $iresult = mysql_query($iquery, $dblink) or die("Unable to assign any applications to you");
        }
    }
    return TRUE;
} //grantForms

function addForm($post, $dblink) {
    $providerid = mysql_escape_string($post['ProviderID']);
    $shortdesciption = mysql_escape_string($post['ShortDescription']);
    $formid = hash("crc32b", $shortdesciption);
    $longdescription = mysql_escape_string($post['LongDescription']);
    $opentodomain = $post['OpenToDomain'];
    $opentodepartment = $post['OpenToDepartment'];
    $opentodpartners = $post['OpenToPartners'];
    $formtype = mysql_escape_string($post['FormType']);
    $query = "INSERT INTO Forms (FormID, ProviderID, ShortDescription, LongDescription, FormType, OpenToDomain, OpenToDepartment, OpenToPartners) VALUES ('$formid', '$providerid', '$shortdesciption', '$longdescription', '$formtype', '$opentodomain', '$opentodepartment', '$opentopartners')";
    $result = mysql_query($query, $dblink) or die(mysql_error());
    $questionid = mysql_insert_id($dblink) or die(mysql_error());
    return TRUE;
} // addQuestions

function getUserInfo($emailHash, $dblink) {
    $query = "SELECT * FROM People WHERE EmailHash='$emailHash'";
    $result = mysql_query($query, $dblink) or die("Unable to retrieve user info");
    $row = mysql_fetch_assoc($result);
    return $row;
} // getUserInfo

function getNextEntry($formid, $emailHash, $dblink) {
    $query = "SELECT PeopleID FROM People WHERE EmailHash='$emailHash'";
    $result = mysql_query($query, $dblink) or die("unable to lookup user information");
    $row = mysql_fetch_assoc($result);
    $peopleid = $row['PeopleID'];
    $query2 = "INSERT INTO AppUsage (FormID, PeopleID) VALUES ('$formid', '$peopleid')";
    $result2 = mysql_query($query2, $dblink) or die("Unable to generate next entry number". mysql_error());
    $entryid = mysql_insert_id($dblink);
    return $entryid;
}

function getUsageInfo($providerID, $dblink) {
    $query = "SELECT FormID, EmailAddress, RunDate, ProviderID FROM AppUsage LEFT JOIN People ON AppUsage.PeopleID=People.PeopleID WHERE ProviderID='$providerID'";
    $result = mysql_query($query, $dblink) or die("Unable to access usage records ".mysql_error());
    $i = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $usageinfo[$i] = $row;
        $i++;
    }
    return $usageinfo;
}

function getNotifiers($formid, $type, $dblink) {
    $query = "SELECT EmailAddress FROM Notifiers WHERE FormID='$formid' AND $type='1'";
    $result = mysql_query($query, $dblink) or die ("Unable to retrieve list of additional emails to notify");
    $notifiersemail = "";
    while ($row = mysql_fetch_assoc($result)) {
        $notifiersemail .= ", ".$row['EmailAddress'];
    }
    return $notifiersemail;
} // getNotifiers

function getSummaryReportRuns($dblink) {
    $query = "SELECT Forms.FormID FROM Forms LEFT JOIN Notifiers ON Forms.FormID = Notifiers.FormID WHERE EmailAddress IS NOT NULL AND SummaryReport='1' GROUP BY FormID";
    $result = mysql_query($query, $dblink);
    $i = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $reportRuns[$i]= $row['FormID'];
        $i++;
    }
    return $reportRuns;
}

?>
