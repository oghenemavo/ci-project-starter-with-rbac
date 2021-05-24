
<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
    <?= var_dump(session()->getFlashdata()); ?>
    <div>
        <?php
            $is_disabled = strtolower($role->role_slug) == strtolower('super_admin') ? 'disabled' : '';
            // Open form
            $attributes = ['id' => 'edit-role'];
            echo form_open(route_to('process_edit_role', $role->id), $attributes);
        ?>

        <div class="form-group">
            <label for="role" class="sr-only sr-only-focusable">ROLE</label>
            <input type="text" class="form-control" name="role" id="role" value="<?= $role->role ?>" placeholder="ROLE" <?= $is_disabled ?> >
            <?= $validation->showError('role'); ?>
        </div>

        <input type="hidden" name="id" value="<?= $role->id ?>">

        <div class="form-group">
            <label for="role" class="sr-only sr-only-focusable">ROLE SLUG</label>
            <input type="text" class="form-control" name="role_slug" id="role_slug" value="<?= $role->role_slug ?>" placeholder="super_admin">
            <?= $validation->showError('role_slug'); ?>
        </div>

        <div class="form-group mt-3">
            <label for="is_super_admin" class="ckbox">
                <input type="checkbox" id="is_super_admin" name="is_super_admin" <?= $role->is_super_admin == '1' ? 'checked' : '' ?>>
                <span>Super Admin</span>
            </label>
        </div>
        
        <div class="form-group mt-3">
            <label for="activate" class="ckbox">
                <input type="checkbox" id="activate" name="activate" <?= $role->is_active ? 'checked' : '' ?> <?= $is_disabled ?> >
                <span>Activate Role</span>
            </label>
        </div>

        <button type="submit" class="btn btn-purple active mg-b-10" <?= $is_disabled ?> >Edit Role</button>

        </form>

    </div><!-- section-wrapper -->

<?= $this->endSection() ?>