<?php
        session_start();
        if (!isset($_GET['langID']))
          $lang = 'en';
        else
        {
          $lang = $_GET['langID'];
          $_SESSION['langID'] = $lang;
        }
  
        if (isset($_SESSION['langID']))
            $lang = $_SESSION['langID'];
        include('locale/'. $lang . '.php');
?>