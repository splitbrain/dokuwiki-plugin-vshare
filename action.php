<?php

/**
 * DokuWiki Plugin vshare (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <andi@splitbrain.org>
 */
class action_plugin_vshare extends \dokuwiki\Extension\ActionPlugin
{

    /** @inheritDoc */
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER', $this, 'addSites');

    }

    /**
     * Add the site regexes
     *
     * @param Doku_Event $event event object by reference
     * @param mixed $param optional parameter passed when event was registered
     * @return void
     */
    public function addSites(Doku_Event $event, $param)
    {
        global $JSINFO;

        $sites = parse_ini_file(__DIR__ . '/sites.ini', true, INI_SCANNER_RAW);
        $js = [];

        foreach ($sites as $site => $data) {
            if (empty($data['rex'])) continue;
            $js[$site] = $data['rex'];
        }

        $JSINFO['plugins']['vshare'] = $js;
    }

}

