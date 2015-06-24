<?php

class ArtisanHasBlockLinkExtension extends ArtisanModelExtension {
    const FieldName = 'BlockLinkID';

    private static $has_one = [
        'BlockLink' => 'SiteTree'
    ];

    private static $tab_name = 'Root.Main';

    private static $insert_field_before = 'ArtisanImages';

    private static $add_to_form = true;

    private static $add_to_grid = false;

    public function updateCMSFields(FieldList $fields) {
        $fields->removeByName(self::FieldName);

        if ($this->showOnCMSForm('add_to_form')) {
            $fields->addFieldToTab(
                self::get_config_setting('tab_name'),
                new DisplayLogicWrapper(
                    new TreeDropdownField(
                        self::FieldName,
                        _t("ArtisanHasBlockLinkExtension.Label", 'Link block to page'),
                        'SiteTree'
                    )
                )
            );
        }
    }

}