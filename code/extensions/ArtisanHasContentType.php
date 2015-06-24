<?php
/**
 * Adds the ability to choose a template to use to render a block.
 *
 * Initial implementation is hard-coded.
 */

class ArtisanHasContentTypeExtension extends ArtisanModelExtension
    implements ProvidesEditableGridFields, ProvidesContentTypes {

    const FieldName = 'ContentType';

    private static $tab_name = 'Root.Main';

    private static $insert_field_before = 'Title';

	private static $db = [
		self::FieldName => "Varchar(32)",
	];
	private static $summary_fields = [
		self::FieldName => 'Content Type',
	];
    /**
     * Replace the existing ContentType field with a drop-down list of templates
     * derived from extend.provideAvailableTemplates.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields) {
        $fields->removeByName(self::FieldName);

        $contentTypes = [];
        $this()->extend('provideContentTypes', $contentTypes);

		$fields->addFieldToTab(
			parent::get_config_setting('tab_name'),
            $this->makeDropdownField($contentTypes, $this()->{self::FieldName}),
			parent::get_config_setting('insert_field_before')
		);
	}

    /**
     * Lang files can be used to store hard-coded content in <ContentType>.<Identifier> syntax.
     *
     * e.g. ArtisanContentBlock.Note
     *
     * @param string $identifier - the bit after the '.' in the lang file.
     * @param string $default - what to return if not found
     * @param array $variables - optional placeholder replacement values
     * @return string
     */
    public function provideTranslationForContentType($identifier, $default = '', array $variables = []) {
        return _t($this()->ContentType . ".$identifier", $default, $variables);
    }

    /**
     * Provide grid fields for EditableGridFields extension as an array.
     */
    public function provideEditableGridFields(array &$fields) {
        $contentTypes = [];
        $this()->extend('provideContentTypes', $contentTypes);

        $fields = array_merge(
            $fields,
            [
                self::FieldName => [
                    'title' => $this->getFieldLabel(),
                    'callback' => function($record, $column, $grid) use ($contentTypes) {
                        return $this->makeDropdownField($contentTypes, $record->$column);
                    }
                ]
            ]
        );
    }

    /**
     * Return a dropdown field with options configured from config.allowed_content_types diff config.disallowed_content_types
     * @param $options - what options to show in dropdown
     * @param $values - selected values
     * @return DropdownField
     */
    private function makeDropdownField($options, $values) {
        return new DropdownField(
            static::FieldName,
            $this->getFieldLabel(),
            $options,
            $values
        );
    }

	/**
	 * Return the extended object rendered with the ContentType field value template.
	 * @return HTMLText
	 */
	public function provideForTemplate() {
		return $this()->renderWith($this()->ContentType);
	}

    /**
     * Adds content types from the configured injector.ContentTypeMetaDataProvider to the $contentType array.
     * NB: this resets the reference passed array.
     *
     * @param array $contentTypes - content types added to here
     * @return null|void
     */
    public function provideContentTypes(array &$contentTypes) {
        /** @var ProvidesContentTypeMetaData $provider */
        $provider = Injector::inst()->get('ContentTypeMetaDataProvider');
        $contentTypes = array_merge(
            $contentTypes,
            $provider->getOptionMap()
        );
    }
}