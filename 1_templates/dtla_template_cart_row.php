<!-- dtla_template_cart_row file -->
<div style="float: left;padding-right: 50px;">
<h6>Your quantities per size</h6>

<?php
if ( strlen(@$cart_item['ksizes'])>1)
	$ksizes = explode('|', $cart_item['ksizes']);
else 
	$ksizes = array('OS'); //?

$colors = array('1', '2', '3', '4', '5');

?>
<table style="width:auto" id="table_q">
	<thead><tr><td>Colors</td><?php foreach($ksizes as $s) echo '<td style="border-bottom: 1px solid black !important; font-weight: 600">'.$s.'</td>'; ?></tr></thead>
	<tbody>
		<tr>
		<?php 
		foreach( $colors as $k=>$c) {
			$ci = 'c'.$c.'_name';
			$chtml = 'c'.$c.'_html';
			$cs = 'style'.$c; // style_id

			//check if color is defined - and style
			if ( isset( $cart_item[$ci] ) && strlen($cart_item[$ci])>0 && isset( $cart_item[$cs])  ) {
				echo '<tr>';
				
				?>
				<td>
					<span class="kstyle_cart"><?php echo $cart_item[$cs]; ?></span>
					<span class="kcolor_box_cart" style="background-color: <?php echo $cart_item[$chtml] ?>;" data=""></span>
					<span  class="c_name_cart" ><?php echo $cart_item[$ci]; ?> </span>
				</td>

				<?php
				foreach( $ksizes as $s ) {
					$i = 'q'.$c.'_'. trim($s) ;
					if ( isset( $cart_item[$i] )) echo '<td><span class="q1" name="'.$i.'">' .$cart_item[$i].'</span></td>';
					else echo '<td><span class="q1" name="'.$i.'">0</span></td>';
				}
				echo '</tr>';
				
			}
		}
		$k_loc_type = array();
		?>	 
		</tr>
	</tbody>
</table>

</div>


<?php
	// display decoration and finishings only if not ordering blanks
	$p1 = '';
	if ( isset($cart_item['ordering_blanks']) && $cart_item['ordering_blanks'] == 'no' ):
		if ( isset($cart_item['ksession']) )
			$p1 = '/wp-content/uploads/artwork/'.$cart_item['ksession'];
?>



<div class="deco-details">

<h6>Decoration details</h6>
	<span>All colors will take the same decoration choices</span>

