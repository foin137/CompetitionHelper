<?php
//Localization
include_once("localization-save.php");
?>

<!DOCTYPE html>
<HTML>
<head>
<script charset="ISO-8859-1" type="text/javascript">
function showHide(elementID)
{
  var element = document.getElementById(elementID);
  if (element.style.display === 'none') {
      element.style.display = 'block';
  } else {
      element.style.display = 'none';
  }
  return false;     
}
function reload()
{
  setTimeout(function() {
  location.reload();
}, 20000);    
}
</script>
<title><?php echo $langArray['title'] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> 
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="Sat, 01 Dec 2001 00:00:00 GMT"> 
<style type="text/css">
 body {background-color:#ffeaa5}
 p {
    color:#00AA00;
    font-size:150%;
 }
p#normal {
    color:#3162dd;
    font-size:100%;
}
p#error {
    color:red;
    font-size:100%;
}
p#liste {
    color:black;
    font-size:100%;
    line-height:1.2;
    margin:0;
}
 h1 {
    color:blue;
    font-family:verdana;
    font-size:300%;
}

table#tnliste, th#tnliste, td#tnliste {
    border: 1px solid black;
    border-collapse: collapse;
}

table#tnliste tr:nth-child(even) {
    background-color: #eee;
}
table#tnliste tr:nth-child(odd) {
    background-color: #fff;
}
table#tnliste th {
    color: white;
    background-color: black;
}

table {
    display: table;
    border-collapse: separate;
    border-spacing: 2px;
    border-color: gray;
}

 th, td {
    padding: 5px;
} 

table#allborder {
    border: 1px solid black;
    border-collapse: collapse;
}

table#allborder th
{
  border: 1px solid black;
  border-collapse: collapse;
  padding:10px;
}
table#allborder td
{
  border: 1px solid black;
  border-collapse: collapse;
  padding:10px;
  font-size:120%;
}
</style>
</head>
<body onload='jvini();'>

<?php
//Load localization
include_once("localization-select.php");
?>

<p align = 'Center'><?php echo $langArray['title'] ?></p>
<?php
if (isset($_GET['id']))
{
  if (!isset($_GET['stat']))
    wertungAnzeigen($_GET['id']);
  else
    zeigeStation($_GET['stat'],$_GET['id']);
  ?>
  <script charset="ISO-8859-1" type="text/javascript">
  function jvini()
  {
    reload();
  }
  </script>
  <?php  
}
else
{
  ?>
  <script charset="ISO-8859-1" type="text/javascript">
  function jvini()
  {
  }
  </script>
  <?php  
}
?>
<hr>
<form action="WettbewerbHelp.php" method="post">
  <input type="hidden" name="hilfe" value=1 />
  <p id = "normal" align = "center"><input type="submit" value = '<?php echo $langArray['howto?'] ?>'/></p>
</form>
<hr>
<p align='center'><input type='submit' value= '<?php echo $langArray['already_part_of_competition'] ?>' onclick='showHide("wettbewerbbearbeiten")'></p>
<div id="wettbewerbbearbeiten" style='display:none'>
  <p align='center'><input type ='submit' value='<?php echo $langArray['i_am_participant'] ?>' onclick='showHide("teilnehmer")'></p>
  <div id='teilnehmer' style='display:none'>
    <form action = "Wettbewerb.php" method="get">
      <p align='center' id='normal'><?php echo $langArray['enter_competition_id'] ?>:<input type='number' min=0 name='id'></p>
      <p align='center'><input type='submit' value='<?php echo $langArray['show_competition'] ?>' ></p>
    </form>
  </div>
  <p align='center'><input type='submit' value='<?php echo $langArray['i_am_staff'] ?>' onclick='showHide("statbetrbearbeiten")'></p>
  <div id='statbetrbearbeiten' style='display:none'>
    <form method='post' action='WettbewerbCreator.php'>
      <table align='center'><tr><td><?php echo $langArray['enter_competition_id'] ?>:</td><td><input type='number' min = 0 name='wettbewerbid'></td></tr>
        <tr><td><?php echo $langArray["enter_challenge_id"]?>:</td><td><input type='number' min = 0 name='statid'></td></tr>
        <tr><td><?php echo $langArray['password']?>:</td><td><input type='password' name='password'></td></tr>
      </table>
      <p align='center'><input type='submit' value='<?php echo $langArray['login'] ?>' name='alsStatbetrBeitreten'>
      </p>
    </form>
  </div>
  <p align='center'><input type='submit' value='<?php echo $langArray['i_am_admin'] ?>' onclick='showHide("adminbearbeiten")'></p>
  <div id='adminbearbeiten' style='display:none'>
    <form method='post' action='WettbewerbCreator.php'>
      <table align='center'><tr><td><?php echo $langArray['enter_competition_id'] ?>:</td><td><input type='number' min = 0 name='wettbewerbid'></td></tr>
        <tr><td><?php echo $langArray['password']?>:</td><td><input type='password' name='password'></td></tr>
      </table>
      <p align='center'><input type='submit' value='<?php echo $langArray['login'] ?>' name='alsAdminBeitreten'>
      </p>
    </form>
  </div>
