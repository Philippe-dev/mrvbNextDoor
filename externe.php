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
 *
 * For information about configuring and using the function NextDoor(),
 * please consult the lisezmoi.txt file (only available in French).
 *
 * Pour toute information concernant le paramétrage et l'usage de la fonction NextDoor(),
 * veuillez consulter le fichier lisezmoi.txt.
 */
$errNUL = 'La constante DC_ROOT n\'est pas définie...';
$errDC2 = 'La constante DC_ROOT n\'indique pas le bon chemin d\'accès à Dotclear...';
$errCBK = 'Impossible de trouver le répertoire de Clearbricks...';
$errSQL = 'Base de données inaccessible...';

if (!defined('DC_ROOT')) {
    echo '<p>' . $errNUL . '</p>';

    return 0;
}

if (is_dir(DC_ROOT . '/inc/libs/clearbricks')) {
    $DC_SGBD = DC_ROOT . '/inc/libs/clearbricks';
} else {
    $DC_SGBD = DC_ROOT . '/inc/clearbricks';
}

if (!@file_exists($DC_SGBD . '/_common.php')) {
    echo '<p>' . $errCBK . '</p>';

    return 0;
}

if (!@file_exists(DC_ROOT . '/inc/config.php')) {
    echo '<p>' . $errDC2 . '</p>';

    return 0;
}

if (!defined('DC_RC_PATH')) {
    define('DC_RC_PATH', DC_ROOT . '/inc/config.php');
}

include_once $DC_SGBD . '/_common.php';
include_once DC_ROOT . '/inc/config.php';

try {
    dcCore::app()->con = dbLayer::init(DC_DBDRIVER, DC_DBHOST, DC_DBNAME, DC_DBUSER, DC_DBPASSWORD);
} catch (Exception $e) {
    echo '<p>' . $errSQL . '</p>';
}

function extNextDoor($attr)
{
    if (!dcCore::app()->con) {
        return;
    }
    include_once 'common.php';
    $attr = $attr + ['mode' => 'externe'];
    echo NextDoor($attr);
}
