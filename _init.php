<?php
/**
 * @brief mrvbNextDoor, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Mirovinben (http://www.mirovinben.fr/)
 *
 * @copyright GPL-2.0 [https://www.gnu.org/licenses/gpl-2.0.html]
 */

if (!defined('DC_RC_PATH')) {
    return;
}

define('NXDO_FORMDATE', '%d/%m/%Y %Hh%M');
define('NXDO_SETTEXT', 'from=full, length=100, cut=[&hellip;]');
define('NXDO_SETIMAGE', 'link=none, from=full, start=1, length=1, title=1');
define('NXDO_SETNBCOMM', 'none="no comments",one="one comment",more="%d comments"');
define('NXDO_FORMITEM', '%DATE% : %BLOG:BLOG% - %TITLE:POST%');
define('NXDO_TITLECUT', '[&hellip;]');
