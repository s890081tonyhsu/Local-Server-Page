<?php

	class semantic extends Template{
		public function __construct($needed){
			$this->needed = $needed;
			parent::__construct($needed);
		}

		protected function structPage(){
				$navbar = '
					<div class="ui fixed large menu">
						<div class="header item left">
							Localhost
						</div>
						<div id="Navbar-container" class="left menu">
						</div>
					</div>
				';
				$server = '
					<div id="Server-container" class="ui grid">
					</div>
				';
				$list = '
					<div id="List-container" class="ui grid">
					</div>
				';
				$temp = '
				'.$navbar.'
				<div class="ui page grid">
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
				<a class="item" href="#{{id}}">{{name}}</a>
			{{/navbar}}
			<div class="ui simple dropdown item">
        Theme <i class="icon dropdown"></i>
        <div class="menu" tabindex="-1">
					{{#templates:i}}
          	<a class="item" href="?theme={{templates[i]}}">{{templates[i]}}</a>
					{{/templates}}
        </div>
      </div>
			';
			parent::setTemplate('Navbar', $temp);
		}

		private function setServer(){
			$temp = '
			<!--server-->
			<div class="ui grid">
			<h2 id="Server-head">Server Status</h2>
			{{#each services:i}}
				<div class="four wide column" intro="fade:{ delay:{{ (i+1)*100 }}, duration:{{ (i+1)*100 }} }">
					<h3>{{name}}:{{port}}</h3>
					{{#if (status === \'success\')}}
						<button class="ui green button" type="button">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
							Connect Successful
						</button>
					{{else}}
						<button class="ui red button" type="button">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							Connect Failed
						</button>
					{{/if}}
				</div>
			{{/each}}
			</div>
			<div class="ui grid"><div class="sixteen wide column"></div></div>
			<button type="button" class="ui bottom attached button {{["blue", "orange", "green"][state]}}" on-tap="loadServer">{{loadstateTxt(state)}}</button>
			';
			parent::setTemplate('Server', $temp);
		}

		private function setList(){
			$badgeList = array(
				'php' => 'purple',
				'ruby' => 'red',
				'python' => 'blue'
			);
			$badge = '';
			foreach($badgeList as $type=>$part){
				$badge = $badge.'{{#if (badge === \''.$type.'\')}}<span class="ui ribbon label '.$part.'">{{type}}</span>{{/if}}';
			}
			$temp = '
			<!--list-->
			<div class="ui grid">
			{{#list}}
			<h2 id="Projects-head">Repositories</h2>
			<div class="ui grid">
				{{#each projects:i}}
					<div class="ui sixteen wide column segment" intro="fade:{ delay:{{ (i+1)*100 }}, duration:{{ (i+1)*100 }} }">
						'.$badge.'
						<h3>{{name}}</h3>
						<div class="">
							{{#commit}}
								<div>
									<h4>Commits</h4>
									<p>Hash: {{hash}}</p>
									<p>{{author}} commited at {{format(timestamp)}}</p>
									<p>{{{message}}}</p>
								</div>
							{{/commit}}
							<div class="ui buttons right floated">
								<button type="button" class="ui button teal" on-tap="markdownToggle:{{i}}">Readme</button>
								<button type="button" class="ui button" on-tap="structureToggle:{{i}}">Structure</button>
								<a href="{{demo}}" type="button" class="ui button green" target="_blank">Demo Link</a>
							</div>
							{{#if markdown.show}}
								<div class="ui segment" intro-outro="fade: 200">
									{{{markdown.html}}}
								</div>
							{{/if}}
							{{#if structure.show}}
								<div class="ui segment" intro-outro="fade: 200">
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
			<div class="ui grid">
				{{#each others}}
					<div class="ui eight wide column segment">
						<h3>{{name}}</h3>
						<p>path: {{path}}</p>
						<div>
							{{#if (href === root)}}
								<button type="button" class="ui disabled button right floated" >Disabled</button>
							{{else}}
								<a href="{{href}}" type="button" class="ui green button right floated" target="_blank">Demo Link</a>
							{{/if}}
						</div>
					</div>
				{{/each}}
			</div>
			{{/list}}
			</div>
			<div class="ui grid">
				<div class="sixteen column"></div>
			</div>
			<button type="button" class="ui bottom attached button {{["blue", "orange", "green"][state]}}" on-tap="loadList">{{loadstateTxt(state)}}</button>
			';
			parent::setTemplate('List', $temp);
		}
	}
