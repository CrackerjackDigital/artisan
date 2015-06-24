<?php

/**
 * Adds Title and SubTitle text fields to the model and provides extra css classes for styling.
 */
class ArtisanHasHeadingsExtension extends ArtisanModelExtension
    implements ProvidesEditableGridFields {

    private static $db = [
        'Title' => 'Text',
        'SubTitle' => 'Text'
    ];
    private static $summary_fields = [
        'Title' => 'Title'
    ];

    private static $tab_name = 'Root.Main';

    // add to CMS form
    private static $add_to_form = true;

    // add to EditableGridField
    private static $add_to_grid = true;

    public function updateCMSFields(FieldList $fields) {
        if (self::get_config_setting('add_to_form')) {
            $fields->addFieldsToTab(
                self::get_config_setting('tab_name'),
                [
                    new TextareaField('Title'),
                    new TextareaField('SubTitle')
                ]
            );
        }
    }

    public function provideEditableGridFields(array &$fields) {
        // if config is true or an array with fields
        if ($config = parent::get_config_setting('add_to_grid')) {
            $summaryFields = $this->config()->get('summary_fields');

            // if config is true then use config.summary_fields
            if (is_bool($config)) {
                $config = $summaryFields;
            }
            // set fields to intersection of config.add_to_grid and config.summary_fields
            $fields = array_merge(
                $fields,
                array_intersect_key(
                    $config,
                    [
                        'Title' => [
                            'title' => $this->getFieldLabel('TitleLabel'),
                            'field' => 'TextField'
                        ],
                        'SubTitle' => [
                            'title' => $this->getFieldLabel('SubTitleLabel'),
                            'field' => 'TextField'
                        ],
                    ]
                )
            );
        }
    }
}