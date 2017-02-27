<?php

use Illuminate\Database\Seeder;

class InitPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tier_1 = \App\Models\Role::where('name', 'tier_1')->first();
        $tier_2 = \App\Models\Role::where('name', 'tier_2')->first();
        $tier_3 = \App\Models\Role::where('name', 'tier_3')->first();
        $tier_4 = \App\Models\Role::where('name', 'tier_4')->first();
        $client = \App\Models\Role::where('name', 'client')->first();
        $unlimited_client = \App\Models\Role::where('name', 'unlimited_client')->first();

        $this->__userManagementPermissions([$tier_1]);
        $this->__categoryPermission([$tier_1, $tier_2, $tier_3, $tier_4, $client, $unlimited_client]);
        $this->__productPermission([$tier_1, $tier_2, $tier_3, $tier_4, $client, $unlimited_client]);
        $this->__sitePermission([$tier_1, $tier_2, $tier_3, $tier_4, $client, $unlimited_client]);
    }

    /**
     * User, Role, Permission, Group CRUD
     * @param array $roles
     */
    private function __userManagementPermissions(Array $roles = [])
    {
        $this->__userManagementUserPermission($roles);
        $this->__userManagementRolePermission($roles);
        $this->__userManagementPermissionPermission($roles);
        $this->__userManagementGroupPermission($roles);
    }

    /**
     * User permission
     * @param array $roles
     */
    private function __userManagementUserPermission(Array $roles = [])
    {
        $manageUser = $this->__createPermission([
            "name" => "manage_user",
            "display_name" => "Manage User",
        ]);
        $createUser = $this->__createPermission([
            "name" => "create_user",
            "display_name" => "Create User",
        ]);
        $readUser = $this->__createPermission([
            "name" => "read_user",
            "display_name" => "Read User",
        ]);
        $updateUser = $this->__createPermission([
            "name" => "update_user",
            "display_name" => "Update User",
        ]);
        $deleteUser = $this->__createPermission([
            "name" => "delete_user",
            "display_name" => "Delete User",
        ]);

        $manageUser->childPermissions()->save($createUser);
        $manageUser->childPermissions()->save($readUser);
        $manageUser->childPermissions()->save($updateUser);
        $manageUser->childPermissions()->save($deleteUser);
        foreach ($roles as $role) {
            $role->attachPermissions([$manageUser]);
        }
    }

    /**
     * Group permission
     * @param array $roles
     */
    private function __userManagementGroupPermission(Array $roles = [])
    {
        $manageGroup = $this->__createPermission([
            "name" => "manage_group",
            "display_name" => "Manage Group",
        ]);
        $createGroup = $this->__createPermission([
            "name" => "create_group",
            "display_name" => "Create Group",
        ]);
        $readGroup = $this->__createPermission([
            "name" => "read_group",
            "display_name" => "Read Group",
        ]);
        $updateGroup = $this->__createPermission([
            "name" => "update_group",
            "display_name" => "Update Group",
        ]);
        $deleteGroup = $this->__createPermission([
            "name" => "delete_group",
            "display_name" => "Delete Group",
        ]);

        $manageGroup->childPermissions()->save($createGroup);
        $manageGroup->childPermissions()->save($readGroup);
        $manageGroup->childPermissions()->save($updateGroup);
        $manageGroup->childPermissions()->save($deleteGroup);
        foreach ($roles as $role) {
            $role->attachPermissions([$manageGroup]);
        }
    }

    /**
     * Role permission
     * @param array $roles
     */
    private function __userManagementRolePermission(Array $roles = [])
    {
        $manageRole = $this->__createPermission([
            "name" => "manage_role",
            "display_name" => "Manage Role",
        ]);
        $createRole = $this->__createPermission([
            "name" => "create_role",
            "display_name" => "Create Role",
        ]);
        $readRole = $this->__createPermission([
            "name" => "read_role",
            "display_name" => "Read Role",
        ]);
        $updateRole = $this->__createPermission([
            "name" => "update_role",
            "display_name" => "Update Role",
        ]);
        $deleteRole = $this->__createPermission([
            "name" => "delete_role",
            "display_name" => "Delete Role",
        ]);

        $manageRole->childPermissions()->save($createRole);
        $manageRole->childPermissions()->save($readRole);
        $manageRole->childPermissions()->save($updateRole);
        $manageRole->childPermissions()->save($deleteRole);
        foreach ($roles as $role) {
            $role->attachPermissions([$manageRole]);
        }
    }

    /**
     * Permission permission
     * @param array $roles
     */
    private function __userManagementPermissionPermission(Array $roles = [])
    {
        $managePermission = $this->__createPermission([
            "name" => "manage_permission",
            "display_name" => "Manage Permission",
        ]);
        $createPermission = $this->__createPermission([
            "name" => "create_permission",
            "display_name" => "Create Permission",
        ]);
        $readPermission = $this->__createPermission([
            "name" => "read_permission",
            "display_name" => "Read Permission",
        ]);
        $updatePermission = $this->__createPermission([
            "name" => "update_permission",
            "display_name" => "Update Permission",
        ]);
        $deletePermission = $this->__createPermission([
            "name" => "delete_permission",
            "display_name" => "Delete Permission",
        ]);

        $managePermission->childPermissions()->save($createPermission);
        $managePermission->childPermissions()->save($readPermission);
        $managePermission->childPermissions()->save($updatePermission);
        $managePermission->childPermissions()->save($deletePermission);
        foreach ($roles as $role) {
            $role->attachPermissions([$managePermission]);
        }
    }

    /**
     * CRUD categories
     * @param array $roles
     */
    private function __categoryPermission(Array $roles = [])
    {
        $manageCategory = $this->__createPermission([
            "name" => "manage_category",
            "display_name" => "Manage Category",
        ]);
        $createCategory = $this->__createPermission([
            "name" => "create_category",
            "display_name" => "Create Category",
        ]);
        $readCategory = $this->__createPermission([
            "name" => "read_category",
            "display_name" => "Read Category",
        ]);
        $updateCategory = $this->__createPermission([
            "name" => "update_category",
            "display_name" => "Update Category",
        ]);
        $deleteCategory = $this->__createPermission([
            "name" => "delete_category",
            "display_name" => "Delete Category",
        ]);

        $manageCategory->childPermissions()->save($createCategory);
        $manageCategory->childPermissions()->save($readCategory);
        $manageCategory->childPermissions()->save($updateCategory);
        $manageCategory->childPermissions()->save($deleteCategory);
        foreach ($roles as $role) {
            $role->attachPermissions([$manageCategory]);
        }
    }

    /**
     * CRUD products
     * @param array $roles
     */
    private function __productPermission(Array $roles = [])
    {
        $manageProduct = $this->__createPermission([
            "name" => "manage_product",
            "display_name" => "Manage Product",
        ]);
        $createProduct = $this->__createPermission([
            "name" => "create_product",
            "display_name" => "Create Product",
        ]);
        $readProduct = $this->__createPermission([
            "name" => "read_product",
            "display_name" => "Read Product",
        ]);
        $updateProduct = $this->__createPermission([
            "name" => "update_product",
            "display_name" => "Update Product",
        ]);
        $deleteProduct = $this->__createPermission([
            "name" => "delete_product",
            "display_name" => "Delete Product",
        ]);

        $manageProduct->childPermissions()->save($createProduct);
        $manageProduct->childPermissions()->save($readProduct);
        $manageProduct->childPermissions()->save($updateProduct);
        $manageProduct->childPermissions()->save($deleteProduct);
        foreach ($roles as $role) {
            $role->attachPermissions([$manageProduct]);
        }
    }

    /**
     * CRUD sites
     * @param array $roles
     */
    private function __sitePermission(Array $roles = [])
    {
        $manageSite = $this->__createPermission([
            "name" => "manage_site",
            "display_name" => "Manage Site",
        ]);
        $createSite = $this->__createPermission([
            "name" => "create_site",
            "display_name" => "Create Site",
        ]);
        $readSite = $this->__createPermission([
            "name" => "read_site",
            "display_name" => "Read Site",
        ]);
        $updateSite = $this->__createPermission([
            "name" => "update_site",
            "display_name" => "Update Site",
        ]);
        $deleteSite = $this->__createPermission([
            "name" => "delete_site",
            "display_name" => "Delete Site",
        ]);

        $manageSite->childPermissions()->save($createSite);
        $manageSite->childPermissions()->save($readSite);
        $manageSite->childPermissions()->save($updateSite);
        $manageSite->childPermissions()->save($deleteSite);
        foreach ($roles as $role) {
            $role->attachPermissions([$manageSite]);
        }
    }

    private function __createPermission(Array $data)
    {
        $permission = new \App\Models\Permission;
        $permission->name = $data['name'];
        $permission->display_name = $data['display_name'];
        $permission->description = isset($data['description']) ? $data['description'] : null;
        $permission->save();
        return $permission;
    }
}
