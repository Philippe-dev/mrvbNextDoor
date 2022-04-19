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

if (!defined('DC_RC_PATH')) { return; }

require_once dirname(__FILE__).'/_widget.php';
include_once('common.php');

class mrvbNextDoorPublic
{
	public static function mrvbNextDoor($w) {
		global $core;

		if ($w->offline) {
			return;
		}
		if (($w->homeonly == 1 && $core->url->type != 'default') ||
			($w->homeonly == 2 && $core->url->type == 'default')) {
			return;
		}

		$res = ($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '');
		if (strlen($w->intro)>0){
			$res .= '<div class="nxdo-first">'.$w->intro.'</div>';
		}
		$attr = array(
			'mode'      => 'widget',
			'blogid'    => $w->blogid,
			'category'  => $w->category,
			'metatag'   => $w->metatag,
			'selected'  => $w->selected,
			'password'  => $w->password,
			'listurl'   => $w->listurl,
			'maxitems'  => $w->maxitems,
			'orderby'   => $w->orderby,
			'typepost'  => $w->typepost,
			'typlist'   => $w->typlist,
			'formdate'  => $w->formdate,
			'setlocal'  => $w->setlocal,
			'settext'   => $w->settext,
			'setimage'  => $w->setimage,
			'setnbcomm' => $w->setnbcomm,
			'formitem'  => $w->formitem,
			'titlemax'  => $w->titlemax,
			'titlecut'  => $w->titlecut,
			'noexcerpt' => $w->noexcerpt,
			'readmore'  => $w->readmore
		);
		$res .= NextDoor($attr);
		if (strlen($w->conclu)>0){
			$res .= '<div class="nxdo-last">'.$w->conclu.'</div>';
		}
		return $w->renderDiv($w->content_only,'mrvbNextDoor '.$w->CSSclass,'',$res);
	}
}