<?php

require_once 'module/module.php';

class moduleFrontPage extends module {

	function moduleFrontPage() {
		$this->module();
	}

	function display_main() {
		$this->parse_header();
		return $this->template->fetch( './display_main.tpl' );
	}
    
}
?>
