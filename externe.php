<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of mrvbNextDoor, a plugin for Dotclear 2
#
# © Mirovinben (http://www.mirovinben.fr/)
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------
#
# For information about configuring and using the function extNextDoor(),
# please consult the lisezmoi.txt file (only available in French).
#
# Pour toute information concernant le paramétrage et l'usage de la fonction extNextDoor(),
# veuillez consulter le fichier lisezmoi.txt.

$errNUL = 'La constante DC_ROOT n\'est pas définie...';
$errDC2 = 'La constante DC_ROOT n\'indique pas le bon chemin d\'accès à Dotclear...';
$errCBK = 'Impossible de trouver le répertoire de Clearbricks...';
$errSQL = 'Base de données inaccessible...';

if (!defined('DC_ROOT')) {
	echo '<p>'.$errNUL.'</p>';
	return 0;
}

if (is_dir(DC_ROOT.'/inc/libs/clearbricks')) {
	$DC_SGBD = DC_ROOT.'/inc/libs/clearbricks';
} else {
	$DC_SGBD = DC_ROOT.'/inc/clearbricks';
}

if (!@file_exists($DC_SGBD.'/_common.php')) {
	echo '<p>'.$errCBK.'</p>';
	return 0;
}

if (!@file_exists(DC_ROOT.'/inc/config.php')) {
	echo '<p>'.$errDC2.'</p>';
	return 0;
}

if (!defined('DC_RC_PATH')) {
	define('DC_RC_PATH',DC_ROOT.'/inc/config.php');
}

include_once($DC_SGBD.'/_common.php');
include_once(DC_ROOT.'/inc/config.php');

try {
	$con = dbLayer::init(DC_DBDRIVER,DC_DBHOST,DC_DBNAME,DC_DBUSER,DC_DBPASSWORD);
}
catch (Exception $e)
{
	echo '<p>'.$errSQL.'</p>';
}

function extNextDoor($attr)
{
	global $con;
	if(!$con) { return; }
	include_once('common.php');
	$attr = $attr + array('mode' => 'externe');
	echo NextDoor($attr);
}