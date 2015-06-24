<?php

/**
 * Add permissions required by Artisan to the DB on dev/build if they don't already exist. These permissions are not
 * linked to groups/members, that is left up to app build.
 */
class ArtisanPermissions extends DataObject {
    // we want to be able to refer to this easily from outside when creating app-specific permissions/groups.
    const AdminPermissionCode = 'ARTISAN_Admin';

    private static $permissions = [
        self::AdminPermissionCode => [
            'Arg' => 0,
            'Type' => 1
        ]
    ];

    /**
     * We don't need a table, this is just a build thing which shouldn't have to be called as task.
     */
    public function requireTable() {
        DB::dontRequireTable('ArtisanPermissions');
    }

    /**
     * Add permissions from config.permissions if they don't exist. If they do exist will not update.
     */
    public function requireDefaultRecords() {
        foreach (self::config()->permissions as $code => $fields) {
            $permission = Permission::get()->filter([
                'Code' => $code
            ])->first();

            if (!$permission) {
                $permission = Permission::create(array_merge(
                    $fields,
                    [
                        'Code' => $code
                    ]
                ));
                $permission->write();
                DB::alteration_message("Added permission '$code'", 'changed');
            } else {
                DB::alteration_message("Unchanged permission '$code'", 'unchanged');
            }
        }
    }
}