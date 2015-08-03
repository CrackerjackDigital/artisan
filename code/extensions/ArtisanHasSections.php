<?php
/**
 * Adds sections to model e.g. Page.
 *
 */
class ArtisanHasSectionsExtension extends ArtisanModelExtension {
    const RelationshipName = 'Sections';
    const RelatedModelClass = 'ArtisanSection';

    private static $tab_name = 'Root.PageRows';

    private static $add_to_form = true;

    private static $add_to_grid = false;

    private static $has_many = [
        self::RelationshipName => self::RelatedModelClass
    ];

    public function ArtisanHasSections() {
        return $this()->{self::RelationshipName}()->Sort(ArtisanHasSortOrderExtension::FieldName);
    }

    public function updateCMSFields(FieldList $fields) {
        // remove if already scaffolded so we can add as editable grid field
        $fields->removeByName(self::RelationshipName);

        if ($this->showOnCMSForm()) {
            // hide the content field if we have sections.
            $fields->replaceField('Content', new HiddenField('Content'));

            $gridField = $this->makeEditableGridField(
                self::RelationshipName,
                $this->getFieldLabel(),
                self::RelatedModelClass,
                $this->ArtisanHasSections()
            );
            $fields->addFieldToTab(
                self::get_config_setting('tab_name'),
                $gridField
            );
        }
    }

}