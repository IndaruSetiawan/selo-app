<?php
/** @var array $members */
$canWrite = can('team.write');
$me = (int) ($_SESSION['staff_id'] ?? 0);
$cols = 7 + ($canWrite ? 1 : 0);
?>
<div class="head-title">
				<div class="left">
					<h1>Team</h1>
					<ul class="breadcrumb">
						<li><a href="#">Dashboard</a></li>
						<li><i class="bx bx-chevron-right"></i></li>
						<li><a class="active" href="#">Team</a></li>
					</ul>
				</div>
				<div class="table-data">
					<div class="order">
						<table>
							<thead>
								<tr>
									<th>No</th>
									<th>Staff Id</th>
									<th>Name</th>
									<th>Email</th>
									<th>Telephone</th>
									<th>Role</th>
									<th>Status</th>
									<?php if ($canWrite): ?><th>Action</th><?php endif; ?>
								</tr>
							</thead>
							<tbody>
								<?php if (empty($members)): ?>
									<tr class="team-list">
										<td colspan="<?= $cols ?>" class="text-center py-4">No</td>
									</tr>
								<?php endif; ?>
								<?php foreach ($members  as $i => $member):
									$id = (int) $member['id_staff'];
									$active = (int) $member['status'] === 1;
									$isMe = $id === $me;
								?>
									<tr class="team-list">
										<td><?= $i + 1 ?></td>
										<td>SF<?= str_pad((string) $id, 3, '0', STR_PAD_LEFT) ?></td>
										<td><p><?= e($member['name']) ?><?= $isMe? ' (you)' : '' ?></p></td>
										<td><?= e($member['email']) ?></td>
										<td><?= e($member['phone'] ?: '-') ?></td>
										<td>
											<?php if ($canWrite && $active && !$isMe): ?>
											<form action="/admin/team/role" method="post">
												<input type="hidden" name="csrf" value="<?= $id ?>">
												<select name="role" class="form-select" onchange="this.form.submit()">
													<option value="staff" <?= $member['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
													<option value="admin" <?= $member['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
												</select>
											</form>
											<?php else: ?>
												<?= e(ucfirst((string) $member['role'])) ?>
											<?php endif; ?>	
										</td>
										<td>
											<span class="badge <?= $active ? 'badge-active' : 'badge-pending' ?>">
												<?= $active ? 'Active' : 'Pending' ?>
											</span>
										</td>
										<?php if ($canWrite): ?>
											<td>
												<?php if (!$isMe): ?>
													<div class="d-flex gap-1">
														<?php if (!$active): ?>
															<form action="/admin/team/approve" method="post">
																<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
																<input type="hidden" name="id_staff" value="<?= $id ?>">
																<button type="submit" class="btn btn-sm btn-success">Approve</button>
															</form>
														<?php endif; ?>
														<form action="/admin/team/reject" method="post" onsubmit="return confirm('<?= $active ? 'Remove' : 'Reject' ?> <?=  e($member['name']) ?>?')">
															<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
															<input type="hidden" name="id_staff" value="<?= $id ?>">
															<button type="submit" class="btn-remove"><?= $active ? 'Remove' : 'Reject' ?></button>
														</form>
													</div>
												<?php endif; ?>
											</td>
										<?php endif; ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>