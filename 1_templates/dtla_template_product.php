<?php
/*
Author: Konrad G
Author URI: www.kgretk.com
File: Product template. Please don't change names and ids of elements, and data-fin attributes.
*/



defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
error_reporting(E_ALL);


?>
<div class="k_product1">
<div id="div_kask">
		<div class="kdtla-row">
			<div class="kdtla-col-md-2">
			<div class="pro-expert-img">
				<img src="/wp-content/uploads/2023/02/Ellipse-67.png" alt="expert" />	
		    </div>
				
			</div>
			<div class="kdtla-col-md-10">
			<div class="ask-exp-cntent">
				<p>Ask our experts . <a href="#request-callback">Request callback</a> . <a href="#cntct-sec">Email</a></p>
				
			</div>            
			</div>
            
            
            
			
		</div>
		
	</div>
    <div class="k_decoration_title">
		<p>
			Do you want to order this product Decorated or Blank? <br />
			<span class="decorated"><input type="checkbox" name="decorated" id="decorated"><span id="decorated-2">Decorated</span></span>
			
		</p>
		<!--
		<a href="javascript:void(0);" class="decoration_links order_decoration_links" id="order-blank-samples-button" >Order Blank Samples</a>
		-->
		
	</div>
    
    
	<div id="div_kcolors_trig" class="cs-tabpro-head"><span>1</span> Choose colors & sizes <i id="div_kcolors_trig_arrow" class="fa fa-angle-right"></i></div>
	<div id="div_kcolors" class="cs-tabpro-cntent">
				<div class="kdtla-row">
					<div class="kdtla-col-md-8">
					<div class="k_colors_main">
						<?php k_colors(); ?>
					</div>
					</div>
					<div class="kdtla-col-md-4">
						<span class="multi_colors"><input type="checkbox" name="multi_colors" id="multi_colors" />Multiple colors </span>
					</div>

				</div>
	
		<p class="kdtla-quantity-txt">Enter your quantity per size</p> 
		<?php k_quantities(); ?>
	

		<button id="kcolors_next" type="button" class="kdtla-nxt-btn">Next</button>

	</div>


	<div id="div_kdecor_trig" class="cs-tabpro-head"><span>2</span> Decoration details <i id="div_kdecor_trig_arrow" class="fa fa-angle-right"></i></div>
	<div id="div_kdecor" class="cs-tabpro-cntent">
		<p class="decoration_info">All product colors will take the same decoration methods and ink / thread colors by default.<br>
