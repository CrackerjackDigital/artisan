<?php
/**
 * Adds blocks to an Artisan Section.
 *
 * Initial implementation is 4 static blocks, Block1, Block2, Block3, Block4 ltr across pages.
 */
class ArtisanHasBlocksExtension extends ArtisanModelExtension {
    const RelationshipName = 'Blocks';
	const RelatedModelClass = 'ArtisanBlock';

    private static $tab_name = 'Root.Main';

    private static $add_to_form = true;

    private static $add_to_grid = false;

	private static $has_many = [
		self::RelationshipName => self::RelatedModelClass
	];

	public function ArtisanHasBlocks() {
		return $this()->{self::RelationshipName}();
	}

	public function updateCMSFields(FieldList $fields) {
		// remove if already scaffolded so we can add as editable grid field
        $fields->removeByName(self::RelationshipName);

		if ($this->showOnCMSForm()) {
			$gridField = $this->makeEditableGridField(
				self::FieldName,
				$this->getFieldLabel(),
				self::RelatedModelClass,
				$this->ArtisanHasBlocks()
			);

			$fields->addFieldToTab(
				parent::get_config_setting('tab_name'),
				$gridField
			);
		}
	}
}