// Docu : http://www.tinymce.com/wiki.php/API3:tinymce.api.3.x

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('post_snippets');

	tinymce.create('tinymce.plugins.post_snippets', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {

			// Register the command so that it can be invoked from the button
			ed.addCommand('mce_post_snippets', function() {
				post_snippets_canvas = ed;
				post_snippets_caller = 'visual';
				jQuery( "#post-snippets-dialog" ).dialog( "open" );
			});

			// Register example button
			ed.addButton('post_snippets', {
				title : 'Post Snippets',
				cmd : 'mce_post_snippets',
				image : url + '/post-snippets.gif'
			});
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
					longname  : 'Post Snippets',
					author 	  : 'GreenTreeLabs',
					authorurl : 'https://www.postsnippets.com/',
					infourl   : 'https://www.postsnippets.com/',
					version   : '1.9'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('post_snippets', tinymce.plugins.post_snippets);
})();