Let us know in Step 4 if certain product colors need an ink or thread color change.
</p>
		<div class="k_decoration_title">
		
		<a href="#artwork-guidelines" class="decoration_links artwork_decoration_links">Artwork Guidelines</a>
		<a href="#compare-decoration-methods" class="decoration_links compare_decoration_links">Compare Decoration Details</a>
		</div>
		
			<script type="text/javascript">
				// define decoration warnings, for #d_warning_front etc ; also used in #moq_info
				var d_warn = [];
				d_warn['sp'] = 'You Are Below MOQ - change print type to DTG or Waterbase transfers for qty’s under 25 pcs';
				d_warn['emb'] = 'You Are Below MOQ - change print type to DTG or Waterbase transfers for qty’s under 12 pcs';
				d_warn['no_artwork'] = 'Please add artwork...';
				d_warn['no_decor'] = 'Please choose decoration method...';
			</script>
		<?php k_positions(); ?>
		<button id="kdecor_next" type="button" class="kdtla-nxt-btn">Next</button>
		
	</div>

	<div id="div_kfin_trig" class="cs-tabpro-head"><span>3</span> Select finishing services <i id="div_kfin_trig_arrow" class="fa fa-angle-right"></i></div>
	<div id="div_kfin" class="cs-tabpro-cntent">
		<div id="blank_samples_info_fin">
			<h3>Blank Samples</h3>
			<p>Finishings disabled when ordering blank samples.
		</div>
		<div class="k_finishing">
			<!-- order of spans must be the same as in csv file -->
			<span class="fin_tile" data-fin="fbp">
				<span class="fin_tile_img">
					<img src="/wp-content/uploads/2023/02/image.png" alt="Fold & Poly Bag" />
				</span>
				<span class="fin_tile_cntent">
									<h3>Fold & Poly Bag</h3>
					<div class="fin_tile_name">
						$ <span id="fin1" class="fin_price">from $0.40</span>/piece <br />
					</div>
					<p class="fin_tile_days">
						+<span id="fin1_days_span">3</span> days
					</p>
				</span>
			</span>
			<span class="fin_tile" data-fin="ss">
				<span class="fin_tile_img">
					<img src="/wp-content/uploads/2023/02/image-1.png" alt="Size Sticker" />
				</span>
				<span class="fin_tile_cntent">
									<h3>Size Sticker</h3>
					<div class="fin_tile_name">
						$ <span id="fin2" class="fin_price">from $0.40</span>/piece <br />
					</div>
					<p class="fin_tile_days">
						+<span id="fin2_days_span">3</span> days
					</p>
				</span>
			</span>
			<span class="fin_tile" data-fin="upc">
				<span class="fin_tile_img">
					<img src="/wp-content/uploads/2023/08/1207-e1692926723453.png" alt="UPC Label" />
				</span>
				<span class="fin_tile_cntent">
									<h3>Custom UPC Label</h3>
					<div class="fin_tile_name">
						$ <span id="fin3" class="fin_price">from $0.40</span>/piece <br />
					</div>
					<p class="fin_tile_days">
						+<span id="fin3_days_span">3</span> days
					</p>
				</span>

			</span>
			<span class="fin_tile" data-fin="ht">
				<span class="fin_tile_img">
					<img src="/wp-content/uploads/2023/02/image-3.png" alt="Hang Tag" />
				</span>
				<span class="fin_tile_cntent">
									<h3>Hang Tags</h3>
					<div class="fin_tile_name">
						$ <span id="fin4" class="fin_price">from $0.40</span>/piece <br />
					</div>
					<p class="fin_tile_days"> +<span id="fin4_days_span">3</span> days</p>
				</span>

			</span>
			<span class="fin_tile" data-fin="wml">
				<span class="fin_tile_img">
					<img src="/wp-content/uploads/2023/02/image-2.png" alt="Woven Main Label" />
				</span>
				<span class="fin_tile_cntent">
									<h3>Woven Main Labels</h3>
					<div class="fin_tile_name">
						$ <span id="fin5" class="fin_price">from $0.40</span>/piece <br />
					</div>
					<p class="fin_tile_days"> +<span id="fin5_days_span">3</span>  days</p>
				</span>
			</span>
			<span class="fin_tile" data-fin="whl">
				<span class="fin_tile_img">
					<img src="/wp-content/uploads/2023/02/image-5.png" alt="Woven Hem Label" />
				</span>
				<span class="fin_tile_cntent">
									<h3>Woven Hem Labels</h3>
					<div class="fin_tile_name">
						$ <span id="fin6" class="fin_price">from $0.40</span>/piece <br />
					</div>
					<p class="fin_tile_days"> +<span id="fin6_days_span">3</span> days</p>
				</span>
			</span>
			<?php k_finishing(); ?>
		</div>
		<button id="kfin_next" type="button" class="kdtla-nxt-btn">Next</button>
	</div>


	<div id="div_knotes_trig" class="cs-tabpro-head"><span>4</span> Add design notes <i id="div_knotes_trig_arrow" class="fa fa-angle-right"></i></div>
		<div id="div_knotes" class="cs-tabpro-cntent">
		<textarea rows="3" name="design_notes" id="design_notes" placeholder="Please enter additional requirements..." ><?php echo $k_edit_notes; ?></textarea>
	</div>

	<div class="k_summary">

		<!-- 		<span class="quantity_total">Total: <input type="number" min="0" max="10000" name="quantity_total" id="quantity_total" readonly /></span> -->
		<br />
		<span class="x_from_row">You are <span id="x_from">xx</span> pieces from next price level. You would save <span id="x_saved">xx</span> per piece ... </span>

	  <div class="kdtla-row">
		 <div class="kdtla-col-md-6">
			<div class="summary_main_box summary_price">
			<h4>Total price</h4>
			<div class="summary_price_input">
				
				<span id="k_total_price_span"></span>
			</div>
				<div class="summary_sec_input">
			
				<span id="k_per_item_span"></span>
			per piece, Qty: <input type="integer" min="0" name="quantity_total2" id="quantity_total2"  placeholder="100" readonly />
            <p>
            <span>No Hidden Fees Guaranteed</span>
            </p>
			</div>
			</div>
		</div>
		  <div class="kdtla-col-md-6">
			<div class="summary_main_box summary_delivery">
			<h4>Estimated delivery</h4>
			<input type="text" name="k_delivery_dates" id="k_delivery_dates"  placeholder="5-7 days" readonly />
				<p>
					Shipping starts at: <br /> <span id="free_shipping_info">Shipping Calculated in Cart</span>
				</p>
			</div>
		  </div>
		</div>

		<div <?php if (!(isset($_GET['d']) && $_GET['d']==1)) echo 'style="display:none;" '; ?> >
			styles: <br />
			<input type="text" id="style1" name="style1" value="<?php echo $k_edit_style1; ?>" style="width:19%" />
			<input type="text" id="style2" name="style2" value="<?php echo $k_edit_style2; ?>" style="width:19%"  />
			<input type="text" id="style3" name="style3" value="<?php echo $k_edit_style3; ?>" style="width:19%"  />
			<input type="text" id="style4" name="style4" value="<?php echo $k_edit_style4; ?>" style="width:19%"  />
			<input type="text" id="style5" name="style5" value="<?php echo $k_edit_style5; ?>" style="width:19%"  />
			<br />
			<?php echo k_add_fields(); ?>
		</div>
		<?php //echo ' nnn='; print_r(WC()->session->get( 'wc_notices', array() ) ); ?>
	</div>

	 <!-- text updated via js also -->
	<span id="moq_info">
	You Are Below MOQ - change print type to DTG or Waterbase Transfers for qty’s under 25 pcs
	</span>

	<span id="moq_info_prod">
	You Are Below MOQ for this product
	</span>
	
	

	<script type="text/javascript">
		var site_url = '<?php echo site_url(); ?>';
		var plugin_url = '<?php echo plugin_dir_url( __FILE__ ) . '../'; ?>';
		var price_table = <?php echo k_get_price_table(); ?>;
		var product_moq = <?php echo k_get_product_moq(); ?>;
		var delivery_text = 'Business Days';
		var delivery_std = '<?php echo k_get_delivery_std(); ?>';
		var delivery_std_dy = '<?php echo k_get_delivery_std_dy(); ?>';
		var delivery_rush = '<?php echo k_get_delivery_rush(); ?>';
		var delivery_rush_dy = '<?php echo k_get_delivery_rush_dy(); ?>';
		var k_token = '<?php //echo k_token(); ?>';
		var k_editing = '<?php echo $k_editing; ?>';
		var k_edit_c = '<?php echo $k_edit_c; ?>';
		var k_edit_q = '<?php echo json_encode( $k_edit_q ); ?>';
		var k_edit_fin = '<?php echo $k_edit_fin; ?>';

	</script>
	<!-- buy now/place order buttons defined in ...  ; price_table_header not used -->
