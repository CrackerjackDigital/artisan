<?php

/**
 * DataObject representing a section on a page.
 */
class ArtisanSection extends ArtisanModel {
    const PageLinkName = 'Page';

    private static $singular_name = 'Page Row';
    private static $plural_name = 'Page Rows';

    private static $db = [
        'Title' => 'Varchar(32)'
    ];
    private static $has_one = [
        self::PageLinkName => 'SiteTree'
    ];
    private static $summary_fields = [
        'Title' => 'Title'
    ];

}