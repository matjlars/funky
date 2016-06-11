<?php
class j_controller
{
	// Overridable functions:
	public function admin_page()
	{
		j()->debug->error('Controller "'.$this->controller_name().'" has not defined "admin_page()" yet.');
	}
	public function admin_install()
	{
		j()->debug->error('Controller "'.$this->controller_name().'" has not defined "admin_install()" yet.');
	}
	public function admin_uninstall()
	{
		j()->debug->error('Controller "'.$this->controller_name().'" has not defined "admin_uninstall()" yet.');
	}
	public function admin_widget()
	{
		j()->debug->error('Controller "'.$this->controller_name().'" has not defined "admin_widget()" yet.');
	}
}