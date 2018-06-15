<?php foreach ($rows as $id => $row): ?>
	<div<?php if ($classes_array[$id]): ?> class="<?php print $classes_array[$id]; ?> w-1/3 py-2 px-1"<?php endif; ?>>
		<?php print $row; ?>
	</div>
<?php endforeach; ?>