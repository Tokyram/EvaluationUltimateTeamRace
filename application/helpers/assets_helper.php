
<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if(!function_exists('img_loader'))
	{
		function img_loader($name)
		{
			return site_url()."assets/img/".$name;
		}
	}

	if(!function_exists('css_loader'))
	{
		function css_loader($name)
		{
			return site_url()."/assets/css/".$name.".css";
		}
	}

	if(!function_exists('js_loader'))
	{
		function js_loader($name)
		{
			return site_url()."/assets/js/".$name.".js";
		}
	}

	if(!function_exists('fonts_loader'))
	{
		function fonts_loader($name)
		{
			return site_url()."/assets/fonts/".$name;
		}
	}

	if(!function_exists('css_loaderAdmin'))
	{
		function css_loaderAdmin($name)
		{
			return site_url()."/assets/back/css/".$name.".css";
		}
	}


	if(!function_exists('js_loaderAdmin'))
	{
		function js_loaderAdmin($name)
		{
			return site_url()."/assets/back/js/".$name.".js";
		}
	}

	if(!function_exists('map_loaderAdmin'))
	{
		function map_loaderAdmin($name)
		{
			return site_url()."assets/back/css/".$name.".map";
		}
	}
?>