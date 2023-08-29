<?php
/*
Author: kg
Author URI: www.kgretk.com
File: Custom fields definition.
*/


defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
error_reporting(E_ALL);


// hook: plugins_loaded

function k_define_custom_fields() {
	global $custom_fields;

	$custom_fields = array(
		array( 'label' => 'style 1', 'key' => 'style1' ),
		array( 'label' => 'style 2', 'key' => 'style2' ),
		array( 'label' => 'style 3', 'key' => 'style3' ),
		array( 'label' => 'style 4', 'key' => 'style4' ),
		array( 'label' => 'style 5', 'key' => 'style5' ),

		array( 'label' => 'color 1', 'key' => 'c1_name' ),
		array( 'label' => 'color 2', 'key' => 'c2_name' ),
		array( 'label' => 'color 3', 'key' => 'c3_name' ),
		array( 'label' => 'color 4', 'key' => 'c4_name' ),
		array( 'label' => 'color 5', 'key' => 'c5_name' ),

		array( 'label' => 'color 1 html', 'key' => 'c1_html' ), // _ TODO
		array( 'label' => 'color 2 html', 'key' => 'c2_html' ),
		array( 'label' => 'color 3 html', 'key' => 'c3_html' ),
		array( 'label' => 'color 4 html', 'key' => 'c4_html' ),
		array( 'label' => 'color 5 html', 'key' => 'c5_html' ),

		// all sizes for color 1
		array( 'label' => 'color 1 size XXS', 'key' => 'q1_XXS' ),
		array( 'label' => 'color 1 size XS', 'key' => 'q1_XS' ),
		array( 'label' => 'color 1 size S', 'key' => 'q1_S' ), //color 1 size S
		array( 'label' => 'color 1 size M', 'key' => 'q1_M' ),
		array( 'label' => 'color 1 size L', 'key' => 'q1_L' ),
		array( 'label' => 'color 1 size XL', 'key' => 'q1_XL' ),
		array( 'label' => 'color 1 size 2XL', 'key' => 'q1_2XL' ), //TODO other? q1_S/M  q1_L/XL
		array( 'label' => 'color 1 size 3XL', 'key' => 'q1_3XL' ),
		array( 'label' => 'color 1 size 4XL', 'key' => 'q1_4XL' ),
		array( 'label' => 'color 1 size 5XL', 'key' => 'q1_5XL' ),
		array( 'label' => 'color 1 size XXL', 'key' => 'q1_XXL' ),
		array( 'label' => 'color 1 size 2XL.3XL', 'key' => 'q1_2XL.3XL' ), //2XL.3XL ??? not working...
		array( 'label' => 'color 1 size 2XL/3XL', 'key' => 'q1_2XL/3XL' ), //2XL.3XL 

		array( 'label' => 'color 1 size S/M', 'key' => 'q1_XS/S' ), // XS/S	S/M 	M/L 	L/XL
		array( 'label' => 'color 1 size S/M', 'key' => 'q1_S/M' ),
		array( 'label' => 'color 1 size M/L', 'key' => 'q1_M/L' ),
		array( 'label' => 'color 1 size L/XL', 'key' => 'q1_L/XL' ),

		array( 'label' => 'color 1 size LT', 'key' => 'q1_LT' ), //LT 	XLT 	2XLT 	3XLT 	4XLT
		array( 'label' => 'color 1 size XLT', 'key' => 'q1_XLT' ),
		array( 'label' => 'color 1 size 2XLT', 'key' => 'q1_2XLT' ),
		array( 'label' => 'color 1 size 3XLT', 'key' => 'q1_3XLT' ),
		array( 'label' => 'color 1 size 4XLT', 'key' => 'q1_4XLT' ),

		array( 'label' => 'color 1 size One Size', 'key' => 'q1_OS' ),
		array( 'label' => 'color 1 size Adjustable', 'key' => 'q1_Adjustable' ),
		array( 'label' => 'color 1 size Adult', 'key' => 'q1_Adult' ),
		
		array( 'label' => 'color 1 size 8', 'key' => 'q1_8' ), // 8	| 10 | 12 | 14 | 16
		array( 'label' => 'color 1 size 10', 'key' => 'q1_10' ),
		array( 'label' => 'color 1 size 12', 'key' => 'q1_12' ),
		array( 'label' => 'color 1 size 14', 'key' => 'q1_14' ),
		array( 'label' => 'color 1 size 16', 'key' => 'q1_16' ),
		
		array( 'label' => 'color 1 size NB', 'key' => 'q1_NB' ), //NB 	6M 	12M 	18M 	24M
		array( 'label' => 'color 1 size 6M', 'key' => 'q1_6M' ),
		array( 'label' => 'color 1 size 12M', 'key' => 'q1_12M' ),
		array( 'label' => 'color 1 size 18M', 'key' => 'q1_18M' ),
		array( 'label' => 'color 1 size 24M', 'key' => 'q1_24M' ),
		
		array( 'label' => 'color 1 size 3-6MO', 'key' => 'q1_3-6MO' ), //3-6MO 	6-12MO 	12-18MO 	18-24MO
		array( 'label' => 'color 1 size 6-12MO', 'key' => 'q1_6-12MO' ),
		array( 'label' => 'color 1 size 12-18MO', 'key' => 'q1_12-18MO' ),
		array( 'label' => 'color 1 size 18-24MO', 'key' => 'q1_18-24MO' ),

		array( 'label' => 'color 1 size 3-6', 'key' => 'q1_3-6' ), // 3/6 	6/12 	12/18 	18/24
		array( 'label' => 'color 1 size 6-12', 'key' => 'q1_6-12' ),
		array( 'label' => 'color 1 size 12-18', 'key' => 'q1_12-18' ),
		array( 'label' => 'color 1 size 18-24', 'key' => 'q1_18-24' ),
		
		array( 'label' => 'color 1 size 2T', 'key' => 'q1_2T' ), //2T 	4T 	5/6
		array( 'label' => 'color 1 size 3T', 'key' => 'q1_3T' ),
		array( 'label' => 'color 1 size 4T', 'key' => 'q1_4T' ),
		array( 'label' => 'color 1 size 5T', 'key' => 'q1_5T' ),
		array( 'label' => 'color 1 size 5-6', 'key' => 'q1_5-6' ),

		// color 2
		array( 'label' => 'color 2 size XXS', 'key' => 'q2_XXS' ),
		array( 'label' => 'color 2 size XS', 'key' => 'q2_XS' ),
		array( 'label' => 'color 2 size S', 'key' => 'q2_S' ), //color 1 size S
		array( 'label' => 'color 2 size M', 'key' => 'q2_M' ),
		array( 'label' => 'color 2 size L', 'key' => 'q2_L' ),
		array( 'label' => 'color 2 size XL', 'key' => 'q2_XL' ),
		array( 'label' => 'color 2 size 2XL', 'key' => 'q2_2XL' ), //TODO other? q2_S/M  q2_L/XL
		array( 'label' => 'color 2 size 3XL', 'key' => 'q2_3XL' ),
		array( 'label' => 'color 2 size 4XL', 'key' => 'q2_4XL' ),
		array( 'label' => 'color 2 size 5XL', 'key' => 'q2_5XL' ),
		array( 'label' => 'color 2 size XXL', 'key' => 'q2_XXL' ),
		array( 'label' => 'color 2 size 2XL.3XL', 'key' => 'q2_2XL.3XL' ), //2XL.3XL
		array( 'label' => 'color 2 size 2XL/3XL', 'key' => 'q2_2XL/3XL' ), //2XL.3XL 

		array( 'label' => 'color 2 size S/M', 'key' => 'q2_XS/S' ), // XS/S	S/M 	M/L 	L/XL
		array( 'label' => 'color 2 size S/M', 'key' => 'q2_S/M' ),
		array( 'label' => 'color 2 size M/L', 'key' => 'q2_M/L' ),
		array( 'label' => 'color 2 size L/XL', 'key' => 'q2_L/XL' ),

		array( 'label' => 'color 2 size LT', 'key' => 'q2_LT' ), //LT 	XLT 	2XLT 	3XLT 	4XLT
		array( 'label' => 'color 2 size XLT', 'key' => 'q2_XLT' ),
		array( 'label' => 'color 2 size 2XLT', 'key' => 'q2_2XLT' ),
		array( 'label' => 'color 2 size 3XLT', 'key' => 'q2_3XLT' ),
		array( 'label' => 'color 2 size 4XLT', 'key' => 'q2_4XLT' ),

		array( 'label' => 'color 2 size One Size', 'key' => 'q2_OS' ),
		array( 'label' => 'color 2 size Adjustable', 'key' => 'q2_Adjustable' ),
		array( 'label' => 'color 2 size Adult', 'key' => 'q2_Adult' ),
		
		array( 'label' => 'color 2 size 8', 'key' => 'q2_8' ), // 8	| 10 | 12 | 14 | 16
		array( 'label' => 'color 2 size 10', 'key' => 'q2_10' ),
		array( 'label' => 'color 2 size 12', 'key' => 'q2_12' ),
		array( 'label' => 'color 2 size 14', 'key' => 'q2_14' ),
		array( 'label' => 'color 2 size 16', 'key' => 'q2_16' ),
		
		array( 'label' => 'color 2 size NB', 'key' => 'q2_NB' ), //NB 	6M 	12M 	18M 	24M
		array( 'label' => 'color 2 size 6M', 'key' => 'q2_6M' ),
		array( 'label' => 'color 2 size 12M', 'key' => 'q2_12M' ),
		array( 'label' => 'color 2 size 18M', 'key' => 'q2_18M' ),
		array( 'label' => 'color 2 size 24M', 'key' => 'q2_24M' ),
		
		array( 'label' => 'color 2 size 3-6MO', 'key' => 'q2_3-6MO' ), //3-6MO 	6-12MO 	12-18MO 	18-24MO
		array( 'label' => 'color 2 size 6-12MO', 'key' => 'q2_6-12MO' ),
		array( 'label' => 'color 2 size 12-18MO', 'key' => 'q2_12-18MO' ),
		array( 'label' => 'color 2 size 18-24MO', 'key' => 'q2_18-24MO' ),

		array( 'label' => 'color 2 size 3-6', 'key' => 'q2_3-6' ), // 3/6 	6/12 	12/18 	18/24
		array( 'label' => 'color 2 size 6-12', 'key' => 'q2_6-12' ),
		array( 'label' => 'color 2 size 12-18', 'key' => 'q2_12-18' ),
		array( 'label' => 'color 2 size 18-24', 'key' => 'q2_18-24' ),
		
		array( 'label' => 'color 2 size 2T', 'key' => 'q2_2T' ), //2T 	4T 	5/6
		array( 'label' => 'color 2 size 3T', 'key' => 'q2_3T' ),
		array( 'label' => 'color 2 size 4T', 'key' => 'q2_4T' ),
		array( 'label' => 'color 2 size 5T', 'key' => 'q2_5T' ),
		array( 'label' => 'color 2 size 5-6', 'key' => 'q2_5-6' ),

		// color 3
		array( 'label' => 'color 3 size XXS', 'key' => 'q3_XXS' ),
		array( 'label' => 'color 3 size XS', 'key' => 'q3_XS' ),
		array( 'label' => 'color 3 size S', 'key' => 'q3_S' ), //color 1 size S
		array( 'label' => 'color 3 size M', 'key' => 'q3_M' ),
		array( 'label' => 'color 3 size L', 'key' => 'q3_L' ),
		array( 'label' => 'color 3 size XL', 'key' => 'q3_XL' ),
		array( 'label' => 'color 3 size 2XL', 'key' => 'q3_2XL' ), //TODO other? q3_S/M  q3_L/XL
		array( 'label' => 'color 3 size 3XL', 'key' => 'q3_3XL' ),
		array( 'label' => 'color 3 size 4XL', 'key' => 'q3_4XL' ),
		array( 'label' => 'color 3 size 5XL', 'key' => 'q3_5XL' ),
		array( 'label' => 'color 3 size XXL', 'key' => 'q3_XXL' ),
		array( 'label' => 'color 3 size 2XL.3XL', 'key' => 'q3_2XL.3XL' ), //2XL.3XL
		array( 'label' => 'color 3 size 2XL/3XL', 'key' => 'q3_2XL/3XL' ), //2XL.3XL 

		array( 'label' => 'color 3 size S/M', 'key' => 'q3_XS/S' ), // XS/S	S/M 	M/L 	L/XL
		array( 'label' => 'color 3 size S/M', 'key' => 'q3_S/M' ),
		array( 'label' => 'color 3 size M/L', 'key' => 'q3_M/L' ),
		array( 'label' => 'color 3 size l/XL', 'key' => 'q3_L/XL' ),

		array( 'label' => 'color 3 size LT', 'key' => 'q3_LT' ), //LT 	XLT 	2XLT 	3XLT 	4XLT
		array( 'label' => 'color 3 size XLT', 'key' => 'q3_XLT' ),
		array( 'label' => 'color 3 size 2XLT', 'key' => 'q3_2XLT' ),
		array( 'label' => 'color 3 size 3XLT', 'key' => 'q3_3XLT' ),
		array( 'label' => 'color 3 size 4XLT', 'key' => 'q3_4XLT' ),

		array( 'label' => 'color 3 size One Size', 'key' => 'q3_OS' ),
		array( 'label' => 'color 3 size Adjustable', 'key' => 'q3_Adjustable' ),
		array( 'label' => 'color 3 size Adult', 'key' => 'q3_Adult' ),
		
		array( 'label' => 'color 3 size 8', 'key' => 'q3_8' ), // 8	| 10 | 12 | 14 | 16
		array( 'label' => 'color 3 size 10', 'key' => 'q3_10' ),
		array( 'label' => 'color 3 size 12', 'key' => 'q3_12' ),
		array( 'label' => 'color 3 size 14', 'key' => 'q3_14' ),
		array( 'label' => 'color 3 size 16', 'key' => 'q3_16' ),
		
		array( 'label' => 'color 3 size NB', 'key' => 'q3_NB' ), //NB 	6M 	12M 	18M 	24M
		array( 'label' => 'color 3 size 6M', 'key' => 'q3_6M' ),
		array( 'label' => 'color 3 size 12M', 'key' => 'q3_12M' ),
		array( 'label' => 'color 3 size 18M', 'key' => 'q3_18M' ),
		array( 'label' => 'color 3 size 24M', 'key' => 'q3_24M' ),
		
		array( 'label' => 'color 3 size 3-6MO', 'key' => 'q3_3-6MO' ), //3-6MO 	6-12MO 	12-18MO 	18-24MO
		array( 'label' => 'color 3 size 6-12MO', 'key' => 'q3_6-12MO' ),
		array( 'label' => 'color 3 size 12-18MO', 'key' => 'q3_12-18MO' ),
		array( 'label' => 'color 3 size 18-24MO', 'key' => 'q3_18-24MO' ),

		array( 'label' => 'color 3 size 3-6', 'key' => 'q3_3-6' ), // 3/6 	6/12 	12/18 	18/24
		array( 'label' => 'color 3 size 6-12', 'key' => 'q3_6-12' ),
		array( 'label' => 'color 3 size 12-18', 'key' => 'q3_12-18' ),
		array( 'label' => 'color 3 size 18-24', 'key' => 'q3_18-24' ),
		
		array( 'label' => 'color 3 size 2T', 'key' => 'q3_2T' ), //2T 	4T 	5/6
		array( 'label' => 'color 3 size 3T', 'key' => 'q3_3T' ),
		array( 'label' => 'color 3 size 4T', 'key' => 'q3_4T' ),
		array( 'label' => 'color 3 size 5T', 'key' => 'q3_5T' ),
		array( 'label' => 'color 3 size 5-6', 'key' => 'q3_5-6' ),

		// color 4
		array( 'label' => 'color 4 size XXS', 'key' => 'q4_XXS' ),
		array( 'label' => 'color 4 size XS', 'key' => 'q4_XS' ),
		array( 'label' => 'color 4 size S', 'key' => 'q4_S' ), //color 1 size S
		array( 'label' => 'color 4 size M', 'key' => 'q4_M' ),
		array( 'label' => 'color 4 size L', 'key' => 'q4_L' ),
		array( 'label' => 'color 4 size XL', 'key' => 'q4_XL' ),
		array( 'label' => 'color 4 size 2XL', 'key' => 'q4_2XL' ), //TODO other? q4_S/M  q4_L/XL
		array( 'label' => 'color 4 size 3XL', 'key' => 'q4_3XL' ),
		array( 'label' => 'color 4 size 4XL', 'key' => 'q4_4XL' ),
		array( 'label' => 'color 4 size 5XL', 'key' => 'q4_5XL' ),
		array( 'label' => 'color 4 size XXL', 'key' => 'q4_XXL' ),
		array( 'label' => 'color 4 size 2XL.3XL', 'key' => 'q4_2XL.3XL' ), //2XL.3XL
		array( 'label' => 'color 4 size 2XL/3XL', 'key' => 'q4_2XL/3XL' ), //2XL.3XL 

		array( 'label' => 'color 4 size S/M', 'key' => 'q4_XS/S' ), // XS/S	S/M 	M/L 	L/XL
		array( 'label' => 'color 4 size S/M', 'key' => 'q4_S/M' ),
		array( 'label' => 'color 4 size M/L', 'key' => 'q4_M/L' ),
		array( 'label' => 'color 4 size L/XL', 'key' => 'q4_L/XL' ),

		array( 'label' => 'color 4 size LT', 'key' => 'q4_LT' ), //LT 	XLT 	2XLT 	3XLT 	4XLT
		array( 'label' => 'color 4 size XLT', 'key' => 'q4_XLT' ),
		array( 'label' => 'color 4 size 2XLT', 'key' => 'q4_2XLT' ),
		array( 'label' => 'color 4 size 3XLT', 'key' => 'q4_3XLT' ),
		array( 'label' => 'color 4 size 4XLT', 'key' => 'q4_4XLT' ),

		array( 'label' => 'color 4 size One Size', 'key' => 'q4_OS' ),
		array( 'label' => 'color 4 size Adjustable', 'key' => 'q4_Adjustable' ),
		array( 'label' => 'color 4 size Adult', 'key' => 'q4_Adult' ),
		
		array( 'label' => 'color 4 size 8', 'key' => 'q4_8' ), // 8	| 10 | 12 | 14 | 16
		array( 'label' => 'color 4 size 10', 'key' => 'q4_10' ),
		array( 'label' => 'color 4 size 12', 'key' => 'q4_12' ),
		array( 'label' => 'color 4 size 14', 'key' => 'q4_14' ),
		array( 'label' => 'color 4 size 16', 'key' => 'q4_16' ),
		
		array( 'label' => 'color 4 size NB', 'key' => 'q4_NB' ), //NB 	6M 	12M 	18M 	24M
		array( 'label' => 'color 4 size 6M', 'key' => 'q4_6M' ),
		array( 'label' => 'color 4 size 12M', 'key' => 'q4_12M' ),
		array( 'label' => 'color 4 size 18M', 'key' => 'q4_18M' ),
		array( 'label' => 'color 4 size 24M', 'key' => 'q4_24M' ),
		
		array( 'label' => 'color 4 size 3-6MO', 'key' => 'q4_3-6MO' ), //3-6MO 	6-12MO 	12-18MO 	18-24MO
		array( 'label' => 'color 4 size 6-12MO', 'key' => 'q4_6-12MO' ),
		array( 'label' => 'color 4 size 12-18MO', 'key' => 'q4_12-18MO' ),
		array( 'label' => 'color 4 size 18-24MO', 'key' => 'q4_18-24MO' ),

		array( 'label' => 'color 4 size 3-6', 'key' => 'q4_3-6' ), // 3/6 	6/12 	12/18 	18/24
		array( 'label' => 'color 4 size 6-12', 'key' => 'q4_6-12' ),
		array( 'label' => 'color 4 size 12-18', 'key' => 'q4_12-18' ),
		array( 'label' => 'color 4 size 18-24', 'key' => 'q4_18-24' ),
		
		array( 'label' => 'color 4 size 2T', 'key' => 'q4_2T' ), //2T 	4T 	5/6
		array( 'label' => 'color 4 size 3T', 'key' => 'q4_3T' ),
		array( 'label' => 'color 4 size 4T', 'key' => 'q4_4T' ),
		array( 'label' => 'color 4 size 5T', 'key' => 'q4_5T' ),
		array( 'label' => 'color 4 size 5-6', 'key' => 'q4_5-6' ),

		// color 5
		array( 'label' => 'color 5 size XXS', 'key' => 'q5_XXS' ),
		array( 'label' => 'color 5 size XS', 'key' => 'q5_XS' ),
		array( 'label' => 'color 5 size S', 'key' => 'q5_S' ), //color 1 size S
		array( 'label' => 'color 5 size M', 'key' => 'q5_M' ),
		array( 'label' => 'color 5 size L', 'key' => 'q5_L' ),
		array( 'label' => 'color 5 size XL', 'key' => 'q5_XL' ),
		array( 'label' => 'color 5 size 2XL', 'key' => 'q5_2XL' ), //TODO other? q5_S/M  q5_L/XL
		array( 'label' => 'color 5 size 3XL', 'key' => 'q5_3XL' ),
		array( 'label' => 'color 5 size 4XL', 'key' => 'q5_4XL' ),
		array( 'label' => 'color 5 size 5XL', 'key' => 'q5_5XL' ),
		array( 'label' => 'color 5 size XXL', 'key' => 'q5_XXL' ),
		array( 'label' => 'color 5 size 2XL.3XL', 'key' => 'q5_2XL.3XL' ), //2XL.3XL 
		array( 'label' => 'color 5 size 2XL/3XL', 'key' => 'q5_2XL/3XL' ), //2XL.3XL 

		array( 'label' => 'color 5 size S/M', 'key' => 'q5_XS/S' ), // XS/S	S/M 	M/L 	L/XL
		array( 'label' => 'color 5 size S/M', 'key' => 'q5_S/M' ),
		array( 'label' => 'color 5 size M/L', 'key' => 'q5_M/L' ),
		array( 'label' => 'color 5 size L/XL', 'key' => 'q5_L/XL' ),

		array( 'label' => 'color 5 size LT', 'key' => 'q5_LT' ), //LT 	XLT 	2XLT 	3XLT 	4XLT
		array( 'label' => 'color 5 size XLT', 'key' => 'q5_XLT' ),
		array( 'label' => 'color 5 size 2XLT', 'key' => 'q5_2XLT' ),
		array( 'label' => 'color 5 size 3XLT', 'key' => 'q5_3XLT' ),
		array( 'label' => 'color 5 size 4XLT', 'key' => 'q5_4XLT' ),

		array( 'label' => 'color 5 size One Size', 'key' => 'q5_OS' ),
		array( 'label' => 'color 5 size Adjustable', 'key' => 'q5_Adjustable' ),
		array( 'label' => 'color 5 size Adult', 'key' => 'q5_Adult' ),
		
		array( 'label' => 'color 5 size 8', 'key' => 'q5_8' ), // 8	| 10 | 12 | 14 | 16
		array( 'label' => 'color 5 size 10', 'key' => 'q5_10' ),
		array( 'label' => 'color 5 size 12', 'key' => 'q5_12' ),
		array( 'label' => 'color 5 size 14', 'key' => 'q5_14' ),
		array( 'label' => 'color 5 size 16', 'key' => 'q5_16' ),
		
		array( 'label' => 'color 5 size NB', 'key' => 'q5_NB' ), //NB 	6M 	12M 	18M 	24M
		array( 'label' => 'color 5 size 6M', 'key' => 'q5_6M' ),
		array( 'label' => 'color 5 size 12M', 'key' => 'q5_12M' ),
		array( 'label' => 'color 5 size 18M', 'key' => 'q5_18M' ),
		array( 'label' => 'color 5 size 24M', 'key' => 'q5_24M' ),
		
		array( 'label' => 'color 5 size 3-6MO', 'key' => 'q5_3-6MO' ), //3-6MO 	6-12MO 	12-18MO 	18-24MO
		array( 'label' => 'color 5 size 6-12MO', 'key' => 'q5_6-12MO' ),
		array( 'label' => 'color 5 size 12-18MO', 'key' => 'q5_12-18MO' ),
		array( 'label' => 'color 5 size 18-24MO', 'key' => 'q5_18-24MO' ),

		array( 'label' => 'color 5 size 3-6', 'key' => 'q5_3-6' ), // 3/6 	6/12 	12/18 	18/24
		array( 'label' => 'color 5 size 6-12', 'key' => 'q5_6-12' ),
		array( 'label' => 'color 5 size 12-18', 'key' => 'q5_12-18' ),
		array( 'label' => 'color 5 size 18-24', 'key' => 'q5_18-24' ),
		
		array( 'label' => 'color 5 size 2T', 'key' => 'q5_2T' ), //2T 	4T 	5/6
		array( 'label' => 'color 5 size 3T', 'key' => 'q5_3T' ),
		array( 'label' => 'color 5 size 4T', 'key' => 'q5_4T' ),
		array( 'label' => 'color 5 size 5T', 'key' => 'q5_5T' ),
		array( 'label' => 'color 5 size 5-6', 'key' => 'q5_5-6' ),

		// decorations
		array( 'label' => 'Front', 'key' => 'front_print_type' ),
		array( 'label' => 'Back', 'key' => 'back_print_type' ),
		array( 'label' => 'Left', 'key' => 'left_print_type' ),
		array( 'label' => 'Right', 'key' => 'right_print_type' ),
		array( 'label' => 'Neck', 'key' => 'neck_print_type' ),

		array( 'label' => 'Front colors', 'key' => 'front_colors' ),
		array( 'label' => 'Front ink', 'key' => 'front_ink' ),
		array( 'label' => 'Back colors', 'key' => 'back_colors' ),
		array( 'label' => 'Back ink', 'key' => 'back_ink' ),
		array( 'label' => 'Left colors', 'key' => 'left_colors' ),
		array( 'label' => 'Left ink', 'key' => 'left_ink' ),
		array( 'label' => 'Right colors', 'key' => 'right_colors' ),
		array( 'label' => 'Right ink', 'key' => 'right_ink' ),
		array( 'label' => 'Neck label colors', 'key' => 'neck_colors' ),
		array( 'label' => 'Neck label ink', 'key' => 'neck_ink' ),

		// finishings
		array( 'label' => 'Finishings', 'key' => 'fin_all' ),
		array( 'label' => 'Finishings cost', 'key' => 'fin_all_cost' ),

		array( 'label' => 'Design notes', 'key' => 'design_notes' ),
		
		array( 'label' => 'Quantity total', 'key' => 'quantity_total2' ),
		array( 'label' => 'Price per item', 'key' => 'k_per_item' ),
		array( 'label' => 'Price total', 'key' => 'k_total_price' ),

		// artwork
		array( 'label' => 'Artwork 1 preview', 'key' => 'art1_preview' ),
		array( 'label' => 'Artwork 2 preview', 'key' => 'art2_preview' ),
		array( 'label' => 'Artwork 3 preview', 'key' => 'art3_preview' ),
		array( 'label' => 'Artwork 4 preview', 'key' => 'art4_preview' ),
		array( 'label' => 'Artwork 5 preview', 'key' => 'art5_preview' ),

		array( 'label' => 'Artwork 1 files', 'key' => 'art1_files' ),
		array( 'label' => 'Artwork 2 files', 'key' => 'art2_files' ),
		array( 'label' => 'Artwork 3 files', 'key' => 'art3_files' ),
		array( 'label' => 'Artwork 4 files', 'key' => 'art4_files' ),
		array( 'label' => 'Artwork 5 files', 'key' => 'art5_files' ),

		array( 'label' => 'Artwork positions', 'key' => '_art_pos' ), // one field for all artwork on all 4 canvases , _

		// other
		array( 'label' => 'Ordering Blanks', 'key' => 'ordering_blanks' ),
		array( 'label' => 'user session', 'key' => 'ksession' ),
		array( 'label' => 'variation', 'key' => 'kvariation' ), // for editing

		// needed for cart
		array( 'label' => 'sizes', 'key' => 'ksizes' ),
		array( 'label' => 'Fin1 cost', 'key' => 'fin1_cost' ),
		array( 'label' => 'Fin2 cost', 'key' => 'fin2_cost' ),
		array( 'label' => 'Fin3 cost', 'key' => 'fin3_cost' ),
		array( 'label' => 'Fin4 cost', 'key' => 'fin4_cost' ),
		array( 'label' => 'Fin5 cost', 'key' => 'fin5_cost' ),
		array( 'label' => 'Fin6 cost', 'key' => 'fin6_cost' ), 
		
		// TODO add days?




		// sizes added 8/3 

		array( 'label' => 'color 1 size 2', 'key' => 'q1_2' ),
		array( 'label' => 'color 1 size 4', 'key' => 'q1_4' ),
		array( 'label' => 'color 1 size 6', 'key' => 'q1_6' ),
		array( 'label' => 'color 1 size 7', 'key' => 'q1_7' ),
		array( 'label' => 'color 1 size 28', 'key' => 'q1_28' ),
		array( 'label' => 'color 1 size 30', 'key' => 'q1_30' ),
		array( 'label' => 'color 1 size 32', 'key' => 'q1_32' ),
		array( 'label' => 'color 1 size 34', 'key' => 'q1_34' ),
		array( 'label' => 'color 1 size 36', 'key' => 'q1_36' ),
		array( 'label' => 'color 1 size 38', 'key' => 'q1_38' ),
		array( 'label' => 'color 1 size 40', 'key' => 'q1_40' ),
		array( 'label' => 'color 1 size 42', 'key' => 'q1_42' ),
		array( 'label' => 'color 1 size 43', 'key' => 'q1_43' ),
		array( 'label' => 'color 1 size 6XL', 'key' => 'q1_6XL' ),
		array( 'label' => 'color 1 size 2/3', 'key' => 'q1_2/3' ),
		array( 'label' => 'color 1 size 32W', 'key' => 'q1_32W' ),
		array( 'label' => 'color 1 size 34W', 'key' => 'q1_34W' ),
		array( 'label' => 'color 1 size 36W', 'key' => 'q1_36W' ),
		array( 'label' => 'color 1 size 38W', 'key' => 'q1_38W' ),
		array( 'label' => 'color 1 size 4/5', 'key' => 'q1_4/5' ),
		array( 'label' => 'color 1 size 40W', 'key' => 'q1_40W' ),
		array( 'label' => 'color 1 size 42W', 'key' => 'q1_42W' ),
		array( 'label' => 'color 1 size 44W', 'key' => 'q1_44W' ),
		array( 'label' => 'color 1 size 4XL/5XL', 'key' => 'q1_4XL/5XL' ),
		array( 'label' => 'color 1 size 6XL', 'key' => 'q1_6XL' ),
		array( 'label' => 'color 1 size MT', 'key' => 'q1_MT' ),
		array( 'label' => 'color 1 size OneSize', 'key' => 'q1_OneSize' ),
		array( 'label' => 'color 1 size XL/2XL', 'key' => 'q1_XL/2XL' ),
		array( 'label' => 'color 1 size XL/2XL', 'key' => 'q1_XL/2XL' ),
		array( 'label' => 'color 1 size Youth', 'key' => 'q1_Youth' ),

		array( 'label' => 'color 2 size 2', 'key' => 'q2_2' ),
		array( 'label' => 'color 2 size 4', 'key' => 'q2_4' ),
		array( 'label' => 'color 2 size 6', 'key' => 'q2_6' ),
		array( 'label' => 'color 2 size 7', 'key' => 'q2_7' ),
		array( 'label' => 'color 2 size 28', 'key' => 'q2_28' ),
		array( 'label' => 'color 2 size 30', 'key' => 'q2_30' ),
		array( 'label' => 'color 2 size 32', 'key' => 'q2_32' ),
		array( 'label' => 'color 2 size 34', 'key' => 'q2_34' ),
		array( 'label' => 'color 2 size 36', 'key' => 'q2_36' ),
		array( 'label' => 'color 2 size 38', 'key' => 'q2_38' ),
		array( 'label' => 'color 2 size 40', 'key' => 'q2_40' ),
		array( 'label' => 'color 2 size 42', 'key' => 'q2_42' ),
		array( 'label' => 'color 2 size 43', 'key' => 'q2_43' ),
		array( 'label' => 'color 2 size 6XL', 'key' => 'q2_6XL' ),
		array( 'label' => 'color 2 size 2/3', 'key' => 'q2_2/3' ),
		array( 'label' => 'color 2 size 32W', 'key' => 'q2_32W' ),
		array( 'label' => 'color 2 size 34W', 'key' => 'q2_34W' ),
		array( 'label' => 'color 2 size 36W', 'key' => 'q2_36W' ),
		array( 'label' => 'color 2 size 38W', 'key' => 'q2_38W' ),
		array( 'label' => 'color 2 size 4/5', 'key' => 'q2_4/5' ),
		array( 'label' => 'color 2 size 40W', 'key' => 'q2_40W' ),
		array( 'label' => 'color 2 size 42W', 'key' => 'q2_42W' ),
		array( 'label' => 'color 2 size 44W', 'key' => 'q2_44W' ),
		array( 'label' => 'color 2 size 4XL/5XL', 'key' => 'q2_4XL/5XL' ),
		array( 'label' => 'color 2 size 6XL', 'key' => 'q2_6XL' ),
		array( 'label' => 'color 2 size MT', 'key' => 'q2_MT' ),
		array( 'label' => 'color 2 size OneSize', 'key' => 'q2_OneSize' ),
		array( 'label' => 'color 2 size XL/2XL', 'key' => 'q2_XL/2XL' ),
		array( 'label' => 'color 2 size XL/2XL', 'key' => 'q2_XL/2XL' ),
		array( 'label' => 'color 2 size Youth', 'key' => 'q2_Youth' ),
		

		array( 'label' => 'color 3 size 2', 'key' => 'q3_2' ),
		array( 'label' => 'color 3 size 4', 'key' => 'q3_4' ),
		array( 'label' => 'color 3 size 6', 'key' => 'q3_6' ),
		array( 'label' => 'color 3 size 7', 'key' => 'q3_7' ),
		array( 'label' => 'color 3 size 28', 'key' => 'q3_28' ),
		array( 'label' => 'color 3 size 30', 'key' => 'q3_30' ),
		array( 'label' => 'color 3 size 32', 'key' => 'q3_32' ),
		array( 'label' => 'color 3 size 34', 'key' => 'q3_34' ),
		array( 'label' => 'color 3 size 36', 'key' => 'q3_36' ),
		array( 'label' => 'color 3 size 38', 'key' => 'q3_38' ),
		array( 'label' => 'color 3 size 40', 'key' => 'q3_40' ),
		array( 'label' => 'color 3 size 42', 'key' => 'q3_42' ),
		array( 'label' => 'color 3 size 43', 'key' => 'q3_43' ),
		array( 'label' => 'color 3 size 6XL', 'key' => 'q3_6XL' ),
		array( 'label' => 'color 3 size 2/3', 'key' => 'q3_2/3' ),
		array( 'label' => 'color 3 size 32W', 'key' => 'q3_32W' ),
		array( 'label' => 'color 3 size 34W', 'key' => 'q3_34W' ),
		array( 'label' => 'color 3 size 36W', 'key' => 'q3_36W' ),
		array( 'label' => 'color 3 size 38W', 'key' => 'q3_38W' ),
		array( 'label' => 'color 3 size 4/5', 'key' => 'q3_4/5' ),
		array( 'label' => 'color 3 size 40W', 'key' => 'q3_40W' ),
		array( 'label' => 'color 3 size 42W', 'key' => 'q3_42W' ),
		array( 'label' => 'color 3 size 44W', 'key' => 'q3_44W' ),
		array( 'label' => 'color 3 size 4XL/5XL', 'key' => 'q3_4XL/5XL' ),
		array( 'label' => 'color 3 size 6XL', 'key' => 'q3_6XL' ),
		array( 'label' => 'color 3 size MT', 'key' => 'q3_MT' ),
		array( 'label' => 'color 3 size OneSize', 'key' => 'q3_OneSize' ),
		array( 'label' => 'color 3 size XL/2XL', 'key' => 'q3_XL/2XL' ),
		array( 'label' => 'color 3 size XL/2XL', 'key' => 'q3_XL/2XL' ),
		array( 'label' => 'color 3 size Youth', 'key' => 'q3_Youth' ),

		array( 'label' => 'color 4 size 2', 'key' => 'q4_2' ),
		array( 'label' => 'color 4 size 4', 'key' => 'q4_4' ),
		array( 'label' => 'color 4 size 6', 'key' => 'q4_6' ),
		array( 'label' => 'color 4 size 7', 'key' => 'q4_7' ),
		array( 'label' => 'color 4 size 28', 'key' => 'q4_28' ),
		array( 'label' => 'color 4 size 30', 'key' => 'q4_30' ),
		array( 'label' => 'color 4 size 32', 'key' => 'q4_32' ),
		array( 'label' => 'color 4 size 34', 'key' => 'q4_34' ),
		array( 'label' => 'color 4 size 36', 'key' => 'q4_36' ),
		array( 'label' => 'color 4 size 38', 'key' => 'q4_38' ),
		array( 'label' => 'color 4 size 40', 'key' => 'q4_40' ),
		array( 'label' => 'color 4 size 42', 'key' => 'q4_42' ),
		array( 'label' => 'color 4 size 43', 'key' => 'q4_43' ),
		array( 'label' => 'color 4 size 6XL', 'key' => 'q4_6XL' ),
		array( 'label' => 'color 4 size 2/3', 'key' => 'q4_2/3' ),
		array( 'label' => 'color 4 size 32W', 'key' => 'q4_32W' ),
		array( 'label' => 'color 4 size 34W', 'key' => 'q4_34W' ),
		array( 'label' => 'color 4 size 36W', 'key' => 'q4_36W' ),
		array( 'label' => 'color 4 size 38W', 'key' => 'q4_38W' ),
		array( 'label' => 'color 4 size 4/5', 'key' => 'q4_4/5' ),
		array( 'label' => 'color 4 size 40W', 'key' => 'q4_40W' ),
		array( 'label' => 'color 4 size 42W', 'key' => 'q4_42W' ),
		array( 'label' => 'color 4 size 44W', 'key' => 'q4_44W' ),
		array( 'label' => 'color 4 size 4XL/5XL', 'key' => 'q4_4XL/5XL' ),
		array( 'label' => 'color 4 size 6XL', 'key' => 'q4_6XL' ),
		array( 'label' => 'color 4 size MT', 'key' => 'q4_MT' ),
		array( 'label' => 'color 4 size OneSize', 'key' => 'q4_OneSize' ),
		array( 'label' => 'color 4 size XL/2XL', 'key' => 'q4_XL/2XL' ),
		array( 'label' => 'color 4 size XL/2XL', 'key' => 'q4_XL/2XL' ),
		array( 'label' => 'color 4 size Youth', 'key' => 'q4_Youth' ),

		array( 'label' => 'color 5 size 2', 'key' => 'q5_2' ),
		array( 'label' => 'color 5 size 4', 'key' => 'q5_4' ),
		array( 'label' => 'color 5 size 6', 'key' => 'q5_6' ),
		array( 'label' => 'color 5 size 7', 'key' => 'q5_7' ),
		array( 'label' => 'color 5 size 28', 'key' => 'q5_28' ),
		array( 'label' => 'color 5 size 30', 'key' => 'q5_30' ),
		array( 'label' => 'color 5 size 32', 'key' => 'q5_32' ),
		array( 'label' => 'color 5 size 34', 'key' => 'q5_34' ),
		array( 'label' => 'color 5 size 36', 'key' => 'q5_36' ),
		array( 'label' => 'color 5 size 38', 'key' => 'q5_38' ),
		array( 'label' => 'color 5 size 40', 'key' => 'q5_40' ),
		array( 'label' => 'color 5 size 42', 'key' => 'q5_42' ),
		array( 'label' => 'color 5 size 43', 'key' => 'q5_43' ),
		array( 'label' => 'color 5 size 6XL', 'key' => 'q5_6XL' ),
		array( 'label' => 'color 5 size 2/3', 'key' => 'q5_2/3' ),
		array( 'label' => 'color 5 size 32W', 'key' => 'q5_32W' ),
		array( 'label' => 'color 5 size 34W', 'key' => 'q5_34W' ),
		array( 'label' => 'color 5 size 36W', 'key' => 'q5_36W' ),
		array( 'label' => 'color 5 size 38W', 'key' => 'q5_38W' ),
		array( 'label' => 'color 5 size 4/5', 'key' => 'q5_4/5' ),
		array( 'label' => 'color 5 size 40W', 'key' => 'q5_40W' ),
		array( 'label' => 'color 5 size 42W', 'key' => 'q5_42W' ),
		array( 'label' => 'color 5 size 44W', 'key' => 'q5_44W' ),
		array( 'label' => 'color 5 size 4XL/5XL', 'key' => 'q5_4XL/5XL' ),
		array( 'label' => 'color 5 size 6XL', 'key' => 'q5_6XL' ),
		array( 'label' => 'color 5 size MT', 'key' => 'q5_MT' ),
		array( 'label' => 'color 5 size OneSize', 'key' => 'q5_OneSize' ),
		array( 'label' => 'color 5 size XL/2XL', 'key' => 'q5_XL/2XL' ),
		array( 'label' => 'color 5 size XL/2XL', 'key' => 'q5_XL/2XL' ),
		array( 'label' => 'color 5 size Youth', 'key' => 'q5_Youth' ),


		/*
		array(
			'label' => 'Front',
			'key' => 'front',
			'classlist' => 'small-12',
			'required' => true,
			'select' => false,
			
		), */

	);

}


add_action('plugins_loaded','k_define_custom_fields');


/*
		array(
			'label' => 'Right',
			'key' => 'right_sleeve',
			'classlist' => 'small-12',
			'required' => true,
			'select' => false,
			
		),
		array(
			'label' => 'size Emb',
			'key' => 'front_size_emb',
			'type' => 'number',
			'classlist' => 'small-6',
			'min' => 1,
			'required' => true,
			'select' => false,
		),
		array(
			'label' => 'Sizes',
			'key' => 'sizes',
			'classlist' => 'small-12',
			'required' => true,
			'select' => true,
			'options' => array('S', 'M'),
		),

*/

