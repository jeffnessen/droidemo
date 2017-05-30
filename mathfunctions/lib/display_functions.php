<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function displayAuthorizedForms($authorizedForms) {
    $elements = count($authorizedForms);
    echo "<p>You have access to $elements applications</p>";
    for ($i = 0; $i < $elements; $i++) {
        $formid = $authorizedForms[$i]['FormID'];
        echo "<p class='applist'><a href='http://www.automadoc.com/SOAR/questions.php?formid=$formid'>".$authorizedForms[$i]['ShortDescription']."</a></p>";
    }
}
function displayQuestions($questions){
    $elements = count($questions);
    for ($i = 0; $i < $elements; $i++) {
        $subelements = count($questions[$i]);
        for ($i2 = 0; $i2 < $subelements; $i2++) {
            $question = $questions[$i][$i2]['QuestionText'];
            $varType = $questions[$i][$i2]['VarType'];
            $required = $questions[$i][$i2]['Required'];
            $variableName = $questions[$i][$i2]['VariableName'];
            $answerLength = $questions[$i][$i2]['AnswerLength'];
            $defaultValue = $questions[$i][$i2]['DefaultValue'];
            $helpText = $questions[$i][$i2]['HelpText'];
            if ($required == 1) {
                                $reqflag = "<span class='required'>*</span>";
                                $inpclass = "req";
            }
            switch ($varType) {
                case "N":
                    $inpclass .= " number";
                    break;
                case "P": 
                    $inpclass .= " percent";
                    break;
                case "A":
                    $inpclass .= " alpha";
                    break;
                case "C":
                    $inpclass .= " currency";
                    break;
                case "EML":
                    $inpclass .= " email";
                    break;
                case "PHN":
                    $inpclass .= " phone";
                    break;
                }
            $inpclass = ltrim($inpclass);
            echo "<div><label for='$variableName'>$question$reqflag</label><input class='$inpclass' type='text' name='$variableName' value='$defaultValue' maxlength='$answerLength' /> <span class='helptext'>$helpText</span></div>\n";
            $reqflag = "";
            $inpclass = "";
            } // Inner for loop
    } // Outer for loop 
} //displayQuestions

function displayUsageInfo($usageinfo) {
    $elements = count($usageinfo);
    for ($i = 0; $i < $elements; $i++) {
        $formid = $usageinfo[$i]['FormID'];
        $rundate = $usageinfo[$i]['RunDate'];
        $requester = $usageinfo[$i]['EmailAddress'];
        echo "<div class=info>$formid : $rundate : $requester</div>";
    }
}

function displayProviderLogo($providerinfo) {
    $logofilename = $providerinfo['LogoFileName'];
    $providername = $providerinfo['Name'];
    $logofile = "graphics/providers/$providername/logo/$logofilename";
    return $logofile;
}

?>