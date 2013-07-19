<?php
$pricetype='redis';
#$pricetype='memcache';
#$pricetype='marketdata';

require_once($pricetype.'price.php');
require_once('db.inc.php');

if (array_key_exists('region',$_GET) & is_numeric($_GET['region']))
{
$region=$_GET['region'];
}
else
{
$region=10000002;
}

$sql='select regionID,regionName from mapRegions order by regionName';
$stmt = $dbh->prepare($sql);
$stmt->execute(array($typeid));
while ($row = $stmt->fetchObject())
{
    $regionnames[$row->regionID]=$row->regionName;
}

if (array_key_exists($region,$regionnames))
{
$name=$regionnames[$region];
}
else
{
$region=10000002;
$name=$regionnames[$region];
}
if ($region==10000002);
{
$region='forge';
}

?>
<html>
<head>
<title>Ore isk/M3</title>
  <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/ore/jquery.tablesorter.min.js"></script>
<script>

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
        $(function() {
        $('.actionRow').hover(function() {
            $(this).children().toggleClass('hover');
        },
        function() {
            $(this).children().toggleClass('hover');
        });}); 
    } 
); 
   
</script>
  <link href="/ore/style.css" rel="stylesheet" type="text/css"/>

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
            list($price,$buyprice) = returnprice($typeid,$region);
            echo $price;
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
<h1>Ore Values</h1>
<table border=1 id="ore" class="tablesorter">
<thead>
<?
echo "<tr><th>Ore</th>";
foreach ($minerals as $typeid => $typename)
{
echo "<th>$typename</th>";
}
echo "<th>Volume</th><th>Required</th><th>Isk/M3</th><th>Isk Each</th></tr></thead><tbody>\n";


$sql='select materialTypeID,quantity,volume,portionSize from invTypes it ,invTypeMaterials itm where it.typeid=itm.TypeID and it.typeid=? order by materialTypeID';

$stmt = $dbh->prepare($sql);

$ores=array(1230=>"Veldspar",17470=>"Concentrated Veldspar",17471=>"Dense Veldspar",1228=>"Scordite",17463=>"Condensed Scordite",17464=>"Massive Scordite",1224=>"Pyroxeres",17459=>"Solid Pyroxeres",17460=>"Viscous Pyroxeres",18=>"Plagioclase",17455=>"Azure Plagioclase",17456=>"Rich Plagioclase",1227=>"Omber",17867=>"Silvery Omber",17868=>"Golden Omber",20=>"Kernite",17452=>"Luminous Kernite",17453=>"Fiery Kernite",1226=>"Jaspet",17448=>"Pure Jaspet",17449=>"Pristine Jaspet",1231=>"Hemorphite",17444=>"Vivid Hemorphite",17445=>"Radiant Hemorphite",21=>"Hedbergite",17440=>"Vitric Hedbergite",17441=>"Glazed Hedbergite",1229=>"Gneiss",17865=>"Iridescent Gneiss",17866=>"Prismatic Gneiss",1232=>"Dark Ochre",17436=>"Onyx Ochre",17437=>"Obsidian Ochre",1225=>"Crokite",17432=>"Sharp Crokite",17433=>"Crystalline Crokite",19=>"Spodumain",17466=>"Bright Spodumain",17467=>"Gleaming Spodumain",1223=>"Bistot",17428=>"Triclinic Bistot",17429=>"Monoclinic Bistot",22=>"Arkonor",17425=>"Crimson Arkonor",17426=>"Prime Arkonor",11396=>"Mercoxit",17869=>"Magma Mercoxit",17870=>"Vitreous Mercoxit");

$bestore=array(34=>17471,35=>17464,36=>17456,37=>17868,38=>17445,39=>17433,40=>17426);

foreach ($ores as $typeid => $typename)
{
    echo "<tr class='actionrow'><th>".$typename."</th>";
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
    echo "<td id='iskeach-".$typeid."'>&nbsp;</td></tr>\n";
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
</body>
</html>
