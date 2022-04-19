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
# For information about configuring and using the function intNextDoor(),
# please consult the lisezmoi.txt file (only available in French).
#
# Pour toute information concernant le paramétrage et l'usage de la fonction intNextDoor(),
# veuillez consulter le fichier lisezmoi.txt.

function intNextDoor($attr) {
	include_once('common.php');
	$attr = $attr + array('mode' => 'interne');
	echo NextDoor($attr);
}