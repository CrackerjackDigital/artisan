<?php

/**
 * Extensions which adds the ability to set the width of an element. If ArtisanBlock is extended then
 * each Block can have a width, if ArtisanSection is extended then Section can be were widths
 * are specified (in template Block you can use e.g. Section.ArtisanWidth).
 */
class ArtisanHasWidthExtension extends ArtisanModelExtension
    implements ProvidesEditableGridFields
{
    const FieldName = 'ArtisanWidth';

    private static $insert_before_field = 'Title';

    private static $db = [
        self::FieldName => 'Int'
    ];

    private static $widths = [
        // set in config.yml
    ];

    // on which tab in CMS the field will be added
    private static $tab_name = 'Root.Main';

    // add to CMS edit form
    private static $add_to_form = false;

    // add to Grid as an editable column
    private static $add_to_grid = true;

    /**
     * Add a width selection listbox.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields) {
        $fields->removeByName(self::FieldName);

        if (parent::get_config_setting('add_to_form')) {
            $fields->addFieldToTab(
                parent::get_config_setting('tab_name'),
                $this->makeListboxField($this()->{self::FieldName}),
                parent::get_config_setting('insert_before_field')
            );
        }
    }

    /**
     * Return a listbox with the record column value.
     *
     * @return array
     */
    public function provideEditableGridFields(array &$fields) {
        if (parent::get_config_setting('add_to_grid')) {
            $fields = array_merge(
                $fields,
                [
                    self::FieldName => [
                        'title' => $this->getFieldLabel(),
                        'callback' => function($record, $column, GridField $grid) {
                            $config = $grid->getConfig();
                            $config->getComponentByType('GridField');
                            return $this->makeListboxField($record->$column);
                        }
                    ]
                ]
            );
        }
    }
    protected function makeListboxField($value) {
        $field = new ListboxField(
            self::FieldName,
            $this->getFieldLabel(),
            array_flip(Config::inst()->get('ArtisanHasWidthExtension', 'widths')),
            $value
        );
        if ($note = $this->getFieldLabel('Note')) {
            $field->setRightTitle($note);
        }
        return $field;
    }
}