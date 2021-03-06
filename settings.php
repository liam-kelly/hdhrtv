<?php
/*
 * HDHRTV is a streaming web application for the HDHomeRun cable tuner
 * Copyright (C) 2013 Brian Kirkman (kirkman [dot] brian [at] gmail [dot] org)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

	// Call this to load config settings and View class
	include('./includes/main.php');

	// evaluate action and call required function
	if (isset($_GET["action"]))
	{
		switch($_GET["action"])
		{
			case "reboot_tuner":
			if ($config['hdhr_reboot_cmd'] != '')
			{
				shell_exec($config['hdhr_reboot_cmd']);
			}
			break;
		}
		header("Location: settings.php");
	}


	// write user settings to user cookie if posting
	if (isset($_POST["listings"])) {
		foreach ($user_settings as $key => $val)
		{
			$new_settings[$key] = $_POST[$key];
		}
	
		$settings->set_user_settings($new_settings);
		header("Location: settings.php");
	}


	// restore defaults
	if (isset($_POST["restore_defaults"])) {
		$settings->restore_defaults();
		header("Location: settings.php");
	}


	// Get dropdowns with cookie data set
	foreach ($user_settings as $key => $val)
	{
		$dropdown[$key] = $settings->create_settings_dropdown($key);
	}
	$data['dropdown'] = $dropdown;


	// Set page title
	$data['page_title'] = $config['page_title'];


	// Determine if reboot command is set
	if ($config['hdhr_reboot_cmd'] != '')
	{
		$data['reboot'] = true;
	}
	else
	{
		$data['reboot'] = false;
	}


	// Allow user to select MythTV listings if enabled in config 
	$data['enable_mythtv'] = $config['enable_mythtv'];


	// Load and display view
	$view = new View();
	echo $view->display('views/settings', $data);
