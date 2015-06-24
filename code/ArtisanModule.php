<?php
/**
 * Module class, holds usefull functionality, configuration information etc.
 */
class ArtisanModule extends DataObject {

	/**
	 * Return the path the module is installed in based on where this script is running from.
	 *
	 * @return string path name ending with '/'
	 */
	public static function get_module_path() {
		return substr(
			str_replace('\\', '/', realpath(__DIR__ . '/../')),
			strlen(Director::baseFolder())
		) . '/';
	}
}