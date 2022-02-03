/**
 * Append a toolbar button
 */
if (window.toolbar !== undefined) {
    toolbar[toolbar.length] = {
        "type": "pluginvshare",
        "title": LANG['plugins']['vshare']['button'],
        "icon": "../../plugins/vshare/button.png",
        "key": ""
    };
}

/**
 * Try to determine the video service, extract the ID and insert
 * the correct syntax
 */
function tb_pluginvshare(btn, props, edid) {
    PluginVShare.edid = edid;
    PluginVShare.buildSyntax();
}

const PluginVShare = {
    edid: null,

    /**
     * Ask for URL, extract data and create syntax
     */
    buildSyntax: function () {

        const text = prompt(LANG['plugins']['vshare']['prompt']);
        if (!text) return;

        for (const [site, rex] of Object.entries(JSINFO.plugins.vshare)) {
                const RE = new RegExp(rex, 'i');
                const match = text.match(RE);
                if (match) {
                    const urlparam = '';
                    const videoid = match[1];
                    PluginVShare.insert(site, videoid, urlparam);
                    return;
                }
        }

        alert(LANG['plugins']['vshare']['notfound']);
    },

    /**
     * Insert the syntax in the editor
     *
     * @param {string} key
     * @param {string} videoid
     * @param {string} urlparam
     */
    insert: function (key, videoid, urlparam) {
        const code = '{{' + key + '>' + videoid + '?' + urlparam + '}}';
        insertAtCarret(PluginVShare.edid, code);
    },

    /**
     * Allow loading videos on click
     */
    attachGDPRHandler: function () {
        const $videos = jQuery('div.vshare');

        // add click handler
        $videos.on('click', function () {
            // create an iframe and copy over the attributes
            const iframe = document.createElement('iframe');
            let attr;
            let attributes = Array.prototype.slice.call(this.attributes);
            while(attr = attributes.pop()) {
                iframe.setAttribute(attr.nodeName, attr.nodeValue);
            }
            // replace the div with the iframe
            this.replaceWith(iframe);
        });

        // add info text
        $videos.each(function (){
            const $self = jQuery(this);
            const info = document.createElement('p');
            info.innerText = LANG.plugins.vshare.click.replace('%s', $self.data('domain'));
            $self.append(info);
        });
    }
};

jQuery(function () {
    PluginVShare.attachGDPRHandler();
});
