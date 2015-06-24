<?php
interface ProvidesPresentationCSSClasses {
    /**
     * Provide css classes used on the inner block level (e.g article) inside the container layout element.
     *
     * @param array $classes
     * @return mixed
     */
    public function providePresentationCSSClasses(array &$classes = []);

}