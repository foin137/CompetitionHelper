<?php
//Localization
include_once("localization-save.php");
?>

<!DOCTYPE html>
<HTML>
<head>
<script charset="ISO-8859-1" type="text/javascript">
function passwordGenerate() 
{
    var zahl = Math.floor(Math.random()*(100000000-100));
    document.getElementById("stationenpw").value=zahl.toString(36);
    return false;
}

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


</script>
<title><?php echo $langArray['title'] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> 
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="Sat, 01 Dec 2001 00:00:00 GMT">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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


.tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
}

/* Tooltip text */
.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    top: 100%;
    left: 50%;
    margin-left:-60px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;
 
    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 1;
    opacity: 0;
    transition: opacity 1s;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}

</style>
</head>
<body>
<?php
//Load localization
include_once("localization-select.php");
?>
<p align = 'Center'><?php echo $langArray['create_a_competition'] ?></p>
<hr>
<?php
  include "includes.php";
  //session_start(); already started by localization
  
  $admin = false;
  $stationenbetreuer = false;
  if (isset($_POST['aussteigen']))
  {
     setcookie("spielID", 0, time()-172800);
     session_unset();
     session_destroy();
     session_start();
  }
  //print_r($_POST);
  //print_r($_SESSION);
  //print_r($_COOKIE);
  if (isset($_SESSION['admin'])&&isset($_COOKIE['spielID']))
  {
    if ($_SESSION['admin']==$_COOKIE['spielID'])
      
      $admin = true;
      
  }
  if (isset($_SESSION['statbetr'])&& isset($_COOKIE['spielID']))
  {
    if ($_SESSION['statbetr']==$_COOKIE['spielID'])
      $stationenbetreuer = true;
  }
  if ($stationenbetreuer)
  {
    if (isset($_POST['statbetrEintragen']))
      statbetrEintragen($mysqli);
    else
    {
      if (isset($_POST['statErgebnisEingebenSpeichern']))
        statErgebnisEingebenSpeichern($mysqli);
      statBetrUebersicht($mysqli);
    }
  }
  elseif (isset($_COOKIE['spielID'])&& $admin==true)
  {
    $res = $mysqli->Query("SELECT * FROM ".$_COOKIE["spielID"]."_general");
    if (isset($res->num_rows))
    {
      if (isset($_SESSION['kontrollcenter']))
      {
        if (isset($_POST['adminErgebnisEingeben']))
        {
          adminErgebnisEingeben($mysqli);
        }
        elseif (isset($_GET['zeigeStation']))
        {
          zeigeStation($mysqli,$_GET['zeigeStation']);
        }
        elseif (isset($_POST['adminTeilnehmerHinzufuegen']))
        {
          teilnehmerErstellen($mysqli);
        }
        elseif (isset($_POST['teilnehmerspeichern']) || isset($_POST['teilnehmerspeichernNeu']))
        { 
          teilnehmerSpeichern($mysqli);
          if (isset($_POST['teilnehmerspeichernNeu']))
          {
            teilnehmerErstellen($mysqli);
          }
          else
          {
            kontrollCenter($mysqli);
          }
        }
        elseif (isset($_POST['tnbearbeiten']))
        {
          teilnehmerBearbeiten($mysqli,$_POST['tnbearbeiten']);
        }
        elseif (isset($_POST['teilnehmerbearbeitenspeichern']))
        {
          if (teilnehmerBearbeitetSpeichern($mysqli))
            kontrollCenter($mysqli); //If name is invalid, false is returned -> back to edit participants!  
        }
        elseif (isset($_POST['adminStationHinzufuegen']))
        {
          stationErstellenAdmin($mysqli);
        }
        elseif (isset($_POST['stationspeichernAdminNeu']) || isset($_POST['stationspeichernAdmin']))
        {
          if (stationSpeichernAdmin($mysqli))
          {
            if (isset($_POST['stationspeichernAdminNeu']))
              stationErstellenAdmin($mysqli);
            else
              kontrollCenter($mysqli);  
          }
        }
        elseif (isset($_POST['statbearbeiten']))
        {
          stationBearbeitenAdmin($mysqli,$_POST['statbearbeiten']);
        }
        elseif (isset($_POST['stationBearbeitenSpeichern']))
        {
          if (stationBearbeitenSpeichernAdmin($mysqli,$_POST['statid']))
            kontrollCenter($mysqli);
        }
        elseif (isset($_POST['einstellungenbearbeiten']))
        {
          einstellungenbearbeiten($mysqli);
        }
        elseif (isset($_POST['admineinstellungenbearbeiten']))
        {
          if (adminEinstellungenSpeichern($mysqli))
            kontrollCenter($mysqli);
        }
        elseif (isset($_POST['katzahlbearbeiten']))
        {
          katZahlBearbeiten($mysqli);
        }
        else
        {
          if (isset($_POST['wettbewerbsnameAendern']))
          {
            wettbewerbNameAendern($mysqli,$_POST['nameWettbewerb']);
          }
          elseif (isset($_POST['adminErgebnisEingebenSpeichern']))
          {
            adminErgebnisEingebenSpeichern($mysqli);
          }
          elseif (isset($_POST['alleStatBewerten']))
          {
            alleStatBewerten($mysqli);
          }
          elseif (isset($_POST['tnaktivieren']))
          {
            tnaktivieren($mysqli,$_POST['tnaktivieren']);
          }
          elseif (isset($_POST['tndeaktivieren']))
          {
            tndeaktivieren($mysqli,$_POST['tndeaktivieren']);
          }
          elseif (isset($_POST['stataktivieren']))
            stataktivieren($mysqli,$_POST['stataktivieren']);
          elseif (isset($_POST['statdeaktivieren']))
            statdeaktivieren($mysqli,$_POST['statdeaktivieren']);
          elseif (isset($_POST['katZahlBearbeitenSpeichern']))
            katZahlBearbeitenSpeichern($mysqli);
          kontrollCenter($mysqli);
        }
      }
      elseif (isset($_POST['stationerstellen']))
        stationErstellen($mysqli);
      elseif (isset($_POST['stationspeichern']) || isset($_POST['stationspeichernNeu']))
      {
        stationSpeichern($mysqli);
        if (isset($_POST['stationspeichernNeu']))
        {
          stationErstellen($mysqli);
        }
        else
        {
          schritt4($mysqli,$_COOKIE['spielID']);
        }
      }
      elseif (isset($_POST['teilnehmererstellen']))
      {
        teilnehmerErstellen($mysqli);
      }
      elseif (isset($_POST['teilnehmerspeichern']) || isset($_POST['teilnehmerspeichernNeu']))
      { 
        teilnehmerSpeichern($mysqli);
        if (isset($_POST['teilnehmerspeichernNeu']))
        {
          teilnehmerErstellen($mysqli);
        }
        else
        {
          schritt6($mysqli);
        }
      }
      elseif (isset($_POST['schritt6weiter']))
      {
        $_SESSION['kontrollcenter']=1;
        kontrollCenter($mysqli);
      }
      elseif (isset($_POST['schritt5']))
      {
        $_SESSION['schritt']=6;
        schritt5($mysqli);
      }
      elseif (isset($_SESSION['schritt']))
      {
        if ($_SESSION['schritt']==6)
          schritt6($mysqli);
      }
      else
        schritt4($mysqli,$_COOKIE['spielID']);
      
    }
    else
    {
      //delete cookie
      setcookie ("spielID", 0, time()-172800);
      echo "<p id='error' align='center'>".$langArray['competition_not_found_anymore']."</p>";
      //Reload
      header("Refresh:0"); 
    }
  }
  elseif (isset($_POST['kat1']))
  {
    schritt3($mysqli);
  }
  elseif (isset($_POST['nameWettbewerb']))
  {
    schritt2();
  }
  elseif (isset($_POST['alsAdminBeitreten']))
  {
    adminLogin($mysqli);
  }
  elseif (isset($_POST['alsStatbetrBeitreten']))
  {
    statBetrLogin($mysqli);
  }
  else
  {
    schritt1();
  }
  aussteigenButton();
?>
</body>
</HTML>
<?php
function schritt1()
{
  global $langArray;
  ?>
  
<form action="WettbewerbCreator.php" method="post">
  <p id="normal" align="center"><?php echo $langArray['first_enter_competition_name']?></p>
  <p align='center'><input type="text" name="nameWettbewerb"/></p>
  <p id="normal" align="center"><?php echo $langArray['description_score_categories'] ?></p>
  <p align='center'><INPUT TYPE='number'   NAME='anzahlKat' Size='2' value=10 MIN=2></p>>
  <p id='normal' align='center'><?php echo $langArray['hint_score_categories'] ?></p>
  <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['Next'] ?>"/></p>
</form>
<form action="Wettbewerb.php" method="post">
  <p align='center'><input type ='submit' value= '<?php echo $langArray['back'] ?>'></p>
</form>
  <?php
}

function schritt2()
{
  global $langArray;
  //Validate inputs
  $nameWettbewerb = $_POST['nameWettbewerb'];
  $anzahlKat = $_POST['anzahlKat'];
  if ($nameWettbewerb != "" && containsBadThings($nameWettbewerb)==false)
  {
     if ($anzahlKat >= 2 && $anzahlKat <= 100)
     {
        echo "<form action ='WettbewerbCreator.php' method='post'>";
        echo "<input type='hidden' name='nameWettbewerb' value='$nameWettbewerb'><input type='hidden' name='anzahlKat' value=$anzahlKat>";
        echo "<p id='normal' align='center'>". $langArray['name'].": $nameWettbewerb</p><p id='normal' align='center'>". $langArray['number_of_categories'].": $anzahlKat</p>";
        echo "<p id='normal' align='center'>". $langArray['description_score_category_distribution']."</p>";
        for ($i = 1; $i <= $anzahlKat; $i++)
        {
          echo "<p id='normal' align='center'>Kat. $i:<INPUT TYPE='number' NAME='kat$i' Size='2' value=$i MIN=0></p>";
        }
        ?>
        <br><p id = 'normal' align='center'><?php echo $langArray['description_addition_staff'] ?></p>
        <p align='center'><select name = 'stationenbetreuer' size = 1><option value = '0' selected=true><?php echo $langArray['no_additional_staff'] ?></option><option value = '1'><?php echo $langArray['yes_additional_staff'] ?></option></select></p>
        <br><p id='normal' align='center'><?php echo $langArray['refresh_score_immediately?'] ?><br>
        <select name = 'punkteakt' size = 1><option value = '0' selected=true><?php echo $langArray['refresh_score_immediately_no'] ?></option><option value = '1'><?php echo $langArray['refresh_score_immediately_yes'] ?></option></select></p>
        <p align='center'><input type='submit' value = '<?php echo $langArray['show_advanced_options']?>' onclick='showHide("erweitert"); return false;'></p>
        <div id='erweitert' name='erweitert' style='display:none'>
        <p id='normal' align='center'><input name='maxkatprozent' type = 'number' min=0.1 max=50.0 step=0.1 value=10><?php echo $langArray['how_many_percent_best_category'] ?></p>
        <p id='normal' align='center'><?php echo $langArray['best_automatically_best_category'] ?> <select name='immergewinnerdabei' size = 1><option value='1' selected><?php echo $langArray['Yes']?></option><option value='0'><?php echo $langArray['No'] ?></option></select><option value='0'></p>
        <p id='normal' align='center'><?php echo $langArray['shift_mean']?>: <input type = 'number' name = 'korrekturprozent' min='-99.9' max='99.9' step='0.1' value='0'></p>
        </div>
        <p align='center' id='normal'><input type = 'text' name='adminpw'><?php echo $langArray['finally_admin_password'] ?></p>
        <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['Next'] ?>"/></p></form>
        <?php
     }
     else
     {
        echo "<p id='error' align='center'>". $langArray['wrong_category_number'] ." (2-100) ...</p>";
        schritt1();
     }
  }
  else
  {
    echo "<p id='error' align='center'>". $langArray['name_invalid'] ." ...</p>";
    schritt1(); 
  }
}