<table id="table_d">
		<thead>
			<td class="col-pos">Position</td>
			<td class="col-print">Print method</td>
			<?php if ((isset($cart_item['front_colors']))||(isset($cart_item['back_colors']))||(isset($cart_item['left_colors']))||(isset($cart_item['right_colors']))||(isset($cart_item['neck_colors']))) { ?>
			<td class="col-colors"># of colors</td>			
			<td class="col-ink">Type of ink</td>
			<?php } ?>
			<?php if ((isset($cart_item['art1_files']))||(isset($cart_item['art2_files']))||(isset($cart_item['art3_files']))||(isset($cart_item['art4_files']))||(isset($cart_item['art5_files']))) { ?>
			<td class="col-art">Artwork preview
				<?php
					//if ( isset($cart_item['art1_files']) || isset($cart_item['art2_files']) || isset($cart_item['art3_files']) || isset($cart_item['art4_files']) || isset($cart_item['art5_files']) )
					//	echo '<a href="'.$p1.'" target="_blank">(open folder)</a>';
				?>
			</td>
			<?php } ?>
		</thead>
		<tr <?php if ( isset($cart_item['front_print_type']) && strlen($cart_item['front_print_type'])>0 ) echo 'style="display:inline-table"'; ?>>
			<td class="col-pos">Front</td>
			<td class="col-print"><?php if (isset($cart_item['front_print_type'])) echo '<span  class="q1" >'.$cart_item['front_print_type'].'</span>';?></td>
			<?php if ((isset($cart_item['front_colors']))||(isset($cart_item['back_colors']))||(isset($cart_item['left_colors']))||(isset($cart_item['right_colors']))||(isset($cart_item['neck_colors']))) { ?>
			<td class="col-colors"><?php if (isset($cart_item['front_colors'])) echo '<span  class="q1" >'.$cart_item['front_colors'].'</span>'; ?></td>			
			<td class="col-ink"><?php if (isset($cart_item['front_ink'])) echo '<span  class="q1" >'.$cart_item['front_ink'].'</span>'; ?></td>
			<?php } ?>
			<?php if ((isset($cart_item['art1_files']))||(isset($cart_item['art2_files']))||(isset($cart_item['art3_files']))||(isset($cart_item['art4_files']))||(isset($cart_item['art5_files']))) { ?>
			<td class="col-art">
				<?php if (isset($cart_item['art1_files'])) { 
					$p2 = $p1.'/preview/canvas-front.png'; // art1_preview field not used...
					echo '<a href="'. $p2 .'" target="_blank"  >';
					echo '<img src="'. $p2 .'" width="50px" class="preview" />';
					echo '</a>';
					echo '<span  class="q1f" >';
					foreach ( explode('|',$cart_item['art1_files']) as $aa ) {
						// to show only filename
						$ff1 = explode( $cart_item['ksession'], $aa ); // so /front/xxx
						if ( isset($ff1[1])) {
							$ff2 = explode( '/', $ff1[1] ); // 0 is empty, 1 is front or back.., 2 is filename
							echo $ff2[2]; 
							echo '<br />';
						}
						
					}
					echo '</span>'; 
				} ?>
			</td>
			<?php } ?>
		</tr>
		<tr <?php if ( isset($cart_item['back_print_type']) && strlen($cart_item['back_print_type'])>0 ) echo 'style="display:inline-table"'; ?>>
			<td class="col-pos">Back</td>
			<td class="col-print"><?php if (isset($cart_item['back_print_type'])) echo '<span  class="q1" >'.$cart_item['back_print_type'].'</span>'; ?> </td>
			<?php if ((isset($cart_item['front_colors']))||(isset($cart_item['back_colors']))||(isset($cart_item['left_colors']))||(isset($cart_item['right_colors']))||(isset($cart_item['neck_colors']))) { ?>
			<td class="col-colors"><?php if (isset($cart_item['back_colors'])) echo '<span  class="q1" >'.$cart_item['back_colors'].'</span>'; ?></td>
			<td class="col-ink"><?php if (isset($cart_item['back_ink'])) echo '<span  class="q1" >'.$cart_item['back_ink'].'</span>'; ?></td>
			<?php } ?>
			<?php if ((isset($cart_item['art1_files']))||(isset($cart_item['art2_files']))||(isset($cart_item['art3_files']))||(isset($cart_item['art4_files']))||(isset($cart_item['art5_files']))) { ?>
			<td class="col-art">
				<?php if (isset($cart_item['art2_files'])) {
					$p2 = $p1.'/preview/canvas-back.png';
					echo '<a href="'. $p2 .'" target="_blank"  >';
					echo '<img src="'. $p2 .'" width="50px" class="preview" />';
					echo '</a>';
					echo '<span  class="q1f" >';
					foreach ( explode('|',$cart_item['art2_files']) as $aa ) {
						// to show only filename
						$ff1 = explode( $cart_item['ksession'], $aa ); // so /front/xxx
						if ( isset($ff1[1])) {
							$ff2 = explode( '/', $ff1[1] ); // 0 is empty, 1 is front or back.., 2 is filename
							echo $ff2[2]; 
							echo '<br />';
						}
						
					}
					echo '</span>'; 
				} ?>
			</td>
			<?php } ?>
		</tr>
		<tr <?php if ( isset($cart_item['left_print_type']) && strlen($cart_item['left_print_type'])>0 ) echo 'style="display:inline-table"'; ?> >
			<td class="col-pos">Left Sleeve</td>
			<td class="col-print"><?php if (isset($cart_item['left_print_type'])) echo '<span  class="q1" >'.$cart_item['left_print_type'].'</span>'; ?> </td>
			<?php if ((isset($cart_item['front_colors']))||(isset($cart_item['back_colors']))||(isset($cart_item['left_colors']))||(isset($cart_item['right_colors']))||(isset($cart_item['neck_colors']))) { ?>
			<td class="col-colors"><?php if (isset($cart_item['left_colors'])) echo '<span  class="q1" >'.$cart_item['left_colors'].'</span>'; ?></td>
			<td class="col-ink"><?php if (isset($cart_item['left_ink'])) echo '<span  class="q1" >'.$cart_item['left_ink'].'</span>'; ?></td>
			<?php } ?>
			<?php if ((isset($cart_item['art1_files']))||(isset($cart_item['art2_files']))||(isset($cart_item['art3_files']))||(isset($cart_item['art4_files']))||(isset($cart_item['art5_files']))) { ?>
			<td class="col-art">
				<?php if (isset($cart_item['art3_files'])) {
					$p2 = $p1.'/preview/canvas-left.png';
					echo '<a href="'. $p2 .'" target="_blank"  >';
					echo '<img src="'. $p2 .'" width="50px" class="preview" />';
					echo '</a>';
					echo '<span  class="q1f" >';
					foreach ( explode('|',$cart_item['art3_files']) as $aa ) {
						// to show only filename
						$ff1 = explode( $cart_item['ksession'], $aa ); // so /front/xxx
						if ( isset($ff1[1])) {
							$ff2 = explode( '/', $ff1[1] ); // 0 is empty, 1 is front or back.., 2 is filename
							echo $ff2[2]; 
							echo '<br />';
						}
						
					}
					echo '</span>'; 
				} ?>
			</td>
			<?php } ?>
		</tr>
		<tr <?php if ( isset($cart_item['right_print_type']) && strlen($cart_item['right_print_type'])>0 ) echo 'style="display:inline-table"'; ?>>
			<td class="col-pos">Right Sleeve</td>
			<td class="col-print"><?php if (isset($cart_item['right_print_type'])) echo '<span  class="q1" >'.$cart_item['right_print_type'].'</span>'; ?> </td>
			<?php if ((isset($cart_item['front_colors']))||(isset($cart_item['back_colors']))||(isset($cart_item['left_colors']))||(isset($cart_item['right_colors']))||(isset($cart_item['neck_colors']))) { ?>
			<td class="col-colors"><?php if (isset($cart_item['right_colors'])) echo '<span  class="q1" >'.$cart_item['right_colors'].'</span>'; ?></td>
			<td class="col-ink"><?php if (isset($cart_item['right_ink'])) echo '<span  class="q1" >'.$cart_item['right_ink'].'</span>'; ?></td>
			<?php } ?>
			<?php if ((isset($cart_item['art1_files']))||(isset($cart_item['art2_files']))||(isset($cart_item['art3_files']))||(isset($cart_item['art4_files']))||(isset($cart_item['art5_files']))) { ?>
			<td class="col-art">
				<?php if (isset($cart_item['art4_files'])) {
					$p2 = $p1.'/preview/canvas-right.png';
					echo '<a href="'. $p2 .'" target="_blank"  >';
					echo '<img src="'. $p2 .'" width="50px" class="preview" />';
					echo '</a>';
					echo '<span  class="q1f" >';
					foreach ( explode('|',$cart_item['art4_files']) as $aa ) {
						// to show only filename
						$ff1 = explode( $cart_item['ksession'], $aa ); // so /front/xxx
						if ( isset($ff1[1])) {
							$ff2 = explode( '/', $ff1[1] ); // 0 is empty, 1 is front or back.., 2 is filename
							echo $ff2[2]; 
							echo '<br />';
						}
						
					}
					echo '</span>'; 
				} ?>
			</td>
			<?php } ?>
		</tr>
		<tr <?php if ( isset($cart_item['neck_print_type']) && strlen($cart_item['neck_print_type'])>0 ) echo 'style="display:inline-table"'; ?>>
			<td class="col-pos">Neck Label</td>
			<td class="col-print"><?php if (isset($cart_item['neck_print_type'])) echo '<span  class="q1" >'.$cart_item['neck_print_type'].'</span>'; ?> </td>
			<?php if ((isset($cart_item['front_colors']))||(isset($cart_item['back_colors']))||(isset($cart_item['left_colors']))||(isset($cart_item['right_colors']))||(isset($cart_item['neck_colors']))) { ?>
			<td class="col-colors"><?php if (isset($cart_item['neck_colors'])) echo '<span  class="q1" >'.$cart_item['neck_colors'].'</span>'; ?></td>
			<td class="col-ink"><?php if (isset($cart_item['neck_ink'])) echo '<span  class="q1" >'.$cart_item['neck_ink'].'</span>'; ?></td>
			<?php } ?>
			<?php if ((isset($cart_item['art1_files']))||(isset($cart_item['art2_files']))||(isset($cart_item['art3_files']))||(isset($cart_item['art4_files']))||(isset($cart_item['art5_files']))) { ?>
			<td class="col-art">
				<?php if (isset($cart_item['art5_files'])) {
					echo '<span  class="q1f" >';
					foreach ( explode('|',$cart_item['art5_files']) as $aa ) {
						// to show only filename
						$ff1 = explode( $cart_item['ksession'], $aa ); // so /front/xxx
						if ( isset($ff1[1])) {
							$ff2 = explode( '/', $ff1[1] ); // 0 is empty, 1 is front or back.., 2 is filename
							echo $ff2[2]; 
							echo '<br />';
						}
						
					}
					
					echo '</span>'; 
				}
				?>
			</td>
			<?php } ?>
		</tr>
	</table>
