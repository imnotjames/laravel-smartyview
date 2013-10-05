<?php

namespace SmartyView\Smarty\Plugins;

interface SmartyPlugin {
	const PLUGIN_TYPE_BLOCK = 'block';
	const PLUGIN_TYPE_COMPILER = 'compiler';
	const PLUGIN_TYPE_FUNCTION = 'function';
	const PLUGIN_TYPE_MODIFIER = 'modifier';

	public function getType();

	public function pluginCallback();
}