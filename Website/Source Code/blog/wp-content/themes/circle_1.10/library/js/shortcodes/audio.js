(function() {
    tinymce.create('tinymce.plugins.audio', {
        init: function(ed, url) {
            ed.addButton('audio', {
                title: 'Add a audio',
                image: url + '/icons/audio.png',
                onclick: function() {
                    ed.selection.setContent('[audio]http://url-to-file.mp3[/audio]');
                }
            });
        },
        createControl: function(n, cm) {
            return null;
        }
    });
    tinymce.PluginManager.add('audio', tinymce.plugins.audio);
})();