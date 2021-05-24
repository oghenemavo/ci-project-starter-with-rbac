<?php

use CodeIgniter\I18n\Time; ?>

<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>

<div class="section-wrapper mg-t-20">
    <?php
    // Open form
    $attributes = ['id' => 'edit-permission'];
    echo form_open(route_to('process_edit_permission', $permission->id), $attributes);
    ?>
    <div class="row no-gutters ht-200">

        <div class="col bg-gray-100 d-flex align-items-center justify-content-center">
            <div class="wd-250">
                <label for="role" class="sr-only sr-only-focusable">PERMISSION NAME</label>
                <input type="text" class="form-control" name="permission" id="permission" value="<?= set_value('permission', $permission->permission) ?>" placeholder="PERMISSION NAME">
                <?= $validation->showError('permission'); ?>
            </div><!-- wd-250 -->
        </div><!-- col-->

        <div class="col bg-gray-200 d-flex align-items-center justify-content-center">
            <div class="wd-250">
                <label for="role" class="sr-only sr-only-focusable">PERMISSION SLUG</label>
                <input type="text" class="form-control" name="permission_slug" id="permission_slug" value="<?= set_value('permission_slug', $permission->permission_slug) ?>" placeholder="app_settings">
                <?= $validation->showError('permission_slug'); ?>
            </div><!-- select2-wrapper -->
        </div><!-- col-->

        <label for="role" class="sr-only sr-only-focusable">Enable Create</label>
        <input type="checkbox" name="enable_create" id="enable_create" value="1" <?= $permission->enable_create == '1' ? 'checked' : '' ?> >

        <label for="role" class="sr-only sr-only-focusable">Enable Read</label>
        <input type="checkbox" name="enable_read" id="enable_read" value="1" <?= $permission->enable_read == '1' ? 'checked' : '' ?> >

        <label for="role" class="sr-only sr-only-focusable">Enable Update</label>
        <input type="checkbox" name="enable_update" id="enable_update" value="1" <?= $permission->enable_update == '1' ? 'checked' : '' ?> >

        <label for="role" class="sr-only sr-only-focusable">Enable Delete</label>
        <input type="checkbox" name="enable_delete" id="enable_delete" value="1" <?= $permission->enable_delete == '1' ? 'checked' : '' ?> >

    </div><!-- row -->

    <button type="submit" class="btn btn-indigo active mg-b-10">Edit Permission</button>
    <br>
    </form>

</div><!-- section-wrapper -->

<?= $this->endSection() ?>