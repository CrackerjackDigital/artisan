<?php
/**
 * Base class for extensions to Models, i.e. a DataExtension
 */
abstract class ArtisanModelExtension extends DataExtension {
    // override in concrete class, declared here to stop getFieldLabel from dying horribly.
    const FieldName = 'sMissing';

    /**
     * Returns the extension's owner.
     * @return DataObject
     */
    public function __invoke() {
        return $this->owner;
    }

    /**
     * Check if the Artisan controls should show in the CMS form using config.add_to_form and if owner's class is not
     * in array config.exclude_from_class_names;
     *
     * @return bool
     */
    public function showOnCMSForm() {
        if (self::get_config_setting('add_to_form')) {
            if (!in_array($this()->class, self::get_config_setting('exclude_from_class_names') ?: [])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return wether or not to show the field in the editable grid using config.add_to_grid and if owner's class is not
     * in array config.exclude_from_class_names;
     *
     * @return bool
     */
    public function showOnGrid() {
        if (self::get_config_setting('add_to_grid')) {
            if (!in_array($this()->class, self::get_config_setting('exclude_from_class_names') ?: [])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the config for a typical inplace-editable gridfield.
     * @param $name
     * @param $title
     * @param $modelClass
     * @param SS_List $data
     * @param array $additionalComponents
     * @return GridFieldConfig
     */
    protected function makeEditableGridField($name, $title, $modelClass, SS_List $data, array $additionalComponents = []) {
        /** @var GridFieldConfig $config */
        $config = GridFieldConfig_RecordEditor::create(1000)
            ->removeComponentsByType('GridFieldDataColumns')
            ->addComponents(
                new GridFieldOrderableRows(ArtisanHasSortOrderExtension::FieldName),
                new GridFieldEditableColumns()
//                new GridFieldCopyButton() - TODO: doesn't copy properly and needs to be integrated with sort order
            );

        foreach ($additionalComponents as $component) {
            $config->addComponent($component);
        }

        $gridField = new GridField(
            $name,
            $title,
            $data,
            $config
        );
        $gridField->setModelClass($modelClass);

        /** @var GridFieldEditableColumns $editableColumns */
        $editableColumns = $gridField->getConfig()->getComponentByType('GridFieldEditableColumns');

        $model = singleton($modelClass);

        $fields = [];
        $model->extend('provideEditableGridFields', $fields);
        if ($fields) {
            $summaryFields = $model->summaryFields();
            $gridFields = array_merge(
                $summaryFields,
                $fields
            );

            $editableColumns->setDisplayFields(
                $gridFields
            );
        }

        return $gridField;
    }

    /**
     * In Artisan an extension generally provides only a single field in lang files, named
     * <extension-name>.Label . If not in lang file then return static.FieldName.
     *
     * @param $identifier - override 'Label' if we really have to.
     * @return string
     */
    public function getFieldLabel($identifier = 'Label') {
        $class = get_called_class();
        return _t($class . ".$identifier", static::FieldName);
    }

    /**
     * Return translated content (labels, text etc) for a given ContentType.
     *
     * @param $identifier
     * @param string $default
     * @param array $variables
     * @return string
     */
    public function getTranslationForContentType($identifier, $default = '', array $variables = []) {
        $note = array_reduce(
            $this()->extend('provideTranslationForContentType', $identifier, $default, $variables),
            function ($prev, $item) {
                return $item ?: $prev;
            }
        );
        return $note ?: $default;
    }
    /**
     * Return a setting from config.MosaicControllerInstance optionally returning the key if the setting is an array.
     *
     * NB: DataExtension doesn't inherit from Object so 'normal' config behaviour not available hence Config.inst().
     *
     * @param $varName
     * @param null $key
     * @return array|scalar|null
     */
    public static function get_config_setting($varName, $key = null) {
        $setting = Config::inst()->get(get_called_class(), $varName);
        if ($key && is_array($setting)) {
            return $setting[$key];
        }
        return $setting;
    }

}