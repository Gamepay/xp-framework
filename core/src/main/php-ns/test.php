<?php

/***
    Some startup tests for a converted XP- Framework to namespaces. (Work in Progress) 
 
 */

  // Let's init
  include ('lang.base.php');


  // Basic using
  use util\Date, util\DateUtil;


  // Gimme a date!
  $b= Date::now();
 
  var_dump($b);

  // Throw an exception

  throw new IllegalArgumentException();
  echo $b;  // BROKEN


 
?>
