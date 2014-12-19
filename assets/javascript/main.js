requirejs.config({
	'baseUrl': 'assets/javascript',
	'paths': {
		'text': 'require/text',
		'json': 'require/json',
		'ractive': 'ractive/ractive.min',
		'ret': 'ractive/ractive-events-tap.min',
		'rtf': 'ractive/ractive-transitions-fade.min',
		'root': '../../'
	},
	waitSeconds: 15,
});

// Refer this to https://gist.github.com/maestrow/9c9fced15d24021dd495
require(['ractive', 'ret', 'rtf'], function(){
	var navbar = new Ractive({
		el: '#Navbar-container',
		template: '#Navbar-template',
		data: {
			navbar: {
								'Ip Status':{'id': 'Ip-head'},
								'Server Status': {'id': 'Server-head'}, 
								'List Projects': {'id': 'Projects-head'},
								'Local Website': {'id': 'Others-head'}
							}
		}
	});
	
	var server = new Ractive({
		el: '#Server-container',
		template: '#Server-template',
		data: {}
	});
	server.on('loadServer', function(){
		var server = this;
		require(['json!root/api.php?params=server'], function(Data){
			server.set('services', Data);
		});
	});

	var list = new Ractive({
		el: '#List-container',
		template: '#List-template',
		data: {
			format: function ( timestamp ) {
				var t = new Date( timestamp*1000 );
				return t.toString();
    	}
		},
	});
	list.on({
		'loadList': function(){
			var list = this;
			require(['json!root/api.php?params=list'], function(Data){
				list.set('list', Data);
			});
		},
		'markdownToggle': function(event, show){
			this.toggle('list.projects['+show+'].markdown.show');
		},
		'structureToggle': function(event, show){
			this.toggle('list.projects['+show+'].structure.show');
		}
	});
});
