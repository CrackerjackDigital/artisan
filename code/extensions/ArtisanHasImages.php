<?php

/**
 * Adds Images to an Artisan block.
 */
class ArtisanHasImagesExtension extends ArtisanModelExtension {
    const FieldName = 'ArtisanImages';

	private static $has_many = [
		self::FieldName => 'Image',
	];

    private static $add_to_form = true;
    private static $add_to_grid = false;

    // default tab image field shows in CMS. An alternate can be set on the extended model using images_tab_name.
    private static $tab_name = 'Root.Main';

	/**
	 * Hook for templates to call into this extension, returns the ArtisanImages collection.
	 * @return mixed
	 */
	public function ArtisanHasImages() {
		return $this()->{self::FieldName}();
	}

	public function ArtisanNumImages() {
		return $this()->{self::FieldName}()->count();
	}

	/**
	 * Add a tab with an upload field to the owner.config.images_tab_name or self.config.tab_name tab.
	 *
	 * @param FieldList $fields
	 */
	public function updateCMSFields(FieldList $fields) {
        $fields->removeByName(self::FieldName);

        if ($this->showOnCMSForm()) {
            // tab name can be set on the owner config.images_tab_name or use the extensions config.tab_name
            $tabName = $this()->config()->get('images_tab_name') ?: parent::get_config_setting('tab_name');

            $fields->addFieldToTab(
                $tabName,
                $field = new DisplayLogicWrapper(new SelectUploadField(
                    self::FieldName,
                    $this->getFieldLabel(),
                    $this()->{self::FieldName}()
                ))
            );
            if ($label = $this()->getTranslationForContentType('ImagesLabel')) {
                $field->setTitle($label);
            }
            if ($note = $this()->getTranslationForContentType('ImagesNote')) {
                $field->setRightTitle($note);
            }
        }
	}
}