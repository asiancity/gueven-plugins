<?php
/**
 *
 * RS Space
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function rs_hero_video_banner( $atts, $content = '', $id = '' ) {
  extract( shortcode_atts( array(
    'id'            => '',
    'class'         => '',
    'style'         => 'html5',
    'small_heading' => '',
    'heading'       => '',
    'video_webm'    => '',
    'data_link'     => '',
    'video_mp4'     => '',
    'poster_img'    => ''
  ), $atts ) );

  if($style == 'youtube') {wp_enqueue_script('adios-youtube');}

  $id          = ( $id ) ? ' id="'. esc_attr($id) .'"' : '';
  $class       = ( $class ) ? ' '. sanitize_html_classes($class) : '';
  $video_class = ($style == 'html5') ? 'white-style inside':'not-white';

  $poster = '';
  if(!empty($poster_img) && is_numeric($poster_img)) {
    $image_url  = wp_get_attachment_image_src( $poster_img, 'full' );
    if(isset($image_url[0])) {
      $poster = 'poster="'.esc_url($image_url[0]).'"';
    }
  }

  $output  =  '<div '.$id.' class="top-baner video-bg bottom-margin'.$class.'">';
  $output .=  '<div class="block-bg top-image">';
  $output .=  '<div class="bg-wrap video-bg-wrap">';

  if(!empty($video_webm) && !empty($video_mp4) && $style == 'html5'):
    $output .=  '<video loop autoplay muted '.$poster.' class="bgvid">';
    $output .=  '<source type="video/webm" src="'.esc_url($video_webm).'">';
    $output .=  '<source type="video/mp4" src="'.esc_url($video_mp4).'">';
    $output .=  '</video>';
  endif;

  if(!empty($data_link) && $style == 'youtube'):
    $output .=  '<div class="video-iframe">';
    $output .=  '<div id="video" data-link="'.esc_attr($data_link).'"></div>';
    $output .=  '</div>';
  endif;

  $output .=  '<div class="white-mobile-layer"></div>';
  $output .=  '</div>';
  $output .=  '<div class="title-style-1 vertical-align '.sanitize_html_classes($video_class).'">';
  $output .=  '<div class="sub-title">';
  $output .=  '<h5 class="h5">'.wp_kses_post($small_heading).'</h5>';
  $output .=  '</div>';
  $output .=  '<h1 class="h1">'.wp_kses_post($heading).'</h1>';
  $output .=  '</div>';
  $output .=  '</div>';
  $output .=  '</div>';

  return $output;
}

add_shortcode('rs_hero_video_banner', 'rs_hero_video_banner');