</div>
<form action="WettbewerbCreator.php" method="post">
  <input type="hidden" name="erstellen" value=1 />
  <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['create_a_competition'] ?>"/></p>
</form>
</body>
</html>

<?php
function wertungAnzeigen($spielID)
{
  global $langArray;
  try{
  include "includes.php";
  $res = $mysqli->Query("SELECT * FROM $spielID"."_general");
  if (!isset($res->num_rows))
  {
    echo "<p id='error' align='center'". $langArray['no_competition_found_with_id'] ." $spielID !</p>";
    return false;
  }
  echo "<table align='center' id='tnliste'>";
  echo "<tr><th align='center' colspan='2'>". $langArray['score'] ."</th><th align='center'>".$langArray['name']."</th>";
  $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen");
  $stationenzahl = $stationenRes->num_rows;
  while ($station = $stationenRes->fetch_assoc())
  {
    if ($station['aktiv']==1)
    {
      echo "<th colspan='2' align='center'><a style='color:white' href='Wettbewerb.php?id=$spielID&stat=".$station['id']."'>".$station['name']."</a></th>";
      $stationAktiv[$station['id']]=1;
      $stationArt[$station['id']]=$station['art'];
    }
    else
    {
      $stationAktiv[$station['id']]=0;
    }
  }
  echo "</tr>";
  $teilnehmerRes = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1 ORDER BY gesamtpunkte DESC");
  $teilnehmerzahl = $teilnehmerRes->num_rows;
  $platzexequo = 1;
  $punkteexequo = -1;
  $zaehler = 1;
  while ($teilnehmer = $teilnehmerRes->fetch_assoc())
  {
    echo "<tr><td align='center'><b>";
    //Platzierung ermitteln
    $punkte = $teilnehmer['gesamtpunkte'];
    if ($zaehler > 1 && $punkte == $punkteexequo)
      echo $platzesequo;
    else
    {
      echo $zaehler;
      $platzesequo = $zaehler;
      $punkteexequo = $punkte;
    }
    $zaehler++;
    echo "</b></td>";
    echo "<td align='center'>"; 
    echo $punkte;
    echo "</td>";
    echo "</td><td align='center'>".$teilnehmer['name']."</td>";
    for ($i = 0; $i < $stationenzahl; $i++)
    {
      if ($stationAktiv[$i]==1)
      {
        echo "<td align='center'>".getWertung($stationArt[$i],$teilnehmer["wertung$i"])."</td>";
          echo "<td align='center'>".$teilnehmer["punkte$i"]."</td>";  
      }
    }
    echo "</tr>";
  }
  }catch(Exception $e)
  {
    
  }
  
echo "</table>";
}

