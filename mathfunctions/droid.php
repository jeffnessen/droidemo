<!doctype html>
<html>
<head>
	<script src="bower_components/angular/angular.min.js"></script>
	<script src="bower_components/jquery/dist/jquery.min.js"></script>
	<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="bower_components/angular-bootstrap/ui-bootstrap.min.js"></script>
	<script src="bower_components/angular-animate/angular-animate.min.js"></script>
	<script src="bower_components/angular-aria/angular-aria.min.js"></script>
	<script src="bower_components/angular-material/angular-material.min.js"></script>
	<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="bower_components/angular-material/angular-material.min.css">
<title>droid</title>
</head>
<body style="margin-left: 20px; margin-top: 20px; max-width: 940px;">
<center>
	<h2>Docker Return On Investment Demo</h2>
	<h3>For cloud native webscale applications</h3>
</center>
	<p>&nbsp;</p>
<?php
if ( $_POST["HandshakeKey"] != "Docker-ROI-Demo-2017" ) die("Bad handshake, goodbye!");

function compoundGrowth($percent, $startValue, $cycles) {
	$percent = $percent/100;
	$newValue = $startValue;
	for ($i = 1; $i <= $cycles; $i++) {
		$newValue = $newValue + ($newValue * $percent);
		$calcValue[$i] = $newValue;
	}
	return ($calcValue);
}

//require_once("lib/growthengine.php");
require_once("lib/number2word.php");
require_once("lib/financial_class.php");
//require_once("MPDF54/mpdf.php");
require_once("libchart/libchart/classes/libchart.php");
$fin = new Financial;

//Hardcoded values to use

$dockerPrice=3000;

/*
Get information from the angular app webhook for the reporting application`
*/

$today = date("M d, Y");
$timestamp = date("dmy-gis");

$firstName = $_POST["fname"];
$lastName = $_POST["lname"];
$Email = $_POST["email"];
$numberApps = $_POST["numapps"];
$devProvisionTime = $_POST["lengprov"];
$appGrowth = $_POST["appgrow"];
$testTime = $_POST["testweeks"];
$prodDeployTime = $_POST["prodweeks"];
$appRevenue = $_POST["revenue"];
$serversPerApp = $_POST["numservs"];
$usersPerApp = $_POST["usersapp"];
$projectLifetime = $_POST["projlife"];
$devTime = $_POST["devtime"];
$startUsers = $_POST["lusers"];

$monthLife = $projectLifetime * 12;

$filename = "Data/droid-$lastName-$timestamp.data";

//echo "storing output to: $filename<br/>";

//Do some front loading of calculated assumptions
$applicationGrowth = compoundGrowth($appGrowth, $startUsers, $monthLife);
$currentGoLive = (($devProvisionTime + $devTime + $testTime + $prodDeployTime) * 7) / 30;
$proposedGoLive = (($devTime * 7) + 3) / 30;
$goLiveDelta = $currentGoLive - $proposedGoLive;
$compoundingDelta = $monthLife - round($goLiveDelta);

$existingGrowth = compoundGrowth($appGrowth, $startUsers, $compoundingDelta);

$b=0;
echo "<p>Prepared for $firstName $lastName on $today</p>";
?>
<h3>Introduction</h3>
<div>
<p>This report will demonstrate the potential Return on Investment when moving from
traditional development and deployment methodologies to a DevOps methodology.
The benefit of making this move is realized in faster development and deployment
cycles, increased application security, lower rate of defects and improved
overall satisfaction, both cusotmer and employee</p>
</div>
<h3>Details</h3>
<p>The information is a combination of revenue growth and cost differences. each
 cost is based on the information you provided combined with industry averages
and best practices. The acceleration of the provisioning and deployment pipeline
is conservative and significantly better results are possible.</p>
<p>&nbsp;</p>
<div>
	<p>The table below shows the number of users and amount of revenue based on your
	previous input. This calulation assumes initial adoption by <?php echo convertnumber($startUsers); ?>
	users with your projected <?php echo convertnumber($appGrowth); ?> percent month over month growth.
<strong><center>Monthly user growth and associated revenue</center></strong>
<?php

