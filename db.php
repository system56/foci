<?php
//Hibaüzenetek kiírása
$mysqlerrorecho="<html><head><title>Várj...</title><noscript><meta http-equiv=\"refresh\" content=\"5\"></noscript></head><body>Rögtön indulunk!</body></html>";
$mysqldberrorecho="<html><head><title>Várj...</title><noscript><meta http-equiv=\"refresh\" content=\"5\"></noscript></head><body>Rögtön indulunk!</body></html>";
 
//Adatbázias kapcsolat beállítása
if(@!$kapcsolat = mysql_connect("localhost", "foci", "foci")) die("$mysqlerrorecho"); 
mysql_select_db("foci", $kapcsolat) or die("$mysqldberrorecho");
 
//Probléma esetén használandó mySQL-hez
//mysql_query("SET SESSION SQL_BIG_SELECTS=1");
//mysql_query("set names latin1");
 
?>
