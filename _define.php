<?php
/**
 * @brief mrvbNextDoor, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Mirovinben (https://www.mirovinben.fr/)
 *
 * @copyright GPL-2.0 [https://www.gnu.org/licenses/gpl-2.0.html]
 */

if (!defined('DC_RC_PATH')) {
    return;
}

$this->registerModule(
    'mrvbNextDoor',																// Name
    'Display posts from other blog of the same multiblog',						// Description
    'Mirovinben',																// Authors
    '1.9',																	// Version
    [
        'requires' => [['core', '2.24']],										// Dependencies
        'permissions' => 'usage,contentadmin',									// Permissions
        'type' => 'plugin',														// Type
        'support' => 'https://www.mirovinben.fr/blog/index.php?post/id2656',	//Support & details
        'details' => 'https://plugins.dotaddict.org/dc2/details/mrvbNextDoor'
    ]
);
