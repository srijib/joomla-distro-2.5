<?php

/**
 * @version		$Id: config.php 221 2011-06-11 17:30:33Z happy_noodle_boy $
 * @package     JCE
 * @copyright   Copyright (C) 2005 - 2009 Ryan Demmer. All rights reserved.
 * @author		Ryan Demmer
 * @license     GNU/GPL 2 - See licence.txt
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class WFFormatPluginConfig {

    public static function getConfig(&$settings) {
        $model = JModel::getInstance('editor', 'WFModel');
        $wf = WFEditor::getInstance();

        // Add format plugin to plugins list
        if (!in_array('format', $settings['plugins'])) {
            $settings['plugins'][] = 'format';
        }

        // Encoding
        $settings['entity_encoding']        = $wf->getParam('editor.entity_encoding', 'raw', 'named');
        $settings['inline_styles']          = $wf->getParam('editor.inline_styles', 1, 1, 'boolean');

        // Paragraph handling
        $settings['forced_root_block']      = $wf->getParam('editor.forced_root_block', 'p');
        
        // set as boolean if disabled
        if (is_numeric($settings['forced_root_block'])) {
            $settings['forced_root_block'] = (bool)$settings['forced_root_block'];
        }
        
        $settings['removeformat_selector']  = $wf->getParam('editor.removeformat_selector', 'span,b,strong,em,i,font,u,strike', 'span,b,strong,em,i,font,u,strike');

        $formats = array(
            'p' => 'advanced.paragraph',
            'address' => 'advanced.address',
            'pre' => 'advanced.pre',
            'h1' => 'advanced.h1',
            'h2' => 'advanced.h2',
            'h3' => 'advanced.h3',
            'h4' => 'advanced.h4',
            'h5' => 'advanced.h5',
            'h6' => 'advanced.h6',
            'div' => 'advanced.div',
            'blockquote' => 'advanced.blockquote',
            'code' => 'advanced.code',
            'samp' => 'advanced.samp',
            'span' => 'advanced.span',
            'section' => 'advanced.section',
            'article' => 'advanced.article',
            'hgroup' => 'advanced.hgroup',
            'aside' => 'advanced.aside',
            'figure' => 'advanced.figure',
            'dt' => 'advanced.dt',
            'dd' => 'advanced.dd'
        );
        
        $html5  = array('section', 'article', 'hgroup', 'aside', 'figure');
        $schema = $wf->getParam('editor.schema', 'html4'); 
        $verify = $wf->getParam('editor.verify_html', 0, 1, 'boolean');

        $tmpblocks  = $wf->getParam('editor.theme_advanced_blockformats', 'p,div,address,pre,h1,h2,h3,h4,h5,h6,code,samp,span,section,article,hgroup,aside,figure,dt,dd', 'p,address,pre,h1,h2,h3,h4,h5,h6');
        $list       = array();
        $blocks     = array();
        
        // make an array
        if (is_string($tmpblocks)) {
            $tmpblocks = explode(',', $tmpblocks);
        }

        foreach ($tmpblocks as $v) {
            $key = $formats[$v];
            
            // skip html5 blocks for html4 schema
            if ($verify && $schema == 'html4' && in_array($v, $html5)) {
                continue;
            }

            if ($key) {
                $list[$key] = $v;
            }

            $blocks[] = $v;
        }

        $selector = $settings['removeformat_selector'] == '' ? 'span,b,strong,em,i,font,u,strike' : $settings['removeformat_selector'];
        $selector = explode(',', $selector);

        // set the root block
        $rootblock = (!$settings['forced_root_block']) ? 'p' : $settings['forced_root_block'];

        if ($k = array_search($rootblock, $blocks) !== false) {
            unset($blocks[$k]);
        }

        // remove format selector
        $settings['removeformat_selector'] = implode(',', array_unique(array_merge($blocks, $selector)));

        // Format list / Remove Format
        $settings['theme_advanced_blockformats'] = json_encode($list);

        // Relative urls
        $settings['relative_urls'] = $wf->getParam('editor.relative_urls', 1, 1, 'boolean');
        if ($settings['relative_urls'] == 0) {
            $settings['remove_script_host'] = false;
        }

        // Fonts
        $settings['theme_advanced_fonts'] = $model->getEditorFonts($wf->getParam('editor.theme_advanced_fonts_add', ''), $wf->getParam('editor.theme_advanced_fonts_remove', ''));
        $settings['theme_advanced_font_sizes'] = $wf->getParam('editor.theme_advanced_font_sizes', '8pt,10pt,12pt,14pt,18pt,24pt,36pt');
        $settings['theme_advanced_default_foreground_color'] = $wf->getParam('editor.theme_advanced_default_foreground_color', '#000000');
        $settings['theme_advanced_default_background_color'] = $wf->getParam('editor.theme_advanced_default_background_color', '#FFFF00');

        // colour picker custom colours
        $settings['custom_colors'] = $wf->getParam('editor.custom_colors', '', '');

        // Styles list
        $styles = $wf->getParam('editor.theme_advanced_styles', '');
        if ($styles) {
            $settings['theme_advanced_styles'] = implode(';', explode(',', $styles));
        }
    }
}

?>
