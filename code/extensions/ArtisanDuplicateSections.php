<?php

/**
 * Add to models to allow the 'duplicate page and children' to also duplicate rows/blocks etc.
 */
class ArtisanDuplicateSectionsExtension extends ArtisanModelExtension {
    /**
     * @param Page $oldPage
     * @param Page $newPage
     * @param bool|false $doWrite - set to false to debug and not write records, true otherwise
     * @throws ValidationException
     */
    public function doArtisanDuplicateSections(Page $oldPage, Page $newPage, $doWrite = true) {
        // if not write then don't do anything.
        if ($doWrite && $newPage->hasExtension('ArtisanHasSectionsExtension')) {
            $sections = $oldPage->ArtisanHasSections();

            $relationshipIDField = ArtisanSection::RelationshipName . 'ID';

            /** @var ArtisanSection $oldSection */
            foreach ($sections as $oldSection) {

                $newSection = new ArtisanSection($oldSection->toMap(), false, $this()->getModel());
                $newSection->ID = 0;
                $newSection->$relationshipIDField = $newPage->ID;
                $newSection->write();

                $newSection->extend('doArtisanDuplicateBlocks', $oldSection, $newSection, $doWrite);
                $newSection->extend('doArtisanDuplicateFields', $oldSection, $newSection, $doWrite);

            }
        }
    }
}