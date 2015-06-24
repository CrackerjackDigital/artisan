<?php

interface ProvidesLayoutCSSClasses {
    /**
     * Extends hook to provide available 'layout' css classes to CMS
     * @param array $classes
     * @return mixed
     */
    public function provideLayoutCSSClasses(array &$classes = []);

}