echo "<table width=100% border=1>";
echo "<tr>";
echo "<th>Month Number</th> <th>User Count</th> <th>Revenue</th>";
echo "</tr>";
echo "<tr>";
echo "<td>Start</td><td>$startUsers</td><td>$".number_format($startUsers*$appRevenue,0,".",",")."</td></tr>";
for ($a=0; $a < $monthLife; $a++ ) {
	$b++;
	$revenueGrowth[$b] = $applicationGrowth[$b] * $appRevenue;
  echo "<tr>";
	echo "<td>$b</td><td>".ceil($applicationGrowth[$b])."</td><td>$". number_format($revenueGrowth[$b], 0, ".", ","). "</td></tr>";
	$totalRevenue = $totalRevenue + $revenueGrowth[$b];
}
echo "<tr><td>&nbsp;</td><td>Total Revenue</td><td>$".number_format($totalRevenue,0,".",",")."</td></tr>";
echo "</table>";
$b=0;
for ($a=0; $a < $compoundingDelta; $a++ ) {
	$b++;
	$existingRevenue[$b] = $existingGrowth[$b] * $appRevenue;
	$totalExistingRevenue = $totalExistingRevenue + $existingRevenue[$b];

	$revenueDelta = $totalRevenue - $totalExistingRevenue;
}
$totalServers = end($applicationGrowth) / $usersPerApp;

?>
<p>As this tables displays the total revenue over <?php echo convertnumber($monthLife); ?> months is
  <?php echo convertnumber(round($totalRevenue)); ?>. This number is only realized, of course, after the application goes
	live. The faster the application can go live, the sooner revenue can be generated. The amount below shows the effect
	going live <?php echo convertnumber(round($goLiveDelta)); ?> months earlier. </p>
	<p ><center><h2>Additional Revenue: $<?php echo number_format($revenueDelta,0,".",","); ?></h2></center></p>
	<?php echo "<h3> $".number_format($totalExistingRevenue,0,".",",") . " vs. $" . number_format($totalRevenue,0,".",","); ?></h3><br/>
<p>This incremental revenue comes from <?php echo convertnumber(round($goLiveDelta)); ?> additional months of compounded user growth.
	<?php echo ucfirst(convertnumber(round($goLiveDelta))); ?> months on the calendar doesn't seem like much in the grand scheme of a company's
	existance but it is meaningful when measured in terms of actual dollars for a given
	calendar time period. Imagine if the competition gets to market a month earlier.
	The impact of this can effect user adoption rates, which can be modeled to determine
	impact by hitting back and filling out the form differently.</P>
	<P>The other import piece of the equation are the costs. Running a DevOps
		environment can be done for less money than a traditional virtualized environment
		as the numbers below demonstrate. </p>

<?php
$servCount = ceil($totalServers);
echo "Total Servers Required at end of analysis: ".number_format($servCount,0,".",",")."<br/>";
echo "Total Docker license costs: $".number_format(($servCount * $dockerPrice),0,".",",")."<br/>";
echo "Total Orchestrated VM license costs: $".number_format(($servCount * 7500),0,".",",")."<br/>";

