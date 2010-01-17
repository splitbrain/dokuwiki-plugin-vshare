
/**
 * Append a toolbar button
 */
if(window.toolbar != undefined){
    toolbar[toolbar.length] = {"type":  "pluginvshare",
                               "title": LANG['plugins']['vshare']['button'],
                               "icon":  DOKU_BASE+"lib/plugins/vshare/button.png",
                               "key":   ""};
}

/**
 * Try to determine the video service, extract the ID and insert
 * the correct syntax
 */
function tb_pluginvshare(btn, props, edid) {
    var text = prompt(LANG['plugins']['vshare']['prompt']);
    if(!text) return;

    // This includes the site patterns:
    /* DOKUWIKI:include sites.js */

    for (var key in sites){
        var RE = new RegExp(sites[key],'i');
        var match = text.match(RE);
        if(match){
            var code = '{{'+key+'>'+match[1]+'?medium}}';
            insertAtCarret(edid, code);
            return;
        }
    }

    alert(LANG['plugins']['vshare']['notfound']);
}

