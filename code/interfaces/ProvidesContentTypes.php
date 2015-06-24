<?php

/**
 * Interface for classes which provide names of templates which can be used for rendering content.
 */
interface ProvidesContentTypes {
    /**
     * Adds available content types to the array as a map of template-name => display-name
     *
     * Generally works with a ContentTypeMetaDataProvider which returns available content types and metadata in an
     * implementation agnostic way.
     *
     * @param array $contentTypes
     * @return null
     */
    public function provideContentTypes(array &$contentTypes);


}