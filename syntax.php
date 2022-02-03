<?php

/**
 * Easily embed videos from various Video Sharing sites
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */
class syntax_plugin_vshare extends DokuWiki_Syntax_Plugin
{
    protected $sites;

    protected $sizes = [
        'small' => [255, 143],
        'medium' => [425, 239],
        'large' => [520, 293],
        'full' => ['100%', '100%'],
        'half' => ['100%', '100%'],
    ];

    protected $alignments = [
        0 => 'none',
        1 => 'right',
        2 => 'left',
        3 => 'center',
    ];

    /**
     * Constructor.
     * Intitalizes the supported video sites
     */
    public function __construct()
    {
        $this->sites = confToHash(dirname(__FILE__) . '/sites.conf');
    }

    /** @inheritdoc */
    public function getType()
    {
        return 'substition';
    }

    /** @inheritdoc */
    public function getPType()
    {
        return 'block';
    }

    /** @inheritdoc */
    public function getSort()
    {
        return 159;
    }

    /** @inheritdoc */
    public function connectTo($mode)
    {
        $pattern = join('|', array_keys($this->sites));
        $this->Lexer->addSpecialPattern('\{\{\s?(?:' . $pattern . ')>[^}]*\}\}', $mode, 'plugin_vshare');
    }

    /** @inheritdoc */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        $command = substr($match, 2, -2);

        // title
        list($command, $title) = array_pad(explode('|', $command), 2, '');
        $title = trim($title);

        // alignment
        $align = 0;
        if (substr($command, 0, 1) == ' ') $align += 1;
        if (substr($command, -1) == ' ') $align += 2;
        $command = trim($command);

        // get site and video
        list($site, $vid) = explode('>', $command);
        if (!$this->sites[$site]) return null; // unknown site
        if (!$vid) return null; // no video!?

        // what size?
        list($vid, $pstr) = array_pad(explode('?', $vid, 2), 2, '');
        parse_str($pstr, $userparams);
        list($width, $height) = $this->parseSize($userparams);

        // get URL
        $url = $this->insertPlaceholders($this->sites[$site], $vid, $width, $height);
        list($url, $urlpstr) = array_pad(explode('?', $url, 2), 2, '');
        parse_str($urlpstr, $urlparams);

        // merge parameters
        $params = array_merge($urlparams, $userparams);
        $url = $url . '?' . buildURLparams($params, '&');

        return array(
            'site' => $site,
            'video' => $vid,
            'url' => $url,
            'align' => $this->alignments[$align],
            'width' => $width,
            'height' => $height,
            'title' => $title,
        );
    }

    /** @inheritdoc */
    public function render($mode, Doku_Renderer $R, $data)
    {
        if ($mode != 'xhtml') return false;
        if (is_null($data)) return false;

        if ($data['title']) $title = ' title="' . hsc($data['title']) . '"';

        if (is_a($R, 'renderer_plugin_dw2pdf')) {
            // Output for PDF renderer
            $R->doc .= '<div class="vshare__' . $data['align'] . '"
                             width="' . $data['width'] . '"
                             height="' . $data['height'] . '">';

            $R->doc .= '<a href="' . $data['url'] . '" class="vshare">';
            $R->doc .= '<img src="' . DOKU_BASE . 'lib/plugins/vshare/video.png" />';
            $R->doc .= '</a>';

            $R->doc .= '<br />';

            $R->doc .= '<a href="' . $data['url'] . '" class="vshare">';
            $R->doc .= ($data['title'] ? hsc($data['title']) : 'Video');
            $R->doc .= '</a>';

            $R->doc .= '</div>';
        } else {
            // embed iframe
            $R->doc .= '<iframe ';
            $R->doc .= buildAttributes(array(
                'src' => $data['url'],
                'height' => $data['height'],
                'width' => $data['width'],
                'class' => 'vshare__' . $data['align'],
                'allowfullscreen' => '',
                'frameborder' => 0,
                'scrolling' => 'no',
            ));
            $R->doc .= '>' . hsc($data['title']) . '</iframe>';
        }
    }

    /**
     * Fill the placeholders in the given URL
     *
     * @param string $url
     * @param string $vid
     * @param int|string $width
     * @param int|string $height
     * @return string
     */
    public function insertPlaceholders($url, $vid, $width, $height)
    {
        global $INPUT;
        $url = str_replace('@VIDEO@', rawurlencode($vid), $url);
        $url = str_replace('@DOMAIN@', rawurlencode($INPUT->str('HTTP_HOST')), $url);
        $url = str_replace('@WIDTH@', $width, $url);
        $url = str_replace('@HEIGHT@', $height, $url);

        return $url;
    }

    /**
     * Extract the wanted size from the parameter list
     *
     * @param array $params
     * @return int[]
     */
    public function parseSize(&$params)
    {
        $known = join('|', array_keys($this->sizes));

        foreach ($params as $key => $value) {
            if (preg_match("/^((\d+)x(\d+))|($known)\$/i", $key, $m)) {
                unset($params[$key]);
                if (isset($m[4])) {
                    return $this->sizes[strtolower($m[4])];
                } else {
                    return [$m[2], $m[3]];
                }
            }
        }

        // default
        return $this->sizes['medium'];
    }
}
