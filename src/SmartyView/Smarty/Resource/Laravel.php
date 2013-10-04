<?php

namespace SmartyView\Smarty\Resource;

use Illuminate\View\ViewFinderInterface;

class Laravel extends \Smarty_Resource_Custom {
	protected $finder;

	public function __construct(ViewFinderInterface $finder) {
		$this->finder = $finder;
	}


	/**
	 * fetch template and its modification time from data source
	 *
	 * @param string $name    template name
	 * @param string &$source template source
	 * @param integer &$mtime  template modification timestamp (epoch)
	 */
	protected function fetch($name, &$source, &$mtime) {
		if (! file_exists($name)) {
			$extension = \Config::get('smartyview::extension', 'tpl');


			try {
				if (substr_compare($name, $extension, -strlen($extension), strlen($extension)) === 0) {
					$name = substr($name, 0, -(strlen($extension)+1));
				}

				$name = $this->finder->find($name);
			} catch(\InvalidArgumentException $e) {
				return;
			}

			if (substr_compare($name, $extension, -strlen($extension), strlen($extension)) !== 0) {
				// This is not a smarty template.
				return;
			}
		}

		$source = file_get_contents($name);
		$mtime = filemtime($name);
	}
}