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
    'mrvbNextDoor',
    'Display posts from other blog of the same multiblog',
    'Mirovinben',
    '1.9',
    [
        'requires' => [['core', '2.24']],
        'permissions' => dcCore::app()->auth->makePermissions([dcAuth::PERMISSION_CONTENT_ADMIN]),
        'type' => 'plugin',
        'support' => 'https://www.mirovinben.fr/blog/index.php?post/id2656',
        'details' => 'https://plugins.dotaddict.org/dc2/details/mrvbNextDoor'
    ]
);
