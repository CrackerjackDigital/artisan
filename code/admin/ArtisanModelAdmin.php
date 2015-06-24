<?php
/**
 * Raw ModelAdmin interface to Artisan.
 */

class ArtisanModelAdmin extends ModelAdmin {
    private static $managed_models = [
        'ArtisanSection'
    ];
    private static $menu_title = 'Page Builder';
    private static $url_segment = 'page-builder';


    /**
     * Add GridFieldOrderableRows component to grid field keyed on ArtisanHasSortOrderExtension.FieldName (ArtisanSort).
     *
     * @param int|null $id
     * @param FieldList|null $fields
     * @return Form
     */
    public function getEditForm($id = null, $fields = null) {
        $form = parent::getEditForm($id, $fields);
        $gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
        $gridField->getConfig()->addComponent(new GridFieldOrderableRows(ArtisanHasSortOrderExtension::FieldName));
        return $form;
    }


}