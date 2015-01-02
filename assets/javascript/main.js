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
							},
			templates: {},
		}
	});

	require(['json!root/api.php?params=theme'], function(Data){
		navbar.set('templates', Data);
		console.log(navbar);
	});

	var server = new Ractive({
		el: '#Server-container',
		template: '#Server-template',
		data: {
			'state': 0,
			loadstateTxt: function(num){
				var txt = ['Load Server Status', 'Now Loading...', 'Loading Complete'];
				return txt[num];
			}
		}
	});
	server.on('loadServer', function(){
		var server = this;
		server.set('state', 1);
		require.undef('json!root/api.php?params=server');
		require(['json!root/api.php?params=server'], function(Data){
			server.set('services', Data);
			server.set('state', 2);
			setTimeout(function(){ server.set('state', 0); }, 1000);
		});
	});

	var list = new Ractive({
		el: '#List-container',
		template: '#List-template',
		data: {
			'state': 0,
			format: function ( timestamp ) {
				var t = new Date( timestamp*1000 );
				return t.toString();
    	},
			loadstateTxt: function(num){
				var txt = ['Load List', 'Now Loading...', 'Loading Complete'];
				return txt[num];
			}
		},
	});
	list.on({
		'loadList': function(){
			var list = this;
			list.set('state', 1);
			require.undef('json!root/api.php?params=list');
			require(['json!root/api.php?params=list'], function(Data){
				list.set('list', Data);
				list.set('state', 2);
				setTimeout(function(){ list.set('state', 0); }, 1000);
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
