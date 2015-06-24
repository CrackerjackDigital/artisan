<?php
/**
 * Represents a block in an Artisan Section. Extend
 */
class ArtisanBlock extends ArtisanModel {

	private static $has_one = [
		'Section' => 'ArtisanSection',
	];
	private static $summary_fields = [
	];

    private static $singular_name = 'Block';

    private static $plural_name = 'Blocks';

    private static $tab_name = 'Root.Main';

	/**
	 * Ask extensions to do the rendering for us, e.g. HasTemplate will render the correct template depending on
	 * it's ContentType provided field.
	 * @return mixed
	 */
	public function forTemplate() {
		return array_reduce(
			$this->extend('provideForTemplate'),
			function ($prev, $item) {
				return $prev ?: $item;
			}
		);
	}

    /**
     * Remove the SectionID dropdown as this is set from the parent.
     * @param FieldList $fields
     */
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('SectionID', true);
        return $fields;
    }

}