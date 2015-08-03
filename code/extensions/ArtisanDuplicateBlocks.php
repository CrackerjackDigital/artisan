<?php

class ArtisanDuplicateBlocksExtension extends DataExtension {
    public function doArtisanDuplicateBlocks(ArtisanSection $oldSection, ArtisanSection $newSection, $doWrite = true) {
        if ($doWrite && $newSection->hasExtension('ArtisanHasBlocksExtension')) {
            $blocks = $oldSection->ArtisanHasBlocks();

            $relationshipIDField = ArtisanBlock::RelationshipName . 'ID';

            /** @var ArtisanBlock $block */
            foreach ($blocks as $oldBlock) {
                $newBlock = new ArtisanBlock($oldBlock->toMap(), false, $oldBlock->getModel());
                $newBlock->ID = 0;
                $newBlock->$relationshipIDField = $newSection->ID;
                $newBlock->write();

                $newBlock->extend('doArtisanDuplicateFields', $oldBlock, $newBlock, $doWrite);
            }
        }

    }
}