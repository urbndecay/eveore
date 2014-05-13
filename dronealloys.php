<?php
$pricetype='redis';
#$pricetype='memcache';
#$pricetype='marketdata';

require_once($pricetype.'price.php');

require_once('db.inc.php');

if (array_key_exists('region',$_GET) & is_numeric($_GET['region'])) {
	$region = $_GET['region'];
} else {
	$region = 10000002;
}

$sql = 'select regionID,regionName from mapRegions order by regionName';

$stmt = $dbh->prepare($sql);
$stmt->execute(array($typeid));

while ($row = $stmt->fetchObject()) {
	$regionnames[$row->regionID] = $row->regionName;
}

if (array_key_exists($region,$regionnames)) {
	$name = $regionnames[$region];
} else {

	$region = 10000002;
	$name = $regionnames[$region];
}

$regionkey = $region;
if ($region == 10000002) {
	$regionkey='forge';
}


?>
<html>
<head>
	<title>EveORE</title>
	<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/ore/jquery.tablesorter.min.js"></script>

	<script type="text/javascript">

		function calculatecosts()
		{  
			taxrate=1-(parseFloat(document.getElementById("tax").value/100));
			bestorem3=0;
			bestorem3value=0;
			bestoreeach=0;
			bestoreeachvalue=0;
			for (ore in orealloy)
			{
				total=0;
				for (mineral in minerals)
				{     
					  if (document.getElementById("quantity-" + orealloy[ore] + "-" + minerals[mineral]) !=undefined)
					  {
						  quantity=parseInt(document.getElementById("quantity-" + orealloy[ore] + "-" + minerals[mineral]).innerHTML);
						  price=parseFloat(document.getElementById("mineral-"+ minerals[mineral]).innerHTML);
						  total=total+(quantity*price);
					  }
				}
				volume=parseFloat(document.getElementById("volume-" + orealloy[ore]).innerHTML);
				portion=parseInt(document.getElementById("portion-" + orealloy[ore]).innerHTML);
				iskm3=total/(volume*portion);
				iskeach=total/portion;
				if (iskm3>bestorem3value)
				{
					if (parseInt(ore)<ores.length)
					{
					   bestorem3=orealloy[ore];
					   bestorem3value=iskm3;
					}
				}
				if (iskeach>bestoreeachvalue)
				{
					if (parseInt(ore)<ores.length)
					{
					   bestoreeach=orealloy[ore];
					   bestoreeachvalue=iskeach;
					}
				}
 
				document.getElementById("iskm3-"+ orealloy[ore]).innerHTML=Math.floor(iskm3*taxrate*100)/100;
				document.getElementById("iskeach-"+ orealloy[ore]).innerHTML=Math.floor(iskeach*taxrate*100)/100;
			}
			$("#iskm3-"+bestorem3).toggleClass('bestore',true);
			$("#iskeach-"+bestoreeach).toggleClass('bestore',true);

		}
		$(document).ready(function() 
			{ 
				$("#ore").tablesorter(); 
				$("#dronepoo").tablesorter(); 
				$("#alloysell").tablesorter(); 
        
			} 
		); 
	</script>

	<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body style="background:silver;">
<h1><? echo $name; ?> 5% of market sell prices</h1>
<table border=1 class="tablesorter">
<tr>
<?
$minerals = array(34=>"Tritanium",35=>"Pyerite",36=>"Mexallon",37=>"Isogen",38=>"Nocxium",39=>"Zydrine",  40=>"Megacyte", 11399=>"Morphite");
$dispminerals = array(34,35,36,37,38,39,40,11399);
foreach ($minerals as $typeid => $typename)
{


	echo "<td>".$typename.'</td><td id="mineral-'.$typeid.'">';

	list($price,$buyprice) = returnprice($typeid,$regionkey);
	if ($price)
	{
		echo $price;
	}
	echo "</td>";
}
?>
</tr>
</table>
<input type=text length=3 id=tax value=0 onchange="calculatecosts();"><label for=tax>Tax rate/Refining loss %</label>


<form action="/ore/" method="GET"><select name=region>
<?
foreach ($regionnames as $id => $regionname)
{
	if ($id==$region)
	{
		echo "<option value='".$id."' selected=selected>".$regionname."</option>\n";
	}
	else
	{
		echo "<option value='".$id."'>".$regionname."</option>\n";
	}

}
?>
</select>
<input type="submit" value="Change region"></form>
<h1><a href="full.html">Ore Values</a></h1>
<table border=1 id="ore" class="tablesorter">
<thead>
<?
echo "<tr><th>Ore</th>";
foreach ($minerals as $typeid => $typename)
{
	echo "<th>$typename</th>";
}
echo "<th>Volume</th><th>Required</th><th>Isk/M3</th><th>Isk Each</th><th>ISK per m3 after refining</th></tr></thead><tbody>\n";


$sql='select materialTypeID,quantity,volume,portionSize from invTypes it ,invTypeMaterials itm where it.typeid=itm.TypeID and it.typeid=? order by materialTypeID';

