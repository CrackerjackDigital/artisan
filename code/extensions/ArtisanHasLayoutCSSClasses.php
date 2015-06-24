<?php
/**
 * Add the ability to specify extra CSS classes on an Artisan Section or Block.
 *
 * Initial implementation is with CSSClasses a simple text field.
 */
class ArtisanHasLayoutCSSClassesExtension extends ArtisanHasCSSClassesExtension
    implements ProvidesLayoutCSSClasses {

    const FieldName = 'ArtisanLayoutCSSClasses';

    private static $css_classes = [];

    // add control to form in CMS
    private static $add_to_form = true;

    // add control to editable grid field
    private static $add_to_grid = true;

    private static $db = [
        self::FieldName => 'Text'
    ];

    /**
     * Return classes for template, replace ',' with ' '.
     *
     * @return string
     */
    public function LayoutCSSClasses() {
        return str_replace(',', ' ', $this()->{self::FieldName});
    }


    /**
     * Provide extra classes which can be set on the outer block level.
     * @param array $classes - map of css-class => display-title suitable for us in a listbox/dropdown
     * @return void
     */
    public function provideLayoutCSSClasses(array &$classes = []) {
        parent::provideCSSClasses($classes);
    }

}