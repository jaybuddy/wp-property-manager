<table>
	<tr><td><label>Unit Availability:</label></td></tr>
	<tr>
		<td>
			<?php $lad = get_post_meta($post->ID, 'last_avail_date', true); ?>
			<?php $ava = get_post_meta($post->ID, 'status', true); ?>

			<input type='hidden' name='previousStatus' id='previousStatus' value='<?php echo $ava; ?>' />
			<input type='hidden' name='last_avail_date' id='last_avail_date' value='<?php echo $lad; ?>' />
			
			<select name='status' id='status'>
				<option value='For Rent' <?php echo ($ava == 'For Rent') ? 'Selected' : ''; ?>>For Rent</option>
				<option value='Rented' <?php echo ($ava == 'Rented') ? 'Selected' : ''; ?>>Rented</option>
			</select>
		</td>
	</tr>
</table>