function schritt3($mysqli)
{
  global $langArray;
  //everything ok?
  $nameWettbewerb = $_POST['nameWettbewerb'];
  $anzahlKat = $_POST['anzahlKat'];
  $stationenbetreuer = $_POST['stationenbetreuer'];
  $punkteakt = $_POST['punkteakt'];
  $maxkatprozent = $_POST['maxkatprozent'];
  $korrekturprozent = $_POST['korrekturprozent'];
  $immergewinnerdabei = $_POST['immergewinnerdabei'];
  if ($nameWettbewerb != "" && containsBadThings($nameWettbewerb)==false && is_numeric($korrekturprozent) && is_numeric($immergewinnerdabei))
  {
     if ($anzahlKat >= 2 && $anzahlKat <= 100 && isset($_POST['adminpw']))
     {
        if ($stationenbetreuer >= 0 && $stationenbetreuer <= 1 && strlen($_POST['adminpw'])>=4)
        {
          //echo "schritt 3";
          if (!isset($_COOKIE["spielID"]))
          {
            $_COOKIE["spielID"]=-1;
            //echo "cookie not set";
          }
          $res = $mysqli->Query("SELECT * FROM ".$_COOKIE["spielID"]."_general");
          if (!isset($res->num_rows) || !isset($_COOKIE["spielID"]))
          {
            //Find an ID that is not used already
            //We create a new competition
            //A loop that runs until we find a new number
            for ($i = 1; $i <= 100000; $i++)
            {
              $spielID = rand(10000,99999);
              //Check if a competition with this number already exists
              $res = $mysqli->Query("SELECT * FROM $spielID"."_general");
              if(isset($res->num_rows)){
                //echo "already exists";
              }else{
                $sql = "
                  CREATE TABLE `$spielID"."_general" ."` (
                  `id` INT( 10 ) DEFAULT $spielID UNIQUE,
                  `name` VARCHAR( 150 ) NOT NULL,
                  `anzahlKat` INT ( 5 ) DEFAULT 2,
                  `maxkatprozent` FLOAT ( 8 ) NOT NULL, 
                  `bestekatimmervergeben` INT ( 2 ) DEFAULT 1, 
                  `mittelwertverschieben` FLOAT ( 8 ) DEFAULT 0, ";
                for ($i = 1; $i <= $anzahlKat; $i++)
                {
                  $sql .= "`kat$i` INT ( 10 ) DEFAULT NULL, ";
                }
                $sql .="`stationenbetreuer` INT ( 2 ) NULL,
                        `punkteakt` INT ( 2 ) NULL,
                        `adminpw` VARCHAR ( 150 ) NOT NULL,
                        `zuletztbearbeitet` INT ( 10 ) DEFAULT 0,
                        `zuletztberechnet` INT ( 10 ) DEFAULT 0,
                        `sekzwischenautoberechnen` INT ( 10 ) DEFAULT 30);";
                $mysqli->Query($sql);
                echo $mysqli->error;
                $pw = password_hash($_POST['adminpw'], PASSWORD_DEFAULT);
                $sql2 = "INSERT INTO $spielID"."_general (`id`, `name`, `anzahlKat`, `maxkatprozent`, `bestekatimmervergeben`, `mittelwertverschieben`, ";
                for ($i = 1; $i <= $anzahlKat; $i++)
                {
                  $sql2 .= "`kat$i`, ";
                }
                $sql2.="`stationenbetreuer`, `punkteakt`, `adminpw`) VALUES ($spielID, '$nameWettbewerb', $anzahlKat, $maxkatprozent, $immergewinnerdabei, $korrekturprozent, ";
                for ($i = 1; $i <= $anzahlKat; $i++)
                {
                  $sql2 .= $_POST["kat$i"].", ";
                }
                $sql2 .="$stationenbetreuer, $punkteakt, '$pw');";
                //echo $sql2;
                $mysqli->Query($sql2);
                //echo $mysqli->error;
                $sql3 = "CREATE TABLE `$spielID"."_stationen` (
                  `id` INT ( 10 ) UNIQUE, 
                  `name` VARCHAR ( 150 ) NOT NULL,
                  `info` VARCHAR ( 1500 ) DEFAULT '',
                  `art` VARCHAR ( 150 ) NOT NULL,
                  `aktiv` INT ( 2 ) DEFAULT 1,";
                for($i=1; $i<=$anzahlKat; $i++)
                {
                  $sql3.="`kat$i` FLOAT ( 8 ) NULL, ";
                }
                $sql3.= "`pw` VARCHAR ( 150 ) NULL);";
                $mysqli->Query($sql3);
                setcookie ("spielID", $spielID, time()+172800); //Duration 2 days
                $_COOKIE["spielID"]=$spielID;
                $_SESSION['admin']=$spielID;
                break;
              }
            }
          }

          schritt4($mysqli, $_COOKIE['spielID']);
        }
        else
        {
          echo "<p id='error' align='center'>". $langArray['something_gone_wrong'] ." ...</p>";
          schritt2(); 
        }
     }
     else
     {
        echo "<p id='error' align='center'>". $langArray['wrong_category_number'] ." (2-100) ...</p>";
        schritt1();
     }
  }
  else
  {
    echo "<p id='error' align='center'>". $langArray['name_invalid'] ." ...</p>";
    schritt1(); 
  }
}

