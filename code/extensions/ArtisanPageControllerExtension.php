<?php
/**
 * Extension to add to Page Controllers which render with Artisan.
 */
abstract class ArtisanPageControllerExtension extends ArtisanControllerExtension {
    const BeforeInit = 'before';
    const AfterInit = 'after';

    /**
     * Add page requirements before page controller init is called.
     */
    public function onBeforeInit() {
        self::add_requirements(self::BeforeInit);
    }
    /**
     * Add page requirements after page controller init is called.
     */
    public function onAfterInit() {
        self::add_requirements(self::AfterInit);
    }
    /**
     * Adds javascript and css files to Requirements based on their extension. If paths start with '/' then
     * goes from document root, otherwise from the Model installation path.
     *
     * @param string $when - look at before or after components.
     */
    public static function add_requirements($when) {
        $installDir = ArtisanModule::get_module_path();

        foreach (self::get_config_setting('requirements', $when) as $path) {
            if (substr($path, 0, 1) !== '/') {
                $path = Controller::join_links(
                    $installDir,
                    $path
                );
            }
            $path = substr($path, 1);

            if (substr($path, -3, 3) === '.js') {
                Requirements::javascript($path);
            }
            if (substr($path, -4, 4) === '.css') {
                Requirements::css($path);
            }
        }

    }

    /**
     * Return a setting from config.MosaicControllerInstance optionally returning the key if the setting is an array.
     *
     * @param $varName
     * @param null $key
     * @return array|scalar
     */
    public static function get_config_setting($varName, $key = null) {
        $setting = Config::inst()->get(get_called_class(), $varName);
        if ($key && is_array($setting)) {
            return $setting[$key];
        }
        return $setting;
    }
}