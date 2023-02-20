(function() {
    tinymce.PluginManager.add('tlp_team_scg', function( editor, url ) {
        var tlpsc_tag = 'tlpteam';


        //add popup
        editor.addCommand('tlp_team_scg_popup', function(ui, v) {
            //setup defaults

            editor.windowManager.open( {
                title: 'TLP Team Shortcode',
                width: jQuery( window ).width() * 0.3,
                height: (jQuery( window ).height() - 36 - 50) * 0.1,
                id: 'tlpteam-insert-dialog',
                body: [
                    {
                        type   : 'container',
                        html   : '<span class="tlp_loading">Loading...</span>'
                    }
                ],
                onsubmit: function( e ) {

                    var shortcode_str,
                        id = jQuery("#scid").val(),
                        title = jQuery( "#scid option:selected" ).text();
                    if(id && id != 'undefined'){
                        shortcode_str = '[' + tlpsc_tag;
                            shortcode_str += ' id="'+id+'" title="'+ title +'"';
                        shortcode_str += ']';
                    }
                    if(shortcode_str) {
                        editor.insertContent(shortcode_str);
                    }else{
                        alert('No short code selected');
                    }
                }
            });

            putScList();
        });

        //add button
        editor.addButton('tlp_team_scg', {
            icon: 'tlp_team_scg',
            tooltip: 'TLP Team',
            cmd: 'tlp_team_scg_popup'
        });

        function putScList(){
                var dialogBody = jQuery( '#tlpteam-insert-dialog-body' );
                jQuery.post( ajaxurl, {
                    action: 'teamShortcodeList'
                }, function( response ) {
                    dialogBody.html(response);
                });

        }

    });
})();