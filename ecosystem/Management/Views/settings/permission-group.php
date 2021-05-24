<?php use CodeIgniter\I18n\Time; ?>

<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>


<div class="section-wrapper mg-t-20">
    <?php
        // Open form
        $attributes = ['id' => 'add-permission-group'];
        echo form_open(route_to('add_pg'), $attributes);
    ?>
        <div class="row no-gutters ht-200">

            <div class="col bg-gray-100 d-flex align-items-center justify-content-center">
                <div class="wd-250">
                    <label for="role" class="sr-only sr-only-focusable">PERMISSION GROUP NAME</label>
                    <input type="text" class="form-control" name="group" id="group" value="<?= set_value('group') ?>" placeholder="PERMISSION GROUP NAME">
                    <?= $validation->showError('group'); ?>
                </div><!-- wd-250 -->
            </div><!-- col-->

            <div class="col bg-gray-200 d-flex align-items-center justify-content-center">
                <div class="wd-250">
                    <label for="role" class="sr-only sr-only-focusable">PERMISSION GROUP SLUG</label>
                    <input type="text" class="form-control" name="group_slug" id="group_slug" value="<?= set_value('group_slug') ?>" placeholder="app_settings">
                    <?= $validation->showError('group_slug'); ?>
                </div><!-- select2-wrapper -->
            </div><!-- col-->

            <div class="col bg-gray-200 d-flex align-items-center justify-content-center">
                <div class="wd-250">
                    <label for="role" class="sr-only sr-only-focusable">PERMISSION DESCRIPTION</label>
                    <textarea name="group_description" id="group_description" cols="30" rows="10"></textarea>
                    <?= $validation->showError('group_description'); ?>
                </div><!-- select2-wrapper -->
            </div><!-- col-->
        </div><!-- row -->

        <label for="is_active" class="sr-only sr-only-focusable">Activate</label>
        <input type="checkbox" name="is_active" id="is_active"> <br>

        <button type="submit" class="btn btn-indigo active mg-b-10">Create Permission Group</button>
        <br>
    </form>

    <table id="permissionGroupList" class="table">
        <thead>
            <tr>
                <th>s/no</th>
                <th>Group</th>
                <th>Group Slug</th>
                <th>Permissions</th>
                <th>Active</th>
                <th>Created at</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=1; foreach ($groups as $group): ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $group->group_name ?></td>
                <td><?= $group->group_slug ?></td>
                <td>
                    <a href="<?= base_url(route_to('view_permissions', $group->id)) ?>">(<?= $permissions_count($group->id) ?>)</a>
                </td>
                <td><?= $group->is_active ? '&#10004;' : '&#10008;'; ?></td>
                <td><?= Time::parse($group->created_at)->toLocalizedString('MMM d, yyyy') ?></td>
                <td>
                    <a href="<?= base_url(route_to('edit_permission_group', $group->id)) ?>">Edit Group</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div><!-- section-wrapper -->

<?= $this->endSection() ?>