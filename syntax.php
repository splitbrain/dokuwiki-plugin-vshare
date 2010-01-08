<?php
/**
 * Easily embed videos from various Video Sharing sites
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */

if(!defined('DOKU_INC')) die();
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_vshare extends DokuWiki_Syntax_Plugin {
    var $sites;

    /**
     * Constructor.
     * Intitalizes the supported video sites
     */
    function syntax_plugin_vshare(){
        $this->sites =  confToHash(dirname(__FILE__).'/sites.conf');
    }

    function getType(){
        return 'substition';
    }

    function getSort(){
        return 159;
    }


    /**
     * Connect to the parser
     */
    function connectTo($mode) {
        $pattern = join('|',array_keys($this->sites));
        $this->Lexer->addSpecialPattern('\{\{\s?(?:'.$pattern.')>[^}]*\}\}',$mode,'plugin_vshare');
    }

    /**
     * Parse the parameters
     */
    function handle($match, $state, $pos, &$handler){
        $command = substr($match,2,-2);

        // alignment
        $align = 0;
        if(substr($command,0,1) == ' ') $align += 1;
        if(substr($command,-1) == ' ')  $align += 2;
        $command = trim($command);

        // get site and video
        list($site,$vid) = explode('>',$command);
        if(!$this->sites[$site]) return null; // unknown site
        if(!$vid) return null; // no video!?

        // what size?
        list($vid,$param) = explode('?',$vid,2);
        if(preg_match('/(\d+)x(\d+)/i',$param,$m)){     // custom
            $width  = $m[1];
            $height = $m[2];
        }elseif(strpos($param,'small') !== false){      // small
            $width  = 255;
            $height = 210;
        }elseif(strpos($param,'large') !== false){      // large
            $width  = 520;
            $height = 406;
        }else{                                          // medium
            $width  = 425;
            $height = 350;
        }

        $url = str_replace('@VIDEO@',rawurlencode($vid),$this->sites[$site]);
        $url = str_replace('@WIDTH@',$width,$url);
        $url = str_replace('@HEIGHT@',$height,$url);
        list(,$vars) = explode('?',$url,2);
        $varr = array();
        parse_str($vars,$varr);

        return array(
            'site'   => $site,
            'video'  => $vid,
            'flash'  => $url,
            'vars'   => $varr,
            'align'  => $align,
            'width'  => $width,
            'height' => $height
        );
    }

    /**
     * Render the flash player
     */
    function render($mode, &$R, $data){
        if($mode != 'xhtml') return false;
        if(is_null($data)) return false;

        if($data['align'] == 1) $R->doc .= '<div class="vshare__right">';
        if($data['align'] == 2) $R->doc .= '<div class="vshare__left">';
        if($data['align'] == 3) $R->doc .= '<div class="vshare__center">';
        $R->doc .= html_flashobject(
                            $data['flash'],
                            $data['width'],
                            $data['height'],
                            $data['vars'],
                            $data['vars']);
        if($data['align']) $R->doc .= '</div>';
    }
}
