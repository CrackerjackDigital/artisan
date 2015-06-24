<?php
/**
 * Provides information about content types using the SilverStripe configuration mechanism/config files.
 */
class ArtisanStaticContentTypeMetaDataProvider extends Object
    implements ProvidesContentTypeMetaData
{
    /**
     * This provider returns content type from config array specified as per defaults below.
     *
     * @ref ProvidesContentTypeMetaData interface.
     *
     */
    private static $content_types = [
/* These are in config.yml, here for example php structure
        'ArtisanFormBlock' => [
            'Title' => 'Form'
        ],
        'ArtisanContentBlock' => [
            'Title' => 'Content',
            'FormFields' => [
                'Content' => true
            ],
        ],
        'ArtisanFotoramaBlock' => [
            'Title' => 'Image Gallery',
            'FormFields' => [
                'ArtisanImages' => [false, 'Images']
            ]
        ],
        'ArtisanImageCompareBlock' => [
            'Title' => 'Image Compare',
            'FormFields' => [
                'ArtisanImages' => [false, 'Image Comparison']
            ]
        ],
        'ArtisanCodeBlock' => [
            'Title' => 'HTML',
            'FormFields' => [
                'Code' => [true, 'HTML Content', 'TextareaField']
            ],
        ]
*/
    ];
    // add content types to exclude here in config.yml, this is because SS config is only merge operation, not set.
    private static $excluded_content_types = [
        // e.g. 'ArtisanFormBlock',
    ];

    // always show these fields
    private static $skip_field_names = [];

    /**
     * Returns config.content_type diff content.excluded_content_types.
     *
     * @ref ProvidesContentTypeMetaData interface
     *
     * @return array
     */
    public function getMetaData() {
        return array_diff_key(
            $this->config()->get('content_types'),
            array_flip($this->config()->get('excluded_content_types'))
        );
    }

    /**
     * @return array - map of FieldName => FieldName
     */
    public function getDefaultFieldNames() {
        return $this->config()->get('skip_field_names');
    }

    /**
     * Return a map of config.content_type template-name => title for use in e.g. dropdown or listbox.
     *
     * @return array
     */
    public function getOptionMap() {
        $metaData = $this->getMetaData();
        $optionMap = [];
        array_map(
            function($metaDataItem, $templateName) use (&$optionMap) {
                $optionMap[$templateName] = $metaDataItem['Title'];
            },
            $metaData,
            array_keys($metaData)
        );
        return $optionMap;
    }

    /**
     * @ref ProvidesContentTypeMetaData interface
     *
     * @param boolean|array $spec
     * @param null|string $label
     * @param null|string $fieldType
     * @return array - [requiredness, label, field-type]
     * @throws Exception on any problems decoding the spec
     */
    public static function decodeFieldSpec($spec, $label = null, $fieldType = null) {
        if (is_array($spec)) {
            switch (count($spec)) {
                case 2:
                    $result = [
                        $spec[0],
                        is_null($spec[1]) ? $label : $spec[1],
                        $fieldType
                    ];
                    break;
                case 3:
                    $result = [
                        $spec[0],
                        is_null($spec[1]) ? $label : $spec[1],
                        is_null($spec[2]) ? $fieldType : $spec[2]
                    ];
                    break;
                default:
                    throw new Exception("Spec has too few or too many components (" . count($spec) . ")");
            }
        } else if (is_bool($spec)) {
            $result = [$spec, $label, $fieldType];
        } else {
            throw new Exception("Spec is not an array or boolean");
        }
        return $result;
    }
}