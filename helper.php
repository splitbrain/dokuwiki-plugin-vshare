<?php

use dokuwiki\Extension\Plugin;

/**
 * DokuWiki Plugin vshare (Helper Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <andi@splitbrain.org>
 */
class helper_plugin_vshare extends Plugin
{
    /**
     * Loads the configures sites and their data
     *
     * @return array
     */
    public static function loadSites()
    {
        return parse_ini_file(__DIR__ . '/sites.ini', true, INI_SCANNER_RAW);
    }
}
