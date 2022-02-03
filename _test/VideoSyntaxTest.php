<?php

namespace dokuwiki\plugin\vshare\test;

use DokuWikiTest;

/**
 * syntax handling tests for the vshare plugin
 *
 * @group plugin_vshare
 * @group plugins
 */
class VideoSyntaxTest extends DokuWikiTest
{

    /**
     * @return array[]
     * @see testParseSize
     */
    public function provideParseSize()
    {
        return [
            ['', 425, 239],
            ['small', 255, 143],
            ['Small', 255, 143],
            ['178x123', 178, 123],
            ['178X123', 178, 123],
            ['small&medium', 255, 143, ['medium' => '']],
            ['small&autoplay=false', 255, 143, ['autoplay' => 'false']],
            ['178x123&autoplay=false', 178, 123, ['autoplay' => 'false']],
            ['autoplay=false', 425, 239, ['autoplay' => 'false']],
        ];
    }

    /**
     * @dataProvider provideParseSize
     * @param string $input
     * @param int $ewidth
     * @param int $eheight
     * @param array $eparams
     */
    public function testParseSize($input, $ewidth, $eheight, $eparams = [])
    {
        $syntax = new \syntax_plugin_vshare_video();
        parse_str($input, $params);
        list($width, $height) = $syntax->parseSize($params);

        $this->assertEquals($ewidth, $width, 'width');
        $this->assertEquals($eheight, $height, 'height');
        $this->assertEquals($eparams, $eparams, 'height');
    }

    /**
     * @see testHandle
     */
    public function provideHandle()
    {
        return [
            [
                '{{youtube>L-WM8YxwqEU}}',
                [
                    'site' => 'youtube',
                    'domain' => 'www.youtube-nocookie.com',
                    'video' => 'L-WM8YxwqEU',
                    'url' => '//www.youtube-nocookie.com/embed/L-WM8YxwqEU?',
                    'align' => 'none',
                    'width' => 425,
                    'height' => 239,
                    'title' => '',
                ],
            ],
            [
                '{{youtube>L-WM8YxwqEU?small&start=30&end=45|A random segment of 15 seconds}}',
                [
                    'site' => 'youtube',
                    'domain' => 'www.youtube-nocookie.com',
                    'video' => 'L-WM8YxwqEU',
                    'url' => '//www.youtube-nocookie.com/embed/L-WM8YxwqEU?start=30&end=45',
                    'align' => 'none',
                    'width' => 255,
                    'height' => 143,
                    'title' => 'A random segment of 15 seconds',
                ],
            ],
            // FIXME add more tests
        ];
    }

    /**
     * @dataProvider provideHandle
     * @param string $input
     * @param array $expect
     */
    public function testHandle($input, $expect)
    {
        $syntax = new \syntax_plugin_vshare_video();
        $result = $syntax->handle($input, DOKU_LEXER_MATCHED, 0, new \Doku_Handler());
        $this->assertEquals($expect, $result);
    }
}