echo "<p><center><h1>ROI ". number_format(((100-($servCount * $dockerPrice) / ($totalRevenue - $totalExistingRevenue))),2,".",",") ." Percent</h1></center></p>";
/*************   Now for the real math to calculate out all the formulas  *********************
$appLiveTime = $devProvisionTime + $devTime + $prodDeployTime;

/*
//Create a Chart
$chart = new VerticalBarChart(700,350);

	$serie1 = new XYDataSet();
	$serie1->addPoint(new Point("Maintenance",$EMCYTotal));
	$serie1->addPoint(new Point("Staffing", $ELCYTotal));
	$serie1->addPoint(new Point("Capex", $EDCYTotal));
	$serie1->addPoint(new Point("Upgrades", $EUCYTotal));
	$serie1->addPoint(new Point("Power", $EPCYTotal));
	$serie1->addPoint(new Point("Facility", $EFCYTotal));

	$serie2 = new XYDataSet();
	$serie2->addPoint(new Point("Maintenance",$PMCYTotal));
	$serie2->addPoint(new Point("Staffing", $PLCYTotal));
	$serie2->addPoint(new Point("Capex", $PDCYTotal));
	$serie2->addPoint(new Point("Upgrades", $PUCYTotal));
	$serie2->addPoint(new Point("Power", $PPCYTotal));
	$serie2->addPoint(new Point("Facility", $PFCYTotal));

	$dataSet = new XYSeriesDataSet();
	$dataSet->addSerie("Existing", $serie1);
	$dataSet->addSerie("Proposed", $serie2);
	$chart->setDataSet($dataSet);
	$chart->getPlot()->setGraphCaptionRatio(0.65);

	$chart->setTitle("Existing vs. Proposed Costs for $projectLifetime years");
	$charname = "Charts/".$entryid.".png";
	$chart->render($charname);

// Debug output of variables here
// Comment out when not in use
/*
foreach ($_POST as $key => $value) {
	$foo = $key.": ".$value."\n";
	echo "$foo<br />";
	}
*/ /*
$report = "<html><head><title>Solution Opportunity Analysis Report - Server Migration Analysis</title><style>body {font-family: sans-serif; font-size: 12pt; }</style></head><body>";
$report .= "<center><h1>Server Solution Opportunity Analysis Report</h1><br />";
$report .= "<h2>Prepared for $customerName</h2>";
$report .= "<img src=$logo>";
$report .= "<h3>by $firstName $lastName of $varName</h3>";
$report .= "<h3>on $today</h3><br /><br /></center>SOAR&#8480; Powered by<br /><img src=http://www.automadoc.com/graphics/Logo3-LB-DropShadow-x-mini-size-transparent.png><br /><font size=1>Powering the decisions that empower your business<br />www.progentus.com<br />(888) 788-9843</font><br /><pagebreak />";
$report .= "<h3>Server Solution Overview</h3>";
$report .= "<p align=justify>This server solution analysis will look at the costs of running ".ConvertNumber($numberServers)." servers for the next ".ConvertNumber($projectLifetime)." years. Data provided can be used to determine if there are potential savings by migrating to a new server solution provided by $varName.  This is a high level analysis used to determine the merit of moving forward with a server migration project and considers capital and operational costs only as they relate to the servers in scope.  A more in-depth analysis is required to provide a more finite level of detail for business critical decisions.  Additional considerations should include:<ul><li>Application implications<ul><li>Technical impact in business critical applications<li>Financial impact</ul><li>Software maintenance costs<li>Storage, Network and other hardware assets<li>Alternative solutions<ul><li>For optimal financial return<li>Private Cloud<li>Public Cloud<li>Alternative server configurations</ul></ul>A review of the the following report will allow $customerName to make an informed decision before proceeding with any next steps.</p>";
$report .= "<h3>Input Data</h3>";
$report .= "<ul><li>Supplied</li>";
$report .= "<ul><li>Number of servers in scope: $numberServers</li>";
$report .= "<li>Current percentage virtualized: ".round($percentVirtual*100)."%</li>";
$report .= "<li>Server growth rate per year: $serverGrowthRate%</li>";
$report .= "<li>Cost of each existing server: $".number_format($existingServerCost)."</li>";
$report .= "<li>Useful life of a server before refresh: $projectLifetime years</li>";
$report .= "<li>Annual cost of upgrades per server: $".number_format($upgradeCost)."</li>";
$report .= "<li>Annual cost of maintenance per server: $".number_format($maintenanceCost)."</li>";
$report .= "<li>Annual floor space cost per rack: $".number_format($facilityCost)."</li>";
$report .= "<li>Servers are located in: $customerState</li>";
$report .= "<li>Number of IT Staff: $numberITStaff</li>";
$report .= "<li>Fully burdened staff cost per FTE: $".number_format($staffCost)."</li>";
$report .= "<li>Weighted Average Cost of Capital: ".round($hurdleRate*100,2)."%</li>";
$report .= "<li>Servers are depreciated on a straight-line schedule for $depreciationSchedule years</li></ul>";
$report .= "<li>Calculated</li>";
$report .= "<ul><li>Power cost in $customerState: $powerCost cents per KW/h</li>";
$report .= "<li>Current floor space utilization: $existingFootprint square feet</li>";
$report .= "<li>Average power cost growth rate: 2% per year</li>";
$report .= "<li>Existing server power consumption $existingServerPower Watts</li>";
$report .= "<li>Proposed server power consumption $proposedServerPower Watts</li>";
$report .= "<li>Servers refreshed per year: $annualServerRefresh (Evenly divided refresh schedule)</li>";
$report .= "<li>Number of potential physical servers managed by IT Staff: $physicalServersPerAdmin</li>";
$report .= "<li>Growth Servers</li><ul>";
for ($a = 1; $a <= $projectLifetime; $a++) {
	$report .= "<li>Growth servers added in year $a: ".round($annualGrowthServers[$a])."</li>";

}
$report .= "</ul><li>Total servers at the end of year $projectLifetime: ".number_format($finalServerCount)."</li>";
$report .= "</ul></ul>";
$report .= "<h3>Current Technology State</h3>";
$report .= "<p align=justify>$varName estimates that $customerName is currently utilizing ".ConvertNumber($existingFootprint)." square feet of data center space to host their servers in ".ConvertNumber($existingRacks)." racks. These servers currently consume ".ConvertNumber($existingPowerConsumption)." KW/h per year.</p>";
$report .= "<h3>Current Financial State</h3>";
$report .= "$existingTable</table>";
$report .= "<h3>Proposed Technical Changes</h3>";
$report .= "<h4>Virtualized Infrastructure</h4>";
$report .= "<p align=justify>$varName is estimating the current environment of ".ConvertNumber($numberServers)." servers, growing to ".ConvertNumber($finalServerCount)." over the next ".ConvertNumber($projectLifetime)." years can be replaced in a phased approach with a new virtual server environment consisting of ".ConvertNumber($vmHosts)." physical servers hosting virtual machines.  This total number of required servers includes accommodation for the ".ConvertNumber($finalServerCount)." virtual servers and additional capacity for an enhanced level of fault tolerance.  The estimated cost for this proposed solution is $".number_format($proposedInvestment)." including Hardware, Warranty, Software and Implementation Services.</p>";
$report .= "<h3>Proposed Solution Financial State</h3>";
$report .= "<p align=justify>$varName has built this financial model in the most conservative way.  The existing systems are phased out as they end their useful life.  While they still have useful life or book value, the servers are included in the power, facility, maintenance, and depreciation calculations. This method of calculation ensures there are no book value write-off expenses incurred, but does over-inflate the proposed solution costs.  The actual financial metrics you can expect to achieve will be better than what is represented here.</p>";
$report .= "<h4>Overall financial metrics</h4>";
$report .= "$proposedTable</table>";
$report .= "<br /><br /><table width=100% align=center border=0><tr><td align=center><img align=center src=http://www.automadoc.com/SOAR/$charname></td></tr></table><br />";
$report .= "<h3>Financial Outlook</h3>";
$report .= "<ul><li>Proposed Investment: $".number_format($proposedInvestment)."</li>";
$report .= "<li>TCO Savings: $".number_format($tcoSavings)."</li>";
$report .= "<li>ROI: $roi%</li>";
$report .= "<li>NPV: $".number_format($npv)."</li>";
$report .= "<li>IRR: ".number_format(($irr*100))."%</li>";
$report .= "</ul>";
/*
$report .= "<h3>Competitive Advantage</h3>";
$report .= "<table width=60% align=center border=1><tr><td width=25%>Cost Name</td><td width=25%>IBM</td><td width=25%>Cisco</td><td width=25%>IBM Advantage</td></tr>";
$report .= "<tr><td>Labor</td><td>$".number_format($PLCYTotal)."</td><td>$".number_format($compLabor)."</td><td>$".number_format($compLabor - $PLCYTotal)."</td></tr>";
$report .= "<tr><td>Depreciation</td><td>$".number_format($PDCYTotal)."</td><td>$".number_format($compHardware)."</td><td>$".number_format($compHardware - $PDCYTotal)."</td></tr>";
$report .= "<tr><td>Maintenance</td><td>$".number_format($PMCYTotal)."</td><td>$".number_format($compMaintenance)."</td><td>$".number_format($compMaintenance - $PMCYTotal)."</td></tr>";
$report .= "</table>";
*/
/*
$report .= "<h3>Summary</h3>";
if ($npv <= 0) {
	$report .= "<p align=justify>The above financial metrics indicate that $customerName is already acheiving better use of their financial resources than the server solution $varName is able to provide them.  The metrics in this report provide a high level view of the situation and may be different with an in-depth anaylsis.</p>";
} else
  	{ $report .= "<p align=justify>The preceding data present a scenario where $customerName engaging with $varName to architect and implement a new server environment would not only produce a superior technical solution, but a solution whose positive financial impact on the business merits serious consideration.</p>";
}
$report .= "<h3>Next Steps</h3>";
$report .= "<p align=justify>The best way to ensure you get a tailored solution that fits $customerName's specific situation is with a detailed BITE&#8480; service server assessment.  This service looks at the technical and financial metrics of each individual asset to ensure the optimum solution is proposed.  The analysis includes financial analysis using a GAAP compliant financial modeling tool.  This analysis is useful when selling the solution to the CFO or Board of Directors. A Statement of Work can be provided to $customerName by $varName with details and cost information.</p>";
$report .= "</body></html>\n";

//$fp = fopen($filename, "w+") or die("Could not open report file");
//fwrite($fp, $report);
echo $report;
//fclose($fp);
*/
/*
$pdfFilename = "PDF/ServerAnalysis-".$entryid.".pdf";
$mpdf=new mPDF('-s');
$mpdf ->UseOnlyCoreFonts = true;
$mpdf ->dpi = 150;
$mpdf ->img_dpi = 100;
$mpdf ->SetTitle($docname);
$mpdf ->SetAuthor("Jeff Nessen");
$mpdf ->SetWatermarkText("BETA");
$mpdf ->showWatermarkText = true;
$mpdf ->watermark_font = 'DejaVuSansCondensed';
$mpdf ->watermarkTextAlpha = 0.1;
$mpdf ->SetDisplayMode('fullpage');
$mpdf ->WriteHTML($report);
$data = $mpdf ->Output($pdfFilename, "S");

// email stuff (change data below)
$to = $Email;
$from = "info@progentus.com";
$subject = "Here is your Docker ROI Report";
$message = "<p>$firstName,<br>Thank you for the opportunity to provide the following information to you.  I hope that you find value in the demo offered by Jeff Nessen.  For additional information and to find out how you can have a custom developed ROI application for your solutions, call Jeff Nessen at 424-235-3339 or email me at jeff@jeffnessen.com today.  <br><br>Sincerely, <br>Jeffrey A. Nessen<br></p>";

// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;

// encode data (puts attachment in proper format)
$attachment = chunk_split(base64_encode($data));


// main header (multipart mandatory)
$headers  = "From: ".$from.$eol;
$headers .= "MIME-Version: 1.0".$eol;
$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol.$eol;
$headers .= "Content-Transfer-Encoding: 7bit".$eol;
$headers .= "This is a MIME encoded message.".$eol.$eol;

// message
$headers .= "--".$separator.$eol;
$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
$headers .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
$headers .= $message.$eol.$eol;

// attachment
$headers .= "--".$separator.$eol;
$headers .= "Content-Type: application/octet-stream; name=\"".$pdfFilename."\"".$eol;
$headers .= "Content-Transfer-Encoding: base64".$eol;
$headers .= "Content-Disposition: attachment".$eol.$eol;
$headers .= $attachment.$eol.$eol;
$headers .= "--".$separator."--";

// send message
mail($to, $subject, "", $headers);
*/
?>
<h3>Summary</h3>
<p>As this report clearly demonstrates there are financial benfits to becoming a
	DevOps enterprise. The technical beneifts of making this migration are the
	enabling underpinnings that form a foundation on which to build. The most basic
	tennants of this foundation are:
	<ul>
		<li>Scalable, orchestrated physical infrastructure</li>
		<li>Continer and container orchestration for applications</li>
		<li>Pipeline management tools to automate workflow</li>
		<li>Integration of teams around functions not technologies</li>
	</ul>
</body>
</html>
