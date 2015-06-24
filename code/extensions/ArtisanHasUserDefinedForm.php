<?php
class ArtisanHasUserDefinedFormExtension extends ArtisanModelExtension {
    public function ArtisanForm() {
        return Controller::curr()->Form();
    }
}