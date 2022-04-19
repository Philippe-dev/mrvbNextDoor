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

$core->addBehavior('initWidgets', ['mrvbNextDoorBehaviors', 'initWidgets']);

require_once dirname(__FILE__) . '/_init.php';

class mrvbNextDoorBehaviors
{
    public static function initWidgets($w)
    {
        $w->create('mrvbNextDoor', __('Mrvb: other blog'), ['mrvbNextDoorPublic', 'mrvbNextDoor'], null, __('posts from other blog of the same multiblog'));

        $w->mrvbNextDoor->setting(
            'title',
            __('Title:'),
            __('In other blogs'),
            'text'
        );
        $w->mrvbNextDoor->setting(
            'CSSclass',
            __('CSS class widget:'),
            '',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'homeonly',
            __('Display on:'),
            'all',
            'combo',
            [__('All pages') => 0, __('Home page only') => 1, __('Except on home page') => 2]
        );
        $w->mrvbNextDoor->setting(
            'offline',
            __('To put off line'),
            false,
            'check'
        );
        $w->mrvbNextDoor->setting(
            'blogid',
            __('List of blogs (ID, ID...):<br />(empty means all, a [!] before to exclude)'),
            'default',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'category',
            __('List of category (URL, #ID...):<br />(empty means all, NULL means posts without category, a [#] before if ID, a [!] before to exclude)'),
            '',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'metatag',
            __('Filter on a tag (empty means all):'),
            '',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'selected',
            __('Selected posts only'),
            false,
            'check'
        );
        $w->mrvbNextDoor->setting(
            'password',
            __('Filter posts with/without password'),
            'nopwd',
            'combo',
            [__('only posts without password') => 'no', __('also include posts with password') => 'also', __('only posts with password') => 'only']
        );
        $w->mrvbNextDoor->setting(
            'listurl',
            __('Filter on a list of url:'),
            '',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'typepost',
            __('One or more types of entries (format typ/url) to be considered (empty means post/post):'),
            '',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'maxitems',
            __('Maximum number of posts:<br />(empty means all, if x-y then x posts after the first y)'),
            '8',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'orderby',
            __('List by order...'),
            'desc',
            'combo',
            [__('Ascending') => 'asc', __('Descending') => 'desc']
        );
        $w->mrvbNextDoor->setting(
            'typlist',
            __('Format of the list'),
            'ul',
            'combo',
            [__('bulleted list') => 'ul', __('div block') => 'div', __('none') => '']
        );
        $w->mrvbNextDoor->setting(
            'formdate',
            __('Format of the date:'),
            NXDO_FORMDATE,
            'text'
        );
        $w->mrvbNextDoor->setting(
            'setlocal',
            __('Localization of the date format:'),
            '',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'titlemax',
            __('Maximum length of post title:'),
            '0',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'settext',
            __('Settings to extract some text (%TEXT%):'),
            NXDO_SETTEXT,
            'text'
        );
        $w->mrvbNextDoor->setting(
            'setimage',
            __('Settings to extract images (%IMAGE%):'),
            NXDO_SETIMAGE,
            'text'
        );
        $w->mrvbNextDoor->setting(
            'setnbcomm',
            __('Settings to display the number of comments (%NBCOMM[]%):'),
            NXDO_SETNBCOMM,
            'text'
        );
        $w->mrvbNextDoor->setting(
            'formitem',
            __('Format of items:'),
            NXDO_FORMITEM,
            'textarea'
        );
        $w->mrvbNextDoor->setting(
            'titlecut',
            __('Text if post title is truncated:'),
            NXDO_TITLECUT,
            'text'
        );
        $w->mrvbNextDoor->setting(
            'noexcerpt',
            __('Text if the excerpt is empty:'),
            '',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'readmore',
            __('Text at the end of the extract to read more:'),
            '',
            'text'
        );
        $w->mrvbNextDoor->setting(
            'intro',
            __('Text of introduction:'),
            '',
            'textarea'
        );
        $w->mrvbNextDoor->setting(
            'conclu',
            __('Text of conclusion:'),
            '',
            'textarea'
        );
    }
}
