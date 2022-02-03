<?php

namespace dokuwiki\plugin\vshare\test;

use DokuWikiTest;

/**
 * site configuration tests for the vshare plugin
 *
 * @group plugin_vshare
 * @group plugins
 */
class SitesTest extends DokuWikiTest
{
    /**
     * @see testPlaceholder
     * @see testRegEx
     */
    public function provideSites()
    {
        $sites = \helper_plugin_vshare::loadSites();
        foreach ($sites as $site => $data) {
            yield [$site, $data];
        }
    }

    /**
     * @dataProvider provideSites
     * @param string $site
     * @param string[] $data
     */
    public function testPlaceholder($site, $data)
    {
        $this->assertArrayHasKey('url', $data, $site);
        $this->assertStringContainsString('@VIDEO@', $data['url'], $site);
    }

    /**
     * @dataProvider provideSites
     * @param string $site
     * @param string[] $data
     */
    public function testRegEx($site, $data)
    {
        if (empty($data['web']) || empty($data['vid'])) {
            $this->markTestSkipped("$site has no sample data configured");
        }
        if (empty($data['rex'])) {
            $this->markTestSkipped("$site has no regular expression");
        }

        // URL to use
        $url = empty($data['emb']) ? $data['web'] : $data['emb'];

        $this->assertSame(
            1,
            preg_match('!' . $data['rex'] . '!i', $url, $match),
            "$site regex did not match web/emb url"
        );
        $this->assertEquals($data['vid'], $match[1], "$site regex did not return vid");
    }
}
