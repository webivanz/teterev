<?php

if (!defined('_JOMRES_INITCHECK'))
	define('_JOMRES_INITCHECK', 1 );

define( '_JOMRES_INITCHECK_ADMIN', 1 );

require_once (dirname(__FILE__).'/../../../jomres_root.php');

if (!jomres_check_if_jomres_installed())
	{
	output_jomres_not_installed_message();
	}

else
	{
	if (isset($_REQUEST['jr_wp_source']))
		{
		if ($_GET['jr_wp_source'] == "admin")
			{
			jr_wp_trigger_admin();
			}
		else
			{
			jr_wp_trigger_frontend();
			}
		}
	else
		{
		if (strpos($_SERVER['SCRIPT_FILENAME'] ,"wp-admin/admin.php") > 0 )
			{
			jr_wp_trigger_admin();
			}
		else
			{
			jr_wp_trigger_frontend();
			}
		}
	}
	
function jr_wp_trigger_frontend()
	{

	require_once( ABSPATH . JOMRES_ROOT_DIRECTORY.'/jomres.php' );
	if ( (int)$_REQUEST['jrajax'] == 1 ) // If it's an ajax called, we need to die when Jomres has done it's stuff
		{
		die();
		}
	}

function jr_wp_trigger_admin()
	{
	global $current_user;
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	$role = trim($user_role);
	if ($role == "administrator")
		{
		require_once( ABSPATH . JOMRES_ROOT_DIRECTORY.'/admin.php' );
		}
	if ( (int)$_REQUEST['jrajax'] == 1 ) // If it's an ajax called, we need to die when Jomres has done it's stuff
		{
		die();
		}
	}

function jomres_check_if_jomres_installed()
	{
	
	$jomres_installed = false;
	if ( defined ( 'JOMRES_ROOT_DIRECTORY' ) && file_exists( ABSPATH . JOMRES_ROOT_DIRECTORY.'/jomres.php' ) )
		{
		$jomres_installed = true;
		}

	return $jomres_installed;
	}

function output_jomres_not_installed_message()
	{
	$dir_path = dirname(__FILE__) ;

	copy(
		$dir_path.'/jomres_webinstall.php' , 
		ABSPATH.'/jomres_webinstall.php'
		);
	
	unlink($dir_path.'/jomres_webinstall.php');

	if (!file_exists(ABSPATH.'/jomres_webinstall.php') )
		{
		echo 'Error, couldn\'t copy '.$dir_path.'/jomres_webinstall.php to '.ABSPATH.'/jomres_webinstall.php <br/>';
		echo 'Please use ftp to copy the file to '.ABSPATH.' then run it manually.';
		}
	else
		{
		wp_redirect(site_url()."/jomres_webinstall.php");
		}
	
	}
