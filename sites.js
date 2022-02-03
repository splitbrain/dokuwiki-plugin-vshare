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
    'dailymotion': 'dailymotion\\.com\\/video\\/([a-z0-9]+)_',
    'twitchtv':    'twitch\\.tv\\/([a-z0-9_\\-]+)(?:\\/c\\/(\\d+))?',
    'msoffice':    '(?:office\\.com.*[&?]videoid=([a-z0-9\\-]+))',
    'msstream':    'microsoftstream\\.com\\/video\\/([a-f0-9\\-]{36})',
    'slideshare':  '(?:(?:slideshare\\.net\\/slideshow\\/embed_code\\/|id=)([0-9]+)|(https?\\:\\/\\/www\\.slideshare\\.net\\/(?:[a-zA-Z0-9_\\-]+)\\/(?:[a-zA-Z0-9_\\-]+)))',
    'archiveorg':  'archive\\.org\\/embed\\/([a-zA-Z0-9_\\-]+)',
    'niconico':    'nicovideo\\.jp/watch/(sm[0-9]+)',
    'youku':       'v\\.youku\\.com/v_show/id_([0-9A-Za-z=]+)\\.html',
    'bilibili':    'bilibili\\.com/video/(BV[0-9A-Za-z]+)',
    'bitchute':    'bitchute\\.com\\/video\\/([a-zA-Z0-9_\\-]+)',
    'coub':        'coub\\.com\\/view\\/([a-zA-Z0-9_\\-]+)',
    'odysee':      'odysee\\.com\\/$\\/embed\\/([-%_?=/a-zA-Z0-9]+)'
};

