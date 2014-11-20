<table class='gallery-table'>
	<tr>
		<td>
			<?php wp_editor( get_post_meta($post->ID, 'photo-gal', true), 'photo-gal', array('teeny' => true, 'textarea_name' => 'photo-gal','textarea_rows' => 20) ) ?>
		</td>
	</tr>
</table>