<?php

namespace SmartyView\Smarty\Template;

class Laravel extends \Smarty_Internal_Template {
	public function getSubTemplate($template, $cache_id, $compile_id, $caching, $cache_lifetime, $data, $parent_scope) {
		// get variables from calling scope
		if ($parent_scope == \Smarty::SCOPE_LOCAL) {
			$newData = $this->tpl_vars;
			$newData['smarty'] = clone $this->tpl_vars['smarty'];
		} elseif ($parent_scope == \Smarty::SCOPE_PARENT) {
			$newData = &$this->tpl_vars;
		} elseif ($parent_scope == \Smarty::SCOPE_GLOBAL) {
			$newData = &\Smarty::$global_tpl_vars;
		} elseif (($scope_ptr = $this->getScopePointer($parent_scope)) == null) {
			$newData = &$this->tpl_vars;
		} else {
			$newData = &$scope_ptr->tpl_vars;
		}

		if (!empty($data)) {
			// set up variable values
			foreach ($data as $_key => $_val) {
				$newData[$_key] = new \Smarty_variable($_val);
			}
		}

		$extension = '.' . \Config::get('smartyview::extension', 'tpl');

		if (substr_compare($template, $extension, -strlen($extension), strlen($extension)) !== 0) {
			try {
				return \View::make($template, array_map(function($v) { return $v->value; }, $newData));
			} catch (\Exception $e) {

			}
		}

		// Fall back to parent's getSubTemplate..
		return parent::getSubTemplate($template, $cache_id, $compile_id, $caching, $cache_lifetime, $data, $parent_scope);
	}
}
