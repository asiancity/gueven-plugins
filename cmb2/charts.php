<?php
function ath_update_function( $post_id ){
  global $post;
	if ( ! wp_is_post_revision( $post_id ) ){
    if($post->post_type != 'charts')
        return;

    require('simple_html_dom.php');
    $content = $post->post_content;
    if ( $content ){

      $content = str_get_html($content);
      $title  = $content->find('tr', 0)->find('td', 1)->innertext;
      $ref    = $content->find('tr', 1)->find('td', 1)->innertext;
      $page   = $content->find('tr', 2)->find('td', 1)->innertext;
      $note   = ($content->find('tr', 3)->find('td', 0)->innertext == 'Anmerkung:') ? $content->find('tr', 3)->find('td', 1)->innertext : '';

      update_post_meta($post->ID, 'charts_title', $title);
      update_post_meta($post->ID, 'charts_reference', $ref);
      update_post_meta($post->ID, 'charts_page', $page);
      update_post_meta($post->ID, 'charts_note', $note);

      if( $content->find('tr', 5)->find('td', 0)->innertext == "Tabelle:"){
        $a = 0;
        for( $i=2; $i <= 10; $i++){
          if ( $content->find('tr', 5)->find('td', $i)->innertext ){
            $xValue[] = $content->find('tr', 5)->find('td', $i)->innertext;
            $a++;
          }
        }
        update_post_meta($post->ID, 'charts_value_xtitle', $xValue);

        $b = 0;
        $yValue = '';
        for( $i=6; $i <= 20; $i++){
          if ( $content->find('tr', $i) ){
            // $yValue[$b] = $i;
            $yValue[$b]["ytitle"] = $content->find('tr', $i)->find('td', 1)->innertext;
            $d=0;
            for( $c=2; $c <= ($a+2); $c++){
              if ( $content->find('tr', $i)->find('td', $c) ){
                $yValue[$b]["x-wert"][$d] = str_replace( ',','.', $content->find('tr', $i)->find('td', $c)->innertext);
                $d++;
              }
            }
            $b++;
          }

        }
        update_post_meta($post->ID, 'charts_value_items', $yValue);
      }


    }
	}
}
add_action('save_post', 'ath_update_function', 990);

add_action( 'cmb2_admin_init', 'ath_register_charts_value_metabox' );
function ath_register_charts_value_metabox(){
  $prefix = 'charts_value_';
  $cmb = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => 'Charts Werte',
		'object_types'  => array( 'charts' ), // Post type
    'priority'   => 'low',
    'classes'    => 'extra-form-2',
	) );
  $cmb->add_field( array(
    'name' => 'X Titel',
    'id'   => $prefix . 'xtitle',
    'type' => 'text_medium',
    'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

  $group_field_id = $cmb->add_field( array(
    'id'          => $prefix . 'items',
    'type'        => 'group',
    'description' => '',
    'repeatable'  => true,
    'options'     => array(
        'group_title'   => '{#})  Y-Wert' , // since version 1.1.4, {#} gets replaced by row number
        'add_button'    => __( 'Add Another Entry', 'cmb2' ),
        'remove_button' => __( 'Remove Entry', 'cmb2' ),
        'sortable'      => true, // beta
        // 'closed'     => true, // true to have the groups closed by default
    ),
  ) );
  $cmb->add_group_field( $group_field_id, array(
      'name' => 'Y Titel',
      'id'   => 'ytitle',
      'type' => 'text_medium',
      'repeatable' => false, // Repeatable fields are supported w/in repeatable groups (for most types)
      'sortable'	  => true, // beta
  ) );

  $cmb->add_group_field( $group_field_id, array(
      'name' => 'X-Wert',
      'id'   => 'x-wert',
      'type' => 'text_small',
      'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
      'sortable'	  => true, // beta
  ) );
}

add_action( 'cmb2_admin_init', 'ath_register_charts_metabox' );
function ath_register_charts_metabox(){
  $prefix = 'charts_';
  $cmb = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => 'Charts',
		'object_types'  => array( 'charts' ), // Post type
    'priority'   => 'high',
		'classes'    => 'extra-form', // Extra cmb2-wrap classes
	) );
  $cmb->add_field( array(
		'name'             => 'Chart Type',
		'desc'             => '',
		'id'               => $prefix . 'type',
    'type'             => 'select',
		'show_option_none' => false,
    'options'          => array(
			'starked_bar_chart' => 'Stacked Bar Chart',
      'basic_bar'         => 'Basic Bar'
		),
	) );
  $cmb->add_field( array(
		'name'       => 'Titel',
		'desc'       => '',
		'id'         => $prefix . 'title',
		'type'       => 'text'
	) );
  $cmb->add_field( array(
		'name'       => 'Quelle',
		'desc'       => '',
		'id'         => $prefix . 'reference',
		'type'       => 'text'
	) );
  $cmb->add_field( array(
		'name'       => 'Seite',
		'desc'       => '',
		'id'         => $prefix . 'page',
		'type'       => 'text_small'
	) );
  $cmb->add_field( array(
		'name'       => 'Anmerkung',
		'desc'       => '',
		'id'         => $prefix . 'note',
		'type'       => 'text'
	) );
  $cmb->add_field( array(
		'name'       => 'Max Wert',
		'desc'       => '',
		'id'         => $prefix . 'maxValue',
		'type'       => 'text_small',
    'default'    => 100
	) );
}
?>
