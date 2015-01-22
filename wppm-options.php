<?php
	//Process the save of the options//

?>

<div class='wrap'>
	<h2>WP-Property-Manager Options</h2>
	<ul class='optionTabs'>
		<li><a href='#general'>General</a></li>
		<li><a href='#archive'>Listings Archive</a></li>
		<li><a href='#single'>Single Listing</a></li>
	</ul>
	<div class='contentWap'>
		<form action='' method='post' role='form'>
			<div id='general' class='content-tab'>
				<h3>General Settings</h3>

				<h4>Application Setting</h4>
				<p>Upload a fall-back Application to rent here. This fall-back can be overwritten and set on a per unit basis on the edit unit page.</p>
				<tr valign="top">
				     <th scope="row">Upload Application</th>
				<td><label for="upload_image">
				         <span id="previewimg1"></span>
					     <input id="default_application" type="hidden" name="default_application" class="upload" value="" />
					     <input id="default_application_button" type="button" value="Upload Application" class="upload_button" />
					 </label></td>
				</tr>
			</div>
			<div id='archive' class='content-tab' style='display:none'>
			<h3>Archive Page Settings</h3>
			</div>
			<div id='single' class='content-tab' style='display:none'>
				<h3>Single Page Settings</h3>
			</div>
		</form>
	</div>
</div>

<script language="JavaScript">
jQuery(document).ready(function() {
jQuery('.upload_button').click(function() {
	uploadID = jQuery(this).prev('input'); // grabs the correct field
	spanID = jQuery(this).parent().find('span'); // grabs the correct span
	formfield = jQuery('.upload').attr('name');
	tb_show('', 'media-upload.php?type=image&TB_iframe=true');
	return false;
});

window.send_to_editor = function(html) {
	imgurl = jQuery(html).attr('src'); // grabs the image URL from the IMG tag
	uploadID.val(imgurl); // sends the image URL to the hidden input field
	spanID.html(html); // sends the IMG tag to the preview span
	tb_remove();
	console.log(uploadID.val(imgurl));
}

});
</script>