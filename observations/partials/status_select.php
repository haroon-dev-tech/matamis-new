<?php
/** @var string $selectedStatus */
$selectedStatus = $selectedStatus ?? 'Open';
?>
<select name="status" class="input-field">
    <?php foreach (OBSERVATION_STATUSES as $statusOption): ?>
    <option value="<?= e($statusOption) ?>" <?= $statusOption === $selectedStatus ? 'selected' : '' ?>><?= e($statusOption) ?></option>
    <?php endforeach; ?>
</select>