</div>

<!-- artwork upload popup -->
<div id="dropzone">

	<!-- <input type="checkbox" name="remove_bg" id="remove_bg" label="remove,, "  />  -->
	<!-- 	temp: <a href="#" id="download_b">download</a> <a href="#" id="link">link</a> <br /> -->

	<div class="kdtla-row">
		<div class="kdtla-col-md-6">
			<div class="artwork-img-box" id="artwork-img-prev">
				<!-- 	<img src="/wp-content/uploads/2023/02/Ellipse-67.png" alt="expert">	 -->
		    </div>
				
		</div>
		<div class="kdtla-col-md-6">
			<div class="dropzone-cntent">
				<h2>Upload Artwork</h2>
				<p>Acceptable file formats; .psd, .ai, .pdf, .tiff, .eps, .png Artwork should be expanded with all text converted to outlines and .psd files should be sent in 300 dpi to scale</p>
				<div class="dropzone-cntent-warn">
					<div class="warn-icon">
						<img src="/wp-content/uploads/2023/02/Frame-1.png" alt="" />
					</div>
					<div class="warn-icon-cntent">
						<p>
							Don’t worry! Every order is triple checked even if it does not look perfect in this demo.
						</p>
					</div>
				</div>
				<div class="dropzone-cntent-bg">
						<input type="checkbox" id="rebackground" name="rebackground" value="John">
						<label for="rebackground">Remove background <span>Remove backgrounds 100% automatically with one click</span></label>
				</div>
			</div>
		</div>
			
	</div>
	<div class="kdtla-row">
		<div class="kdtla-col-md-12">
			<span id="dropzone_close">Cancel</span>
			<label class="add-artwork-label"> Add Artwork
				<input type="file" accept="image/*,application/pdf,application/x-photoshop,application/postscript"  id="upload_b" multiple />
			</label>
			<span id="dropzone_save">Save & Close</span>
		</div>
	</div>
</div>


