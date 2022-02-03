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

        // This includes the site patterns:
        /* DOKUWIKI:include sites.js */

        for (let key in sites) {

            if (sites.hasOwnProperty(key)) {
                const RE = new RegExp(sites[key], 'i');
                const match = text.match(RE);
                if (match) {
                    let urlparam = '';
                    let videoid = match[1];

                    switch (key) {
                        case 'slideshare':
                            //provided video url?
                            if (match[2]) {

                                jQuery.ajax({
                                    url: '//www.slideshare.net/api/oembed/2',
                                    dataType: 'jsonp',
                                    data: {
                                        url: match[2],
                                        format: 'jsonp'
                                    }
                                }).done(function (response, status, error) {
                                    const videoid = response.slideshow_id;
                                    PluginVShare.insert(key, videoid, urlparam);
                                }).fail(function (data, status, error) {
                                    /* http://www.slideshare.net/developers/oembed
                                     * If not found, an status 200 with response {error:true} is returned,
                                     * but "Content-Type:application/javascript; charset=utf-8" is then
                                     * wrongly changed to "Content-Type:application/json; charset=utf-8"
                                     * so it throws a parseerror
                                     */
                                    alert(LANG['plugins']['vshare']['notfound']);
                                });
                                return;
                            }
                            break;
                        case 'twitchtv':
                            if (match[2]) {
                                urlparam = 'chapter_id=' + match[2];
                            }
                            break;
                    }

                    PluginVShare.insert(key, videoid, urlparam);
                    return;
                }
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
        var code = '{{' + key + '>' + videoid + '?' + urlparam + '}}';
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