function schritt4($mysqli, $spielID)
{
  global $langArray;
  //$spielID = $_COOKIE['spielID'];
  $result = $mysqli->Query("SELECT * FROM $spielID"."_general");
  $assoc = $result->fetch_assoc();
  $anzahlKat = $assoc['anzahlKat'];
  $nameWettbewerb=$assoc['name'];
  echo "<TABLE BORDER='0' align='center'>
  <TR><TD>Name: </TD><TD>$nameWettbewerb</TD></TR><TR><TD>ID:</TD><TD>$spielID</TD></TR><TR><TD>".$langArray['number_of_categories'].":</TD><TD>$anzahlKat</TD></TR>";
  
  for ($i = 1; $i <= $anzahlKat; $i++)
  {
    echo "<TR><TD>Kat. $i:</TD><TD>".$assoc["kat$i"]."</TD></TR>";
  }    
  if ($assoc['stationenbetreuer']==0)
  {
     echo "<TR><TD>".$langArray['additional_staff'].":</TD><TD>".$langArray['No']."</TD></TR></p>";
  }
  else
  {
    echo "<TR><TD>".$langArray['additional_staff'].":</TD><TD>".$langArray['Yes']."</TD></TR></p>";
  }
  $res = $mysqli->Query("SELECT * FROM $spielID"."_stationen");
  $anzahl = $res->num_rows;
  echo "<tr><td>".$langArray['number_challenges'].":</td><td>$anzahl</td></tr></p>";
  while ($a = $res->fetch_assoc())
  {
     echo "<tr><td>".$a['name']. "</td><td>".getNameForArt($a['art'])."</td>";
     if ($a['pw'] == "")
     {
        echo "</tr>";
     }
     else
        echo "<td>".$langArray['password'].": ".$langArray['Yes']."</td></tr>";
  }
  ?>
  </table>
  <form action="WettbewerbCreator.php" method="post">
    <input type="hidden" name="stationerstellen" value=1 />
    <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['add_challenge'] ?>"/></p>
  </form>
  <br>
  <form action="WettbewerbCreator.php" method="post">
    <input type="hidden" name="schritt5" value=1 />
    <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['go_to_participants'] ?>"/></p>
  </form>
  
  <?php
}
function stationErstellen($mysqli)
{
  global $langArray;
  ?>
  <p id='normal' align='center'><?php echo $langArray['list_of_challenges'] ?>:</p>
  <TABLE BORDER="0" align='center'>
  <?php
    $stationen = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_stationen");
    while ($station = $stationen->fetch_assoc())
    {
      echo "<tr><td>".$station['id']."</td>
            <td>".$station['name']."</td></tr>";
    }
  ?>
  </TABLE>
  <form action="WettbewerbCreator.php" method="post">
    <p align='center'><?php echo $langArray['create_challenge']?></p>
    <hr>
    <div id='normal' align='center'>
      <TABLE BORDER="0">
        <TR><TD><?php echo $langArray['challenge_name']?>:</TD>
        <TD><input type = 'text' name='stationenname'></TD></TR>
        <tr><td><?php echo $langArray['Info']?>:</td><td><textarea rows='4' cols='50' name='statinfo'></textarea></td></tr>
        <TR><TD><?php echo $langArray['score_type']?>:</TD>
        <TD><select name = 'art' size = 1>
          <option value = 'punktehoch' selected=true><?php echo $langArray['score_type_high_points']?></option>
          <option value = 'punktenieder'><?php echo $langArray['score_type_low_points']?></option>
          <option value = 'punktedirekt'><?php echo $langArray['score_type_direct']?></option>
          <option value = 'zeitnieder'><?php echo $langArray['score_type_low_time']?></option>
          <option value = 'zeithoch'><?php echo $langArray['score_type_high_time']?></option>
        </select></TD></TR>
      <?php
        //Passwort?
        $res = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_general");
        $ass = $res->fetch_assoc();
        if ($ass['stationenbetreuer']==1)
        {
          ?>
          <TR><TD><?php echo $langArray['password_for_challenge_staff']?>:</TD>
          <TD><input type = 'text' name = 'pw' id='stationenpw'></TD>
          <TD><input type = 'submit' value = '<?php echo $langArray['generate_password']?>' onclick="return passwordGenerate();"></TD></TR>
          <?php
        }
      ?>      
      </TABLE>
    </div>
    <p id = "normal" align = "center"><input type="submit" name = "stationspeichernNeu" value = "<?php echo $langArray['save_challenge_create_new']?>"/></p>
    <p id = "normal" align = "center"><input type="submit" name = "stationspeichern" value = "<?php echo $langArray['save_challenge_next']?>"/></p>
  </form>
  <form action="WettbewerbCreator.php" method="post">
    <input type="hidden" name="abbruch" value=1 />
    <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['Cancel']?>"/></p>
  </form>
  <?php
}
function stationSpeichern($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  //Check inputs
  $name = $_POST['stationenname'];
  $info = $_POST['statinfo'];
  if ($name != "" && containsBadThings($name)==false && containsBadThings($info)==false)
  {
    //No duplicates?
    $stationenRes = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_stationen WHERE name = '$name'");
    if ($stationenRes->num_rows<=0)
    {
      //Name ok
      $passwortproblem = true;
      if (isset($_POST['pw']))
      {
        if (strlen($_POST['pw'])<=2)
        {
          echo "<p id='error' align='center'>".$langArray['password_too_short']."</p>";
          stationErstellen($mysqli);
        }
        else
        {
          $passwortproblem = false;
          $pw = password_hash($_POST['pw'], PASSWORD_DEFAULT); 
        }
      }
      else
      {
        $passwortproblem = false;
        $pw = "";
      }
      if ($passwortproblem == false)
      {
        //Insert into database
        $stationenRes = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_stationen");
        $id = $stationenRes->num_rows;
        $mysqli->Query("INSERT INTO ".$_COOKIE['spielID']."_stationen (`id`, `name`, `info`, `art`, `pw`) VALUES ($id, '$name', '$info', '".$_POST['art']."', '$pw');");
      }
    }
    else
    {
      echo "<p id='error' align='center'>".$langArray['challenge_name_duplicate']."</p>";
      stationErstellen($mysqli);
    } 
  }
  else
  {
    echo "<p id='error' align='center'>".$langArray['challenge_name_or_info_invalid']."</p>";
    stationErstellen($mysqli);
  }
}
function stationErstellenAdmin($mysqli)
{
  global $langArray;
  ?>
  <p id='normal' align='center'><?php echo $langArray['list_of_challenges']?>:</p>
  <TABLE BORDER="0" align='center'>
  <?php
    $stationen = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_stationen");
    while ($station = $stationen->fetch_assoc())
    {
      echo "<tr><td>".$station['id']."</td>
            <td>".$station['name']."</td></tr>";
    }
  ?>
  </TABLE>
  <form action="WettbewerbCreator.php#stationen" method="post">
    <p align='center'><?php echo $langArray['create_challenge']?></p>
    <hr>
    <div id='normal' align='center'>
      <TABLE BORDER="0">
        <TR><TD><?php echo $langArray['challenge_name'] ?>:</TD>
        <TD><input type = 'text' name='stationenname'></TD></TR>
        <tr><td><?php echo $langArray['Info']?>:</td><td><textarea rows='4' cols='50' name='statinfo'></textarea></td></tr>
        <TR><TD><?php echo $langArray['score_type']?>:</TD>
        <TD><select name = 'art' size = 1>
          <option value = 'punktehoch' selected=true><?php echo $langArray['score_type_high_points']?></option>
          <option value = 'punktenieder'><?php echo $langArray['score_type_low_points']?></option>
          <option value = 'punktedirekt'><?php echo $langArray['score_type_direct']?></option>
          <option value = 'zeitnieder'><?php echo $langArray['score_type_low_time']?></option>
          <option value = 'zeithoch'><?php echo $langArray['score_type_high_time']?></option>
        </select></TD></TR>
      <?php
        //Passwort?
        $res = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_general");
        $ass = $res->fetch_assoc();
        if ($ass['stationenbetreuer']==1)
        {
          ?>
          <TR><TD><?php echo $langArray['password_for_challenge_staff']?>:</TD>
          <TD><input type = 'text' name = 'pw' id='stationenpw'></TD>
          <TD><input type = 'submit' value = '<?php echo $langArray['generate_password']?>' onclick="return passwordGenerate();"></TD></TR>
          <?php
        }
      ?>      
      </TABLE>
    </div>
    <p id = "normal" align = "center"><input type="submit" name = "stationspeichernAdminNeu" value = "<?php echo $langArray['save_challenge_create_new']?>"/></p>
    <p id = "normal" align = "center"><input type="submit" name = "stationspeichernAdmin" value = "<?php echo $langArray['save_challenge_next']?>"/></p>
  </form>
  <form action="WettbewerbCreator.php" method="post">
    <input type="hidden" name="abbruch" value=1 />
    <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['Cancel']?>"/></p>
  </form>
  <?php
}
function stationBearbeitenAdmin($mysqli,$statid)
{
  global $langArray;
  $spielID = $_COOKIE['spielID'];
  ?>
  <form action="WettbewerbCreator.php#stationen" method="post">
    <p align='center'><?php echo $langArray['edit_challenge']?></p>
    <hr>
    <div id='normal' align='center'>
      <TABLE BORDER="0">
        <TR><TD><?php echo $langArray['challenge_name']?>:</TD>
        <?php
        $statRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen WHERE id = $statid");
        $stat = $statRes->fetch_assoc();
        echo "<TD colspan = 2><input type = 'text' name='stationenname' value = '".$stat['name']."'></TD></TR>";
        echo "<tr><td>ID</td><td colspan = 2>$statid</td></tr>";
        echo "<tr><td>Info:</td><td colspan = 2><textarea rows='4' cols='50' name='statinfo'>".$stat['info']."</textarea></td></tr>";
        $art = $stat['art'];
        echo "<TR><TD>".$langArray['score_type'].":</TD>
          <TD><select name = 'art' size = 1>";
            echo "<option value = 'punktehoch' ";
            if ($art == "punktehoch")
              echo "selected";
            echo " >".$langArray['score_type_high_points']."</option>";
            echo "<option value = 'punktenieder' ";
            if ($art == "punktenieder")
              echo "selected";
            echo " >".$langArray['score_type_low_points']."</option>";
            echo "<option value = 'punktedirekt' ";
            if ($art == "punktedirekt")
              echo "selected";
            echo " >".$langArray['score_type_direct']."</option>";
            echo "<option value = 'zeitnieder' ";
            if ($art == "zeitnieder")
              echo "selected";
            echo " >".$langArray['score_type_low_time']."</option>";
            echo "<option value = 'zeithoch' ";
            if ($art == "zeithoch")
              echo "selected";
            echo " >".$langArray['score_type_high_time']."</option>";
        echo "</select></TD></TR>";
        //aktiv
        echo "<tr><td>".$langArray['active']."</td><td colspan = 2><select name='aktiv'>";
        if ($stat['aktiv']==0)
        {
          echo "<option value='0' selected>".$langArray['No']."</option>";
          echo "<option value='1'>".$langArray['Yes']."</option>";
        }
        else
        {
          echo "<option value='0'>".$langArray['No']."</option>";
          echo "<option value='1' selected>".$langArray['Yes']."</option>";
        }
        echo "</select></td></tr>";
        
        //Passwort?
        $res = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_general");
        $ass = $res->fetch_assoc();
        if ($ass['stationenbetreuer']==1)
        {
          ?>
          <TR><TD>Passwort für Stationenbetreuer:</TD>
          <TD><input type = 'text' name = 'pw' id='stationenpw'></TD>
          <TD><input type = 'submit' value = 'Passwort generieren' onclick="return passwordGenerate();"></TD></TR>
          <tr><td colspan = 3 align='center'><?php echo $langArray['leave_pwd_empty_if_remain']?></td></tr>
          <?php
        }
        echo "</table><input type = 'hidden' name = 'statid' value = $statid>";
      ?>      
    </div>
    <p id = "normal" align="center"><input type = "reset" value = "<?php echo $langArray['Reset']?>"></p>
    <p id = "normal" align = "center"><input type="submit" name = "stationBearbeitenSpeichern" value = "<?php echo $langArray['save_challenge']?>"/></p>
  </form>
  <form action="WettbewerbCreator.php" method="post">
    <input type="hidden" name="abbruch" value=1 />
    <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['Cancel']?>"/></p>
  </form>
  <?php
}
function stationBearbeitenSpeichernAdmin($mysqli, $statid)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  //Check inputs
  $name = $_POST['stationenname'];
  $info = $_POST['statinfo'];
  if ($name != "" && containsBadThings($name)==false && containsBadThings($info)==false)
  {
    //Check duplicates
    $stationenRes = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_stationen WHERE name = '$name'");
    $kritischeStat = $stationenRes->fetch_assoc();
    if ($stationenRes->num_rows<=0 || $kritischeStat['id']==$statid)
    {
      //Name ok
      $passwortproblem = true;
      if (isset($_POST['pw']))
      {
        if (strlen($_POST['pw'])<=2)
        {
          echo "<a id='stationen'></a><p id='error' align='center'>".$langArray['password_too_short_dont_change']."</p>";
        }
        else
        {
          $passwortproblem = false;
          $pw = password_hash($_POST['pw'], PASSWORD_DEFAULT); 
        }
      }
      //Database
      $art = $_POST['art'];
      $aktiv = $_POST['aktiv'];
      if (!is_numeric($aktiv))
      {
        echo "<p id ='error' align='center'>ERROR SE01</p>";
        stationBearbeitenAdmin($mysqli, $statid);
        return false;
      }
      $sql = "UPDATE ".$_COOKIE['spielID']."_stationen SET `name` = '$name', `info` = '$info', `art` = '$art', ";
      if ($passwortproblem == false)
        $sql .= "`pw` = '$pw', ";
      $sql .= "aktiv = $aktiv WHERE `id` = $statid;";  
      $mysqli->Query($sql); 
      echo $mysqli->error;
      //Challenge updated
      return true;
    }
    else
    {
      echo "<p id='error' align='center'>".$langArray['challenge_name_duplicate']."</p>";
      stationBearbeitenAdmin($mysqli, $stadid);
      return false;
    } 
  }
  else
  {
    echo "<p id='error' align='center'>".$langArray['challenge_name_or_info_invalid']."</p>";
    stationBearbeitenAdmin($mysqli, $statid);
    return false;
  }
}
function stationSpeichernAdmin($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  //check inputs
  $name = $_POST['stationenname'];
  $info = $_POST['statinfo'];
  if ($name != "" && containsBadThings($name)==false && containsBadThings($info)==false)
  {
    //check duplicates
    $stationenRes = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_stationen WHERE name = '$name'");
    if ($stationenRes->num_rows<=0)
    {
      //Name ok
      $passwortproblem = true;
      if (isset($_POST['pw']))
      {
        if (strlen($_POST['pw'])<=2)
        {
          echo "<p id='error' align='center'>".$langArray['password_too_short']."</p>";
          stationErstellenAdmin($mysqli);
        }
        else
        {
          $passwortproblem = false;
          $pw = password_hash($_POST['pw'], PASSWORD_DEFAULT); 
        }
      }
      else
      {
        $passwortproblem = false;
        $pw = "";
      }
      if ($passwortproblem == false)
      {
        //database
        $stationenRes = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_stationen");
        $id = $stationenRes->num_rows;
        $mysqli->Query("INSERT INTO ".$_COOKIE['spielID']."_stationen (`id`, `name`, `info`, `art`, `pw`) VALUES ($id, '$name', '$info', '".$_POST['art']."', '$pw');"); 
        echo $mysqli->error;
        //challenge inserted
        //Update participant table
        $mysqli->Query("ALTER TABLE ".$_COOKIE['spielID']."_teilnehmer ADD COLUMN `wertung$id` INT ( 10 ) DEFAULT 0, ADD COLUMN `punkte$id` INT ( 10 ) DEFAULT 0;");
        echo $mysqli->error;
        return true;
      }
    }
    else
    {
      echo "<p id='error' align='center'>".$langArray['challenge_name_duplicate']."</p>";
      stationErstellenAdmin($mysqli);
      return false;
    } 
  }
  else
  {
    echo "<p id='error' align='center'>".$langArray['challenge_name_or_info_invalid']."</p>";
    stationErstellenAdmin($mysqli);
    return false;
  }
}
function schritt5($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID = $_COOKIE['spielID'];
  $res = $mysqli->Query("SELECT * FROM ".$spielID."_stationen");
  $anzahlStat=$res->num_rows;
  //create participant table
  $sql = "CREATE TABLE `$spielID"."_teilnehmer" ."` (
          `id` INT( 10 ) UNIQUE,
          `name` VARCHAR( 150 ) NOT NULL,
          `info` VARCHAR ( 1024 ) NULL,
          `aktiv` INT ( 2 ) DEFAULT 1,
          `gesamtpunkte` INT ( 10 ) DEFAULT 0";
  for ($i = 0; $i < $anzahlStat; $i++)
  {
    $sql .= ", `wertung$i` INT ( 10 ) DEFAULT 0";
    $sql .= ", `punkte$i` INT ( 10 ) DEFAULT 0";
  }
  $sql .=");";
  $mysqli->Query($sql);
  //echo $sql;
  //echo $mysqli->error;
  schritt6($mysqli);
}

