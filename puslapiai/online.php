<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

//patikrinam ar teisingai uzkrautas puslapis

if (!defined("OK")) {
	header('location: ?');
	exit;
}
unset($extra);

//$timeout = (time() - 300);
//echo $timeout;
if (defined("LEVEL") && LEVEL == 1) { //ADMINAS
	$q = "SELECT * FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "'";
} elseif (!defined("LEVEL")) { //SVECIAS
	$q = "SELECT `id`, `uid`, `file`, `user`, `clicks`, `timestamp` FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' LIMIT 10";
} else { //USERIS
	$q = "SELECT `id`, `uid`, `user`, `clicks`, `file`, `timestamp` FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' ORDER BY `clicks` ASC LIMIT 50";
}

$result = mysql_query1($q) or die(klaida("Klaida", mysql_error()));
$u = mysql_num_rows($result);
$i = 0;

while ($row = mysql_fetch_assoc($result)) {
	//if (!empty($row['user'])) {
	$narsykle = ((isset($row['agent'])) ? browser($row['agent']) : '?');
	$info[$i] = array("{$lang['online']['who']}" => user($row['user'], $row['id']), "{$lang['online']['timestamp']}" => date('i:s', $timestamp - $row['timestamp']), "{$lang['online']['clicks']}" => $row['clicks']);
	if (defined("LEVEL") && LEVEL == 1) {
		$info[$i]['IP'] = "<a href='http://www.dnsstuff.com/tools/whois.ch?ip=" . $row['ip'] . "&src=ShowIP' target='_blank' title='" . $row['ip'] . "'>" . $row['ip'] . "</a>";
		$info[$i][$lang['online']['page']] = '<a href="?' . $row['file'] . '"><img src="images/icons/link.png" alt="page" border="0" class="middle"/></a>';
		$info[$i][$lang['online']['browser']] = "<div>" . $narsykle . "</div>";
		$info[$i]['OS'] = get_user_os();
		$info[$i][$lang['online']['country']] = '<img src="http://api.hostip.info/flag.php?ip=' . $row['ip'] . '" height="16" ALT="' . $lang['online']['country'] . '" />';
	}
	$i++;
}

include_once ("priedai/class.php");
$bla = new Table();
lentele("{$lang['online']['users']} - " . $u, $bla->render($info));

mysql_free_result($result);
//unset($user,$nekvepuoja,$file,$img,$content,$i,$u,$q,$row,$extra);


?>