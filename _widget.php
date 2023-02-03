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

dcCore::app()->addBehavior('initWidgets', [mrvbNextDoorBehaviors::class, 'initWidgets']);

require_once dirname(__FILE__) . '/_init.php';

class mrvbNextDoorBehaviors
{
    public static function initWidgets($widgets)
    {
        $widgets
        ->create('mrvbNextDoor', __('Mrvb: other blog'), ['mrvbNextDoorPublic', 'mrvbNextDoor'], null, __('posts from other blog of the same multiblog'))
            ->addTitle(__('In other blogs'))
            ->setting(
                'title',
                __('Title:'),
                __('In other blogs'),
                'text'
            )
            ->setting(
                'blogid',
                __('List of blogs (ID, ID...):<br />(empty means all, a [!] before to exclude)'),
                'default',
                'text'
            )
            ->setting(
                'category',
                __('List of category (URL, #ID...):<br />(empty means all, NULL means posts without category, a [#] before if ID, a [!] before to exclude)'),
                '',
                'text'
            )
            ->setting(
                'metatag',
                __('Filter on a tag (empty means all):'),
                '',
                'text'
            )
            ->setting(
                'selected',
                __('Selected posts only'),
                false,
                'check'
            )
            ->setting(
                'password',
                __('Filter posts with/without password'),
                'nopwd',
                'combo',
                [__('only posts without password') => 'no', __('also include posts with password') => 'also', __('only posts with password') => 'only']
            )
            ->setting(
                'listurl',
                __('Filter on a list of url:'),
                '',
                'text'
            )
            ->setting(
                'typepost',
                __('One or more types of entries (format typ/url) to be considered (empty means post/post):'),
                '',
                'text'
            )
            ->setting(
                'maxitems',
                __('Maximum number of posts:<br />(empty means all, if x-y then x posts after the first y)'),
                '8',
                'text'
            )
            ->setting(
                'orderby',
                __('List by order...'),
                'desc',
                'combo',
                [__('Ascending') => 'asc', __('Descending') => 'desc']
            )
            ->setting(
                'typlist',
                __('Format of the list'),
                'ul',
                'combo',
                [__('bulleted list') => 'ul', __('div block') => 'div', __('none') => '']
            )
            ->setting(
                'formdate',
                __('Format of the date:'),
                NXDO_FORMDATE,
                'text'
            )
            ->setting(
                'setlocal',
                __('Localization of the date format:'),
                '',
                'text'
            )
            ->setting(
                'titlemax',
                __('Maximum length of post title:'),
                '0',
                'text'
            )
            ->setting(
                'settext',
                __('Settings to extract some text (%TEXT%):'),
                NXDO_SETTEXT,
                'text'
            )
            ->setting(
                'setimage',
                __('Settings to extract images (%IMAGE%):'),
                NXDO_SETIMAGE,
                'text'
            )
            ->setting(
                'setnbcomm',
                __('Settings to display the number of comments (%NBCOMM[]%):'),
                NXDO_SETNBCOMM,
                'text'
            )
            ->setting(
                'formitem',
                __('Format of items:'),
                NXDO_FORMITEM,
                'textarea'
            )
            ->setting(
                'titlecut',
                __('Text if post title is truncated:'),
                NXDO_TITLECUT,
                'text'
            )
            ->setting(
                'noexcerpt',
                __('Text if the excerpt is empty:'),
                '',
                'text'
            )
            ->setting(
                'readmore',
                __('Text at the end of the extract to read more:'),
                '',
                'text'
            )
            ->setting(
                'intro',
                __('Text of introduction:'),
                '',
                'textarea'
            )
            ->setting(
                'conclu',
                __('Text of conclusion:'),
                '',
                'textarea'
            )
            ->addHomeOnly()
            ->addContentOnly()
            ->addClass()
            ->addOffline();
    }
}