function schritt6($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID = $_COOKIE['spielID'];
  $result = $mysqli->Query("SELECT * FROM $spielID"."_general");
  $assoc = $result->fetch_assoc();
  $anzahlKat = $assoc['anzahlKat'];
  $nameWettbewerb=$assoc['name'];
  echo "<TABLE BORDER='0' align='center'>
  <TR><TD>Name: </TD><TD>$nameWettbewerb</TD></TR><TR><TD>ID:</TD><TD>$spielID</TD></TR><TR><TD>".$langArray['number_of_categories'].":</TD><TD>$anzahlKat</TD></TR>";    
  if ($assoc['stationenbetreuer']==0)
  {
     echo "<TR><TD>".$langArray['additional_staff'].":</TD><TD>".$langArray['No']."</TD></TR></p>";
  }
  else
  {
    echo "<TR><TD>".$langArray['additional_staff'].":</TD><TD>".$langArray['Yes']."</TD></TR></p>";
  }
  $res = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer");
  $anzahl = $res->num_rows;
  echo "<tr><td>".$langArray['number_of_participants'].":</td><td>$anzahl</td></tr>";
  while ($a = $res->fetch_assoc())
  {
     echo "<tr><td>".$a['id']."</td><td>".$a['name']. "</td></tr>";
  }
  ?>
  </TABLE>
  <form action="WettbewerbCreator.php" method="post">
    <input type="hidden" name="teilnehmererstellen" value=1 />
    <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['add_participant']?>"/></p>
  </form>
  <br>
  <form action="WettbewerbCreator.php" method="post">
    <input type="hidden" name="schritt6weiter" value=1 />
    <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['Next']?>"/></p>
  </form>
  
  <?php
}

function teilnehmerErstellen($mysqli)
{
  global $langArray;
  ?>
  <p id='normal' align='center'><?php echo $langArray['list_of_participants']?>:</p>
  <TABLE BORDER="0" align='center'>
  <?php
    $teilnehmer = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_teilnehmer");
    $anzahl = $teilnehmer->num_rows;
    while ($item = $teilnehmer->fetch_assoc())
    {
      echo "<tr><td>".$item['id']."</td>
            <td>".$item['name']."</td></tr>";
    }
  ?>
  </TABLE>
  <form action="WettbewerbCreator.php" method="post">
    <p align='center'><?php echo $langArray['create_participant'] ?></p>
    <hr>
    <TABLE BORDER="0" align='center'>
    <?php
      echo "<tr><td>ID</td><td>$anzahl</td></tr>";
    ?>
      <TR><TD><?php echo $langArray['name']?>:</TD>
      <TD><input type = 'text' name='teilnehmername'></TD></TR>
      <tr><td>Info:</td><td><textarea rows='4' cols='50' name='teilnehmerinfo'></textarea></td></tr>
    </TABLE>
    <p id = "normal" align = "center"><input type="submit" name="teilnehmerspeichernNeu" value = "<?php echo $langArray['save_participant_create_new']?>"/></p>
    <p id = "normal" align = "center"><input type="submit" name="teilnehmerspeichern" value = "<?php echo $langArray['save_participant_next']?>"/></p>
  </form>
  <form>
    <input type="hidden" name="abbruch" value=1 />
    <p id = "normal" align = "center"><input type="submit" value = "Cancel"/></p>
  </form
  <?php
}

function teilnehmerSpeichern($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  //Check inputs
  $name = $_POST['teilnehmername'];
  $info = $_POST['teilnehmerinfo'];
  if ($name != "" && containsBadThings($name)==false)
  {
    if (containsBadThings($info)==false)
    {
      //Check duplicates
      $teilnehmerRes = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_teilnehmer WHERE name = '$name'");
      if ($teilnehmerRes->num_rows<=0)
      {
        //Name ok
        //database
        $teilnehmerRes = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_teilnehmer");
        $id = $teilnehmerRes->num_rows;
        $mysqli->Query("INSERT INTO ".$_COOKIE['spielID']."_teilnehmer (`id`, `name`, `info`) VALUES ($id, '$name', '$info');");
        //echo $mysqli->error;
      }
      else
      {
        echo "<p id='error' align='center'>".$langArray['participant_name_duplicate']."</p>";
        teilnehmerErstellen($mysqli);
      }
    }
    else
    {
      echo "<p id='error' align='center'>".$langArray['participant_info_invalid']."</p>";
      teilnehmerErstellen($mysqli);
    } 
  }
  else
  {
    echo "<p id='error' align='center'>".$langArray['participant_name_invalid']."</p>";
    teilnehmerErstellen($mysqli);
  }
}

function teilnehmerBearbeiten($mysqli, $tnid)
{
  global $langArray;
  ?>
  <form action="WettbewerbCreator.php#teilnehmer" method="post">
    <p align='center'><?php echo $langArray['edit_participant']?></p>
    <hr>
    <TABLE BORDER="0" align='center'>
    <?php
      $spielID=$_COOKIE['spielID'];
      $res = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE id = $tnid");
      $tn = $res->fetch_assoc();
      echo "<tr><td>ID</td><td>".$tn['id']."</td></tr>";
    ?>
      <TR><TD><?php echo $langArray['name']?>:</TD>
      <?php
      echo "<TD><input type = 'text' name='teilnehmername' value='";
      echo $tn['name'];
      echo "'></TD></TR>";
      echo "<tr><td>".$langArray['Info'].":</td><td><textarea rows='4' cols='50' name='teilnehmerinfo'>";
      echo $tn['info'];
      echo "</textarea></td></tr>";
      echo "<tr><td>".$langArray['active']."</td><td><select name='aktiv'>";
      if ($tn['aktiv']==0)
      {
        echo "<option value='0' selected>".$langArray['No']."</option>";
        echo "<option value='1'>".$langArray['Yes']."</option>";
      }
      else
      {
        echo "<option value='0'>".$langArray['No']."</option>";
        echo "<option value='1' selected>".$langArray['Yes']."</option>";
      }
      echo "</select></td></tr>";
    
    echo "</TABLE>";
    echo "<input type='hidden' name='tnid' value=$tnid>";
    ?>
    <p id = "normal" align = "center"><input type="reset" value = "<?php echo $langArray['Reset']?>"/></p>
    <p id = "normal" align = "center"><input type="submit" name="teilnehmerbearbeitenspeichern" value = "<?php echo $langArray['save_participant']?>"/></p>
  </form>
  <form action='WettbewerbCreator.php#teilnehmer' method='post'>
    <input type="hidden" name="abbruch" value=1/>
    <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['Cancel']?>"/></p>
  </form>
  <?php
}

function teilnehmerBearbeitetSpeichern($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return false;
  //Check inputs
  $name = $_POST['teilnehmername'];
  $info = $_POST['teilnehmerinfo'];
  $tnid = $_POST['tnid'];
  $aktiv = $_POST['aktiv'];
  if ($name != "" && containsBadThings($name)==false)
  {
    if (containsBadThings($info)==false)
    {
      //check duplicates
      $teilnehmerRes = $mysqli->Query("SELECT * FROM ".$_COOKIE['spielID']."_teilnehmer WHERE name = '$name'");
      if ($teilnehmerRes->num_rows<=0)
      {
        //Name ok
        //database
        $mysqli->Query("UPDATE ".$_COOKIE['spielID']."_teilnehmer SET name = '$name', info = '$info', aktiv = $aktiv WHERE id = $tnid;");
        //echo $mysqli->error;
        return true;
      }
      else
      {
        //self?
        $a = $teilnehmerRes->fetch_assoc();
        if ($a['id']==$tnid)
        {
          //Name ok --> own name
          //database
          $mysqli->Query("UPDATE ".$_COOKIE['spielID']."_teilnehmer SET name = '$name', info = '$info', aktiv = $aktiv WHERE id = $tnid;");
          return true; 
        }
        else
        {
          echo "<p id='error' align='center'>".$langArray['participant_name_duplicate']."</p>";
          teilnehmerBearbeiten($mysqli,$tnid);
          return false;
        }
      }
    }
    else
    {
      echo "<p id='error' align='center'>".$langArray['participant_info_invalid']."</p>";
      teilnehmerBearbeiten($mysqli,$tnid);
      return false;
    } 
  }
  else
  {
    echo "<p id='error' align='center'>".$langArray['participant_name_invalid']."</p>";
    teilnehmerBearbeiten($mysqli,$tnid);
    return false;
  }
}

