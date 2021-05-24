<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>

<h4>Set <?= $role_info->role ?> Role Permissions</h4>

<?php
    // Open form
    $attributes = ['id' => 'role-permission'];
    echo form_open(route_to('edit_rp', $role_info->id), $attributes);
?>


<?php foreach($permission_group as $group): ?>

    <h3><?= $group->group_name ?></h3>

    <?php foreach($permissions as $key => $permission): ?>

        <?php if($permission->perm_group_id == $group->id): ?>
        
    
        <h4><?= $permission->permission ?></h4>
        
        <label for="can_create-<?= $permission->permission_slug ?>">Can Create</label>
        <input type="checkbox" 
            name="can_create[]" id="perm-<?= $permission->id ?>" 
            value="<?= $permission->id ?>"
            <?= $is_checked[$permission->id]['_create'] ?? '' ?>
            <?= $is_disabled[$permission->id]['_create'] ?? '' ?>
        >

        <label for="can_read-<?= $permission->permission_slug ?>">Can Read</label>
        <input type="checkbox" 
            name="can_read[]" id="perm-<?= $permission->id ?>" 
            value="<?= $permission->id ?>"
            <?= $is_checked[$permission->id]['_read'] ?? '' ?>
            <?= $is_disabled[$permission->id]['_read'] ?? '' ?>
        >

        <label for="can_update-<?= $permission->permission_slug ?>">Can Update</label>
        <input type="checkbox" 
            name="can_update[]" id="perm-<?= $permission->id ?>" 
            value="<?= $permission->id ?>"
            <?= $is_checked[$permission->id]['_update'] ?? '' ?>
            <?= $is_disabled[$permission->id]['_update'] ?? '' ?>
        >

        <label for="can_delete-<?= $permission->permission_slug ?>">Can Delete</label>
        <input type="checkbox" 
            name="can_delete[]" id="perm-<?= $permission->id ?>" 
            value="<?= $permission->id ?>"
            <?= $is_checked[$permission->id]['_delete'] ?? '' ?>
            <?= $is_disabled[$permission->id]['_delete'] ?? '' ?>
        >

        <label for="is_active-<?= $permission->id ?>">Active</label>
        <input type="checkbox" 
            name="is_active[]" id="perm-<?= $permission->id ?>" 
            value="<?= $permission->id ?>"
            <?= $is_active[$permission->id] ?? '' ?>
        >

        <br>

        <?php endif; ?>
        
    <?php endforeach; ?>
<?php endforeach; ?>

    <br>
    <button type="submit">Edit Role Permission</button>

</form>

<?= $this->endSection() ?>
