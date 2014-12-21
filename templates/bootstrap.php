<?php

	include('library/template.php');

	class bootstrap extends template{
		public function __construct($needed){
			$this->needed = $needed;
			parent::__construct($needed);
		}

		protected function structPage(){
				$navbar = '
				<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
				  <div class="container-fluid">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						  <a class="navbar-brand" href="#">Localhost</a>
						</div>
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						  <ul id="Navbar-container" class="nav navbar-nav"></ul>
						</div>
					</div>
				</nav>
				';
				$server = '
					<div id="Server-container" class="row">
					</div>
				';
				$list = '
					<div id="List-container" class="row">
					</div>
				';
				$temp = '
				<div class="container">
					'.$navbar.'
					<h1>Localhost\'s Server</h1>
					'.$server.'
					'.$list.'
				</div>
				';
				echo $temp;
		}

		protected function setup($needed){
			foreach($needed as $item){
				$this->{'set'.$item}();
			}
		}

		private function setNavbar(){
			$temp = '
			<!--navbar-->
			{{#navbar:name}}
				<li><a href="#{{id}}">{{name}}</a></li>
			{{/navbar}}
			';
			parent::setTemplate('Navbar', $temp);
		}

		private function setServer(){
			$temp = '
			<!--server-->
			<div class="row">
			<h2 id="Server-head">Server Status</h2>
			{{#each services:i}}
				<div class="col-md-4" intro="fade:{ delay:{{ (i+1)*100 }}, duration:{{ (i+1)*100 }} }">
					<h3>{{name}}:{{port}}</h3>
					{{#if (status === \'success\')}}
						<button class="btn btn-success" type="button">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							Connect Successful
						</button>
					{{else}}
						<button class="btn btn-danger" type="button">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							Connect Failed
						</button>
					{{/if}}
				</div>
			{{/each}}
			</div>
			<div class="col-md-8 col-md-offset-2 row">
					<button type="button" class="btn btn-block btn-{{["primary", "warning", "success"][state]}}" on-tap="loadServer">{{loadstateTxt(state)}}</button>
			</div>
			';
			parent::setTemplate('Server', $temp);
		}

		private function setList(){
			$badgeList = array(
				'php' => 'info',
				'ruby' => 'danger',
				'python' => 'primary'
			);
			$badge = '';
			foreach($badgeList as $type=>$part){
				$badge = $badge.'{{#if (badge === \''.$type.'\')}}<span class="label label-'.$part.'">{{type}}</span>{{/if}}';
			}
			$temp = '
			<!--list-->
			<div class="row">
			{{#list}}
			<h2 id="Projects-head">Repositories</h2>
			<div class="container">
				{{#each projects:i}}
					<div class="col-md-12 panel" intro="fade:{ delay:{{ (i+1)*100 }}, duration:{{ (i+1)*100 }} }">
						<div class="panel-heading"><h3>{{name}} '.$badge.'</h3></div>
						<div class="panel-body">
							{{#commit}}
								<div>
									<h4>Commits</h4>
									<p>Hash: {{hash}}</p>
									<p>{{commiter}} {{type}} at {{format(timestamp)}}</p>
									<p>{{comtent}}</p>
								</div>
							{{/commit}}
							<div class="btn-group pull-right">
								<button type="button" class="btn btn-info" on-tap="markdownToggle:{{i}}">Readme</button>
								<button type="button" class="btn btn-default" on-tap="structureToggle:{{i}}">Structure</button>
								<a href="{{demo}}" type="button" class="btn btn-success" target="_blank">Demo Link</a>
							</div>
							{{#if markdown.show}}
								<div class="well" intro-outro="fade: 200">
									{{{markdown.html}}}
								</div>
							{{/if}}
							{{#if structure.show}}
								<div class="well" intro-outro="fade: 200">
									{{#each structure.directory}}
										<p><b>{{this}}</b></p>
									{{/each}}
									{{#each structure.file}}
										<p>{{this}}</p>
									{{/each}}
								</div>
							{{/if}}
						</div>
					</div>
				{{/each}}
			</div>
			<h2 id="Others-head">Others\' Link</h2>
			<div class="container">
				{{#each others}}
					<div class="col-md-6">
						<h3>{{name}}</h3>
						<p>path: {{path}}</p>
						<div class="pull-right">
							{{#if (href === root)}}
								<button type="button" class="btn btn-default" disabled="disabled">Disabled</button>
							{{else}}
								<a href="{{href}}" type="button" class="btn btn-success" target="_blank">Demo Link</a>
							{{/if}}
						</div>
					</div>
				{{/each}}
			</div>
			{{/list}}
			</div>
			<div class="col-md-8 col-md-offset-2 row">
				<button type="button" class="btn btn-{{["primary", "warning", "success"][state]}} col-md-12" on-tap="loadList">{{loadstateTxt(state)}}</button>
			</div>
			';
			parent::setTemplate('List', $temp);
		}
	}