</div>

<div style="float: left;">

<h6>Finishing services</h6>

<div id="k_cart_finishings">
<?php 

if (isset($cart_item['fin_all'])) {
	$fin_all_ar = explode(',', $cart_item['fin_all'] );  //print_r($fin_all_ar);

	foreach($fin_all_ar as $fin) {
		//echo ' f='.$fin;
		switch ( trim($fin) ) { 	//  fbp, ss, upc, ht, wml, hl
			case 'fbp':
		?>
				<span class="fin_tile" data-fin="fbp">
					<span class="fin_tile_img">
						<img src="/wp-content/uploads/2023/02/image.png" alt="fold bag pack" />
					</span>
					<span class="fin_tile_cntent">
										<h3>Fold & Poly Bag</h3>
						<div class="fin_tile_name">
							<span id="fin1">$<?php echo $cart_item['fin1_cost']; ?>/piece</span> <br />
						</div>
					</span>
				</span>
		<?php
			break;
			case 'ss':
				?>
				<span class="fin_tile" data-fin="ss">
					<span class="fin_tile_img">
						<img src="/wp-content/uploads/2023/02/image-1.png" alt="size sticker" />
					</span>
					<span class="fin_tile_cntent">
										<h3>Size Sticker</h3>
						<div class="fin_tile_name">
							<span id="fin2">$<?php echo $cart_item['fin2_cost']; ?>/piece</span> <br />
						</div>
					</span>
				</span>
		<?php
			break;
			case 'upc':
				?>
				<span class="fin_tile" data-fin="upc">
					<span class="fin_tile_img">
						<img src="/wp-content/uploads/2023/02/image-2.png" alt="Label Print" />
					</span>
					<span class="fin_tile_cntent">
										<h3>Custom UPC Label</h3>
						<div class="fin_tile_name">
							<span id="fin3">$<?php echo $cart_item['fin3_cost']; ?>/piece</span> <br />
						</div>
					</span>
				</span>
		<?php
			break;
			case 'ht':
				?>
				<span class="fin_tile" data-fin="ht">
					<span class="fin_tile_img">
						<img src="/wp-content/uploads/2023/02/image-3.png" alt="hang tag" />
					</span>
					<span class="fin_tile_cntent">
										<h3>Hang Tags</h3>
						<div class="fin_tile_name">
							<span id="fin4">$<?php echo $cart_item['fin4_cost']; ?>/piece</span> <br />
						</div>
					</span>
				</span>
		<?php
			break;
			case 'wml':
				?>
				<span class="fin_tile" data-fin="wml">
					<span class="fin_tile_img">
						<img src="/wp-content/uploads/2023/02/image-4.png" alt="woven main label" />
					</span>
					<span class="fin_tile_cntent">
										<h3>Woven Main Labels</h3>
						<div class="fin_tile_name">
							<span id="fin5">$<?php echo $cart_item['fin5_cost']; ?>/piece</span> <br />
						</div>
					</span>
				</span>
		<?php
			break;
			case 'whl':
				?>
				<span class="fin_tile" data-fin="whl">
					<span class="fin_tile_img">
						<img src="/wp-content/uploads/2023/02/image-5.png" alt="hem label" />
					</span>
					<span class="fin_tile_cntent">
										<h3>Woven Hem Labels</h3>
						<div class="fin_tile_name">
							<span id="fin6">$<?php echo $cart_item['fin6_cost']; ?>/piece</span> <br />
						</div>
					</span>
				</span>
		<?php
			break;

		}

	}
}
?>
</div>
<?php
endif; // blanks
?>


<h6>Design notes</h6>
<p class="design_notes_p">
<?php
if ( isset($cart_item['design_notes']) )
	echo $cart_item['design_notes'];

?>
</p>




<div id="cart_debug" style="color:#ccc;">
<?php
// Meta data.
if (isset($_GET['d']) && $_GET['d'] == 1 ) {

	echo '<br /><br /> to check: Total quantity: ';
	echo $cart_item['quantity_total2'];

	echo ' per item: '; echo $cart_item['k_per_item'];
	echo ' total price: '; echo $cart_item['k_total_price'];

	echo ' sizes: '; echo $cart_item['ksizes'];
	//$ksizes = explode('|', $cart_item['ksizes']);
	//print_r($ksizes);
	echo '<br />';
	echo ' blanks: '; echo $cart_item['ordering_blanks'];
	echo ' user session: '; echo $cart_item['ksession'];

	echo '<br /> <br /> meta: '; echo wc_get_formatted_cart_item_data( $cart_item ); 

}
?>
	<pre>
		<?php //print_r( $cart_item ); ?>
	</pre>
</div>
	
	
	
</div>