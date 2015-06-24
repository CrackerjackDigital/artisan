<?php

/**
 * Base class for Artisan Model classes.
 */
class ArtisanModel extends DataObject {
    const AdminGroupCode = 'administrators';

    private static $admin_group_code = self::AdminGroupCode;

    /**
     * Keep access orthogonal across models and model extensions.
     * @return $this
     */
    public function __invoke() {
        return $this;
    }

    /**
     * Get fields and then call extend.manualUpdateCMSFields to do display_logic filtering
     * as currently priorities in SilverStripe configuration don't seem to be working so
     * can't force ArtisanAdaptableFormExtension to load/have updateCMSFields called after
     * all other extensions.
     *
     * @return FieldList
     */
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $this->extend('manualUpdateCMSFields', $fields);
        return $fields;
    }

    /**
     * Can create if in 'Content Managers' group.
     * @param null $member
     * @return bool|void
     */
    public function canCreate($member = null) {
        return $this->checkPermissions($member);
    }

    /**
     * Can edit if in 'Content Managers' group.
     * @param null $member
     * @return bool|void
     */
    public function canEdit($member = null) {
        return $this->checkPermissions($member);
    }
    /**
     * Can delete if in 'Content Managers' group.
     * @param null $member
     * @return bool|void
     */
    public function canDelete($member = null) {
        return $this->checkPermissions($member);
    }

    /**
     * Can always view Sections and Blocks (e.g. if not logged in).
     *
     * @param null $member
     * @return bool
     */
    public function canView($member = null) {
        return true;
    }

    /**
     * Checks if member is in 'Content Managers' group or is an administrator.
     *
     * @param $member
     * @return boolean - true if in one or more groups, false otherwise
     */
    protected function checkPermissions($member) {
        // get member by passed ID, passed Member object or the current logged-in user.
        $member = $member
            ? (($member instanceof Member)
                ? $member
                : Member::get()->byID($member)
            )
            : Member::currentUser();

        $groupCode = HomeTechPermissionsConfig::config()->group_code;

        $inGroup = $member->inGroups([
            $this->config()->admin_group_code,
            $groupCode
        ]);

        return $inGroup;
    }

}