<!-- bulk pricing popup -->
<div class="bulk_pricing-modal" id="bulk_pricing">
  <div class="bulk_pricing-modal-overlay bulk_pricing-modal-toggle"></div>
  <div class="bulk_pricing-modal-wrapper bulk_pricing-modal-transition">
    <div class="bulk_pricing-modal-header">
      <h2 class="bulk_pricing-modal-heading">Bulk pricing discount</h2>
      <p class="bulk_pricing-modal-description">Use the chart below to determine the cost per item. Each price listed
        below is the 'per piece' price for the blank item plus ONE (1) Standard Print Location up to 14" Wide x 17" Tall
        using DTLA Print's premium services.</p>
    </div>
    <div class="bulk_pricing-modal-body">
      <div class="bulk_pricing-modal-content">
        <table id="table_bulk">
          <thead>
            <tr>
              <td></td>
              <td>Sample </td>
              <td> 50 </td>
              <td> 100 </td>
              <td> 250 </td>
              <td> 500 </td>
              <td> 1000</td>
              <td> 2500</td>
              <td> 5000</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Your selection</td>
              <td>$25.00 </td>
              <td> $700 </td>
              <td> $1200 </td>
              <td> $3000 </td>
              <td> $5000 </td>
              <td>$7000</td>
              <td> $9000</td>
              <td> $12000</td>
            </tr>
            <tr colspan="9">
              <td>Recommended</td>
            </tr>
            <tr>
              <td>Screen Print 1 Color</td>
              <td>$25.00 </td>
              <td> $700 </td>
              <td> $1200 </td>
              <td> $3000 </td>
              <td> $5000 </td>
              <td>$7000</td>
              <td> $9000</td>
              <td> $12000</td>
            </tr>
            <tr>
              <td>Screen Print 2 Colors</td>
              <td>$25.00 </td>
              <td> $700 </td>
              <td> $1200 </td>
              <td> $3000 </td>
              <td> $5000 </td>
              <td>$7000</td>
              <td> $9000</td>
              <td> $12000</td>
            </tr>
            <tr>
              <td>Screen Print 3 Colors</td>
              <td>$25.00 </td>
              <td> $700 </td>
              <td> $1200 </td>
              <td> $3000 </td>
              <td> $5000 </td>
              <td>$7000</td>
              <td> $9000</td>
              <td> $12000</td>
            </tr>
            <tr>
              <td>Screen Print 4 Colors</td>
              <td>$25.00 </td>
              <td> $700 </td>
              <td> $1200 </td>
              <td> $3000 </td>
              <td> $5000 </td>
              <td>$7000</td>
              <td> $9000</td>
              <td> $12000</td>
            </tr>
            <tr>
              <td>Embroidery</td>
              <td>$25.00 </td>
              <td> $700 </td>
              <td> $1200 </td>
              <td> $3000 </td>
              <td> $5000 </td>
              <td>$7000</td>
              <td> $9000</td>
              <td> $12000</td>
            </tr>
            <tr>
              <td>Direct to Garment</td>
              <td>$25.00 </td>
              <td> $700 </td>
              <td> $1200 </td>
              <td> $3000 </td>
              <td> $5000 </td>
              <td>$7000</td>
              <td> $9000</td>
              <td> $12000</td>
            </tr>
            <tr>
              <td>Water-based Transfer</td>
              <td>$25.00 </td>
              <td> $700 </td>
              <td> $1200 </td>
              <td> $3000 </td>
              <td> $5000 </td>
              <td>$7000</td>
              <td> $9000</td>
              <td> $12000</td>
            </tr>
          </tbody>
        </table>
      </div>
	 <div
          class="elementor-element elementor-element-4f6aff9 elementor-align-center elementor-widget elementor-widget-button"
          data-id="4f6aff9" data-element_type="widget" data-widget_type="button.default" bis_skin_checked="1">
          <div class="elementor-widget-container" bis_skin_checked="1">
            <div class="elementor-button-wrapper" bis_skin_checked="1">
              <a href="#" class="elementor-button-link elementor-button elementor-size-sm bulk_pricing-modal-toggle" role="button">
                <span class="elementor-button-content-wrapper">
                  <span class="elementor-button-text">Close</span>
                </span>
              </a>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>

<div id="dtla_loader">

</div>

<!-- kg version 
<div id="k_sticky">
	<span id="ks_title">The North Face product</span>
	<span id="ks_sku">TMTM1MU412</span>

	<div id="ks_delivery">Estimated delivery: <span id="ks_del">1-2 days</span> <br/> <span class="ks_rush">Rush available</span> </div>
	<div id="ks_quantity">Total: <span id="ks_q">100</span>pc @ <span id="ks_price">$12</span>/pc </div>
	<div id="ks_right"><div id="ks_total">$1111</div> <span id="ks_add">Add to cart</span> </div>

</div>
-->

<div id="k_sticky">
	<div id="sticky-primary">
		<div id="sticky-main">	
			<span id="ks_title">TMTM1MU412<span id="ks_sku">TMTM1MU412</span></span>
			<span id="ks_sku" class="ks_sku2">TMTM1MU412</span>

			<div id="ks_delivery">Est. Delivery: <span id="ks_del">1-2 days </span><span class="ks_rush"> Rush available</span> </div>
			<div id="ks_quantity">Total: <span id="ks_q">100</span> pcs @ <span id="ks_price">$12</span>/pc </div>
			<div id="ks_right"><div id="ks_total">$1111</div> <span id="ks_add">Add to cart</span> </div>
		</div>
	</div>
</div>