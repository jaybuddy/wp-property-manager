<?php include( dirname(dirname(__FILE__)) . '/helpers/admin-helpers.php' ); ?>

<div class='gallery-table'>
	
	<?php $media = get_post_meta($post->ID, 'photo-gal', true); ?>

	<fieldset id="upload_gallery_button" class="redux-field-container redux-field redux-field-init redux-container-gallery " data-id="opt-gallery"  data-type="gallery">
		<div class="screenshot">
			<?php echo the_gallery_images( $media ); ?>
		</div>
		<a href="#" onclick="return false;" id="edit-gallery" class="gallery-attachments button button-primary">Add/Edit Gallery</a> 
		<a href="#" onclick="return false;" id="clear-gallery" class="gallery-attachments button">Clear Gallery</a>
		<input type="hidden" class="gallery_values " value="<?php echo get_post_meta($post->ID, 'photo-gal', true); ?>" name="photo-gal" />
	</fieldset>
		
</div>