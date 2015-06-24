<?php

/**
 * Adds an TextArea Content field to the model and provides extra classes
 */
class ArtisanHasCodeExtension extends ArtisanModelExtension {
    private static $db = [
        'Code' => 'Text'
    ];
    private static $add_to_form = true;
    private static $add_to_grid = false;

    public function updateCMSFields(FieldList $fields) {
        if (self::get_config_setting('add_to_form')) {
            $fields->addFieldToTab(
                'Root.Main',
                new TextareaField(
                    'Code'
                )
            );
        }
    }
}