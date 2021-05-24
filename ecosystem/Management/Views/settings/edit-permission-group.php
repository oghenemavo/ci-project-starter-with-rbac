<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>


    <div class="section-wrapper mg-t-20">
        <?php
            // Open form
            $attributes = ['id' => 'edit-pg'];
            echo form_open(route_to('edit_pg', $group->id), $attributes);
        ?>

            <div class="form-group">
                <label for="role" class="sr-only sr-only-focusable">GROUP NAME</label>
                <input type="text" class="form-control w-75" name="group" id="group" value="<?= $group->group_name ?>" placeholder="GROUP NAME">
                <?= $validation->showError('group'); ?>
            </div>

            <div class="col bg-gray-200 d-flex align-items-center justify-content-center">
                <div class="wd-250">
                    <label for="role" class="sr-only sr-only-focusable">PERMISSION GROUP SLUG</label>
                    <input type="text" class="form-control" name="group_slug" id="group_slug" value="<?= $group->group_slug ?>" placeholder="app_settings">
                    <?= $validation->showError('group_slug'); ?>
                </div><!-- select2-wrapper -->
            </div><!-- col-->

            <div class="col bg-gray-200 d-flex align-items-center justify-content-center">
                <div class="wd-250">
                    <label for="role" class="sr-only sr-only-focusable">PERMISSION DESCRIPTION</label>
                    <textarea name="group_description" id="group_description" cols="30" rows="10"><?= $group->group_description ?></textarea>
                    <?= $validation->showError('group_description'); ?>
                </div><!-- select2-wrapper -->
            </div><!-- col-->
        </div><!-- row -->

        <label for="is_active" class="sr-only sr-only-focusable">Activate</label>
        <input type="checkbox" name="is_active" id="is_active" <?= $group->is_active == '1' ? 'checked' : '' ?> > <br>

            <input type="hidden" name="id" value="<?= $group->id ?>">

            <div class="btn-demo my-3">
                <button type="submit" class="btn btn-purple active mg-b-10">Edit Group</button>
            </div>

        </form>

    </div><!-- section-wrapper -->

<?= $this->endSection() ?>