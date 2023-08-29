<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: WordPress admin page
Changelog:
2023-02-18 start

*/
?>

<div class="wrap kdtla">

	<?php if (isset($_POST['message'])) { ?>
		<div class="updated below-h2">
			<p><?php echo($_POST['message']); ?></p>
		</div>
	<?php } ?>

	<form method="post" enctype="multipart/form-data" id="kdtla-options">

		<h2> KDTLA </h2>
		
		<p> 
		The plugin written for dtlaprint.com, expanding WooCommerce functionality:<br />
		- provides WooCommerce code for custom fields<br />
		- displays product images from cdn<br />
		- upload artwork (to /wp-content/uploads/artwork/ ) <br />
		- loads kdtla.js (for product pricing, artwork uploads, etc.) and kdtla.css in header in each product page<br />
		- dynamic pricing (csv files should be placed in /wp-content/uploads/ , filenames: dtla-2023-sp.csv , dtla-2023-other.csv , dtla-2023-finishings.csv) <br />
		- loads kdtla-mini.js on all pages (mini cart compressing, My Quote popup product info, Checkout and WC backend corrections ) <br />
		<br />
		Files which can be edited:<br />
		<a href="/wp-admin/plugin-editor.php?file=kdtla%2F1_templates%2Fdtla_template_product.php&plugin=kdtla%2Fkdtla.php" target="_blank">dtla_template_product.php</a>
		- product page structure (colors, quantities, decorations headers, finishings images) <br />

		<a href="/wp-admin/plugin-editor.php?file=kdtla%2F1_templates%2Fdtla_template_cart_top.php&plugin=kdtla%2Fkdtla.php" target="_blank">dtla_template_cart_top.php</a> - cart page , top info <br />
		<a href="/wp-admin/plugin-editor.php?file=kdtla%2F1_templates%2Fdtla_template_cart_row.php&plugin=kdtla%2Fkdtla.php" target="_blank">dtla_template_cart_row.php</a> - cart page , each product row (quantity, decoratons, finishings) <br />

		<a href="/wp-admin/plugin-editor.php?file=kdtla%2Fkdtla.css&plugin=kdtla%2Fkdtla.php" target="_blank">kdtla.css</a>
		- css file for product page (cart, checkout...) <br />

		<br /><br />
		Artwork uploads, conversion, background removal and canvas preview log files: <br />
		- <a href="/wp-content/uploads/artwork/uploads.txt" target="_blank">uploads.txt</a> <br />
		- <a href="/wp-content/uploads/artwork/converted.txt" target="_blank">converted.txt</a> <br />
		- <a href="/wp-content/uploads/artwork/without_bg.txt" target="_blank">without_bg.txt</a> <br />
		- <a href="/wp-content/uploads/artwork/without_bg_errors.txt" target="_blank">without_bg_errors.txt</a> <br />
		- <a href="/wp-content/uploads/artwork/uploads_canvas.txt" target="_blank">uploads_canvas.txt</a> <br />




		
		<br /><br />
		
		</p>


		
			<h3> Settings </h3>
		

			<div class="set">
				<input type="checkbox" name="artwork_cron" value="1" id="artwork_cron" <?php checked( $options['artwork_cron'], 1 ); ?> > 
				enable removing old artwork? daily, moving to /artwork-old 
				
			</div>

			<span style="display:none;">
			<div class="set">
				<div class="field double-wide">
					<p><label>HTML template</label></p>
					<p class="codemirror css">
						
						<textarea name="t1" id="t1" cols="70" rows="15"><?php echo esc_textarea($options['t1']); ?> </textarea>
					</p>
					
				</div>
		
			</div>

		
			<h3>Advanced Configuration</h3>
				<div class="set">
					<div class="field double-wide">
						<p><label>Custom CSS</label></p>
						<p class="codemirror css">
							<textarea name="CustomCss" id="CustomCss" cols="50" rows="10"><?php echo esc_textarea($options['CustomCss']); ?></textarea>
						</p>
						<span class="info">
							
						</span>
						<span class="info">

						</span>
					</div>
			
				</div>
		
			<br /><br />

			</span>
			<p>
				<input class="button-primary" type="submit" name="kdtla_save" id="kdtla_save" value="Save Changes" />
			</p>
		

	</form>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	wp.codeEditor.initialize($('#t1'), cm_settings['ce_html']);
	wp.codeEditor.initialize($('#CustomCss'), cm_settings['ce_css']);
})

jQuery('#kdtla_save').on('click', function() { 
	console.log('saving...');
	
})
</script>
