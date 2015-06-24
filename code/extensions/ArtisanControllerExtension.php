<?php
/**
 * Base clas for Artisan Controller extensions, i.e. an Extension
 */
abstract class ArtisanControllerExtension extends Extension {
    /**
     * Returns the extension's owner.
     * @return Controller
     */
    public function __invoke() {
        return $this->owner;
    }

    public function ArtisanDir() {
        return ArtisanModule::get_module_path();
    }

}