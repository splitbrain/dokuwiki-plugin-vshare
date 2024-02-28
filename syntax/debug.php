<?php

use dokuwiki\Extension\SyntaxPlugin;

/**
 * DokuWiki Plugin vshare (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <andi@splitbrain.org>
 */
class syntax_plugin_vshare_debug extends SyntaxPlugin
{
    /** @inheritDoc */
    public function getType()
    {
        return 'substition';
    }

    /** @inheritDoc */
    public function getPType()
    {
        return 'block';
    }

    /** @inheritDoc */
    public function getSort()
    {
        return 155;
    }

    /** @inheritDoc */
    public function connectTo($mode)
    {
        if ($this->getConf('debug')) {
            $this->Lexer->addSpecialPattern('~~vshare-debug~~', $mode, 'plugin_vshare_debug');
        }
    }

    /** @inheritDoc */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        return [];
    }

    /** @inheritDoc */
    public function render($mode, Doku_Renderer $renderer, $handlerdata)
    {
        if ($mode !== 'xhtml') {
            return false;
        }

        $sites = helper_plugin_vshare::loadSites();
        $syntax = new syntax_plugin_vshare_video();
        $handler = new \Doku_Handler();


        $renderer->header('vshare sites', 1, 0);

        foreach ($sites as $site => $info) {
            $renderer->header($site, 2, 0);

            if (!empty($info['vid'])) {
                $data = $syntax->handle("{{ $site>{$info['vid']} }}", DOKU_LEXER_MATCHED, 0, $handler);
                $syntax->render($mode, $renderer, $data);
            } else {
                $renderer->p_open();
                $renderer->smiley('FIXME');
                $renderer->cdata(' No sample video ID available');
                $renderer->p_close();
            }

            if (!empty($info['web'])) {
                $renderer->p_open();
                $renderer->externallink($info['web']);
                $renderer->p_close();
            } else {
                $renderer->p_open();
                $renderer->smiley('FIXME');
                $renderer->cdata(' No sample video available');
                $renderer->p_close();
            }
        }

        return true;
    }
}
