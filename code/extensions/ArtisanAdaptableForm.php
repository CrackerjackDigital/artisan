<?php

/**
 * Uses configuration information from the injector.AdaptableFormMetaDataProvider to
 * configure field to show/hide depending on the key for the field definitions matching the
 * selector identified by config.selector_field_name
 *
 */
class ArtisanAdaptableFormExtension extends ArtisanModelExtension {
    private static $selector_field_name = 'ContentType';

    private static $hidden_css_class = 'display-logic-hidden';

    /**
     * This should be updateCMSFields and configured to be called last but configuration
     * priority does not seem to work to force this to be the last loaded/called extension.
     *
     * For now will call via extend.manualUpdate with the field list in ArtisanModel.getCMSFields
     *
     * @param FieldList $fields
     */
    public function manualUpdateCMSFields(FieldList $fields) {
//        $formFields = $fields->dataFields();
        $formFields = $fields->VisibleFields();

        $selectorFieldName = parent::get_config_setting('selector_field_name');
        $hiddenCSSClass = parent::get_config_setting('hidden_css_class');

        $provider = Injector::inst()->get('AdaptableFormMetaDataProvider');

        $defaultFieldNames = $provider->getDefaultFieldNames();

        // keep track of fields we have encountered in the spec, other fields will be hidden,
        // we can initialise this to defaultFieldNames as we don't want to hide these ever
        $handled = array_combine($defaultFieldNames, $defaultFieldNames);

        // we want to get the form so we can remove fields later
        $form = null;

        $metaData = $provider->getMetaData();

        // the master list of content types by field name
        $this->contentTypes = [];

        foreach ($metaData as $contentType => $fieldSpec) {
            if (isset($fieldSpec['FormFields'])) {
                $fieldDefinitions = $fieldSpec['FormFields'];

                $specFields = array_merge(
                    $fieldDefinitions,
                    $defaultFieldNames
                );

                $this->processFieldList($formFields, $contentType, $selectorFieldName, $specFields, $defaultFieldNames, $handled);

            }
        }
        $this->hideUnhandled($formFields, $handled, $hiddenCSSClass);
    }
    private function processFieldList(FieldList $fields, $contentType, $selectorFieldName, array $specFields, array $skipFields, array &$handled = []) {
        /** @var FormField $formField */
        foreach ($fields as $formField) {
            $formFieldName = ($formField instanceof DisplayLogicWrapper)
                ? $formField->children[0]->getName()
                : $formField->getName();

            if (in_array($formFieldName, $skipFields)) {
                // skip this field and don't hide it later
                $handled[$formFieldName] = $formFieldName;

            } else {
                if ($formField->isComposite() && !$formField instanceof DisplayLogicWrapper) {
                    $handled[$formFieldName] = $formFieldName;

                    $this->processFieldList($formField->children, $contentType, $selectorFieldName, $specFields, $skipFields, $handled);

                } else {

                    if ($formFieldName !== $selectorFieldName) {
                        if (array_key_exists($formFieldName, $specFields)) {

                            $this->contentTypes[$formFieldName][] = $contentType;

                            $formField->hideUnless(
                                $selectorFieldName
                            )->orIf()->contains(
                                implode('|', $this->contentTypes[$formFieldName])
                            )->end();

                            $handled[$formFieldName] = $formFieldName;
                        }
                    }
                }
            }
        }
    }

    /**
     * Hide all fields which haven't been hooked by display logic during processFieldList
     * calls.
     *
     * @param FieldList $fields
     * @param array $handled
     * @param $hiddenCSSClass
     */
    public function hideUnhandled(FieldList $fields, array $handled, $hiddenCSSClass) {
        // remove all form fields which weren't handled above so aren't in any field spec or default fields
        /** @var FormField $formField */
        foreach ($fields as $formField) {
            $formFieldName = ($formField instanceof DisplayLogicWrapper)
                ? $formField->children[0]->getName()
                : $formField->getName();

            if ($formField->isComposite() && !$formField instanceof DisplayLogicWrapper) {

                $this->hideUnhandled($formField->children, $handled, $hiddenCSSClass);

            } else {
                if (!in_array($formFieldName, $handled)) {
                    $formField->addExtraClass($hiddenCSSClass);
                }
            }
        }
    }

}