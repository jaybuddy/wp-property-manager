<table class='gallery-table'>
	<tr>
		<td>
			<?php wp_editor( get_post_meta($post->ID, 'photo-gal', true), 'photo-gal', array('teeny' => true, 'textarea_name' => 'photo-gal','textarea_rows' => 20) ) ?>
			<fieldset id="redux_options-opt-gallery" class="redux-field-container redux-field redux-field-init redux-container-gallery " data-id="opt-gallery"  data-type="gallery"><div class="screenshot"></div><a href="#" onclick="return false;" id="edit-gallery" class="gallery-attachments button button-primary">Add/Edit Gallery</a> <a href="#" onclick="return false;" id="clear-gallery" class="gallery-attachments button">Clear Gallery</a><input type="hidden" class="gallery_values " value="" name="redux_options[opt-gallery]" /><div class="description field-desc">This is the description field, again good for additional info.</div></fieldset>
		</td>
	</tr>
</table>