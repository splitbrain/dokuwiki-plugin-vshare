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
    '5min':        '5min\\.com\\/Video/.*-([0-9]+)([&?]|$)',
    'clipfish':    'clipfishi\\.de\\/.*\\/video\\/([0-9])+\\/',
    'dailymotion': 'dailymotion\\.com\\/video\\/([a-z0-9]+)_',
    'gtrailers':   'gametrailers\\.com\\/.*\\/(\\d+)',
    'metacafe':    'metacafe\\.com\\/watch\\/(\\d+)\\/',
    'myspacetv':   'vids\\.myspace\\.com\\/.*videoid=(\\d+)',
    'rcmovie':     'rcmovie\\.de\\/video\\/([a-f0-9]+)\\/',
    'scivee':      'scivee\\.tv\\/node\\/(\\d+)',
    'veoh':        'veoh\\.com\\/.*watch[^v]*(v[a-z0-9]+)'
};