function kontrollCenter($mysqli)
{
  global $langArray;
  echo "<p align='center'>".$langArray['control_center']."</p>";
  //From here (control center) one may edit all aspects of the competition
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $result = $mysqli->Query("SELECT * FROM $spielID"."_general");
  $general = $result->fetch_assoc();
  $anzahlKat = $general['anzahlKat'];
  $nameWettbewerb=$general['name'];
  $spielID = $_COOKIE['spielID'];
  $aktuell = false;
  if ($general['zuletztbearbeitet']<=$general['zuletztberechnet'])
    $aktuell = true;
  echo "<TABLE BORDER='0' align='center'>
  <TR><TD>".$langArray['name'].": </TD><TD>$nameWettbewerb</TD>";
  ?>
  <TD><form action='WettbewerbCreator.php' method='post'>
    <input type = hidden name='nameWettbewerb' id='nameWettbewerb' value='test'>
    <input type = 'submit' name='wettbewerbsnameAendern' value='<?php echo $langArray['change_name']?>' <?php echo "onclick='document.getElementById(\"nameWettbewerb\").value = prompt(\"".$langArray['new_name_question']."\");'>" ?>
  </form></TD></TR>
  <?php
    echo "<TR><TD>ID:</TD><TD>$spielID</TD></TR></table>";
  ?>
  <p align='center'><input type = 'submit' value='<?php echo $langArray['show_whole_table']?>' onclick='showHide("gesamtliste");'></p>
  <div id="gesamtliste">
    <table align='center' id="tnliste">
      <tr><th align='center' colspan='2'><?php echo $langArray['score'] ?></th><th align='center'><?php echo $langArray['participant_number_short'] ?></th><th align='center'><?php echo $langArray['name'] ?></th>
      <?php
        $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen");
        $stationenzahl = $stationenRes->num_rows;
        while ($station = $stationenRes->fetch_assoc())
        {
          if ($station['aktiv']==1)
          {
            echo "<th colspan='2' align='center'><a style='color:white' href='WettbewerbCreator.php?zeigeStation=".$station['id']."'>".$station['name']."</a></th>";
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
          if ($aktuell)
            echo "<td align='center'>";
          else
            echo "<td align='center' style='color:grey'>";  
          echo $punkte;
          echo "</td>";
          echo "<td align='center'>";
          if ($teilnehmer['info']=="")
            echo $teilnehmer['id'];
          else
          {
            echo "<div class='tooltip'>".$teilnehmer['id'];
            echo "<span class='tooltiptext'>".$teilnehmer['name'].": ";
            echo $teilnehmer['info'];
            echo "</span></div>";
          }
          echo "</td><td align='center'>".$teilnehmer['name']."</td>";
          for ($i = 0; $i < $stationenzahl; $i++)
          {
            if ($stationAktiv[$i]==1)
            {
              echo "<td align='center'>".getWertung($stationArt[$i],$teilnehmer["wertung$i"])."</td>";
              if ($aktuell)
                echo "<td align='center'>".$teilnehmer["punkte$i"]."</td>";
              else
                echo "<td align='center' style='color:grey'>".$teilnehmer["punkte$i"]."</td>";  
            }
          }
          echo "</tr>";
        }
      
    echo "</table>";
    if (!$aktuell)
      echo "<p id='normal' align='center'>".$langArray['score_not_updated']."</p>";
    ?>
    <table align='center'>
    <tr><td>
    <form method='post' action='WettbewerbCreator.php'>
      <p align='center'><input type='submit' name='adminErgebnisEingeben' value='<?php echo $langArray['enter_results']?>'></p>
    </form></td>
    <td>
    <form method='post' action='WettbewerbCreator.php'>
      <p align='center'><input type='submit' name='alleStatBewerten' value='<?php echo $langArray['evaluate_now']?>'></p>
    </form></td></tr></table>
  </div>
  <p align='center'><input type = 'submit' value='<?php echo $langArray['show_hide_settings']?>' onclick='showHide("einstellungen");'></p>
  <div id="einstellungen">
    <p align='center'><?php echo $langArray['Settings']?></p>
    <table align='center'>
    <?php
    if ($general['stationenbetreuer']==0)
    {
       echo "<TR><TD>".$langArray['additional_staff'].":</TD><TD>".$langArray['No']."</TD></TR>";
    }
    else
    {
      echo "<TR><TD>".$langArray['additional_staff'].":</TD><TD>".$langArray['Yes']."</TD></TR>";
    }
    echo "<tr><td>".$langArray['evaluate_score_always']."</td>";
    if ($general['punkteakt'] == 0)
      echo "<td>".$langArray['No']."</td></tr>";
    else
      echo "<td>".$langArray['Yes']."</td></tr>";
    echo "<tr><td>".$langArray['percent_best_category'].":</td>";
    echo "<td>".$general['maxkatprozent']."%</td></tr>";
    echo "<tr><td>".$langArray['lower_mean'].":</td>";
    echo "<td>".$general['mittelwertverschieben']."%</td></tr>";
    echo "<tr><td>".$langArray['always_assign_best_category'].":</td><td>";
    if ($general['bestekatimmervergeben']==1)
      echo $langArray['Yes'];
    else
      echo $langArray['No'];
    echo "</td></tr>";
    echo "<TR><TD>".$langArray['number_of_categories'].":</TD><TD>$anzahlKat</TD></TR></table>";
    echo "<form action='WettbewerbCreator.php' method='post'><p align='center'><input type='submit' name='einstellungenbearbeiten' value='".$langArray['edit_settings']."'></p></form>";    
    echo "<form action='WettbewerbCreator.php' method='post'><p align='center'><input type='submit' name='katzahlbearbeiten' value='".$langArray['edit_number_of_categories']."'></p></form>";  
    echo "<p id='normal' align='center'>".$langArray['categories'].":";
    echo "<table align='center' id='allborder'><tr>";
    for ($i = 1; $i <= $anzahlKat; $i++)
    {
      echo "<td>$i</td>";
    }
    echo "</tr><tr>";
    for ($i = 1; $i <= $anzahlKat; $i++)
    {
      echo "<td>".$general["kat$i"]."</td>";
    }
    echo "</tr></table>";
    echo "</div>";
    $tnres = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer");
    $anzahl = $tnres->num_rows;
    echo "<p align='center'>".$langArray['Participants']."</p>";
    echo "<p id='normal' align='center'>".$langArray['number_of_participants'].": $anzahl</p>";
    echo "<a id='teilnehmer'></a><table align='center' id='tnliste'>";
    echo "<tr><th colspan=4 align='center'>".$langArray['Participants']."</th></tr>";
    while ($a = $tnres->fetch_assoc())
    {
      $tnid = $a['id'];
      echo "<tr><td>";
      if ($a['info']=="")
        echo $tnid;
      else
      {
        echo "<div class='tooltip'>$tnid";
        echo "<span class='tooltiptext'>".$a['name'].": ";
        echo $a['info'];
        echo "</span></div>";
      }
      echo "</td>";
      echo "<td>".$a['name'];
      if ($a['aktiv']==0)
      {
        echo "(".$langArray['inactive'].")</td>";
        echo "<td>
                  <form action='WettbewerbCreator.php#teilnehmer' method='post'>
                    <input type = 'hidden' name='tnaktivieren' value=$tnid>
                    <div class='tooltip'><input type='submit' value='A'><span class='tooltiptext'>".$langArray['activate']."</span></div>
                  </form>
                  </td>
                  <td>
                  <form action='WettbewerbCreator.php' method='post'>
                    <input type = 'hidden' name='tnbearbeiten' value=$tnid>
                    <input type='submit' value='".$langArray['edit']."'>
                  </form>
                  </td>"; 
      }
      else
      {
        echo "</td>";
        echo "<td>
                  <form action='WettbewerbCreator.php#teilnehmer' method='post'>
                    <input type = 'hidden' name='tndeaktivieren' value=$tnid>
                    <div class='tooltip'><input type='submit' value='D'><span class='tooltiptext'>".$langArray['deactivate']."</span></div>
                  </form>
              </td>
              <td>
                  <form action='WettbewerbCreator.php' method='post'>
                    <input type = 'hidden' name='tnbearbeiten' value=$tnid>
                    <input type='submit' value='".$langArray['edit']."'>
                  </form>
                </td>";
      }
      "</tr>";
    }
    echo "</table>";
    ?>
    <form action="WettbewerbCreator.php" method="post">
      <p id = "normal" align = "center"><input type="submit" name="adminTeilnehmerHinzufuegen" value = "<?php echo $langArray['add_participant']?>"/></p>
    </form>
    <br>
  <?php
    $statres = $mysqli->Query("SELECT * FROM $spielID"."_stationen");
    $anzahl = $statres->num_rows;
    echo "<p align='center'>".$langArray['Challenges']."</p>";
    echo "<p id='normal' align='center'>".$langArray['number_challenges'].": $anzahl</p>";
    echo "<a id='stationen'></a><table align='center' id='tnliste'>";
    echo "<tr><th align='center'>ID</th><th align='center'>".$langArray['name']."</th><th align='center'>".$langArray['score_type_short']."</th><th align='center' colspan = 2>".$langArray['options']."</th></tr>";
    while ($a = $statres->fetch_assoc())
    {
      $stid = $a['id'];
      echo "<tr><td>";
      if ($a['info']=="")
        echo $stid;
      else
      {
        echo "<div class='tooltip'>$stid";
        echo "<span class='tooltiptext'>".$a['name'].": ";
        echo $a['info'];
        echo "</span></div>";
      }
      echo "</td>";
      echo "<td>".$a['name'];
      if ($a['aktiv']==0)
      {
        echo "(".$langArray['inactive'].")</td>";
        echo "<td>";
        echo getNameForArt($a['art']);
        echo "</td>";
        echo "<td>
                  <form action='WettbewerbCreator.php#stationen' method='post'>
                    <input type = 'hidden' name='stataktivieren' value=$stid>
                    <div class='tooltip'><input type='submit' value='A'><span class='tooltiptext'>".$langArray['activate']."</span></div>
                  </form>
                  </td>
                  <td>
                  <form action='WettbewerbCreator.php' method='post'>
                    <input type = 'hidden' name='statbearbeiten' value=$stid>
                    <input type='submit' value='".$langArray['edit']."'>
                  </form>
                  </td>"; 
      }
      else
      {
        echo "</td>";
        echo "<td>";
        echo getNameForArt($a['art']);
        echo "</td>";
        echo "<td>
                  <form action='WettbewerbCreator.php#stationen' method='post'>
                    <input type = 'hidden' name='statdeaktivieren' value=$stid>
                    <div class='tooltip'><input type='submit' value='D'><span class='tooltiptext'>".$langArray['deactivate']."</span></div>
                  </form>
              </td>
              <td>
                  <form action='WettbewerbCreator.php' method='post'>
                    <input type = 'hidden' name='statbearbeiten' value=$stid>
                    <input type='submit' value='".$langArray['edit']."'>
                  </form>
                </td>";
      }
      "</tr>";
    }
    echo "</table>"; 
  ?>
  <form action="WettbewerbCreator.php" method="post">
    <p id = "normal" align = "center"><input type="submit" name="adminStationHinzufuegen" value = "<?php echo $langArray['add_challenge']?>"/></p>
  </form>
  <br>
  <form action="WettbewerbCreator.php" method="post">
    <p id = "normal" align = "center"><input type="submit" value = "Reload"/></p>
  </form>
  <?php
}

function tnaktivieren($mysqli,$id)
{
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $mysqli->Query("UPDATE $spielID"."_teilnehmer SET aktiv = 1 WHERE id = $id");
}

function tndeaktivieren($mysqli,$id)
{
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $mysqli->Query("UPDATE $spielID"."_teilnehmer SET aktiv = 0 WHERE id = $id");
}

function stataktivieren($mysqli,$id)
{
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $mysqli->Query("UPDATE $spielID"."_stationen SET aktiv = 1 WHERE id = $id");
}

function statdeaktivieren($mysqli,$id)
{
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $mysqli->Query("UPDATE $spielID"."_stationen SET aktiv = 0 WHERE id = $id");
}

function wettbewerbNameAendern($mysqli, $name)
{
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  if ($name != "" && strpos($name,"$")===false && strpos($name,";")===false && strpos($name,'"')===false && strpos($name,"'")===false && strpos($name,"=")===false && strpos(strtoupper($name),"DROP")===false)
  {
    $sql = "UPDATE ".$_COOKIE['spielID']."_general SET name = '$name';";
    $mysqli->Query($sql);
    //echo $sql;
    //echo $mysqli->error;
  }
}

function adminErgebnisEingeben($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  echo "<form action='WettbewerbCreator.php' method='post'>
    <p align='center'><input type='submit' value='".$langArray['Save']."'></p>
    <table align='center'>";
  echo "<tr><th align='center'>".$langArray['participant_number_short']."</th><th align='center'>".$langArray['name']."</th>";
  $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen");
  $stationenzahl = $stationenRes->num_rows;
  while ($station = $stationenRes->fetch_assoc())
  {
    if ($station['aktiv']==1)
    {
      echo "<th align='center'>".$station['name']."</th>";
      $stationAktiv[$station['id']]=1;
      $stationArt[$station['id']]=$station['art'];
    }
    else
    {
      $stationAktiv[$station['id']]=0;
    }
  }
  echo "</tr>";
  $teilnehmerRes = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
  $teilnehmerzahl = $teilnehmerRes->num_rows;
  while ($teilnehmer = $teilnehmerRes->fetch_assoc())
  {
    echo "<tr><td align='center'>";
    if ($teilnehmer['info']=="")
      echo $teilnehmer['id'];
    else
    {
      echo "<div class='tooltip'>".$teilnehmer['id'];
      echo "<span class='tooltiptext'>".$teilnehmer['name'].": ";
      echo $teilnehmer['info'];
      echo "</span></div>";
    }
    echo "</td><td align='center'>".$teilnehmer['name']."</td>";
    for ($i = 0; $i < $stationenzahl; $i++)
    {
      if ($stationAktiv[$i]==1)
      {
        echo "<td align='center'>";
        if ($stationArt[$i]=="punktehoch" || $stationArt[$i]=="punktenieder" || $stationArt[$i]=="punktedirekt")
        {
          echo "<input type='text' name='t".$teilnehmer['id']."s$i' 
            value='".$teilnehmer["wertung$i"]."'>";
        }
        else
        {
          $wertung = $teilnehmer["wertung$i"];
          $stunden = floor($wertung/3600000);
          $wertung -= $stunden*3600000;
          
          $min = floor($wertung/60000);
          $wertung -= $min*60000;
          
          $sek = floor($wertung/1000);
          $wertung -= $sek*1000;
          
          $millisek = floor($wertung);
          
          echo "<div class='tooltip'><input type='number' min=0 step=1 value=$stunden name = 't".$teilnehmer['id']."s$i"."h'><span class='tooltiptext'>".$langArray['Hours']."</span></div>";
          echo "<div class='tooltip'><input type='number' min=0 step=1 value=$min name = 't".$teilnehmer['id']."s$i"."min'><span class='tooltiptext'>".$langArray['Minutes']."</span></div>";
          echo "<div class='tooltip'><input type='number' min=0 step=1 value=$sek name = 't".$teilnehmer['id']."s$i"."s'><span class='tooltiptext'>".$langArray['Seconds']."</span></div>";
          echo "<div class='tooltip'><input type='number' min=0 step=1 value=$millisek name = 't".$teilnehmer['id']."s$i"."mil'><span class='tooltiptext'>".$langArray['Milliseconds']."</span></div>";
        }
        echo "</td>";
      }
    }
    echo "</tr>";
  }
  echo "</table><p align='center'><input type='reset' value='".$langArray['Reset']."'></p>
    <input type = 'hidden' name = 'adminErgebnisEingebenSpeichern' value=1>
    <p align='center'><input type='submit' value='".$langArray['Save']."'></p>
    </form>
    <form action='WettbewerbCreator.php' method='post'><p align='center'><input type='submit' value='".$langArray['Cancel']."'></p></form>";  
}

function adminErgebnisEingebenSpeichern($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen");
  $stationenzahl = $stationenRes->num_rows;
  while ($station = $stationenRes->fetch_assoc())
  {
    if ($station['aktiv']==1)
    {
      $stationAktiv[$station['id']]=1;
      $stationArt[$station['id']]=$station['art'];
    }
    else
    {
      $stationAktiv[$station['id']]=0;
    }
  }
  $teilnehmerRes = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
  $teilnehmerzahl = $teilnehmerRes->num_rows;
  while ($teilnehmer = $teilnehmerRes->fetch_assoc())
  {
    $sql = "UPDATE $spielID"."_teilnehmer SET ";
    for ($i = 0; $i < $stationenzahl; $i++)
    {
      if ($stationAktiv[$i]==1)
      {
        if ($stationArt[$i]=="punktehoch" || $stationArt[$i]=="punktenieder" || $stationArt[$i]=="punktedirekt")
        {
          try
          {
            $eingabe = $_POST["t".$teilnehmer['id']."s$i"];
            $sql.= "wertung$i = $eingabe, ";
          }
          catch (Exception $e)
          {
            echo "<p id='error'>".$e->getMessage()."</p>";
          }
        }
        else
        {
          try
          {
            $stunden = $_POST["t".$teilnehmer['id']."s$i"."h"];
            $min = $_POST["t".$teilnehmer['id']."s$i"."min"];
            $sek = $_POST["t".$teilnehmer['id']."s$i"."s"];
            $millisek = $_POST["t".$teilnehmer['id']."s$i"."mil"];
            $wertung = $millisek + 1000*$sek + 60000*$min + 3600000*$stunden;
            $sql.="wertung$i = $wertung, ";
          }
          catch (Exception $e)
          {
            echo "<p id='error'>".$e->getMessage()."</p>";
          }
        }
      }
    }
    //perform UPDATE
    //erase comma
    $sql = substr($sql,0,strlen($sql)-2);
    $sql.= " WHERE id = ".$teilnehmer['id'].";";
    $mysqli->Query($sql);
    
    //echo $sql.$mysqli->error;    
  }
  $mysqli->Query("UPDATE $spielID"."_general SET `zuletztbearbeitet` = ".time()." WHERE id = $spielID ;");
}

function einstellungenbearbeiten($mysqli)
{
  global $langArray;
  $spielID=$_COOKIE['spielID'];
  $generalRes = $mysqli->Query("SELECT * FROM $spielID"."_general");
  $general = $generalRes->fetch_assoc();
  $nameWettbewerb = $general['name'];
  $anzahlKat = $general['anzahlKat'];
  //weiter gehts :)
  echo "<form action ='WettbewerbCreator.php' method='post'>";
  echo "<p id='normal' align='center'>".$langArray['name'].": $nameWettbewerb</p><p id='normal' align='center'>".$langArray['number_of_categories'].": $anzahlKat</p>";
  echo "<p id='normal' align='center'>".$langArray['description_score_category_distribution']."</p>";
  for ($i = 1; $i <= $anzahlKat; $i++)
  {
    echo "<p id='normal' align='center'>Kat. $i:<INPUT TYPE='number' NAME='kat$i' Size='2' value=".$general["kat$i"]." MIN=0></p>";
  }
  
  echo "<br><p id = 'normal' align='center'>".$langArray['description_addition_staff']."</p>";
  echo "<p align='center'><select name = 'stationenbetreuer' size = 1>
    <option value = '0'";
  if ($general['stationenbetreuer']==0)
    echo "selected=true";
  echo ">".$langArray['no_additional_staff']."</option>";
  echo "<option value = '1' ";
  if ($general['stationenbetreuer']==1)
    echo "selected = true";
  echo ">".$langArray['yes_additional_staff']."</option></select></p>";
  echo "<br><p id='normal' align='center'>".$langArray['refresh_score_immediately?']."<br>";
  echo "<select name = 'punkteakt' size = 1><option value = '0' ";
  if ($general['punkteakt']==0)
    echo "selected=true";
  echo ">".$langArray['refresh_score_immediately_no']."</option><option value = '1' ";
  if ($general['punkteakt']==1)
    echo "selected=true";
  echo ">".$langArray['refresh_score_immediately_yes']."</option></select></p>";
  ?>
  <p align='center'><input type='submit' value = '<?php echo $langArray['show_advanced_options']?>' onclick='showHide("erweitert"); return false;'></p>
  <div id='erweitert' name='erweitert' style='display:none'>
  <?php
  echo "<p id='normal' align='center'><input name='maxkatprozent' type = 'number' min=0.1 max=50.0 step=0.1 value='";
  echo $general['maxkatprozent'];
  echo "' >".$langArray['how_many_percent_best_category']."</p>";
  echo "<p id='normal' align='center'>".$langArray['best_automatically_best_category'].": <select name='immergewinnerdabei' size = 1><option value='1' ";
  if ($general['bestekatimmervergeben']==1)
    echo "selected=true";
  echo ">".$langArray['Yes']."</option><option value='0' ";
  if ($general['bestekatimmervergeben']==0)
    echo "selected = true";
  echo ">".$langArray['No']."</option></select><option value='0'></p>";
  echo "<p id='normal' align='center'>".$langArray['shift_mean'].": <input type = 'number' name = 'korrekturprozent' min='-99.9' max='99.9' step='0.1' value='";
  echo $general['mittelwertverschieben'];
  echo "'></p></div>";
  echo "<p align='center' id='normal'><input type = 'text' name='adminpw'>".$langArray['admin_password_leave_empty']."</p>";
  echo "<p align='center'><input type='reset' value='".$langArray['Reset']."'></p>";
  echo "<p id = 'normal' align = 'center'><input type='submit' name = 'admineinstellungenbearbeiten' value = '".$langArray['Next']."'/></p></form>";  
}

function adminEinstellungenSpeichern($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  //everything ok?
  $spielID=$_COOKIE['spielID'];
  $generalRes = $mysqli->Query("SELECT * FROM $spielID"."_general");
  $general = $generalRes->fetch_assoc();
  $nameWettbewerb = $general['name'];
  $anzahlKat = $general['anzahlKat'];
  $stationenbetreuer = $_POST['stationenbetreuer'];
  $punkteakt = $_POST['punkteakt'];
  $maxkatprozent = $_POST['maxkatprozent'];
  $korrekturprozent = $_POST['korrekturprozent'];
  $immergewinnerdabei = $_POST['immergewinnerdabei'];
  if ($nameWettbewerb != "" && containsBadThings($nameWettbewerb)==false && is_numeric($korrekturprozent) && is_numeric($immergewinnerdabei) && is_numeric($maxkatprozent))
  {
    if ($stationenbetreuer >= 0 && $stationenbetreuer <= 1)
    {
      $sql = "UPDATE $spielID"."_general SET 
        `maxkatprozent` = $maxkatprozent,
        `bestekatimmervergeben` = $immergewinnerdabei,
        `mittelwertverschieben` = $korrekturprozent, ";
      for ($i = 1; $i <= $anzahlKat; $i++)
      {
        $sql .= "`kat$i` = ".$_POST["kat$i"].", ";
      }
      $sql .= "`stationenbetreuer` = $stationenbetreuer, 
                `punkteakt` = $punkteakt WHERE id = $spielID;";
      $mysqli->Query($sql);
      if (strlen($_POST['adminpw'])>0)
      {
        if (strlen($_POST['adminpw'])<4)
        {
          echo "<p id='error'>Admin-Passwort zu kurz!</p>";
          einstellungenbearbeiten($mysqli);
          return false;
        }
        else
        {
          $pw = password_hash($_POST['adminpw'], PASSWORD_DEFAULT);
          $mysqli->Query("UPDATE $spielID"."_general SET `adminpw` = '$pw' WHERE id = $spielID ;");
          echo $mysqli->error;  
        }
      }
    }
    else
    {
      echo "<p id='error' align='center'>".$langArray['something_gone_wrong']." ...</p>";
      einstellungenbearbeiten($mysqli);
      return false; 
    }
 
  }
  else
  {
    echo "<p id='error' align='center'>".$langArray['name_invalid']." ...</p>";
    einstellungenbearbeiten($mysqli);
    return false; 
  }
  return true;
}

function katZahlBearbeiten($mysqli)
{
  global $langArray;
  $spielID=$_COOKIE['spielID'];
  $generalRes = $mysqli->Query("SELECT * FROM $spielID"."_general");
  $general = $generalRes->fetch_assoc();
  $anzahlKat = $general['anzahlKat'];
  echo "<form action='WettbewerbCreator.php' method='post'>";
  echo "<p align='center' id='normal'><input type='number' name='anzahlKat' min='2' max = '400' value='$anzahlKat'>".$langArray['number_of_categories']."</p>";
  echo "<p align='center'><input type='reset' value='".$langArray['Reset']."'></p>";
  echo "<p align='center'><input type='submit' value='".$langArray['Save']."' name='katZahlBearbeitenSpeichern'></p></form>";  
}

function katZahlBearbeitenSpeichern($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $anzahlKat = $_POST['anzahlKat'];
  if (is_numeric($anzahlKat))
  {
    if ($anzahlKat >= 2 && $anzahlKat <= 400)
    {
      $generalRes = $mysqli->Query("SELECT * FROM $spielID"."_general");
      $general = $generalRes->fetch_assoc();
      $zuvor = $general['anzahlKat'];
      if ($zuvor == $anzahlKat)
        return; //no change
      elseif ($zuvor < $anzahlKat)
      {
        //add categories
        $sql = "ALTER TABLE $spielID"."_general ";
        for ($i = $zuvor+1; $i < $anzahlKat; $i++)
        {
          $sql.="ADD COLUMN `kat$i` INT ( 10 ) DEFAULT $i, ";
        }
        $sql.="ADD COLUMN `kat$anzahlKat` INT ( 10 ) DEFAULT $anzahlKat;";
        $mysqli->Query($sql);
        echo $sql;
        echo $mysqli->error;
        $sql2 = "ALTER TABLE $spielID"."_stationen ";
        for ($i = $zuvor+1; $i < $anzahlKat; $i++)
        {
          $sql2.="ADD COLUMN `kat$i` INT ( 10 ) DEFAULT 0, ";
        }
        $sql2.="ADD COLUMN `kat$anzahlKat` INT ( 10 ) DEFAULT 0;";
        $mysqli->Query($sql2);
        $mysqli->Query("UPDATE $spielID"."_general SET anzahlKat = $anzahlKat WHERE id = $spielID");
      }
      elseif ($zuvor > $anzahlKat)
      {
        //remove categories
        $sql = "ALTER TABLE $spielID"."_general ";
        for ($i = $zuvor; $i > ($anzahlKat+1); $i--)
        {
          $sql.="DROP `kat$i`, ";
        }
        $sql.="DROP `kat".($anzahlKat+1)."` ;";
        $mysqli->Query($sql);
        $sql2 = "ALTER TABLE $spielID"."_stationen ";
        for ($i = $zuvor; $i > ($anzahlKat+1); $i--)
        {
          $sql2.="DROP `kat$i`, ";
        }
        $sql2.="DROP `kat".($anzahlKat+1)."` ;";
        $mysqli->Query($sql2);
        $mysqli->Query("UPDATE $spielID"."_general SET anzahlKat = $anzahlKat WHERE id = $spielID");
      }  
    }
  }  
}

function zeigeStation($mysqli, $statId, $zurueckButton = true)
{
  global $langArray;
  $spielID=$_COOKIE['spielID'];
  try{
    $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen WHERE id = $statId");
    $station = $stationenRes->fetch_assoc();
    $name = $station['name'];
    echo "<p align='center'>$name</p>";
    echo "<table align='center' id='tnliste'>";
    echo "<tr><th align='center'>Startnr.</th><th align='center'>".$langArray['name']."</th>";
    echo $mysqli->error;
    
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
      //show grading
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
  if ($zurueckButton)
    echo "<form action='WettbewerbCreator.php' method='post'><p align='center'><input type = 'submit' value='".$langArray['back']."'></p></form>";   
}

function bewerteWennMoeglich($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'] && $_SESSION['statbetr']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $generalRes = $mysqli->Query("SELECT * FROM $spielID"."_general");
  $general = $generalRes->fetch_assoc();
  if ($general['zuletztbearbeitet']>=($general['zuletztberechnet']+$general['sekzwischenautoberechnen']))
    alleStatBewerten($mysqli);
}

function alleStatBewerten($mysqli)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'] && $_SESSION['statbetr']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen WHERE aktiv = 1");
  $stationenzahl = 0;
  $aktiv[0]=0;
  while ($stat = $stationenRes->fetch_assoc())
  {
    stationBewerten($mysqli,$stat['id']);
    $aktiv[$stat['id']]=1;
    $stationenzahl = $stat['id'];
  }
  
  //add points
  $alletn = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
  while ($tn = $alletn->fetch_assoc())
  {
    $punkte = 0;
    for ($i = 0; $i <= $stationenzahl; $i++)
    {
      if (array_key_exists($i,$aktiv))
      {
        if ($aktiv[$i]==1)
        {
          $punkte += $tn["punkte$i"];
        }
      }  
    }
    $mysqli->Query("UPDATE $spielID"."_teilnehmer SET gesamtpunkte = $punkte WHERE id = ".$tn['id']);
  }
  
  $mysqli->Query("UPDATE $spielID"."_general SET `zuletztberechnet` = ".time()." WHERE id = $spielID");
}

function stationBewerten($mysqli,$statId)
{
  global $langArray;
  if ($_SESSION['admin']!=$_COOKIE['spielID'] && $_SESSION['statbetr']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen WHERE id = $statId");
  $stationena = $stationenRes->fetch_assoc();
  if ($stationena['aktiv']==0)
    return false;
  
  //write data into a list first
  $teilnehmerRes = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
  $anzahl = $teilnehmerRes->num_rows;
  if ($anzahl < 2)
  {
    echo "<p id='error'>Error K2: ".$langArray['too_few_participants_grade']."!</p>";
    return false;
  }  
  while ($a = $teilnehmerRes->fetch_assoc())
  {
    $data[]=$a["wertung$statId"];
  }
  if (count($data)!=$anzahl)
  {
    echo "<p id='error'>Error K1</p>";
    return false;
  }
  //print_r($data);
  $generalRes = $mysqli->Query("SELECT * FROM $spielID"."_general");
  $general = $generalRes->fetch_assoc();
  $maxkatprozent = $general['maxkatprozent'];
  $mittelwertverschieben = $general['mittelwertverschieben'];
  $bestekatimmervergeben = $general['bestekatimmervergeben'];
  $anzahlkat = $general['anzahlKat'];
  
  $mittelwert = array_sum($data)/$anzahl;
  
  //change mean
  if ($stationena['art']=="punktehoch" || $stationena['art']=="zeithoch")
  {
    $mittelwert = (1-$mittelwertverschieben/100)*$mittelwert;
  }
  else
  {
    $mittelwert = (1+$mittelwertverschieben/100)*$mittelwert;  
  }
  
  //calculate stdv
  //formula: \sigma = \sqrt{\frac{\sum_{i=1}^n(\overline x-x_i)^2}{n-1}}
  
  //first calculate the sum
  $sum=0;
  for($i=0;$i<$anzahl;$i++)
    $sum+=pow(($mittelwert - $data[$i]),2);
  //echo "Summe: $sum";
  $standardAbweichung = sqrt($sum/($anzahl - 1));
  
  $res = $mysqli->Query("SELECT * FROM normalverteilung WHERE wahrscheinlichkeit > ".(1-$maxkatprozent/100)." ORDER BY wahrscheinlichkeit ASC;");
  $ass = $res->fetch_assoc();
  $x = $ass['x'];
  
  //high or low?
  if ($stationena['art']=="punktehoch" || $stationena['art']=="zeithoch")
  {
    $besteKat = $mittelwert + $x*$standardAbweichung;
    //$zweiteKat = stats_cdf_normal($maxkatprozent/100,$mittelwert,$standardAbweichung,2);
    //is at least one participants in the best category?
    if (max($data) < $besteKat && $bestekatimmervergeben==1)
    {
      //change it 
      $besteKat = max($data);
    }
    $abweichung = $besteKat-$mittelwert;
    $zweiteKat = $mittelwert-$abweichung;
    
    //now the other cateogires, 3 are fixed:
    //Kat. 1: 0
    //Kat. 2: secondCat (zweiteKat)
    //Kat. 3: ?
    //Kat ...
    //Kat. 10: besteKat (bestCat)
    
    if ($anzahlkat <= 3)
    {
      $kat[1]=$zweiteKat-abs($zweiteKat*100);
      $kat[2]=$zweiteKat;
      $kat[3]=$besteKat;
    }
    else
    {
      //Divide the remainder in anzahlkat-2 pieces
      $stueck = 2*$abweichung/($anzahlkat-2);
      $kat[1]=$zweiteKat-abs($zweiteKat*100);
      $kat[2]=$zweiteKat;
      for ($i = 3; $i < $anzahlkat; $i++)
      {
        $kat[$i] = $zweiteKat + ($i-2)*$stueck;
      }
      $kat[$anzahlkat]=$besteKat;
    }
    $sql = "UPDATE $spielID"."_stationen SET ";
    for ($i = 1; $i < $anzahlkat; $i++)
    {
      $sql.="kat$i = ".round($kat[$i],1).", ";
    }
    $sql.=" kat$anzahlkat = ".round($besteKat,1)." WHERE id = $statId";
    //echo $sql;
    $mysqli->Query($sql);
    //echo $mysqli->error;
    
    //Nun calculate score
    $alleTn = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
    $i = -1;
    while ($a = $alleTn->fetch_assoc()) //participant loop
    {
      $i++;
      //grade participant $i
      for ($z = $anzahlkat; $z > 0; $z-=1) //category loop
      {
        if ($data[$i]>=$kat[$z])
        {
          //is in category $z !
          $punkte = $general["kat$z"];
          $mysqli->Query("UPDATE $spielID"."_teilnehmer SET punkte$statId = $punkte WHERE id = ".$a['id']);
          break;
        }
      }
    }
  }
  elseif ($stationena['art']=="punktedirekt")
  {
    $alleTn = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
    while ($a = $alleTn->fetch_assoc())
    {
      $mysqli->Query("UPDATE $spielID"."_teilnehmer SET punkte$statId = ".$a["wertung$statId"]." WHERE id = ".$a['id']);  
    } 
  }
  else
  {
    //low points are better
    $besteKat = $mittelwert - $x*$standardAbweichung;
    //$zweiteKat = stats_cdf_normal($maxkatprozent/100,$mittelwert,$standardAbweichung,2);
    //is one participant in best category?
    if (min($data) > $besteKat && $bestekatimmervergeben==1)
    {
      //adjust $besteKat
      $besteKat = min($data);
    }
    $abweichung = $mittelwert-$besteKat;
    $zweiteKat = $mittelwert+$abweichung;
    
    //Now the other categories
    //Kat. 1: unendlich
    //Kat. 2: zweiteKat
    //Kat. 3: ?
    //Kat ...
    //Kat. 10: besteKat
    
    if ($anzahlkat <= 3)
    {
      $kat[1]=$zweiteKat + abs($zweiteKat*100);
      $kat[2]=$zweiteKat;
      $kat[3]=$besteKat;
    }
    else
    {
      //same as above
      $stueck = 2*$abweichung/($anzahlkat-2);
      $kat[1]=$zweiteKat + abs($zweiteKat*100);
      $kat[2]=$zweiteKat;
      for ($i = 3; $i < $anzahlkat; $i++)
      {
        $kat[$i] = $zweiteKat - ($i-2)*$stueck;
      }
      $kat[$anzahlkat]=$besteKat;
    }
    $sql = "UPDATE $spielID"."_stationen SET ";
    for ($i = 1; $i < $anzahlkat; $i++)
    {
      $sql.="kat$i = ".round($kat[$i],1).", ";
    }
    $sql.=" kat$anzahlkat = ".round($besteKat,1)." WHERE id = $statId";
    //echo $sql;
    $mysqli->Query($sql);
    //echo $mysqli->error;
    
    $i = -1;
    $alleTn = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
    while ($a = $alleTn->fetch_assoc()) //participant loop
    {
      $i++;
      //grade participant $a
      for ($z = $anzahlkat; $z > 0; $z-=1) //category loop
      {
        if ($data[$i]<=$kat[$z])
        {
          //category $z !
          $punkte = $general["kat$z"];
          $mysqli->Query("UPDATE $spielID"."_teilnehmer SET punkte$statId = $punkte WHERE id = ".$a['id']);
          break;
        }
      }
    }
  }
  
  
  //Nun haben wir die Kategorien-->bewerten!
  
  return true;
}

function aussteigenButton()
{
  global $langArray;
  ?>
  <br><hr>
  <form action="WettbewerbCreator.php" method="post">
    <input type="hidden" name="aussteigen" value=1 />
    <p id = "normal" align = "center"><input type="submit" value = "<?php echo $langArray['exit']?>"/></p>
  </form>
  <?php
}

function getNameForArt($art)
{
  global $langArray;
  //Returns a short description of a score type
  if ($art == "punktehoch")
    return $langArray['score_type_high_points_short'];
  elseif ($art == "punktenieder")
    return $langArray['score_type_low_points_short'];
  elseif ($art == "punktedirekt")
    return $langArray['score_type_direct_short'];
  elseif ($art == "zeitnieder")
    return $langArray['score_type_low_time_short'];
  elseif ($art == "zeithoch")
    return $langArray['score_type_high_time_short'];
  else
    return "?";
}

function getWertung($art, $wertung)
{
  global $langArray;
  if ($art == "punktehoch" || $art == "punktenieder" || $art == "punktedirekt")
    return $wertung;
  //Then time
  //is given in milliseconds
  $zeit = "";
  if ($wertung >= 3600000)
  {
    //we have hours
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

function adminLogin($mysqli)
{
  global $langArray;
  try
  {
    $spielID=$_POST['wettbewerbid'];
    //is id a number?
    if (is_numeric($spielID))
    {
      $res = $mysqli->Query("SELECT * FROM $spielID"."_general");
      if ($res)
      {
        //exists
        //right password?
        $general = $res->fetch_assoc();
        $pwHashDb = $general['adminpw'];
        $pw = $_POST['password']; 
        if (password_verify($pw,$pwHashDb))
        {
          //password correct
          setcookie ("spielID", $spielID, time()+172800); //2 days
          $_COOKIE["spielID"]=$spielID;
          $_SESSION['admin']=$spielID;
          
          //Already a participant table?
          $res2 = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer");
          if ($res2)
          {
            //To the control center
            $_SESSION['kontrollcenter']=1;
            kontrollCenter($mysqli);
          }
          else
          {
            schritt4($mysqli,$spielID);
          }
        }
        else
        {
          echo "<p id='error'>".$langArray['error_occured'].": L04<br>".$langArray['wrong_password']."</p>";
        }
      }
      else
      {
        //No game with this id exists
         echo "<p id='error'>".$langArray['error_occured'].": L03<br>".$langArray['no_game_id_exists']."</p>";
      }
    }
    else
    {
      //No number entered ...
      echo "<p id='error'>".$langArray['error_occured'].": L02</p>"; 
    }
  }
  catch(Exception $e)
  {
    echo "<p id='error'>".$langArray['error_occured'].": L01</p>";
  }
}

function statBetrLogin($mysqli) //login for staff
{
  global $langArray;
  try
  {
    $spielID=$_POST['wettbewerbid'];
    $statID=$_POST['statid'];
    //ist id a number
    if (is_numeric($spielID) && is_numeric($statID))
    {
      $res = $mysqli->Query("SELECT * FROM $spielID"."_stationen WHERE id = $statID");
      if ($res)
      {
        //exists
        $stat = $res->fetch_assoc();
        $gres = $mysqli->Query("SELECT * FROM $spielID"."_general");
        $general = $gres->fetch_assoc();
        if ($general['stationenbetreuer']==1 && $stat['pw']!="")
        {
          //correct pw?
          $pwHashDb = $stat['pw'];
          $pw = $_POST['password']; 
          if (password_verify($pw,$pwHashDb))
          {
            //Password correct
            setcookie ("spielID", $spielID, time()+172800);
            $_COOKIE["spielID"]=$spielID;
            $_SESSION['statbetr']=$spielID;
            $_SESSION['statID']=$statID;
            $_SESSION['admin']=-1;
            statBetrUebersicht($mysqli);
          }
          else
          {
            echo "<p id='error'>".$langArray['error_occured'].": L04<br>".$langArray['wrong_password']."</p>";
          }
        }
        else
        {
          echo "<p id='error'>".$langArray['error_occured'].": L05<br>".$langArray['no_staff_for_this_challenge']."</p>";  
        }
      }
      else
      {
        //no game with this ID exists
         echo "<p id='error'>".$langArray['error_occured'].": L03<br>".$langArray['no_game_id_or_challenge_exists']."</p>";
      }
    }
    else
    {
      //no ID entered
      echo "<p id='error'>".$langArray['error_occured'].": L02</p>"; 
    }
  }
  catch(Exception $e)
  {
    echo "<p id='error'>".$langArray['error_occured'].": L01</p>";
  }
}

function containsBadThings($ausdruck)
{
  if (strpos($ausdruck,"$")===false && strpos($ausdruck,";")===false && strpos($ausdruck,'"')===false && strpos($ausdruck,"'")===false && strpos($ausdruck,"=")===false && strpos(strtoupper($ausdruck),"DROP")===false)
  {
    return false;
  }
  else
    return true;
}

function statBetrUebersicht($mysqli)
{
  global $langArray;
  if ($_COOKIE["spielID"]!=$_SESSION['statbetr'])
    return;
  $statID = $_SESSION['statID'];
  zeigeStation($mysqli,$statID,false);
  echo "<form action='WettbewerbCreator.php' method='post'><p align='center'><input type='submit' value='".$langArray['enter_result']."' name='statbetrEintragen'></p></form>";
  ?>
  <form action="WettbewerbCreator.php" method="post">
    <p id = "normal" align = "center"><input type="submit" value = "Reload"/></p>
  </form>
  <?php
}

function statbetrEintragen($mysqli)
{
  global $langArray;
  if ($_SESSION['statbetr']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $statID = $_SESSION['statID'];
  echo "<form action='WettbewerbCreator.php' method='post'>
    <p align='center'><input type='submit' value='".$langArray['Save']."'></p>
    <table align='center'>";
  echo "<tr><th align='center'>".$langArray['participant_number_short']."</th><th align='center'>".$langArray['name']."</th>";
  $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen WHERE id = $statID");
  if ($station = $stationenRes->fetch_assoc())
  {
    echo "<th align='center'>".$station['name']."</th>";
  }
  echo "</tr>";
  $teilnehmerRes = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
  $teilnehmerzahl = $teilnehmerRes->num_rows;
  while ($teilnehmer = $teilnehmerRes->fetch_assoc())
  {
    echo "<tr><td align='center'>";
    if ($teilnehmer['info']=="")
      echo $teilnehmer['id'];
    else
    {
      echo "<div class='tooltip'>".$teilnehmer['id'];
      echo "<span class='tooltiptext'>".$teilnehmer['name'].": ";
      echo $teilnehmer['info'];
      echo "</span></div>";
    }
    echo "</td><td align='center'>".$teilnehmer['name']."</td>";
    echo "<td align='center'>";
    $stationArt = $station['art'];
    if ($stationArt=="punktehoch" || $stationArt=="punktenieder" || $stationArt=="punktedirekt")
    {
      echo "<input type='text' name='t".$teilnehmer['id']."s$statID' 
        value='".$teilnehmer["wertung$statID"]."'>";
    }
    else
    {
      $wertung = $teilnehmer["wertung$statID"];
      $stunden = floor($wertung/3600000);
      $wertung -= $stunden*3600000;
      
      $min = floor($wertung/60000);
      $wertung -= $min*60000;
      
      $sek = floor($wertung/1000);
      $wertung -= $sek*1000;
      
      $millisek = floor($wertung);
      
      echo "<div class='tooltip'><input type='number' min=0 step=1 value=$stunden name = 't".$teilnehmer['id']."s$statID"."h'><span class='tooltiptext'>".$langArray['Hours']."</span></div>";
      echo "<div class='tooltip'><input type='number' min=0 step=1 value=$min name = 't".$teilnehmer['id']."s$statID"."min'><span class='tooltiptext'>".$langArray['Minutes']."</span></div>";
      echo "<div class='tooltip'><input type='number' min=0 step=1 value=$sek name = 't".$teilnehmer['id']."s$statID"."s'><span class='tooltiptext'>".$langArray['Seconds']."</span></div>";
      echo "<div class='tooltip'><input type='number' min=0 step=1 value=$millisek name = 't".$teilnehmer['id']."s$statID"."mil'><span class='tooltiptext'>".$langArray['Milliseconds']."</span></div>";
    }
  
    echo "</tr>";
  }
  echo "</table><p align='center'><input type='reset' value='".$langArray['Reset']."'></p>
    <input type = 'hidden' name = 'statErgebnisEingebenSpeichern' value=1>
    <p align='center'><input type='submit' value='".$langArray['Save']."'></p>
    </form>
    <form action='WettbewerbCreator.php' method='post'><p align='center'><input type='submit' value='".$langArray['Cancel']."'></p></form>";  
}

function statErgebnisEingebenSpeichern($mysqli)
{
  global $langArray;
  if ($_SESSION['statbetr']!=$_COOKIE['spielID'])
    return;
  $spielID=$_COOKIE['spielID'];
  $statID = $_SESSION['statID'];
  $teilnehmerRes = $mysqli->Query("SELECT * FROM $spielID"."_teilnehmer WHERE aktiv = 1");
  $teilnehmerzahl = $teilnehmerRes->num_rows;
  $stationenRes = $mysqli->Query("SELECT * FROM $spielID"."_stationen WHERE id = $statID");
  $station = $stationenRes->fetch_assoc();
  $stationArt = $station['art'];
  while ($teilnehmer = $teilnehmerRes->fetch_assoc())
  {
    $sql = "UPDATE $spielID"."_teilnehmer SET ";
    
    if ($stationArt=="punktehoch" || $stationArt=="punktenieder" || $stationArt=="punktedirekt")
    {
      try
      {
        $eingabe = $_POST["t".$teilnehmer['id']."s$statID"];
        $sql.= "wertung$statID = $eingabe, ";
      }
      catch (Exception $e)
      {
        echo "<p id='error'>".$e->getMessage()."</p>";
      }
    }
    else
    {
      try
      {
        $stunden = $_POST["t".$teilnehmer['id']."s$statID"."h"];
        $min = $_POST["t".$teilnehmer['id']."s$statID"."min"];
        $sek = $_POST["t".$teilnehmer['id']."s$statID"."s"];
        $millisek = $_POST["t".$teilnehmer['id']."s$statID"."mil"];
        $wertung = $millisek + 1000*$sek + 60000*$min + 3600000*$stunden;
        $sql.="wertung$statID = $wertung, ";
      }
      catch (Exception $e)
      {
        echo "<p id='error'>".$e->getMessage()."</p>";
      }
    }
    //delete comma
    $sql = substr($sql,0,strlen($sql)-2);
    $sql.= " WHERE id = ".$teilnehmer['id'].";";
    $mysqli->Query($sql);
       
  }
  $mysqli->Query("UPDATE $spielID"."_general SET `zuletztbearbeitet` = ".time()." WHERE id = $spielID ;");
  $gres = $mysqli->Query("SELECT * FROM $spielID"."_general");
  $general = $gres->fetch_assoc();
  if ($general['punkteakt']==1)
    bewerteWennMoeglich($mysqli);
}
?>


