<?php

/**
 * Interface for classes which provide meta-data about a Content-Type template, either statically, from database or
 * some other source in an implementation neutral manner as a map.
 */
interface ProvidesContentTypeMetaData {
    /**
     * @return array with structure:
     *  array(
     *      'template-name' => array(
     *          'Title' => title,
     *          'FormFields' => array(
     *              'field-name' => field-spec
     *          )
     *      ),
     * )
     * template-name    = string: name of physical template file excluding extension e.g. ArtisanContentBlock
     * title            = string: title to show in selector e.g. 'Content' for ArtisanContentBlock
     * field-spec       = requiredness | field-defn [,]
     * requiredness     = boolean: true required, false optional
     * field-defn       = array: requiredness [, field-label [, field-type [, options] ] ]
     * field-label      = string: label for form field
     * field-type       = string: FormField derived class name
     * options          = mixed: any extra options which are needed, variant on field-type
     */
    public function getMetaData();

    /**
     * Return a map of template-name => title for use in e.g. dropdown or listbox
     * @return array
     */
    public function getOptionMap();

    /**
     * Decode a field-spec into an array which always has three values. Provided parameters are used if the spec
     * does not have that value in it, or if the spec's value is null.
     *
     * - for a boolean spec then $label and $fieldType parameters will be used (may be null though),
     * - for a spec with requiredness, field-label then the specs requiredness and field-label will be used with
     *   the passed $fieldType. If the spec has a null field-label then the passed in label will be used
     * - for a spec with requiredness, field-label and field-type then the spec's field-label and field-type
     *   will be used in preference to the parameters $label and $fieldType. If the spec's field-label or field-type
     *   are null then the passed in parameters will be used.
     * - if a value is null in both spec and paramter then null will be in the returned array
     * - an empty string will result in an empty string in the output (usefull for label, probably die if field type)
     *
     * Usage: list($requiredness, $label, $type) = self::decodeFieldSpec(field-spec, 'The label', 'TextareaField')
     *
     * @param boolean|array $spec - field-spec as per config.content_types
     * @param null|string $label - optional label e.g from $field->getTitle() of existing field
     * @param null|string $fieldType - optional FormField derived class name e.g. 'TextareaField'
     * @return array with structure:
     * array(
     *      requiredness,
     *      label,
     *      field-type
     * )
     */
    public static function decodeFieldSpec($spec, $label = null, $fieldType = null);
}