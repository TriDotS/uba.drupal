<div id="node-<?php print $node->nid; ?>" class="w-full rounded overflow-hidden shadow-lg">
	<img class="w-full" src="<?php print file_create_url($node->field_image['und'][0]['uri']); ?>"
	     alt="<?php print file_create_url($node->field_image['und'][0]['title']); ?>">
	<div class="px-6 py-4">
		<div class="font-bold text-xl mb-2">
			<a class="no-underline text-black" href="<?php print $node_url; ?>"><?php print $title; ?></a></div>
		<p class="text-grey-darker text-base">
			<?php
				print $node->body["und"]['0']["safe_summary"];
			?>
		</p>
	</div>
	<div class="px-6 py-4">
		<?php foreach ($node->field_tags as $id => $tags): ?>
			<?php foreach ($tags as $key => $tag): ?>
				<span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">#<?php print $tag['taxonomy_term']->name ?></span>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
</div>