function zeigeStation($statId, $spielID)
{
  global $langArray;
  include "includes.php";
  try{
    $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen WHERE id = $statId");
    $station = $stationenRes->fetch_assoc();
    $name = $station['name'];
    echo "<p align='center'>$name</p>";
    echo "<table align='center' id='tnliste'>";
    echo "<tr><th align='center'>".$langArray['participant_number_short']."</th><th align='center'>".$langArray['name']."</th>";
    
    
    echo "<th colspan='2' align='center'>".$station['name']."</th>";
    
   
    echo "</tr>";
    $teilnehmerRes = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
    $teilnehmerzahl = $teilnehmerRes->num_rows;
    while ($teilnehmer = $teilnehmerRes->fetch_assoc())
    {
      echo "<tr><td align='center'>"; 
      echo $teilnehmer['id'];
      echo "</td><td align='center'>".$teilnehmer['name']."</td>";
      echo "<td align='center'>".getWertung($station['art'],$teilnehmer["wertung$statId"])."</td>";
      echo "<td align='center'>".$teilnehmer["punkte$statId"]."</td>";
      echo "</tr>";
    }
    echo "</table>";
    
    if ($station['art']!="punktedirekt")
    {
      //Show rating grid
      echo "<p align='center'>".$langArray['rating_grid']."</p>";
      $generalRes = $mysqli->Query("SELECT * FROM $spielID"."_general");
      $general = $generalRes->fetch_assoc();
      $katzahl = $general['anzahlKat'];
      echo "<table align='center' id='tnliste'>";
      echo "<tr><th align='center'>".$langArray['score']."</th><th align='center'>".$langArray['condition']."</th></tr>";
      if ($station['art'] == "punktehoch" || $station['art']=="zeithoch")
      {
        echo "<tr><td align='center'>".$general["kat1"]."</td><td>";
        echo $langArray['less_than']." ".getWertung($station['art'],$station["kat2"]);
        for ($i = 2; $i < $katzahl; $i++)
        { 
          echo "<tr><td align='center'>".$general["kat$i"]."</td><td>";
          echo getWertung($station['art'],$station["kat$i"])." bis ". getWertung($station['art'],$station['kat'.($i+1)]);
        } 
        echo "<tr><td align='center'>".$general["kat$katzahl"]."</td><td>";
        echo $langArray['more_than']." ".getWertung($station['art'],$station["kat$katzahl"]);
      }
      else
      {
        echo "<tr><td align='center'>".$general["kat1"]."</td><td>";
        echo $langArray['more_than']." ".getWertung($station['art'],$station["kat2"]);
        for ($i = 2; $i < $katzahl; $i++)
        { 
          echo "<tr><td align='center'>".$general["kat$i"]."</td><td>";
          echo getWertung($station['art'],$station["kat".($i+1)])." bis ". getWertung($station['art'],$station['kat'.$i]);
        } 
        echo "<tr><td align='center'>".$general["kat$katzahl"]."</td><td>";
        echo $langArray['less_than']." ".getWertung($station['art'],$station["kat$katzahl"]);
      }
      echo "</table>";
    }
  }
  catch(Exception $e)
  {
    
  }
  echo "<form action='Wettbewerb.php' method='get'>
    <input type='hidden' name='id' value = '$spielID'>
    <p align='center'><input type = 'submit' value='".$langArray['back']."'></p></form>";   
}

function getWertung($art, $wertung)
{
  global $langArray;
  if ($art == "punktehoch" || $art == "punktenieder" || $art == "punktedirekt")
    return $wertung;
  //Sonst Zeit
  //gibt in Millisekunden an
  $zeit = "";
  if ($wertung >= 3600000)
  {
    //es gibt Stunden
    $stunden = floor($wertung/3600000);
    $zeit .="$stunden h ";
    $wertung -= $stunden * 3600000;
  }
  if ($wertung >= 60000)
  {
    $minuten = floor($wertung/60000);
    $zeit .="$minuten min ";
    $wertung -= $minuten * 60000;
  }
  $sek = floor ($wertung/1000);
  $zeit .="$sek";
  $wertung -=$sek*1000;
  if ($wertung >0)
  {
    $zeit .=".$wertung";
  }
  $zeit .=" s";
  return $zeit;
}
  
?>