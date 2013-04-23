/**
 * video URL recognition patterns
 *
 * The first match group is used as video ID
 *
 * You need to touch conf/local.php to refresh the cache after changing
 * this file
 */

var sites = {
    'youtube':     'youtube\\.com/.*[&?]v=([a-z0-9_\\-]+)',
    'vimeo':       'vimeo\\.com\\/(\\d+)',
    'ustream':     'ustream\\.tv\\/recorded\\/(\\d+)\\/',
    '12seconds':   '12seconds\\.com\\/v\\/([a-z0-9_]+)',
    '5min':        '5min\\.com\\/Video/.*-([0-9]+)([&?]|$)',
    'clipfish':    'clipfishi\\.de\\/.*\\/video\\/([0-9])+\\/',
    'blogtv':      'blogtv\.com\\/Shows\\/\\d+\\/([a-z0-9]+_[a-z0-9]+)([&?]|$)',
    'current':     'current\\.com\\/items\\/(\\d+)',
    'dailymotion': 'dailymotion\\.com\\/video\\/([a-z0-9]+)_',
    'googlevideo': 'video\\.google\\.com\\/videoplay\\?docid=([\\-\\d]+)',
    'gtrailers':   'gametrailers\\.com\\/.*\\/(\\d+)',
    'justintv':    'justin\\.tv\\/clip\\/([a-f0-9]+)',
    'metacafe':    'metacafe\\.com\\/watch\\/(\\d+)\\/',
    'myspacetv':   'vids\\.myspace\\.com\\/.*videoid=(\\d+)',
    'rcmovie':     'rcmovie\\.de\\/video\\/([a-f0-9]+)\\/',
    'revver':      'revver\\.com\\/video\\/(\\d+)\\/',
    'scivee':      'scivee\\.tv\\/node\\/(\\d+)',
    'sevenload':   'sevenload\\.com\\/.*\\/([a-z0-9]+)-[^\\/]*$',
    'stickam':     'stickam\\.com\\/viewMedia.do\\?mId=(\\d+)',
    'veoh':        'veoh\\.com\\/.*watch[^v]*(v[a-z0-9]+)'
};

