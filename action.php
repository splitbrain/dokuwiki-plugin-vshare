<?php

use dokuwiki\Extension\ActionPlugin;
use dokuwiki\Extension\EventHandler;
use dokuwiki\Extension\Event;

/**
 * DokuWiki Plugin vshare (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <andi@splitbrain.org>
 */
class action_plugin_vshare extends ActionPlugin
{
    /** @inheritDoc */
    public function register(EventHandler $controller)
    {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER', $this, 'addSites');
    }

    /**
     * Add the site regexes
     *
     * @param Event $event event object by reference
     * @param mixed $param optional parameter passed when event was registered
     * @return void
     */
    public function addSites(Event $event, $param)
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
