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

require_once dirname(__FILE__) . '/_init.php';

function nxdo_trunc_title($str, $max, $etc, $lnk)
{
    $a = ($lnk == 0) ? '' : '</a>';
    if (($max > 0) && (strlen($str) > $max)) {
        $res = htmlentities(mb_substr($str, 0, $max, 'UTF-8'), ENT_QUOTES, 'UTF-8') . $a . ' <span class="nxdo-etc">' . $etc . '</span>';
    } else {
        $res = htmlentities($str, ENT_QUOTES, 'UTF-8') . $a;
    }
    return $res;
}

function nxdo_trunc_text($txt, $max, $etc)
{
    $txt = strip_tags(str_replace(['<br />', '&nbsp;'], ' ', $txt));
    if ($max > 0) {
        return (strlen($txt) > $max ? substr(substr($txt, 0, $max), 0, strrpos(substr($txt, 0, $max), ' ')) . ' ' . $etc : $txt);
    } else {
        return $txt;
    }
}

function nxdo_format_link($txt, $deb, $end, $lnk, $tit, $css)
{
    $s = strlen($deb);
    $d = strpos($txt, $deb);
    if ($d !== false) {
        $e = strpos($txt, $end, $d);
        if ($e !== false) {
            $res = substr($txt, $d + $s, $e - ($d + $s));
            return str_replace($deb . $res . $end, '<a href="' . $lnk . '" ' . $css . ' ' . $tit . '>' . htmlentities($res, ENT_QUOTES, 'UTF-8') . '</a>', $txt);
        }
    }
}

function nxdo_cat_name($txt, $deb, $end, $lnk, $tit, $css)
{
    $s = strlen($deb);
    $d = strpos($txt, $deb);
    if ($d !== false) {
        $e = strpos($txt, $end, $d);
        if ($e !== false) {
            $res = substr($txt, $d + $s, $e - ($d + $s));
            if (strlen($tit) > 0) {
                $t = htmlentities($tit, ENT_QUOTES, 'UTF-8');
                if (strlen($lnk) > 0) {
                    return str_replace($deb . $res . $end, $res . '<a href="' . $lnk . '" ' . $css . '>' . $t . '</a>', $txt);
                } else {
                    if (strlen($css) > 0) {
                        return str_replace($deb . $res . $end, $res . '<span ' . $css . '>' . $t . '</span>', $txt);
                    } else {
                        return str_replace($deb . $res . $end, $res . $t, $txt);
                    }
                }
            } else {
                return str_replace($deb . $res . $end, '', $txt);
            }
        }
    }
}