$stmt = $dbh->prepare($sql);

$ores=array(1230=>"Veldspar",1228=>"Scordite",1224=>"Pyroxeres",18=>"Plagioclase",1227=>"Omber",20=>"Kernite",1226=>"Jaspet",1231=>"Hemorphite",21=>"Hedbergite",1229=>"Gneiss",1232=>"Dark Ochre",1225=>"Crokite",19=>"Spodumain",1223=>"Bistot",22=>"Arkonor",11396=>"Mercoxit");

$bestore=array(34=>1230,35=>1228,36=>18,37=>1227,38=>1231,39=>1225,40=>22);

foreach ($ores as $typeid => $typename)
{
	echo "<tr><th>".$typename."</th>";
	$stmt->execute(array($typeid));
	$position=0;
	while ($row = $stmt->fetchObject()){
		while ($row->materialTypeID != $dispminerals[$position]){
			$position++;
			echo "<td>&nbsp;</td>";
		}
		$class="notbest";
		if ($bestore[$row->materialTypeID]==$typeid)
		{
			$class="bestore";
		}
		echo "<td id='quantity-".$typeid."-".$row->materialTypeID."' class='".$class."'>".$row->quantity."</td>";
		$volume=$row->volume;
		$portion=$row->portionSize;
		$position++;
	}
	while ($position<8)
	{
		$position++;
		echo "<td>&nbsp;</td>";
	}
	echo "<td id='volume-".$typeid."'>".$volume."</td>";
	echo "<td id='portion-".$typeid."'>".$portion."</td>";
	echo "<td id='iskm3-".$typeid."'>&nbsp;</td>\n";
	echo "<td id='iskeach-".$typeid."'>&nbsp;</td>\n";
	echo "<td id='iskrefinedm3-".$typeid."'>&nbsp;</td></tr>\n";
}



?>
</tbody>
</table>
<h1>Drone Alloy Values</h1>
<table border=1 id="dronepoo" class="tablesorter">
<thead>
<?
echo "<tr><th>Alloy</th>";
foreach ($minerals as $typeid => $typename)
{
	echo "<th>$typename</th>";
}
echo "<th style='display:none;'>Volume</th><th style='display:none;'>Required</th><th style='display:none;'>Isk/M3</th><th>Isk Each</th></tr></thead><tbody>\n";


$sql='select materialTypeID,quantity,volume,portionSize from invTypes it ,invTypeMaterials itm where it.typeid=itm.TypeID and it.typeid=? order by materialTypeID';

$stmt = $dbh->prepare($sql);

$alloy=array(11724=>"Glossy Compound",11725=>"Plush Compound", 11732=>"Sheen Compound", 11733=>"Motley Compound",11734=>"Opulent Compound",11735=>"Dark Compound", 11736=>"Lustering Alloy",11737=>"Precious Alloy", 11738=>"Lucent Compound",11739=>"Condensed Alloy",11740=>"Gleaming Alloy", 11741=>"Crystal Compound");

foreach ($alloy as $typeid => $typename)
{
	echo "<tr><th>".$typename."</th>";
	$stmt->execute(array($typeid));
	$position=0;
	while ($row = $stmt->fetchObject()){
		while ($row->materialTypeID != $dispminerals[$position]){
			$position++;
			echo "<td>&nbsp;</td>";
		}
		echo "<td id='quantity-".$typeid."-".$row->materialTypeID."'>".$row->quantity."</td>";
		$volume=$row->volume;
		$portion=$row->portionSize;
		$position++;
	}
	while ($position<8)
	{
		$position++;
		echo "<td>&nbsp;</td>";
	}
	echo "<td style='display:none;' id='volume-".$typeid."'>".$volume."</td>";
	echo "<td style='display:none;' id='portion-".$typeid."'>".$portion."</td>";
	echo "<td style='display:none;' id='iskm3-".$typeid."'>&nbsp;</td>\n";
	echo "<td id='iskeach-".$typeid."'>&nbsp;</td></tr>\n";
}



?>
</tbody>
</table>
<h1>Alloy Region Sell</h1>
<table border=1 class="tablesorter" id="alloysell">
<thead>
<tr><th>Alloy Name</th><th>Isk Each</th></tr>
</thead>
<tbody>
<?
foreach ($alloy as $typeid => $typename)
{


	echo "<tr><th>".$typename.'</th><td id="alloy-'.$typeid.'">';
	list($price,$buyprice) = returnprice($typeid,$regionkey);
	if ($price)
	{
		echo $price;
	}

	echo "</td></tr>\n";
}
?>
</tbody>
</table>


<script>
<?
echo "ores=[".join(",",array_keys($ores))."];\n";
echo "orealloy=[".join(",",array_keys($ores)).",".join(",",array_keys($alloy))."];\n";
echo "minerals=[".join(",",array_keys($minerals))."];\n";
?>
calculatecosts();
</script>
<?php include('/home/web/fuzzwork/analytics.php'); ?>
</body>
</html>
