<?php
/**
 * Add the ability to specify extra CSS classes on an Artisan Section or Block.
 *
 * Initial implementation is with CSSClasses a simple text field.
 */
class ArtisanHasPresentationCSSClassesExtension extends ArtisanHasCSSClassesExtension
    implements ProvidesPresentationCSSClasses {

    const FieldName = 'ArtisanPresentationCSSClasses';

    private static $css_classes = [];

    // add control to form in CMS
    private static $add_to_form = true;

    // add to grid field
    private static $add_to_grid = false;

    private static $db = [
        self::FieldName => 'Text'
    ];

    /**
     * Return classes for template, replace ',' with ' '.
     *
     * @return string
     */
    public function PresentationCSSClasses() {
        return str_replace(',', ' ', $this()->{self::FieldName});
    }


    /**
     * Provide extra classes from config.extra_css_classes which can be set on the inner block (not container) level.
     * @param array $classes - map of css-class => display-title suitable for us in a listbox/dropdown
     * @return void
     */
    public function providePresentationCSSClasses(array &$classes = []) {
        parent::provideCSSClasses($classes);
    }

}