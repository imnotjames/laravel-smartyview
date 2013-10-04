<?php

/**
* Smarty Views in Illuminate / Laravel 4.
*
* @author James Ward <james@notjam.es>
* @license MIT
*/

namespace SmartyView\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CleanCommand extends Command {
	protected $name = 'smartyview:clean';

	protected $description = 'Clear the Smarty compiled templates and cache.';

	public function fire() {
		$smarty = $this->laravel['smarty'];

		$compilePath = $smarty->compile_dir;
		$cachePath = $smarty->cache_dir;

		$file = new Filesystem();

		$file->deleteDirectory($cachePath);

		if (!file_exists($cachePath)) {
			$this->info('Smarty cache path cleaned');
		} else {
			$this->error('Smarty cache path could not be cleaned');
		}

		$file->deleteDirectory($compilePath);


		if (!file_exists($compilePath)) {
			$this->info('Smarty compile path cleaned');
		} else {
			$this->error('Smarty compile path could not be cleaned');
		}
	}
}