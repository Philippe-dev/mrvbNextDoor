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
function intNextDoor($attr)
{
    include_once 'common.php';
    $attr = $attr + ['mode' => 'interne'];
    echo NextDoor($attr);
}