function NextDoor($attr)
{
    //--- initialisation/formatage des paramètres fournis à la fonction
    $mode = isset($attr['mode']) ? $attr['mode'] : 'interne';
    $blogid = isset($attr['blogid']) ? $attr['blogid'] : ' ';
    $category = isset($attr['category']) ? $attr['category'] : '';
    $metatag = isset($attr['metatag']) ? $attr['metatag'] : '';
    $maxitems = isset($attr['maxitems']) ? $attr['maxitems'] : '8';
    $selected = isset($attr['selected']) ? $attr['selected'] : 0;
    $password = isset($attr['password']) ? $attr['password'] : '';
    $formdate = isset($attr['formdate']) ? $attr['formdate'] : NXDO_FORMDATE;
    $setlocal = isset($attr['setlocal']) ? $attr['setlocal'] : '';
    //--- formatage pour %TEXT%
    $settext = str_replace(' ', '', isset($attr['settext']) ? $attr['settext'] : NXDO_SETTEXT);
    $txt_src = 'full';
    $txt_siz = '100';
    $txt_cut = '[&hellip;]';
    if (!empty($settext)) {
        $txt_prm = explode(',', $settext);
        for ($i = 0 ; $i < count($txt_prm) ; $i++) {
            $txt_val = explode('=', $txt_prm[$i]);
            switch ($txt_val[0]) {
                case 'from': $txt_src = $txt_val[1];
                break;
                case 'length': $txt_siz = $txt_val[1];
                break;
                case 'cut': $txt_cut = $txt_val[1];
                break;
            }
        }
    }
    //--- formatage pour %IMAGE%
    $setimage = str_replace(' ', '', isset($attr['setimage']) ? $attr['setimage'] : NXDO_SETIMAGE);
    $img_lnk = 'none';
    $img_src = 'full';
    $img_deb = '1';
    $img_nbr = '1';
    $img_siz = '';
    $img_tit = '1';
    if (!empty($setimage)) {
        $img_prm = explode(',', $setimage);
        for ($i = 0 ; $i < count($img_prm) ; $i++) {
            $img_val = explode('=', $img_prm[$i]);
            switch ($img_val[0]) {
                case 'link': $img_lnk = $img_val[1];
                break;
                case 'from': $img_src = $img_val[1];
                break;
                case 'start': $img_deb = $img_val[1];
                break;
                case 'length': $img_nbr = $img_val[1];
                break;
                case 'size': $img_siz = $img_val[1];
                break;
                case 'title': $img_tit = $img_val[1];
                break;
            }
        }
    }
    //--- formatage pour le nombre de commentaires
    $setnbcomm = isset($attr['setnbcomm']) ? $attr['setnbcomm'] : NXDO_SETNBCOMM;
    $com_non = 'no comments';
    $com_one = 'one comment';
    $com_mor = '%d comments';
    if (!empty($setnbcomm)) {
        $com_prm = explode(',', $setnbcomm);
        for ($i = 0 ; $i < count($com_prm) ; $i++) {
            $com_val = explode('=', $com_prm[$i]);
            switch ($com_val[0]) {
                case 'none': $com_non = str_replace('"', '', $com_val[1]);
                break;
                case 'one': $com_one = str_replace('"', '', $com_val[1]);
                break;
                case 'more': $com_mor = str_replace('"', '', $com_val[1]);
                break;
            }
        }
    }
    $nbcomm = [__($com_non), __($com_one), __($com_mor)];
    //--- initialisation de $formitem
    $formitem = isset($attr['formitem']) ? $attr['formitem'] : NXDO_FORMITEM;
    if (empty($formitem)) {
        $formitem = '&nbsp;';
    }
    //---
    $titlemax = isset($attr['titlemax']) ? $attr['titlemax'] : '0';
    $titlecut = isset($attr['titlecut']) ? $attr['titlecut'] : NXDO_TITLECUT;
    $noexcerpt = isset($attr['noexcerpt']) ? $attr['noexcerpt'] : '';
    $readmore = isset($attr['readmore']) ? $attr['readmore'] : '';
    $nolist = isset($attr['nolist']) ? $attr['nolist'] : 0;
    $typlist = isset($attr['typlist']) ? $attr['typlist'] : 'ul';
    $listepuce = ($typlist === 'ul');
    //--- $typepost
    $typepost = str_replace(' ', '', isset($attr['typepost']) ? $attr['typepost'] : '');
    if (empty($typepost)) {
        $typepost = 'post/post';
    }
    $tmp = explode(',', str_replace(' ', '', $typepost));
    for ($i = 0 ; $i < count($tmp) ; $i++) {
        $tmp2 = explode('/', $tmp[$i]);
        if (count($tmp2) < 2) {
            $p_typ[$i] = $tmp2[0];
            $p_url[$i] = $tmp2[0];
        } else {
            $p_typ[$i] = $tmp2[0];
            $p_url[$i] = $tmp2[1];
        }
    }
    $orderby = strtoupper(str_replace(' ', '', isset($attr['orderby']) ? $attr['orderby'] : 'DESC'));
    if (strpos(' ASC DESC ', $orderby) === false) {
        $orderby = 'DESC';
    }
    //--- $listurl
    $listurl = str_replace(' ', '', isset($attr['listurl']) ? $attr['listurl'] : '');
    $u_lst = explode(',', $listurl);
    //--- $blogid
    $tmp = str_replace(' ', '', $blogid);
    $filter = '';
    $blogOK = '';
    $blogKO = '';
    if (!empty($tmp)) {
        $blogs = explode(',', $tmp);
        $nbblogs = count($blogs);
        for ($i = 0 ; $i < $nbblogs ; $i++) {
            $blog = $blogs[$i];
            if (substr($blog, 0, 1) == '!') {
                $blog = substr($blog, 1);
                $blogKO .= '\'' . $blog . '\',';
            } else {
                $blogOK .= '\'' . $blog . '\',';
            }
        }
        $blogKO = trim($blogKO, ',');
        if (strlen($blogKO) > 2) {
            $filter .= ' AND P.blog_id NOT IN (' . $blogKO . ')';
        }
        $blogOK = trim($blogOK, ',');
        if (strlen($blogOK) > 2) {
            $filter .= ' AND P.blog_id IN (' . trim($blogOK, ',') . ')';
        }
    } else {
        $nbblogs = 0;
    }
    //--- $maxitems
    $maxitems = str_replace(' ', '', $maxitems);
    $tmp = strpos($maxitems, '-');
    if ($tmp === false) {
        if (empty($maxitems)) {
            $sizlist = -1;
        } else {
            $sizlist = intval($maxitems);
        }
        $deblist = 0;
    } else {
        $siz = substr($maxitems, 0, $tmp);
        if (empty($siz)) {
            $sizlist = -1;
        } else {
            $sizlist = intval($siz);
        }
        $deblist = intval(substr($maxitems, $tmp + 1));
    }
    //--- $setlocal
    $tmp = str_replace(' ', '', $setlocal);
    $tmp = str_replace('"', '', $tmp);
    $tmp = str_replace('\'', '', $tmp);
    if (!empty($tmp)) {
        $locale = explode(',', $tmp);
    } else {
        $locale = '';
    }
    //--- requête SQL
    $fields = 'P.post_id, P.blog_id, P.post_url, P.post_type, P.post_title, P.post_dt, P.post_lang, P.user_id, P.post_excerpt_xhtml, P.post_content_xhtml, P.nb_comment, P.nb_trackback, P.post_password, P.post_meta, ';
    $fields .= 'B.blog_url, B.blog_name, C.cat_id, C.cat_url, C.cat_title, S_UH.setting_value AS url_handlers';
    $query = ' SELECT ' . $fields . ' FROM ' . DC_DBPREFIX . 'post AS P';
    $query .= ' LEFT JOIN ' . DC_DBPREFIX . 'blog AS B ON B.blog_id = P.blog_id';
    $query .= ' LEFT JOIN ' . DC_DBPREFIX . 'setting AS S_UH ON (S_UH.blog_id = P.blog_id AND S_UH.setting_ns = \'myurlhandlers\' AND S_UH.setting_id = \'url_handlers\')';
    $query .= ' LEFT JOIN ' . DC_DBPREFIX . 'category AS C ON C.cat_id = P.cat_id ';
    //--- SQL : filtre sur catégories
    $catOK = '';
    $catKO = '';
    $tmp = str_replace(' ', '', $category);
    if (!empty($tmp)) {
        $cats = explode(',', $tmp);
        $nbcats = count($cats);
        for ($i = 0 ; $i < $nbcats ; $i++) {
            $cat = $cats[$i];
            if (substr($cat, 0, 1) == '!') {
                if ($cat == '!NULL') {
                    $cat = 'P.cat_id IS NOT NULL';
                } else {
                    if (substr($cat, 1, 1) == '#') {
                        if (is_numeric(substr($cat, 2))) {
                            $cat = 'C.cat_id != \'' . substr($cat, 2) . '\'';
                        } else {
                            $cat = 'C.cat_url != \'' . substr($cat, 2) . '\'';
                        }
                    } else {
                        $cat = 'C.cat_url != \'' . substr($cat, 1) . '\'';
                    }
                }
                if (empty($catKO)) {
                    $catKO .= $cat;
                } else {
                    $catKO .= ' AND ' . $cat;
                }
            } else {
                if ($cat == 'NULL') {
                    $cat = 'P.cat_id IS NULL';
                } else {
                    if (substr($cat, 0, 1) == '#') {
                        if (is_numeric(substr($cat, 1))) {
                            $cat = 'C.cat_id = \'' . substr($cat, 1) . '\'';
                        } else {
                            $cat = 'C.cat_url = \'' . substr($cat, 1) . '\'';
                        }
                    } else {
                        $cat = 'C.cat_url = \'' . $cat . '\'';
                    }
                }
                if (empty($catOK)) {
                    $catOK .= $cat;
                } else {
                    $catOK .= ' OR ' . $cat;
                }
            }
        }
        if (!empty($catKO)) {
            $filter .= ' AND (' . $catKO . ')';
        } else {
            ;
            if (!empty($catOK)) {
                $filter .= ' AND (' . $catOK . ')';
            };
        };
    }
    //--- SQL : filtre sur mot-clé
    if (!empty($metatag)) {
        $query .= ' LEFT JOIN ' . DC_DBPREFIX . 'meta AS M ON M.post_id = P.post_id AND M.meta_type = \'tag\' ';
        $filter .= ' AND M.meta_id = \'' . $metatag . '\'';
    }
    //--- SQL : fin de la requête
    if ($selected) {
        $filter .= ' AND P.post_selected = TRUE';
    }
    if ($password <> 'also') {
        if ($password == 'only') {
            $filter .= ' AND P.post_password IS NOT NULL';
        } else {
            $filter .= ' AND P.post_password IS NULL';
        }
    }
    if (!empty($listurl)) {
        $filter .= ' AND P.post_url IN (\'' . implode("','", $u_lst) . '\')';
    }
    $query .= ' WHERE P.post_status = 1 AND P.post_type IN (\'' . implode("','", $p_typ) . '\')' . $filter . ' ORDER BY P.post_dt ' . $orderby;
    if ($sizlist >= 0) {
        $query .= ' LIMIT ' . ($sizlist + $deblist);
    }
    //--- SQL : exécution de la requête (recherche des billets)
    if ($mode == 'externe') {
        $res_post = dcCore::app()->con->select($query);
        $modifurl = isset($attr['modifurl']) ? (bool)$attr['modifurl'] : (bool)false;
    } else {
        $res_post = dcCore::app()->con->select($query);
        $modifurl = (dcCore::app()->plugins->moduleExists('myUrlHandlers')) && (!isset(dcCore::app()->plugins->getDisabledModules['myUrlHandlers']));
    }
    //--- boucle sur les enregistrements renvoyés par la requête SQL
    $p = '';
    if ($res_post->count() > 0) {
        if (($nolist == 0) && ($listepuce) && ($sizlist <> 0)) {
            $p = '<ul class="nxdo-list">' . "\n";
        }
        $rng = 0;
        while ($res_post->fetch()) {
            $rng = $rng + 1;
            if ($rng > $deblist) {
                $url_handlers = @unserialize($res_post->f('url_handlers'));
                //--- type/url : "post/post", "page/pages", "related/static" (etc...)
                $j = 0;
                for ($i = 0 ; $i < count($p_typ) ; $i++) {
                    if ($p_typ[$i] == $res_post->f('post_type')) {
                        $j = $i;
                    }
                }
                if ($modifurl && (is_array($url_handlers) && (!empty($url_handlers[$p_typ[$j]])))) {
                    $post_prefix = $url_handlers[$p_typ[$j]];
                } else {
                    $post_prefix = $p_url[$j];
                }
                //--- url catégorie
                if ($modifurl && (is_array($url_handlers))) {
                    $cat_prefix = $url_handlers['category'];
                } else {
                    $cat_prefix = 'category';
                }
                //---
                $blog_id = $res_post->f('blog_id');
                $blog_url = htmlentities($res_post->f('blog_url'), ENT_QUOTES, 'UTF-8');
                $post_id = $res_post->f('post_id');
                $post_lang = $res_post->f('post_lang');
                $user_id = $res_post->f('user_id');
                $post_url = $blog_url . $post_prefix . '/' . htmlentities($res_post->f('post_url'), ENT_QUOTES, 'UTF-8');
                $tmp = parse_url($blog_url);
                $image_url = $tmp['scheme'] . '://'. $tmp['host'];
                $blog_name = htmlentities($res_post->f('blog_name'), ENT_QUOTES, 'UTF-8');
                setlocale(LC_ALL, $locale);
                $post_date = @strftime(htmlentities($formdate, ENT_QUOTES, 'UTF-8'), strtotime($res_post->f('post_dt')));
                $post_title = $res_post->f('post_title');
                $info_title = ' title="' . htmlentities($post_title, ENT_QUOTES, 'UTF-8') . '"';
                if ($res_post->f('cat_id') <> null) {
                    $cat_title = $res_post->f('cat_title');
                    $cat_url = $blog_url . $cat_prefix . '/' . $res_post->f('cat_url');
                    $cat_id = $res_post->f('cat_id');
                    $cat_css = ' cat' . $cat_id;
                } else {
                    $cat_title = '';
                    $cat_url = '';
                    $cat_id = '0';
                    $cat_css = ' nocat';
                }
                $nb_comment = $res_post->f('nb_comment');
                switch ($nb_comment) {
                    case '0': $txt_nbcomm = $nbcomm[0];
                    break;
                    case '1': $txt_nbcomm = $nbcomm[1];
                    break;
                    default: $txt_nbcomm = sprintf($nbcomm[2], $nb_comment);
                    break;
                }
                $nb_trackback = $res_post->f('nb_trackback');
                if ($res_post->f('post_password') <> null) {
                    $class_pwd = ' nxdo-pwd';
                } else {
                    $class_pwd = '';
                };
                $excerpt = $res_post->f('post_excerpt_xhtml');
                dcCore::app()->content = $res_post->f('post_content_xhtml');
                $tmp = $formitem;

                //--- remplacement des variables de formatage présentes dans $formitem par les données issues de $res_post->f(xxx)
                if (strpos($formitem, '$blog_id') !== false) {
                    $tmp = str_replace('$blog_id', $blog_id, $tmp);
                }
                if (strpos($formitem, '$blog_url') !== false) {
                    $tmp = str_replace('$blog_url', $blog_url, $tmp);
                }
                if (strpos($formitem, '$blog_name') !== false) {
                    $tmp = str_replace('$blog_name', $blog_name, $tmp);
                }
                //---
                if (strpos($formitem, '$post_id') !== false) {
                    $tmp = str_replace('$post_id', $post_id, $tmp);
                }
                if (strpos($formitem, '$user_id') !== false) {
                    $tmp = str_replace('$user_id', $user_id, $tmp);
                }
                if (strpos($formitem, '$post_url') !== false) {
                    $tmp = str_replace('$post_url', $post_url, $tmp);
                }
                if (strpos($formitem, '$post_title') !== false) {
                    $tmp = str_replace('$post_title', $post_title, $tmp);
                }
                if (strpos($formitem, '$post_trunc_title') !== false) {
                    $tmp = str_replace('$post_trunc_title', nxdo_trunc_title($post_title, $titlemax, $titlecut, false), $tmp);
                }
                if (strpos($formitem, '$post_date') !== false) {
                    $tmp = str_replace('$post_date', $post_date, $tmp);
                }
                if (strpos($formitem, '$post_lang') !== false) {
                    $tmp = str_replace('$post_lang', $post_lang, $tmp);
                }
                if (strpos($formitem, '$nb_comment') !== false) {
                    $tmp = str_replace('$nb_comment', $nb_comment, $tmp);
                }
                if (strpos($formitem, '$txt_nbcomm') !== false) {
                    $tmp = str_replace('$txt_nbcomm', $txt_nbcomm, $tmp);
                }
                if (strpos($formitem, '$nb_trackback') !== false) {
                    $tmp = str_replace('$nb_trackback', $nb_trackback, $tmp);
                }
                //---
                if (strpos($formitem, '$cat_url') !== false) {
                    $tmp = str_replace('$cat_url', $cat_url, $tmp);
                }
                if (strpos($formitem, '$cat_title') !== false) {
                    $tmp = str_replace('$cat_title', $cat_title, $tmp);
                }
                if (strpos($formitem, '$cat_id') !== false) {
                    $tmp = str_replace('$cat_id', $cat_id, $tmp);
                }

                //--- remplacement des codes de formatage présents dans $formitem par les données issues de $res_post->f(xxx)
                if (strpos($formitem, '%date%') !== false) {
                    $tmp = str_replace('%date%', $post_date, $tmp);
                }
                if (strpos($formitem, '%DATE%') !== false) {
                    $tmp = str_replace('%DATE%', '<span class="nxdo-date">' . $post_date . '</span>', $tmp);
                }
                if (strpos($formitem, '%DATE:BLOG%') !== false) {
                    $tmp = str_replace('%DATE:BLOG%', '<a class="nxdo-datelink" href="' . $blog_url . '" title="' . $blog_name . '">' . $post_date . '</a>', $tmp);
                }
                if (strpos($formitem, '%DATE:POST%') !== false) {
                    $tmp = str_replace('%DATE:POST%', '<a class="nxdo-datelink" href="' . $post_url . '"' . $info_title . '>' . $post_date . '</a>', $tmp);
                }
                //---
                if (strpos($formitem, '%blog%') !== false) {
                    $tmp = str_replace('%blog%', $blog_name, $tmp);
                }
                if (strpos($formitem, '%BLOG%') !== false) {
                    $tmp = str_replace('%BLOG%', '<span class="nxdo-blog">' . $blog_name . '</span>', $tmp);
                }
                if (strpos($formitem, '%BLOG:BLOG%') !== false) {
                    $tmp = str_replace('%BLOG:BLOG%', '<a class="nxdo-bloglink" href="' . $blog_url . '">' . $blog_name . '</a>', $tmp);
                }
                if (strpos($formitem, '%BLOG:POST%') !== false) {
                    $tmp = str_replace('%BLOG:POST%', '<a class="nxdo-bloglink" href="' . $post_url . '"' . $info_title . '>' . $blog_name . '</a>', $tmp);
                }
                //---
                if (strpos($formitem, '%title%') !== false) {
                    $tmp = str_replace('%title%', nxdo_trunc_title($post_title, $titlemax, $titlecut, false), $tmp);
                }
                if (strpos($formitem, '%TITLE%') !== false) {
                    $tmp = str_replace('%TITLE%', '<span class="nxdo-post" ' . $info_title . '>' . nxdo_trunc_title($post_title, $titlemax, $titlecut, false) . '</span>', $tmp);
                }
                if (strpos($formitem, '%TITLE:POST%') !== false) {
                    $tmp = str_replace('%TITLE:POST%', '<a class="nxdo-postlink" href="' . $post_url . '">' . nxdo_trunc_title($post_title, $titlemax, $titlecut, true), $tmp);
                }
                if (strpos($formitem, '%TITLE:BLOG%') !== false) {
                    $tmp = str_replace('%TITLE:BLOG%', '<a class="nxdo-postlink" href="' . $blog_url . '" title="' . $blog_name . '">' . nxdo_trunc_title($post_title, $titlemax, $titlecut, true), $tmp);
                }
                if (strpos($formitem, '%nbcomm%') !== false) {
                    $tmp = str_replace('%nbcomm%', $nb_comment, $tmp);
                }
                if (strpos($formitem, '%NBCOMM%') !== false) {
                    $tmp = str_replace('%NBCOMM%', '<span class="nxdo-comments">' . $nb_comment . '</span>', $tmp);
                }
                if (strpos($formitem, '%NBCOMM:COMM%') !== false) {
                    $tmp = str_replace('%NBCOMM:COMM%', '<a class="nxdo-comments" href="' . $post_url . '#comments"' . $info_title . '>' . $nb_comment . '</a>', $tmp);
                }
                if (strpos($formitem, '%nbcomm[]%') !== false) {
                    $tmp = str_replace('%nbcomm[]%', $txt_nbcomm, $tmp);
                }
                if (strpos($formitem, '%NBCOMM[]%') !== false) {
                    $tmp = str_replace('%NBCOMM[]%', '<span class="nxdo-comments">' . $txt_nbcomm . '</span>', $tmp);
                }
                if (strpos($formitem, '%NBCOMM:COMM[]%') !== false) {
                    $tmp = str_replace('%NBCOMM:COMM[]%', '<a class="nxdo-comments" href="' . $post_url . '#comments"' . $info_title . '>' . $txt_nbcomm . '</a>', $tmp);
                }
                if (strpos($formitem, '%nbping%') !== false) {
                    $tmp = str_replace('%nbping%', $nb_trackback, $tmp);
                }
                if (strpos($formitem, '%NBPING%') !== false) {
                    $tmp = str_replace('%NBPING%', '<span class="nxdo-pings">' . $nb_trackback . '</span>', $tmp);
                }
                if (strpos($formitem, '%NBPING:PING%') !== false) {
                    $tmp = str_replace('%NBPING:PING%', '<a class="nxdo-pings" href="' . $post_url . '#pings"' . $info_title . '>' . $nb_trackback . '</a>', $tmp);
                }
                //--- boucle de remplacement des codes de formatage commençant par "%CATNAME" et par "%catname"
                while (strpos($tmp, '%catname[') !== false) {
                    $tmp = nxdo_cat_name($tmp, '%catname[', ']%', '', $cat_title, '');
                }
                while (strpos($tmp, '%CATNAME[') !== false) {
                    $tmp = nxdo_cat_name($tmp, '%CATNAME[', ']%', '', $cat_title, 'class="nxdo-cat"');
                }
                while (strpos($tmp, '%CATNAME:CAT[') !== false) {
                    $tmp = nxdo_cat_name($tmp, '%CATNAME:CAT[', ']%', $cat_url, $cat_title, 'class="nxdo-cat"');
                }
                //--- boucle de remplacement des codes de formatage commençant par "%LINK:" et par "%link:"
                while (strpos($tmp, '%LINK:BLOG[') !== false) {
                    $tmp = nxdo_format_link($tmp, '%LINK:BLOG[', ']%', $blog_url, 'title="' . $blog_name . '"', 'class="nxdo-blog"');
                }
                while (strpos($tmp, '%LINK:POST[') !== false) {
                    $tmp = nxdo_format_link($tmp, '%LINK:POST[', ']%', $post_url, $info_title, 'class="nxdo-postlink"');
                }
                while (strpos($tmp, '%LINK:COMM[') !== false) {
                    $tmp = nxdo_format_link($tmp, '%LINK:COMM[', ']%', $post_url . '#comments', $info_title, 'class="nxdo-comments"');
                }
                while (strpos($tmp, '%LINK:PING[') !== false) {
                    $tmp = nxdo_format_link($tmp, '%LINK:PING[', ']%', $post_url . '#pings', $info_title, 'class="nxdo-ping"');
                }
                while (strpos($tmp, '%link:BLOG[') !== false) {
                    $tmp = nxdo_format_link($tmp, '%link:BLOG[', ']%', $blog_url, '', 'class="nxdo-blog"');
                }
                while (strpos($tmp, '%link:POST[') !== false) {
                    $tmp = nxdo_format_link($tmp, '%link:POST[', ']%', $post_url, '', 'class="nxdo-postlink"');
                }
                while (strpos($tmp, '%link:COMM[') !== false) {
                    $tmp = nxdo_format_link($tmp, '%link:COMM[', ']%', $post_url . '#comments', '', 'class="nxdo-comments"');
                }
                while (strpos($tmp, '%link:PING[') !== false) {
                    $tmp = nxdo_format_link($tmp, '%link:PING[', ']%', $post_url . '#pings', '', 'class="nxdo-ping"');
                }
                //--- extrait et/ou contenu (%E%, %C%, %EC%)
                if (strpos($formitem, '%E%') !== false) {
                    $txt = $excerpt;
                    if (!empty($txt)) {
                        if (!empty($readmore)) {
                            $txt .= '<p class="nxdo-readmore"><a href="' . $post_url . '">' . $readmore . '</a></p>';
                        }
                    } else {
                        $txt = $noexcerpt;
                    }
                    $tmp = str_replace('%E%', '<div class="nxdo-excerpt">' . $txt . '</div>', $tmp);
                }
                if (strpos($formitem, '%C%') !== false) {
                    $tmp = str_replace('%C%', '<div class="nxdo-content">' . dcCore::app()->content . '</div>', $tmp);
                }
                if (strpos($formitem, '%EC%') !== false) {
                    $txt = $excerpt;
                    if (!empty($txt)) {
                        if (!empty($readmore)) {
                            $txt .= '<p class="nxdo-readmore"><a href="' . $post_url . '">' . $readmore . '</a></p>';
                        }
                        $tmp = str_replace('%EC%', '<div class="nxdo-excerpt">' . $txt . '</div>', $tmp);
                    } else {
                        $tmp = str_replace('%EC%', '<div class="nxdo-content">' . dcCore::app()->content . '</div>', $tmp);
                    }
                }
                //--- %TEXT%
                if (strpos($formitem, '%TEXT%') !== false) {
                    $txt = '';
                    switch ($txt_src) {
                        case 'excerpt': $txt = $excerpt;
                        break;
                        case 'content': $txt = dcCore::app()->content;
                        break;
                        case 'full': $txt = $excerpt;
                        $txt .= ' ' . dcCore::app()->content;
                        break;
                        case 'first': (empty($excerpt) ? $txt = dcCore::app()->content : $txt = $excerpt);
                        break;
                    }
                    $tmp = str_replace('%TEXT%', nxdo_trunc_text($txt, $txt_siz, $txt_cut), $tmp);
                }
                //--- %IMAGE%
                if (strpos($formitem, '%IMAGE%') !== false) {
                    $txt = '';
                    switch ($img_src) {
                        case 'excerpt': $txt = $excerpt;
                        break;
                        case 'content': $txt = dcCore::app()->content;
                        break;
                        case 'full': $txt = $excerpt;
                        $txt .= dcCore::app()->content;
                        break;
                        case 'first': (empty($excerpt) ? $txt = dcCore::app()->content : $txt = $excerpt);
                        break;
                    }
                    if ((preg_match_all('!<img(.*)/>!Ui', $txt, $res)) && ($img_deb > 0)) {
                        $j = $img_nbr;
                        if (($j == 0) || ($j > count($res[1]))) {
                            $j = count($res[1]);
                        }
                        $i_all = '';
                        for ($i = ($img_deb - 1) ; $i < $j ; $i++) {
                            $i_one = $res[1][$i];
                            preg_match('!src="(.*)"!Ui', $i_one, $i_url);
                            $i_src = $i_url[1];
                            $i_old = pathinfo($i_src, PATHINFO_FILENAME);
                            $i_new = $i_old;
                            $i_nom = str_replace(['.', '_sq', '_t', '_s', '_m'], '', $i_old);
                            switch ($img_siz) {
                                case 'sq': $i_new = '.' . $i_nom . '_sq';
                                break;
                                case 't': $i_new = '.' . $i_nom . '_t';
                                break;
                                case 's': $i_new = '.' . $i_nom . '_s';
                                break;
                                case 'm': $i_new = '.' . $i_nom . '_m';
                                break;
                                case 'o': $i_new = $i_nom;
                                break;
                            }
                            if (strpos($i_src, 'http') === false) {
                                $i_one = $image_url . $i_src;
                            } else {
                                $i_one = $i_src;
                            }
                            if ($i_new != $i_old) {
                                $i_one = str_replace($i_old, $i_new, $i_one);
                            }
                            $i_one = '<img class="nxdo-img" alt="' . $i_new . '" src="' . $i_one . '" />';
                            switch ($img_lnk) {
                                case 'none': $i_all .= $i_one;
                                break;
                                case 'entry':
                                    $i_all .= '<a class="nxdo-postlink" href="' . $post_url . '"';
                                    if ($img_tit <> '1') {
                                        $i_all .= ' title="' . __('Open the post') . '">';
                                    } else {
                                        $i_all .= $info_title . '>';
                                    };
                                    $i_all .= $i_one . '</a>';
                                    break;
                            }
                        }
                        $tmp = str_replace('%IMAGE%', $i_all, $tmp);
                    } else {
                        $tmp = str_replace('%IMAGE%', '', $tmp);
                    }
                }
                //--- type de liste
                if ($nolist == 0) {
                    // $blog_id = $res_post->f('blog_id');
                    switch ($typlist) {
                        case 'ul':
                            $p .= '<li class="nxdo-item blog-' . $blog_id . $cat_css . $class_pwd . '">';
                            $p .= $tmp;
                            $p .= '</li>';
                            break;
                        case 'div':
                            $p .= '<div class="nxdo-item blog-' . $blog_id . $cat_css . $class_pwd . '">';
                            $p .= $tmp;
                            $p .= '</div>';
                            break;
                        case '':
                            $p .= $tmp;
                            break;
                    }
                } else {
                    $p .= $tmp;
                }
                $p .= "\n";
            }
        }
        if (($nolist == 0) && ($sizlist <> 0)) {
            if ($listepuce) {
                $p .= '</ul>';
            }
        };
    }
    return $p;
}
