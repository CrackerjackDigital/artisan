<?php
/**
 * Adds blocks to an Artisan Section.
 *
 * Initial implementation is 4 static blocks, Block1, Block2, Block3, Block4 ltr across pages.
 */
class ArtisanHasBlocksExtension extends ArtisanModelExtension {
    const FieldName = 'Blocks';

    private static $tab_name = 'Root.Main';

    private static $add_to_form = true;

    private static $add_to_grid = false;

	private static $has_many = [
		'Blocks' => 'ArtisanBlock',
	];

	public function updateCMSFields(FieldList $fields) {
        $fields->removeByName('Blocks');

		$gridField = $this->makeEditableGridField(
			self::FieldName,
			$this->getFieldLabel(),
			'ArtisanBlock',
			$this()->Blocks()
		);

		$fields->addFieldToTab(
			parent::get_config_setting('tab_name'),
			$gridField
		);
	}
}