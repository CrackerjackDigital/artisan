<?php

/**
 * Extension provides Unsematic css specific functionality to controllers such as
 * adding requiremetns.
 *
 */
class ArtisanUnsemanticExtension extends ArtisanPageControllerExtension {
    private static $requirements = [
        self::BeforeInit => [

        ],
        self::AfterInit => [

        ],
    ];
}