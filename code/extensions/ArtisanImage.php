<?php

/**
 * Artisan image functionality and back-links to Image from model with ArtisanHasImages extension.
 *
 * Added to SilverStripe Image class.
 */
class ArtisanImageExtension extends ArtisanModelExtension {
    const PageModelClassName = 'Page';

    private static $has_one = [
        'Page' => self::PageModelClassName,
        'ArtisanBlock' => 'ArtisanBlock'
    ];
}