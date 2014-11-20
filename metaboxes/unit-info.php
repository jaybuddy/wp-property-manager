<div class='tab-wrap'>
	<div class='lcol'>
		<ul>
			<li class='active'><a href='#unit-infor'><i class='fa fa-home'></i> Unit Info</a></li>
			<li><a href='#unit-details'><i class='fa fa-gear'></i>  Unit Details</a></li>
			<li><a href='#rent-details'><i class='fa fa-dollar'></i>  Rent Details</a></li>
			<li><a href='#unit-features'><i class='fa fa-flash'></i>  Features</a></li>
			<li><a href='#unit-utilities'><i class='fa fa-tasks'></i>  Utilities</a></li>
		</ul>
	</div>
	<div class='rcol'>
		<div id='unit-infor' class='adminpanel' style="display:block">
			<table>
				<input type="hidden" name="unitsMetaBoxUpdate" id="unitsMetaBoxUpdate" value="yesPlease" />
				<input type="hidden" name="lat" id="lat" value="<?php echo get_post_meta($post->ID, 'lat', true); ?>" />
				<input type="hidden" name="lon" id="lon" value="<?php echo get_post_meta($post->ID, 'lon', true); ?>" />
				<input type="hidden" name="o_address" id="o_address" value="<?php echo get_post_meta($post->ID, 'address', true); ?> <?php echo get_post_meta($post->ID, 'city', true); ?>, <?php echo get_post_meta($post->ID, 'state', true); ?> <?php echo get_post_meta($post->ID, 'zip', true); ?>" />
				<tr>
					<td class='label'><label>Unit Address:</label></td>
					<td class='tinput'><input class='input-lg' type="text" name="address" id="address" value="<?php echo get_post_meta($post->ID, 'address', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>City:</label></td>
					<td class='tinput'><input class='input-md' type="text" name="city" id="city" value="<?php echo get_post_meta($post->ID, 'city', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>State:</label></td>
					<td class='tinput'><input class='input-sm' type="text" name="state" id="state" value="<?php echo get_post_meta($post->ID, 'state', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Zip Code:</label></td>
					<td class='tinput'><input class='input-sm' type="text" name="zip" id="zip" value="<?php echo get_post_meta($post->ID, 'zip', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Community:</label></td>
					<td class='tinput'><input class='input-md' type="text" name="community" id="community" value="<?php echo get_post_meta($post->ID, 'community', true); ?>"  /></td>
				</tr>
			</table>
			<hr>
			<table>
				<tr>
					<td class='label'><label>Unit Code:</label></td>
					<td class='tinput'><input class='input-sm' type="text" name="code" id="code" value="<?php echo get_post_meta($post->ID, 'code', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Unit Type:</label></td>
					<td class='tinput'>
						<?php $unit_type_val = get_post_meta($post->ID, 'unit_type', true); ?>
						<select name='unit_type' id='unit_type'>
							<option value='Apartment' <?php echo ($unit_type_val == 'Apartment') ? 'Selected' : ''; ?>>Apartment</option>
							<option value='Condominium' <?php echo ($unit_type_val == 'Condominium') ? 'Selected' : ''; ?>>Condominium</option>
							<option value='Townhome' <?php echo ($unit_type_val == 'Townhome') ? 'Selected' : ''; ?>>Townhome</option>
							<option value='House' <?php echo ($unit_type_val == 'House') ? 'Selected' : ''; ?>>House</option> 
							<option value='Garage' <?php echo ($unit_type_val == 'Garage') ? 'Selected' : ''; ?>>Garage</option> 
						</select>
					</td>
				</tr>
			</table>
		</div>
		<div id='unit-details' class='adminpanel' style='display:none'>
			<table>
				<tr>
					<td><label>Tagline:</label></td>
					<td class='tinput'><input class='input-lg' type="text" name="tagline" id="tagline" value="<?php echo get_post_meta($post->ID, 'tagline', true); ?>"  /></td>
				</tr>
			</table>
			<table>
				<tr>
					<td colspan='2'>
						<br><br><label>Unit Description:</label>
						<?php wp_editor( get_post_meta($post->ID, 'description', true), 'description', array('media_buttons' => false, 'textarea_name' => 'description', 'textarea_rows' => 5) ) ?>
					</td>
				</tr>
			</table>
			<hr>
			<table>
				<tr>
					<td class='label'><label>Bedrooms:</label></td>
					<td class='tinput'><input class='input-sm' type="text" name="bedrooms" id="bedrooms" value="<?php echo get_post_meta($post->ID, 'bedrooms', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Bathrooms:</label></td>
					<td class='tinput'><input class='input-sm' type="text" name="bathrooms" id="bathrooms" value="<?php echo get_post_meta($post->ID, 'bathrooms', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Approx. SQFT:</label></td>
					<td class='tinput'><input class='input-sm' type="text" name="sqft" id="sqft" value="<?php echo get_post_meta($post->ID, 'sqft', true); ?>"  /></td>
				</tr>
			</table>
			
			<hr>
			<table>
				<tr>
					<td colspan='2'><label>Parking</label><br><textarea class='input-lg' name="parking" id="parking"><?php echo get_post_meta($post->ID, 'parking', true); ?></textarea></td>
				</tr>
			</table>
			<hr>
			<table>
				<tr>
					<td colspan='2'><label>Pet Policy</label><br><textarea class='input-lg' name="pet_policy" id="pet_policy"><?php echo get_post_meta($post->ID, 'pet_policy', true); ?></textarea></td>
				</tr>
			</table>
			<hr>
			<table>
				<tr>
					<td colspan='2'><label>Smoking Policy</label><br><textarea class='input-lg' name="smoking" id="smoking"><?php echo get_post_meta($post->ID, 'smoking', true); ?></textarea></td>
				</tr>
			</table>
		</div>
		<div id='rent-details' class='adminpanel' style='display:none'>
			<table>
				<tr>
					<td class='label'><label>Monthly Rent:</label></td>
					<td><input class='input-sm' type="text" name="rent" id="rent" value="<?php echo get_post_meta($post->ID, 'rent', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Security Deposit:</label></td>
					<td><input class='input-sm' type="text" name="deposit" id="deposit" value="<?php echo get_post_meta($post->ID, 'deposit', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Agreement Type:</label></td>
					<td><input class='input-md' type="text" name="agreement_type" id="agreement_type" value="<?php echo get_post_meta($post->ID, 'agreement_type', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Date Available:</label></td>
					<td><input class='input-md' type="text" name="date_available" id="date_available" value="<?php echo get_post_meta($post->ID, 'date_available', true); ?>"  /></td>
				</tr>
			</table>
		</div>
		<div id='unit-features' class='adminpanel' style='display:none'>
			<table>
				<tr>
					<td class='label'><label>Fireplace:</label></td>
					<td>
						<?php $fireplace_val = get_post_meta($post->ID, 'fireplace', true); ?>
						<select name='fireplace' id='fireplace'>
							<option value='No' <?php echo ($fireplace_val == 'No') ? 'Selected' : ''; ?>>No</option>
							<option value='Yes' <?php echo ($fireplace_val == 'Yes') ? 'Selected' : ''; ?>>Yes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Washer/Dryer:</label></td>
					<td>
						<?php $wd_val = get_post_meta($post->ID, 'washerdryer', true); ?>
						<select name='washerdryer' id='washerdryer'>
							<option value='None' <?php echo ($wd_val == 'None') ? 'Selected' : ''; ?>>None</option>
							<option value='Hook-ups in garage' <?php echo ($wd_val == 'Hook-ups in garage') ? 'Selected' : ''; ?>>Hook-ups in garage</option>
							<option value='Hook-ups in unit' <?php echo ($wd_val == 'Hook-ups in unit') ? 'Selected' : ''; ?>>Hook-ups in unit</option>
							<option value='Washer/Dryer in complex' <?php echo ($wd_val == 'Washer/Dryer in complex') ? 'Selected' : ''; ?>>Washer/Dryer in complex</option>
							<option value='Washer/Dryer in garage' <?php echo ($wd_val == 'Washer/Dryer in garage') ? 'Selected' : ''; ?>>Washer/Dryer in garage</option>
							<option value='Washer/Dryer in unit' <?php echo ($wd_val == 'Washer/Dryer in unit') ? 'Selected' : ''; ?>>Washer/Dryer in unit</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Patio:</label></td>
					<td>
						<?php $patio_val = get_post_meta($post->ID, 'patio', true); ?>
						<select name='patio' id='patio'>
							<option value='No' <?php echo ($patio_val == 'No') ? 'Selected' : ''; ?>>No</option>
							<option value='Yes, shared' <?php echo ($patio_val == 'Yes, shared') ? 'Selected' : ''; ?>>Yes, shared</option>
							<option value='Yes, private' <?php echo ($patio_val == 'Yes, private') ? 'Selected' : ''; ?>>Yes, private</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Balcony:</label></td>
					<td>
						<?php $balcony_val = get_post_meta($post->ID, 'balcony', true); ?>
						<select name='balcony' id='balcony'>
							<option value='No' <?php echo ($balcony_val == 'No') ? 'Selected' : ''; ?>>No</option>
							<option value='Yes, shared' <?php echo ($balcony_val == 'Yes, shared') ? 'Selected' : ''; ?>>Yes, shared</option>
							<option value='Yes, private' <?php echo ($balcony_val == 'Yes, private') ? 'Selected' : ''; ?>>Yes, private</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Pool:</label></td>
					<td>
						<?php $pool_val = get_post_meta($post->ID, 'pool', true); ?>
						<select name='pool' id='pool'>
							<option value='No' <?php echo ($pool_val == 'No') ? 'Selected' : ''; ?>>No</option>
							<option value='Yes, shared' <?php echo ($pool_val == 'Yes, shared') ? 'Selected' : ''; ?>>Yes, shared</option>
							<option value='Yes, private' <?php echo ($pool_val == 'Yes, private') ? 'Selected' : ''; ?>>Yes, private</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Spa:</label></td>
					<td>
						<?php $spa_val = get_post_meta($post->ID, 'spa', true); ?>
						<select name='spa' id='spa'>
							<option value='No' <?php echo ($spa_val == 'No') ? 'Selected' : ''; ?>>No</option>
							<option value='Yes, shared' <?php echo ($spa_val == 'Yes, shared') ? 'Selected' : ''; ?>>Yes, shared</option>
							<option value='Yes, private' <?php echo ($spa_val == 'Yes, private') ? 'Selected' : ''; ?>>Yes, private</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Walk-in Closet(s):</label></td>
					<td>
						<?php $wic_val = get_post_meta($post->ID, 'walkin_closets', true); ?>
						<select name='walkin_closets' id='walkin_closets'>
							<option value='No' <?php echo ($wic_val == 'No') ? 'Selected' : ''; ?>>No</option>
							<option value='Yes' <?php echo ($wic_val == 'Yes') ? 'Selected' : ''; ?>>Yes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Custom Paint Colors:</label></td>
					<td>
						<?php $cp_val = get_post_meta($post->ID, 'custom_paint', true); ?>
						<select name='custom_paint' id='custom_paint' value='<?php echo get_post_meta($post->ID, 'custom_paint', true); ?>'>
							<option value='No' <?php echo ($cp_val == 'No') ? 'Selected' : ''; ?>>No</option>
							<option value='Yes' <?php echo ($cp_val == 'Yes') ? 'Selected' : ''; ?>>Yes</option>
						</select>
					</td>
				</tr>
			</table>
			<hr>
			<table>
				<tr>
					<td class='label'><label>Window Coverings:</label></td>
					<td class='tinput'><input class='input-md' type="text" name="window_coverings" id="window_coverings" value="<?php echo get_post_meta($post->ID, 'window_coverings', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Floor Coverings:</label></td>
					<td class='tinput'><input class='input-md' type="text" name="floor_coverings" id="floor_coverings" value="<?php echo get_post_meta($post->ID, 'floor_coverings', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>Counter Tops:</label></td>
					<td class='tinput'><input class='input-md' type="text" name="countertops" id="countertops" value="<?php echo get_post_meta($post->ID, 'countertops', true); ?>"  /></td>
				</tr>
			</table>
			<hr>
			<table>
				<tr>
					<td class='label'><label>Heating Type:</label></td>
					<td class='tinput'><input class='input-md' type="text" name="heating_type" id="heating_type" value="<?php echo get_post_meta($post->ID, 'heating_type', true); ?>"  /></td>
				</tr>
				<tr>
					<td class='label'><label>A/C Type:</label></td>
					<td class='tinput'><input class='input-md' type="text" name="ac_type" id="ac_type" value="<?php echo get_post_meta($post->ID, 'ac_type', true); ?>"  /></td>
				</tr>
			</table>
		</div>
		<div id='unit-utilities' class='adminpanel' style='display:none'>
			<table>
				<tr>
					<td class='label'><label>Water:</label></td>
					<td class='tinput'>
						<?php $water_val = get_post_meta($post->ID, 'water', true); ?>
						<select name='water' id='water'>
							<option value='N/A' <?php echo ($water_val == 'N/A') ? 'Selected' : ''; ?>>N/A</option>
							<option value='Tenant' <?php echo ($water_val == 'Tenant') ? 'Selected' : ''; ?>>Tenant</option>
							<option value='Owner' <?php echo ($water_val == 'Owner') ? 'Selected' : ''; ?>>Owner</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Sewer:</label></td>
					<td>
						<?php $sewer_val = get_post_meta($post->ID, 'sewer', true); ?>
						<select name='sewer' id='sewer'>
							<option value='N/A' <?php echo ($sewer_val == 'N/A') ? 'Selected' : ''; ?>>N/A</option>
							<option value='Tenant' <?php echo ($sewer_val == 'Tenant') ? 'Selected' : ''; ?>>Tenant</option>
							<option value='Owner' <?php echo ($sewer_val == 'Owner') ? 'Selected' : ''; ?>>Owner</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Trash:</label></td>
					<td>
						<?php $trash_val = get_post_meta($post->ID, 'trash', true); ?>
						<select name='trash' id='trash'>
							<option value='N/A' <?php echo ($trash_val == 'N/A') ? 'Selected' : ''; ?>>N/A</option>
							<option value='Tenant' <?php echo ($trash_val == 'Tenant') ? 'Selected' : ''; ?>>Tenant</option>
							<option value='Owner' <?php echo ($trash_val == 'Owner') ? 'Selected' : ''; ?>>Owner</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Gas:</label></td>
					<td>
						<?php $gas_val = get_post_meta($post->ID, 'gas', true); ?>
						<select name='gas' id='gas'>
							<option value='N/A' <?php echo ($gas_val == 'N/A') ? 'Selected' : ''; ?>>N/A</option>
							<option value='Tenant' <?php echo ($gas_val == 'Tenant') ? 'Selected' : ''; ?>>Tenant</option>
							<option value='Owner' <?php echo ($gas_val == 'Owner') ? 'Selected' : ''; ?>>Owner</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Electric:</label></td>
					<td>
						<?php $electric_val = get_post_meta($post->ID, 'electric', true); ?>
						<select name='electric' id='electric'>
							<option value='N/A' <?php echo ($electric_val == 'N/A') ? 'Selected' : ''; ?>>N/A</option>
							<option value='Tenant' <?php echo ($electric_val == 'Tenant') ? 'Selected' : ''; ?>>Tenant</option>
							<option value='Owner' <?php echo ($electric_val == 'Owner') ? 'Selected' : ''; ?>>Owner</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Phone:</label></td>
					<td>
						<?php $phone_val = get_post_meta($post->ID, 'phone', true); ?>
						<select name='phone' id='phone'>
							<option value='N/A' <?php echo ($phone_val == 'N/A') ? 'Selected' : ''; ?>>N/A</option>
							<option value='Tenant' <?php echo ($phone_val == 'Tenant') ? 'Selected' : ''; ?>>Tenant</option>
							<option value='Owner' <?php echo ($phone_val == 'Owner') ? 'Selected' : ''; ?>>Owner</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Cable:</label></td>
					<td>
						<?php $cable_val = get_post_meta($post->ID, 'cable', true); ?>
						<select name='cable' id='cable'>
							<option value='N/A' <?php echo ($cable_val == 'N/A') ? 'Selected' : ''; ?>>N/A</option>
							<option value='Tenant' <?php echo ($cable_val == 'Tenant') ? 'Selected' : ''; ?>>Tenant</option>
							<option value='Owner' <?php echo ($cable_val == 'Owner') ? 'Selected' : ''; ?>>Owner</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='label'><label>Internet:</label></td>
					<td>
						<?php $internet_val = get_post_meta($post->ID, 'internet', true); ?>
						<select name='internet' id='internet'>
							<option value='N/A' <?php echo ($internet_val == 'N/A') ? 'Selected' : ''; ?>>N/A</option>
							<option value='Tenant' <?php echo ($internet_val == 'Tenant') ? 'Selected' : ''; ?>>Tenant</option>
							<option value='Owner' <?php echo ($internet_val == 'Owner') ? 'Selected' : ''; ?>>Owner</option>
						</select>
					</td>
				</tr>
				<table>
				<tr>
					<td colspan='2'><label>Other Utilities:</label><br><textarea class='input-lg' name="other_utilities" id="other_utilities"><?php echo get_post_meta($post->ID, 'other_utilities', true); ?></textarea></td>
				</tr>
			</table>
			</table>
		</div>
	</div>
	<div class='clear'></div>
</div>

<script type='text/javascript'>
	jQuery(function($){
		$('#unit-info .lcol ul li a').click(function(){
			var t = $(this);
			
			$('#unit-info .adminpanel').hide();
			$(t.attr('href')).show();
			
			$('#unit-info .lcol ul li').removeClass('active');
			t.parent().addClass('active');
			return false;
		});
	});
</script>


	