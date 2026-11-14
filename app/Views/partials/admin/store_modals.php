<!-- Modal: Add / Edit -->
<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 360px;">
		<div class="modal-content">
			<div class="modal-head">
				<h2 id="menuModalTitle">Add Menu</h2>
				<button type="button" class="btn-x" data-bs-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
			</div>

			<form id="menu-form" action="/admin/store/save" method="post" enctype="multipart/form-data">
				<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
				<div class="px-4 pb-1">
					<input type="hidden" name="id_menu" id="f-id">

					<label class="field-label" for="f-name">Name</label>
					<input class="field" id="f-name" name="name" maxlength="100" placeholder="Name" required>

					<label class="field-label" for="f-category">Category</label>
					<select class="field" id="f-category" name="category" required>
						<?php foreach (\App\Models\Menu::CATEGORIES as $value => $label): ?>
						<option value="<?= e($value) ?>"><?= e($label) ?></option>
						<?php endforeach; ?>
					</select>

					<label class="field-label" for="f-picture">Picture</label>
					<input class="field" id="f-picture" name="picture" type="file" accept="image/jpeg,image/png,image/webp">
					<small class="text-secondary" id="f-picture-hint">JPG, PNG, or WEBP, max 2 MB</small>

					<label class="field-label" for="f-desc">Description</label>
					<textarea class="field" id="f-desc" name="description" rows="3" maxlength="1000" placeholder="Add description . . ."></textarea>

					<label class="field-label" for="f-cost">Cost</label>
					<input class="field" id="f-cost" name="cost" type="number" min="0" step="1" placeholder="Cost" required>

					<label class="field-label" for="f-price">Price</label>
					<input class="field" id="f-price" name="price" type="number" min="0" step="1" placeholder="Price" required>
				</div>
				<button type="submit" class="btn-submit" id="f-submit">Add Menu</button>
			</form>
		</div>
	</div>
</div>

<!-- Modal: Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" style="max-width: 300px;">
		<div class="modal-content text-center p-4">
			<form id="delete-form" action="/admin/store/delete" method="post">
				<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
				<input type="hidden" name="id_menu" id="delete-id">
				<p class="fs-5 mb-3">Are you sure you want to delete this?</p>
				<div class="d-flex justify-content-center gap-4">
					<button type="button" class="btn-pink px-4" data-bs-dismiss="modal">No</button>
					<button type="submit" class="btn-mint px-4">Yes</button>
				</div>
			</form>
		</div>
	</div>
</div>