<?php

$memcache = new Memcache;
$memcache->connect('localhost', 11211) or die ("Could not connect");
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

$regionkey=$region;
if ($region==10000002)
{
$regionkey='forge';
}


?>
<html>
<head>
<title>Ice isk/M3</title>
  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/ore/jquery.tablesorter.min.js"></script>
<script>

function calculatecosts()
{  
    taxrate=1-(parseFloat(document.getElementById("tax").value/100));
    besticem3=0;
    besticem3value=0;
    besticeeach=0;
    besticeeachvalue=0;
    for (ice in ices)
    {
        total=0;
        for (material in materials)
        {     
              if (document.getElementById("quantity-" + ices[ice] + "-" + materials[material]) !=undefined)
              {
                  quantity=parseInt(document.getElementById("quantity-" + ices[ice] + "-" + materials[material]).innerHTML);
                  price=parseFloat(document.getElementById("material-"+ materials[material]).innerHTML);
                  total=total+(quantity*price);
              }
        }
        volume=parseFloat(document.getElementById("volume-" + ices[ice]).innerHTML);
        portion=parseInt(document.getElementById("portion-" + ices[ice]).innerHTML);
        iskm3=total/(volume*portion);
        iskeach=total/portion;
 
        document.getElementById("iskm3-"+ ices[ice]).innerHTML=Math.floor(iskm3*taxrate*100)/100;
        document.getElementById("iskeach-"+ ices[ice]).innerHTML=Math.floor(iskeach*taxrate*100)/100;
    }

}
$(document).ready(function() 
    { 
        $("#ice").tablesorter(); 
        
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
$materialsql='select typename,typeid from (select distinct materialtypeid from invTypeMaterials where typeid in ( select typeid from invTypes where groupid=465)) innr join invTypes on (materialtypeid=typeid)';
$matstmt = $dbh->prepare($materialsql);
$matstmt->execute();
$materials=array();
while ($row = $matstmt->fetchObject())
{
$materials[$row->typeid]=$row->typename;
$displaymaterials[]=$row->typeid;
}

foreach ($materials as $typeid => $typename)
{


echo "<td>".$typename.'</td><td id="material-'.$typeid.'">';

    $pricedata = $memcache->get($regionkey.'sell-'.$typeid);
    $values=explode("|",$pricedata);
    $price=$values[0];
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


<form action="/ore/ice.html" method="GET"><select name=region>

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
<h1>Ice Values</h1>
<table border=1 id="ice" class="tablesorter">
<thead>
<?
echo "<tr><th>Ice</th>";
foreach ($materials as $typeid => $typename)
{
echo "<th>$typename</th>";
}
echo "<th>Volume</th><th>Required</th><th>Isk/M3</th><th>Isk Each</th></tr></thead><tbody>\n";


$sql='select materialTypeID,quantity,volume,portionSize from invTypes it ,invTypeMaterials itm where it.typeid=itm.TypeID and it.typeid=? order by materialTypeID';

$stmt = $dbh->prepare($sql);

$icesql='select typename,typeid from invTypes where groupid=465 and published =1 and typeid not in (28627,28628)';
$icestmt = $dbh->prepare($icesql);
$icestmt->execute();
$ices=array();
while ($row = $icestmt->fetchObject())
{
$ices[$row->typeid]=$row->typename;
}


foreach ($ices as $typeid => $typename)
{
    echo "<tr><th>".$typename."</th>";
    $stmt->execute(array($typeid));
    $position=0;
    while ($row = $stmt->fetchObject()){
        while ($row->materialTypeID != $displaymaterials[$position]){
            $position++;
            echo "<td>&nbsp;</td>";
        }
        echo "<td id='quantity-".$typeid."-".$row->materialTypeID."''>".$row->quantity."</td>";
        $volume=$row->volume;
        $portion=$row->portionSize;
            $position++;
    }
    while ($position<count($materials))
    {
        $position++;
        echo "<td>&nbsp;</td>";
    }
    echo "<td id='volume-".$typeid."'>".$volume."</td>";
    echo "<td id='portion-".$typeid."'>".$portion."</td>";
    echo "<td id='iskm3-".$typeid."'>&nbsp;</td>\n";
    echo "<td id='iskeach-".$typeid."'>&nbsp;</td>\n";
}



?>
</tbody>
</table>

<script>
<?
echo "ices=[".join(",",array_keys($ices))."];\n";
echo "materials=[".join(",",array_keys($materials))."];\n";
?>
calculatecosts();
</script>
<?php include('/home/web/fuzzwork/analytics.php'); ?>
</body>
</html>
