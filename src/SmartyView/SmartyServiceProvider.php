<?php

/**
* Smarty Views in Illuminate / Laravel 4.
*
* @author James Ward <james@notjam.es>
* @license MIT
*/

namespace SmartyView;

use Illuminate\Support\ServiceProvider;
use SmartyView\Engines\SmartyEngine;
use SmartyView\Smarty\Resource\Laravel as LaravelResource;
use SmartyView\Smarty\Plugins\URL as URLPlugin;
use SmartyView\Smarty\Plugins\Localize as LocalizePlugin;

class SmartyServiceProvider extends ServiceProvider {
	protected $plugins = array();

	public function register() {
		$this->app['config']->package('imnotjames/smartyview', __DIR__.'/../config');

		$this->registerSmartyEngine();

		$this->registerCommands();
	}

	public function boot() {
		\Smarty::muteExpectedErrors();

		$this->plugins = array(
			'url' => new URLPlugin(),
			'localize' => new LocalizePlugin()
		);
	}

	public function registerSmartyEngine() {
		$app = $this->app;
		$me = $this;

		$app['smarty'] = $this->app->share(function() use ($me) {
			return $me->getSmarty();
		});

		$app['view']->addExtension(
			$app['config']->get('smartyview::extension', 'tpl'),
			'smarty',
			function() use ($app) {
				return new SmartyEngine($app['smarty']);
			}
		);
	}

	public function registerCommands() {
		// Empty Twig cache command
		$this->app['command.smarty.clean'] = $this->app->share(
			function () {
				return new Console\CleanCommand;
			}
		);

		$this->commands(
			'command.smarty.clean'
		);
	}

	public function getSmarty() {
		$smarty = new \Smarty();

		$finder = $this->app['view.finder'];

		$extension = $this->app['config']->get('smartyview::extension', 'tpl');

		$escapeHTML = $this->app['config']->get('smartyview::escape_html', true);

		$errorReporting = $this->app['config']->get('smartyview::error_reporting', 0);

		$shouldCache = $this->app['config']->get('smartyview::cache', false);

		$compileDir = $this->app['path.storage'].'/views/smarty/compile';
		$cacheDir = $this->app['path.storage'].'/views/smarty/cache';

		$delimiters = $this->app['config']->get(
			'smartyview::delimiters',
			array(
				'left' => '{',
				'right' => '}'
			)
		);

		$smarty->escape_html = $escapeHTML;

		$smarty->error_reporting = $errorReporting;

		$smarty->setCompileDir($compileDir);
		$smarty->setCacheDir($cacheDir);

		$smarty->left_delimiter = $delimiters['left'];
		$smarty->right_delimiter = $delimiters['right'];

		if ($shouldCache) {
			$smarty->compile_check = true;
			$smarty->caching = \Smarty::CACHING_LIFETIME_SAVED;
		}

		foreach($this->plugins as $name => $plugin) {
			$smarty->registerPlugin(
				$plugin->getType(),
				$name,
				array($plugin, 'pluginCallback')
			);
		}

		$smarty->registerResource('laravel', new LaravelResource($finder, $extension));

		$smarty->default_resource_type = 'laravel';

		$this->app['events']->fire('smartyview.smarty', array('smarty' => $smarty));

		return $smarty;
	}
}
