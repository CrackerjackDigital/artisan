<?php
/**
 * Adds a 'Sort' column which will be used e.g. by GridFieldOrderableRows
 */
class ArtisanHasSortOrderExtension extends ArtisanModelExtension {
    const FieldName = 'ArtisanSort';

    private static $db = [
        self::FieldName => 'Int'
    ];

    public function onBeforeWrite() {
        $maxExistingSort = DataObject::get(
            $this()->class
        )->max(self::FieldName);

        if (!$this()->{self::FieldName}) {
            $this()->{self::FieldName} = $maxExistingSort + 1;
        }
        parent::onBeforeWrite();
    }

    /**
     * Remove the sort order field from the CMS form.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields) {
        $fields->removeByName(ArtisanHasSortOrderExtension::FieldName);
    }
}