<?php

namespace SmartyView\Smarty\Plugins;

use Illuminate\Support\Facades\Lang;

class Localize implements SmartyPlugin {
	public function getType() {
		return self::PLUGIN_TYPE_MODIFIER;
	}

	public function pluginCallback() {
		$params = func_get_args();

		$value = array_shift($params);

		if (isset($params[0])) {
			return Lang::choice($value, $params[0]);
		}

		return Lang::get($value);
	}
}