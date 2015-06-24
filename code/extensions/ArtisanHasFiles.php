<?php

/**
 * Extension which provides linking of uploadable files to another object in a many_many relationship.
 *
 */
class ArtisanHasFilesExtension extends ArtisanModelExtension {
    const RelationshipName = 'ArtisanFiles';
    const FileModelClass = 'File';

    private static $many_many = [
        self::RelationshipName => self::FileModelClass
    ];

    private static $tab_name = 'Root.Main';

    private static $add_to_form = true;

    private static $add_to_grid = false;

    public function ArtisanHasFiles() {
        return $this()->{self::RelationshipName}();
    }

    public function updateCMSFields(FieldList $fields) {
        $fields->removeByName(self::RelationshipName);

        if ($this->showOnCMSForm()) {
            $fields->addFieldToTab(
                self::get_config_setting('tab_name'),
                new DisplayLogicWrapper(
                    new SelectUploadField(
                        self::RelationshipName,
                        $this->getFieldLabel(),
                        $this->ArtisanHasFiles()
                    )
                )
            );
        }
    }
}