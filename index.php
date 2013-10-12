<?php
/*
### Buzás Attila - Focista Fejlesztés @2013.10.09. - 2013.10.10. ###
 
##A program hagyományos szövegszerkesztőben készült...
 
#EMAIL: Készíts egy egyszerű webes alkalmazást, mely képes futball játékosokat, klubokat és az átigazolások történetét tárolni.
#
#A Program csak az alapvető funkciókat tudja -> TÁROL,MÓDOSÍT,TÖRÖL,MEGJELENÍT.
#Igény esetén egyes részei tovább bővíthetőek.
#A program kinézeti elemeket nem tartalmaz!
#Feladat: Az alkalmazásnak képesnek kell(...)
#	megjeleníteni:	Adatok megjelennek!
#	módosítani:		Adatok szerkeszthetőek!
#	törölni:		Adatok törölhetők!
#	adatbázis konzisztencia: Fenntartható! (Adatok szerkesztése és törlése egyéni ötlet alapján.)
#	
#	bevitelkor a mezők validálása megtörténjen: Adatok bevitelkori ellenőrzése megtörténik, de nincsenek fix korlátok!
#		Következtetés:	Életkor: 6-70 év között;
#						Név játékos/csapat: Maximum 100 utf8-as karakter;
#						Csapat alapítása: min 1700;
#						Javasolt az adatok megfelelőségét elsődlegesen JS-re bízni.
#	Dokumentációt nem vagy csak elvétve tartalmaz a Program.
#	A program részletei egymástól elkülöníthetőek, külön fájlokra bontható az index.php.
#	Igény esetén Többnyelvűség megoldható.
#	Globális változók a CORE/CONFIG.PHP-ban
#	Adatbázis fájl: CORE/DB.PHP
#	sk -> Smart key (SWITCH)
#	Felhasznált sk lehetőségek:
#		-np		-> New player (Új játékos FORM)
#		-np_s	-> -||- Adatbázisba mentés / frissítés
#		-nc		-> New club (Új Klub FORM)
#		-nc_s	-> -||- Adatbázisba mentés / frissítés
#		-nch	-> New change (Átigazolás FORM) 
#		-nch_s	-> -||- Adatbázisba mentés / frissítés
#		-chd	-> Delete change (Átigazolás törlés: Törli a nála újabb átigazolásokat is.)
#		-pd		-> Delete player (Játékos törlése: Törli az átigazolásait is.)
#		-cd		-> Delete club (Klub törlése: Törli az összes adatot, amiben jelen van!)
#		-club	-> Klub adatlap: Neve, Alapítás, Játékosok [link]
#		-player	-> Játékos adatlap: Név, Csapat [link], Kor, Nemzetiség
#	Jelmagyarázat: E ->szerkesztés | X->törlés
#	Működési teszt:
✔		Klub felvétele [sikeres]
✔		Klub szerkesztése [sikeres]
✔		Klub törlése [sikeres]
✔		Felhasználó felvétele [sikeres]
✔		Felhasználó szerkesztése [sikeres]
✔		Felhasználó törlése [sikeres]
✔		Átigazolás [sikeres]
✔		Átigazolás szerkesztése [sikeres]
✔		Átigazolás törlése [sikeres]
#	Funkciók:
#		club(id) -> visszaadja egy klub nevét [link] 
#		player(id) -> visszaadja egy játékos nevét [link]
#
#	A program a működésegyeztetés után jobban igényekre szabható!
#
#	A programban található nevek és csapatok a véletlen művei, a valósággal való bármi egyezés csak a véletlen műve.
#
#	Kelt: Budapest 21. 2013.10.10.
 
*/
ob_start();
header('Content-Type: text/html; charset=utf-8');
header("Pragma: no-cache");
header("Cache-control: private, no-store, no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1993 05:00:00 GMT");
include"Core/config.php";
function player($id){
	$PlayerSQL = @mysql_query("SELECT PID,PName,PAge,PNation,PCID FROM player WHERE PID='$id' LIMIT 1");
	while($Player = @mysql_fetch_array($PlayerSQL)){
		return "<a href=\"?sk=player&id=$Player[PID]\">$Player[PName]</a>";
	}
}
function club($id){
	include_once"Core/db.php";
	$ClubSQL = @mysql_query("SELECT CID,CName FROM club WHERE CID=$id LIMIT 1");
	while($Club = @mysql_fetch_array($ClubSQL)){
		return "<a href=\"?sk=club&id=$Club[CID]\">$Club[CName]</a>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<HTML>
	<HEAD>
		<TITLE><?=$Lang[football]?></TITLE>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-language" content="hu" />
	</HEAD>
	<BODY>
	<?
	switch($_GET[sk]){
		case"np":
			echo"<a href=\"index.php\"><?=$Lang[Home]?></a><br>";
			if($_GET[e]==1){echo"<font color=\"red\">Hiba, Minden adatot adj meg.</font>";}
			if(isset($_GET[id])){
				include$Config["DBfile"];
				$PlayerSQL = @mysql_query("SELECT PID,PName,PAge,PNation,PCID FROM player WHERE PID='$_GET[id]' LIMIT 1");
				while($Player = @mysql_fetch_array($PlayerSQL)){
					$DataPlayerN = $Player[PName];
					$DataPlayerC = $Player[PCID];
					$DataPlayerA = $Player[PAge];
					$DataPlayerNA = $Player[PNation];
				}
			}
			?>
				<form method="post" action="?sk=np_s<?if(isset($_GET[id])){echo"&id=".$_GET[id];}?>">
					<table>
					<tr>
						<td><?=$Lang[Name]?>:</td>
						<td><input type="text" name="Pname" value="<?=$DataPlayerN?>"></td>
					</tr>
					<tr>
						<td><?=$Lang[Team]?>:</td>
						<td><select name="Pteam" title="Módosítása esetén új átigazolás keletkezik. Kérem töltse ki a hiányzó adatokat.">
							<?
								include$Config["DBfile"];
								$ClubSQL = @mysql_query("SELECT CID,CName,CFound FROM club ORDER BY CID ASC");
								while($Club = @mysql_fetch_array($ClubSQL)){
									if($Club[CID]==$DataPlayerC){$sel="selected";}else{$sel="";}
									echo"<option value=\"".$Club[CID]."\" $sel>".$Club[CName]."</option>";
								}
							?>
						</select><a href="?sk=nc" target="_balnk"><?=$Lang[New_team]?></a></td>
					</tr>
					<tr>
						<td><?=$Lang[Age]?>:</td>
						<td><input type="text" name="Page" value="<?=$DataPlayerA?>" title="min: 6 max: 70"></td>
					</tr>
					<tr>
						<td><?=$Lang[Nationality]?>:</td>
						<td><input type="text" name="Pnation" value="<?=$DataPlayerNA?>" title="PL: Magyar"></td>
					</tr>
					</table>
					<input type="submit">
				</form>
			<?
		break;
		case"np_s":
			include$Config["DBfile"];
			if($_POST[Pname]!="" && ($_POST[Page]>=6 && $_POST[Page]<=70) && $_POST[Pnation]!=""){ //hibakezelés
				if(isset($_GET[id]) && $_GET[id]>0){
					$PlayerSQL = @mysql_query("SELECT PID,PCID FROM player WHERE PID='$_GET[id]' LIMIT 1");
					while($Player = @mysql_fetch_array($PlayerSQL)){
						if($Player[PCID] != $_POST[Pteam]){
							@mysql_query("INSERT INTO `change` (`ID` ,`PID` ,`oCID` ,`nCID` ,`CTime` ,`CPrice`)VALUES (NULL, '$_GET[id]', '$Player[PCID]', '$_POST[Pteam]', '".time()."', '');"); //felvétel
							$NewCHID = mysql_insert_id();
							$NPedit = 1;
						}
					}
					@mysql_query("UPDATE player SET PName='$_POST[Pname]', PAge='$_POST[Page]', PNation='$_POST[Pnation]', PCID='$_POST[Pteam]' WHERE PID='$_GET[id]' LIMIT 1");//módosítás
					if($NPedit == 1){
						header("Location: ?sk=nch&id=$NewCHID"); //átirányítás
						exit;
					}
				}else{
					@mysql_query("INSERT INTO player (PID,PName,PAge,PCID,PNation)VALUES(NULL,'$_POST[Pname]','$_POST[Page]','$_POST[Pteam]','$_POST[Pnation]');"); //felvétel
				}
				header("Location: index.php"); //átirányítás
				exit;
			}else{
				header("Location: index.php?sk=np&e=1"); //hiba
				exit;
			}
		break;
		case"nc":
			if($_GET[e]==1){echo"<font color=\"red\">Hiba, Minden adatot adj meg.</font>";}
			if(isset($_GET[id])){
				include$Config["DBfile"];
				$ClubSQL = @mysql_query("SELECT CID,CName,CFound FROM club WHERE CID='$_GET[id]' LIMIT 1");
				while($Club = @mysql_fetch_array($ClubSQL)){
					$DataClubN = $Club[CName];
					$DataClubF = $Club[CFound];
				}
			}
			?>
				<form method="post" action="?sk=nc_s<?if(isset($_GET[id])){echo"&id=".$_GET[id];}?>">
					<table>
					<tr>
						<td><?=$Lang[ClubName]?>:</td>
						<td><input type="text" name="Cname" value="<?=$DataClubN?>"></td>
					</tr>
					<tr>
						<td><?=$Lang[Founded]?>:</td>
						<td><input type="text" name="Cfound" value="<?=$DataClubF?>"></td>
					</tr>
					</table>
					<input type="submit" value="Küldés">
				</form>
			<?
		break;
		case"nc_s":
			include$Config["DBfile"];
			if($_POST[Cname]!="" && ($_POST[Cfound]>=$Config[MinYear] || $_POST[Cfound]<=date("Y")) && isset($_POST[Cfound])){ //hibakezelés
				if(isset($_GET[id]) && $_GET[id]>0){
					@mysql_query("UPDATE club SET CName='$_POST[Cname]', CFound='$_POST[Cfound]' WHERE CID='$_GET[id]' LIMIT 1"); //módosítás
				}else{
					@mysql_query("INSERT INTO club (CID,CName,CFound)VALUES(NULL,'$_POST[Cname]','$_POST[Cfound]');"); //felvétel
				}
				header("Location: index.php"); //átirányítás
				exit;
			}else{
				header("Location: index.php?sk=nc&e=1"); //hiba
				exit;
			}
		break;
		case"nch":
			if($_GET[e]==1){echo"<font color=\"red\">Hiba, Minden adatot adj meg.</font>";}
			include$Config["DBfile"];
			if(isset($_GET[id])){
				$ChangeSQL = @mysql_query("SELECT * FROM `change` WHERE ID='$_GET[id]' LIMIT 1");
				while($Change = @mysql_fetch_array($ChangeSQL)){
					$DataTimeT = $Change[CTime];
					$DataChangePID = $Change[PID];
					$DataChangeP = $Change[CPrice];
					$DataChangeoCID = $Change[oCID];
					$DataChangenCID = $Change[nCID];
				}
			}else{$DataChangePID = $_GET[pid];}
			if(isset($DataChangePID)){
				$PlayerSQL = @mysql_query("SELECT PID,PName,PAge,PNation,PCID FROM player WHERE PID='$DataChangePID' LIMIT 1");
				while($Player = @mysql_fetch_array($PlayerSQL)){
					$DataPlayerPID = $Player[PID];
					$DataPlayerN = $Player[PName];
					$DataPlayerC = $Player[PCID];
					$DataPlayerA = $Player[PAge];
					$DataPlayerNA = $Player[PNation];
				}
			?>
				<a href="index.php"><?=$Lang[Home]?></a>
				<form method="post" action="?sk=nch_s<?if(isset($_GET[id])){echo"&id=".$_GET[id];}?>">
					<table>
					<tr>
						<td><?=$Lang[Player]?>:</td>
						<td>
						<select name="Cplayer">
							<?
								if(isset($DataPlayerPID)){
									echo"<option value=\"$DataPlayerPID\" >$DataPlayerN [$DataPlayerA, $DataPlayerNA]</option>";
								}else{
									$PlayerSQL = @mysql_query("SELECT PID,PName,PAge,PNation FROM player ORDER BY PName ASC");
									while($Player = @mysql_fetch_array($PlayerSQL)){
										echo"<option value=\"$Player[PID]\" >$Player[PName] [$Player[PAge], $Player[PNation]]</option>";
									}
								}
							?>
						</select>
						<?
						if(isset($DataPlayerPID)){
							echo"<a href=\"?sk=player&id=".$DataPlayerPID."\">Adatlapja</a>";
						}
						?>
						</td>
					</tr>
					<tr>
						<td><?=$Lang[Timeline]?>:</td>
						<td>
						<table>
						<tr>
							<td><?=$Lang[When]?>:</td>
							<td><?=$Lang[OldTeam]?>:</td>
							<td><?=$Lang[NewTeam]?></td>
							<td><?=$Lang[Price]?>:</td>
							<td>e/x</td>
						</tr>
							<?
								$I_CH = 0;
								$ChangeSQL = @mysql_query("SELECT * FROM `change` WHERE PID='$DataPlayerPID' ORDER BY CTime ASC");
								while($Change = @mysql_fetch_array($ChangeSQL)){
									$I_CH++;
									if($Change[ID] == $_GET[id]){
										echo"<tr>";
										?>
										<td>
										<select name="CTYear">
											<?
												$year = $Config[MinYear];
												if(!isset($now)){$now = date("Y",$DataTimeT);}
												while($now >= $year){
													if($now == date("Y")){$sele = "selected";}else{$sele = "";}
															echo"<option value=\"".$now."\" ".$sele.">".$now."</option>";
													$now--;
												}
											?>
										</select>
										<select name="CTMounth">
											<?
												if(!isset($ho)){$ho = 1;}
												while($ho <= 12){
													if($ho == date("m",$DataTimeT)){$hosele = "selected";}else{$hosele = "";}
													echo"<option value=\"".$ho."\" ".$hosele.">".$ho.".</option>";
													$ho++;
												}
											?>
										</select>
										<select name="CTday">
											<?
											if(!isset($nap)){$nap = 1;}
											while($nap <= 31){
												if($nap == date("d",$DataTimeT)){$nsele = "selected";}else{$nsele = "";}
												echo"<option value=\"".$nap."\" ".$nsele.">".$nap.".</option>";
												$nap++;
											}
											?>
										</select>
										</td>
										<td>
											<select name="CClubO">
											<?
												if(isset($DataPlayerPID) && !isset($_GET[id])){
													$SQL_OPT="WHERE CID='$DataPlayerC' LIMIT 1";
												}else{
													$SQL_OPT="ORDER BY CName ASC";
												}
												$ClubSQL = @mysql_query("SELECT CID,CName,CFound FROM club $SQL_OPT");
												while($Club = @mysql_fetch_array($ClubSQL)){
													if($DataChangeoCID==$Club[CID]){$CCO_s = "selected";}else{$CCO_s = "";}
													echo"<option value=\"$Club[CID]\" $CCO_s>$Club[CName] [$Club[CFound]]</option>";
												}
											?>
											</select>
										</td>
										<td>
											<select name="CClubN">
											<?
												if(isset($DataPlayerPID) && !isset($_GET[id])){
													$SQL_OPT2="WHERE CID!='$DataPlayerC'";
												}
												$ClubSQL = @mysql_query("SELECT CID,CName,CFound FROM club $SQL_OPT2 ORDER BY CName ASC");
												while($Club = @mysql_fetch_array($ClubSQL)){
													if($DataChangenCID==$Club[CID]){$CCN_s = "selected";}else{$CCN_s = "";}
													echo"<option value=\"$Club[CID]\" $CCN_s>$Club[CName] [$Club[CFound]]</option>";
												}
											?>
											</select>
										</td>
										<td><input type="text" name="Cprice" value="<?=$DataChangeP?>"></td>
										<td><input type="submit" value="Mentés">x</td>
										<?
										echo"</tr>";
									}else{
										echo"<tr><td>".date("Y.m.d",$Change[CTime])."</td><td>".club($Change[oCID])."</td><td>".club($Change[nCID])."</td><td>".$Change[CPrice]."</td><td><a href=\"?sk=nch&id=$Change[ID]\">e</a> | <a>x</a></td></tr>";
									}
								}
								if($I_CH==0){
									echo"<tr><td>".$Lang[No_data_to_display]."</td></tr>";
								}
							if(!isset($_GET[id])){
							?>
							<td>
							<select name="CTYear">
								<?
									$ChangeSQL = @mysql_query("SELECT CTime FROM `change` WHERE PID='$DataChangePID' ORDER BY CTime DESC LIMIT 1");
									while($Change = @mysql_fetch_array($ChangeSQL)){
										$DataTimeT = $Change[CTime];
									}
									if(!isset($DataTimeT)){$year = $Config[MinYear];}else{$year = date("Y",$DataTimeT);}
									if(!isset($now)){$now = date("Y");}
									while($now >= $year){
										if($now == date("Y")){$sele = "selected";}else{$sele = "";}
												echo"<option value=\"".$now."\" ".$sele.">".$now."</option>";
										$now--;
									}
								?>
							</select>
							<select name="CTMounth">
								<?
									if(!isset($ho)){$ho = 1;}
									while($ho <= 12){
										if($ho == date("m",$DataTimeT)){$hosele = "selected";}else{$hosele = "";}
										echo"<option value=\"".$ho."\" ".$hosele.">".$ho.".</option>";
										$ho++;
									}
								?>
							</select>
							<select name="CTday">
								<?
								if(!isset($nap)){$nap = 1;}
								while($nap <= 31){
									if($nap == date("d",$DataTimeT)){$nsele = "selected";}else{$nsele = "";}
									echo"<option value=\"".$nap."\" ".$nsele.">".$nap.".</option>";
									$nap++;
								}
								?>
							</select>
							</td>
							<td>
								<select name="CClubO">
								<?
									if(isset($DataPlayerPID)){
										$SQL_OPT="WHERE CID='$DataPlayerC' LIMIT 1";
									}else{
										$SQL_OPT="ORDER BY CName ASC";
									}
									$ClubSQL = @mysql_query("SELECT CID,CName,CFound FROM club $SQL_OPT");
									while($Club = @mysql_fetch_array($ClubSQL)){
										echo"<option value=\"$Club[CID]\" >$Club[CName] [$Club[CFound]]</option>";
									}
								?>
								</select>
							</td>
							<td>
								<select name="CClubN">
								<?
									if(isset($DataPlayerPID)){
										$SQL_OPT2="WHERE CID!='$DataPlayerC'";
									}
									$ClubSQL = @mysql_query("SELECT CID,CName,CFound FROM club $SQL_OPT2 ORDER BY CName ASC");
									while($Club = @mysql_fetch_array($ClubSQL)){
										echo"<option value=\"$Club[CID]\" >$Club[CName] [$Club[CFound]]</option>";
									}
								?>
								</select>
							</td>
							<td><input type="text" name="Cprice"></td>
							<td><?if(!isset($_GET[id])){echo"<input type=\"submit\" value=\"Küldés\">";}?></td>
							<?
							}
							?>
						</table>
						</td>
					</tr>
					</table>
				</form>
				<a href="?sk=nch&pid=<?=$DataPlayerPID?>"><?=$Lang['New']?></a>
			<?
			}else{
				$I_P = 0;
				$PlayerSQL = @mysql_query("SELECT PID,PName,PCID FROM player ORDER BY PID ASC");
				while($Player = @mysql_fetch_array($PlayerSQL)){
					$I_P++;
					echo"<div class=\"Player\" id=\"P".$Player[PID]."\"><a href=\"?sk=player&id=$Player[PID]\">".$Player[PName]."</a> [".club($Player[PCID])."]</div><a href=\"?sk=nch&pid=$Player[PID]\">".$Lang['New']."</a><br>";
					$ChangeSQL = @mysql_query("SELECT * FROM `change` WHERE PID='$Player[PID]' ORDER BY CTime ASC");
					while($Change = @mysql_fetch_array($ChangeSQL)){
						echo date("Y.m.d",$Change[CTime])." <a href=\"?sk=nch&id=$Change[ID]\">".club($Change[oCID])." - ".club($Change[nCID])."</a><br>";
 
					}
				}
				if($I_P==0){
					echo"Nincs megjeleníthető játékos!";
				}
			}
		break;
		case"nch_s":
			include$Config["DBfile"];
			if($_POST[Cplayer]>0 && $_POST[CClubO]>0 && $_POST[CClubN]>0 && $_POST[CTday]>0 && $_POST[CTday]<=31 && $_POST[CTMounth]>0 && $_POST[CTMounth]<=12 && ($_POST[CTYear]>1800 || $_POST[CTYear]>=date("Y")) && $_POST[Cprice]!=""){ //hibakezelés
				$Ctime = mktime(0,0,0,$_POST[CTMounth],$_POST[CTday],$_POST[CTYear]);
				if(isset($_GET[id])){
					@mysql_query("UPDATE `change` SET oCID='$_POST[CClubO]', nCID='$_POST[CClubN]', CTime='$Ctime', CPrice='$_POST[Cprice]' WHERE ID='$_GET[id]' LIMIT 1"); //módosítás
					$ChangeSQL = @mysql_query("SELECT * FROM `change` WHERE PID='$_POST[Cplayer]' ORDER BY CTime DESC LIMIT 1");
					while($Change = @mysql_fetch_array($ChangeSQL)){
						@mysql_query("UPDATE player SET PCID='$Change[nCID]' WHERE PID='$Change[PID]' LIMIT 1");//módosítás
					}
				}else{
					@mysql_query("INSERT INTO `change` (`ID` ,`PID` ,`oCID` ,`nCID` ,`CTime` ,`CPrice`)VALUES (NULL, '$_POST[Cplayer]', '$_POST[CClubO]', '$_POST[CClubN]', '$Ctime', '$_POST[Cprice]');"); //felvétel
					@mysql_query("UPDATE player SET PCID='$_POST[CClubN]' WHERE PID='$_POST[Cplayer]' LIMIT 1");//módosítás
				}
				header("Location: index.php"); //átirányítás
				exit;
			}else{
				header("Location: index.php?sk=nch&e=1"); //hiba
				exit;
			}
		break;
		case"chd":
			include$Config["DBfile"];
			if(isset($_GET[pid]) && isset($_GET[id])){
				//@mysql_query("DELETE FROM `change` WHERE ID='$_GET[id]' LIMIT 1");
				@mysql_query("DELETE FROM `change` WHERE ID>='$_GET[id]' AND PID='$_GET[pid]'"); //Törli a már nem megfelelő részt
				$ChangeSQL = @mysql_query("SELECT * FROM `change` WHERE PID='$_GET[pid]' ORDER BY CTime DESC LIMIT 1");
				while($Change = @mysql_fetch_array($ChangeSQL)){
					@mysql_query("UPDATE player SET PCID='$Change[nCID]' WHERE PID='$Change[PID]' LIMIT 1");//módosítás
				}
				header("Location: index.php");//törölve
				exit;
			}
		break;
		case"pd":
			include$Config["DBfile"];
			if(isset($_GET[id])){
				@mysql_query("DELETE FROM `change` WHERE PID='$_GET[id]'");
				@mysql_query("DELETE FROM player WHERE PID='$_GET[id]' LIMIT 1");
				header("Location: index.php");//törölve
				exit;
			}
		break;
		case"cd":
			include$Config["DBfile"];
			if(isset($_GET[id])){
				@mysql_query("DELETE FROM `change` WHERE oCID='$_GET[id]' OR nCID='$_GET[id]'");
				@mysql_query("DELETE FROM player WHERE PCID='$_GET[id]'");
				@mysql_query("DELETE FROM club WHERE CID='$_GET[id]' LIMIT 1");
				header("Location: index.php");//törölve
				exit;
			}
		break;
		case"club":
			include$Config["DBfile"];
			$ClubSQL = @mysql_query("SELECT CID,CName,CFound FROM club WHERE CID='$_GET[id]' LIMIT 1");
			while($Club = @mysql_fetch_array($ClubSQL)){
			?>
				<a href="index.php"><?=$Lang[Home]?></a> <a href="?sk=nc&id=<?=$_GET[id]?>"><?=$Lang[Editing]?></a><br>
				<table>
				<tr>
					<td><?=$Lang[Name]?>:</td>
					<td><?=$Club[CName]?></td>
				</tr>
				<tr>
					<td><?=$Lang[Founded]?>:</td>
					<td><?=$Club[CFound]?></td>
				</tr>
				<tr>
					<td><?=$Lang[Players]?>:</td>
					<td>
						<table>
							<?
							echo"<tr><td>Név:</td><td>Kor:</td><td>Nemzetiség:</td></tr>";
							$PlayerSQL = @mysql_query("SELECT PID,PName,PAge,PNation FROM player WHERE PCID='$_GET[id]' ORDER BY PName ASC");
							while($Player = @mysql_fetch_array($PlayerSQL)){
								echo"<tr><td><a href=\"?sk=player&id=$Player[PID]\">$Player[PName]</a></td><td>$Player[PAge]</td><td>$Player[PNation]</td></tr>";
							}
							?>
						</table>
					</td>
				</tr>
				</table>
			<?
			}
		break;
		case"player":
			include$Config["DBfile"];
			$PlayerSQL = @mysql_query("SELECT PID,PName,PAge,PNation,PCID FROM player WHERE PID='$_GET[id]' LIMIT 1");
			while($Player = @mysql_fetch_array($PlayerSQL)){
			?>
				<a href="index.php"><?=$Lang[Home]?></a> <a href="?sk=np&id=<?=$_GET[id]?>"><?=$Lang[Editing]?></a> <a href="?sk=nch&pid=<?=$_GET[id]?>">Átigazolás</a><br>
				<table>
					<tr>
						<td><?=$Lang[Name]?>:</td>
						<td><?=$Player[PName]?></td>
					</tr>
					<tr>
						<td><?=$Lang[Team]?>:</td>
						<td><?=club($Player[PCID])?></td></tr>
					<tr>
						<td><?=$Lang[Age]?>:</td>
						<td><?=$Player[PAge]?></td>
					</tr>
					<tr>
						<td><?=$Lang[Nationality]?>:</td>
						<td><?=$Player[PNation]?></td>
					</tr>
				</table>
			<?
			}
		break;
		default:
			include$Config["DBfile"]; //adatbázis kapcsolat megnyitása
			?>
				<div class="Player" style="position: absolute; top: 0px; bottom: 40%; left:0px; right: 50%;border: solid 1px black;text-align: center;overflow: auto;">
					<a href="?sk=np"><?=$Lang[Add_new_player]?>.</a><br><br>
					<table>
					<tr>
						<td><?=$Lang[Name]?></td>
						<td><?=$Lang[Age]?></td>
						<td><?=$Lang[Club]?></td>
						<td>E | X</td>
					</tr>
					<?
						$I_P = 0;
						$PlayerSQL = @mysql_query("SELECT PID,PAge,PName,PCID FROM player ORDER BY PID ASC");
						while($Player = @mysql_fetch_array($PlayerSQL)){
							$I_P++;
 
							echo"<tr><td><a href=\"?sk=player&id=$Player[PID]\" title=\"Adatlap\">".$Player[PName]."</a></td><td>".$Player[PAge]."</td><td>".club($Player[PCID])."</td><td><a href=\"?sk=np&id=$Player[PID]\">E</a> | <a href=\"?sk=pd&id=$Player[PID]\">X</a></td></tr>";
						}
						if($I_P==0){
							echo"<tr><td>Nincs megjeleníthető játékos!</td></tr>";
						}
					?>
					</table>
				</div>
				<div class="club" style="position: absolute; top: 0px; bottom: 40%; left:50%; right: 0px;border: solid 1px black;text-align: center;overflow: auto;">
					<a href="?sk=nc"><?=$Lang[Add_new_club]?>.</a><br><br>
					<table>
					<tr>
						<td><?=$Lang[Name]?>:</td>
						<td><?=$Lang[Founded]?>:</td>
						<td>E | X</td>
					</tr>
					<?
						$I_C = 0;
						$ClubSQL = @mysql_query("SELECT CID,CName,CFound FROM club ORDER BY CID ASC");
						while($Club = @mysql_fetch_array($ClubSQL)){
							$I_C++;
							echo"<tr><td><a href=\"?sk=club&id=$Club[CID]\">".$Club[CName]."</a></td><td>".$Club[CFound]."</td><td><a href=\"?sk=nc&id=$Club[CID]\">E</a> | <a href=\"?sk=cd&id=$Club[CID]\">X</a></td></tr>";
						}
						if($I_C==0){
							echo"<tr><td>Nincs megjeleníthető klub!</td></tr>";
						}
					?>
					</table>
				</div>
				<div class="Change" style="position: absolute; top: 61%; bottom: 0px; left:0px; right: 0px;border: solid 1px black;text-align: center;overflow: auto;">
					<a href="?sk=nch"><?=$Lang[Add_new_transfers]?></a><br><br>
					<table>
					<tr>
						<td><?=$Lang['Date']?>:</td>
						<td><?=$Lang[Player]?>:</td>
						<td><?=$Lang[OldTeam]?>:</td>
						<td><?=$Lang[NewTeam]?>:</td>
						<td><?=$Lang[Price]?>:</td>
						<td>E | X</td>
					</tr>
					<?
						$I_CH = 0;
						$ChangeSQL = @mysql_query("SELECT ID,PID,oCID,nCID,CTime,CPrice FROM `change` ORDER BY CTime DESC");
						while($Change = @mysql_fetch_array($ChangeSQL)){
							$I_CH++;
							echo"<tr><td>".date("Y.m.d",$Change[CTime])."</td><td>".player($Change[PID])."</td><td>".club($Change[oCID])."</td><td>".club($Change[nCID])."</td><td>".$Change[CPrice]."</td><td><a href=\"?sk=nch&id=$Change[ID]\">E</a> | <a href=\"?sk=chd&id=$Change[ID]&pid=$Change[PID]\" title=\"Ha törlöd a bejegyzést, akkor törlődik minden azutáni átigazolása a focistának.\">X</a></td></tr>\n";
						}
						if($I_CH==0){
							echo"<tr><td>".$Lang[No_data_to_display]."</td></tr>";
						}
					?>
					</table>
				</div>
			<?
		break;
	}
	?>
	</BODY>
</HTML>
