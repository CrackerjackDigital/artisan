<?php
/**
 * Adds sections to model.
 *
 */
class ArtisanHasSectionsExtension extends ArtisanModelExtension {
    const FieldName = 'Sections';
    const SectionModelClass = 'ArtisanSection';

    private static $tab_name = 'Root.PageRows';

    private static $add_to_form = true;

    private static $add_to_grid = false;

    private static $show_use_sections_from_other_page = false;

    private static $has_one = [
        'UseSectionsFromOtherPage' => 'SiteTree'
    ];

    private static $has_many = [
        self::FieldName => self::SectionModelClass
    ];

    public function ArtisanHasSections() {
        if ($this()->UseSectionsFromOtherPageID) {
            if ($otherPage = SiteTree::get()->byID($this()->UseSectionsFromOtherPageID)) {
                return $otherPage->ArtisanHasSections()->Sort(ArtisanHasSortOrderExtension::FieldName);
            }
        }
        return $this()->{self::FieldName}()->Sort(ArtisanHasSortOrderExtension::FieldName);
    }

    public function updateCMSFields(FieldList $fields) {
        if ($this->showOnCMSForm()) {
            $fields->removeByName('Content');

            $gridField = $this->makeEditableGridField(
                self::FieldName,
                $this->getFieldLabel(),
                self::SectionModelClass,
                $this()->Sections()
            );
            $fields->addFieldToTab(
                self::get_config_setting('tab_name'),
                $gridField
            );
            if (self::get_config_setting('show_use_sections_from_other_page')) {

                $fields->addFieldToTab(
                    self::get_config_setting('tab_name'),
                    new TreeDropdownField(
                        'UseSectionsFromOtherPageID',
                        $this->getFieldLabel(),
                        'SiteTree'
                    )
                );
            }
        }
    }

}