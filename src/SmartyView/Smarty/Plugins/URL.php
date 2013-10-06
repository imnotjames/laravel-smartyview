<?php

namespace SmartyView\Smarty\Plugins;

use Illuminate\Support\Facades\URL as URLFacade;

class URL implements SmartyPlugin {
	const URL_TYPE_ROUTE = 'route';
	const URL_TYPE_ACTION = 'action';
	const URL_TYPE_ASSET = 'asset';
	const URL_TYPE_SECURE_URL = 'secure_url';
	const URL_TYPE_URL = 'url';

	public function getType() {
		return self::PLUGIN_TYPE_MODIFIER;
	}

	public function pluginCallback() {
		$params = func_get_args();

		$value = array_shift($params);
		$type = array_shift($params);
		$params = json_decode(array_shift($params), true);

		$acceptableTypes = array(
			self::URL_TYPE_ROUTE,
			self::URL_TYPE_ACTION,
			self::URL_TYPE_ASSET,
			self::URL_TYPE_SECURE_URL,
			self::URL_TYPE_URL
		);

		if (in_array($type, $acceptableTypes)) {
			return URLFacade::$type($value, is_array($params) ? $params : null);
		}

		return '';
	}
}
