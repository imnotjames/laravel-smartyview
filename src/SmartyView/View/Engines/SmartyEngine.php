<?php

/**
* Smarty Views in Illuminate / Laravel 4.
*
* @author James Ward <james@notjam.es>
* @license MIT
*/

namespace SmartyView\Engines;

use Illuminate\View\Engines\EngineInterface;

class SmartyEngine implements EngineInterface {
	/**
	 * @var \Smarty
	 */
	protected $smarty;

	public function __construct(\Smarty $smarty) {
		$this->smarty = $smarty;
	}

	public function getSmarty() {
		return $this->smarty;
	}

	/**
	 * Get the evaluated contents of the view.
	 *
	 * @param  string $path
	 * @param  array $data
	 * @return string
	 */
	public function get($path, array $data = array()) {
		$template = $this->smarty->createTemplate($path);

		$template->assign($data);

		return $template->fetch();
	}
}