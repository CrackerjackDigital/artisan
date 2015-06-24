<?php
/**
 * Abstract extension adds ability to choose from a list of css classes by a nice name and provide them for
 * Template or as a CMS Form Field or Editable Grid Field column. Requires concrete instance of extension to be added.
 */
abstract class ArtisanHasCSSClassesExtension extends ArtisanModelExtension {

    const FieldName = '';
    const ConfigStaticName = '';

    private static $tab_name = 'Root.Main';

    private static $insert_field_before = 'Title';

    // add to cms form
    private static $add_to_form = false;

    // add to editable grid
    private static $add_to_grid = false;

/* add to concrete class:

    private static $css_classes = [];

    private static $db = [
        self::FieldName => 'Text'
    ];

    /**
     * Return classes for template, replaces ',' with ' '.
     *
     * @return string
     *
    public function <FieldName>() {
        return str_replace(',', ' ', $this()->{static::FieldName});
    }
*/
    public function updateCMSFields(FieldList $fields) {
        // gather extra classes from extensions.
        $fields->removeByName(static::FieldName);

        if (parent::get_config_setting('add_to_form')) {
            $classes = [];
            $this()->extend('provide' . static::FieldName, $classes);

            $fields->addFieldToTab(
                self::get_config_setting('tab_name'),
                new ListboxField(
                    static::FieldName,
                    $this->getFieldLabel(),
                    $classes,
                    null,
                    null,
                    true
                ),
                self::get_config_setting('insert_field_before')
            );
        }
    }
    /**
     * Provides any extra field definitions for the GridField inplace-editing, such as CSS Classes listbox. May be
     * disabled using config.add_to_grid in concrete class.
     *
     * @return array
     */
    public function provideEditableGridFields(array &$fields) {
        // in this case grid_fields should be a boolean true to add them
        if (parent::get_config_setting('add_to_grid')) {
            $classes = [];
            $this()->extend('provide' . static::FieldName, $classes);
            $fields = array_merge(
                $fields,
                [
                    static::FieldName => [
                        'title' => $this->getFieldLabel(),
                        function($record, $column, $grid) use ($classes) {
                            return new ListboxField(
                                static::FieldName,
                                $this->getFieldLabel(),
                                $classes,
                                $record->$column ?: [],
                                null,
                                true
                            );
                        }
                    ]
                ]
            );
        }
    }

    /**
     * Provide extra classes which can be set on the inner block (not container) level. May be
     * disabled using config.add_to_form in concrete class.
     *
     * @param array $classes - map of css-class => display-title suitable for us in a listbox/dropdown
     * @return void
     */
    protected function provideCSSClasses(array &$classes = []) {
        $classes = array_merge(
            parent::get_config_setting(static::FieldName),
            $classes
        );
    }

}