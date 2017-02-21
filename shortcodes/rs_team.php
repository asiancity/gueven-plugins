<?php
/**
 *
 * RS Team
 * @since 1.0.0
 * @version 1.0.0
 *
 *
 */
function rs_team( $atts, $content = '', $id = '' ) {

  global $post;

  extract( shortcode_atts( array(
    'id'               => '',
    'class'            => '',
    'style'            => 'style1',
    'pagination_style' => 'dot',
    'person_id'        => '',
    'limit'            => 4,
    'per_slide'        => 3

  ), $atts ) );

  $id    = ( $id ) ? ' id="'. esc_attr($id) .'"' : '';
  $class = ( $class ) ? ' '. sanitize_html_classes($class) : '';

  $args = array(
    'post_type'      => 'team',
    'posts_per_page' => $limit,
    'post__in'       => explode(',', $person_id),
  );

  $query   = new WP_Query( $args );

  ob_start();
  echo '<div '.$id.' class="team '.$class.'">';
  echo '<div class="row">';

  switch ($style) {
    case 'style1':
      $delay = 0.2;
      while( $query->have_posts() ) : $query->the_post();
        $position = adios_get_post_opt('team-position');
        $item_args = array(
          'style'    => $style,
          'delay'    => $delay,
          'position' => $position
        );
        rs_team_item( $item_args );
        $delay += 0.2;
      endwhile;
      wp_reset_postdata();
      break;
    case 'style2':
      wp_enqueue_script('adios-swiper');
      wp_enqueue_style( 'adios-swiper');
      $delay = 0.2;
      echo '<div class="swiper-container team-slider" data-autoplay="5000" data-loop="0" data-speed="1000" data-center="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2" data-md-slides="'.esc_attr($per_slide).'" data-lg-slides="'.esc_attr($per_slide).'" data-add-slides="'.esc_attr($per_slide).'">';
      echo '<div class="swiper-wrapper">';
      while( $query->have_posts() ) : $query->the_post();
        $position = adios_get_post_opt('team-position');
        $item_args = array(
          'style'    => $style,
          'delay'    => $delay,
          'position' => $position
        );
        rs_team_item( $item_args );
        $delay += 0.1;
      endwhile;
      wp_reset_postdata();
      echo '</div>';
      echo '</div>';
      break;
    default:
      # code...
      break;
  }
  echo '</div>';
  echo '</div>';
  echo ($style == 'style2' && $pagination_style == 'dot' ) ? '<div class="pagination point-style alb-point"></div>':'<div class="pagination vertical-point right-align"></div>';
  $output = ob_get_clean();
  return $output;

}
add_shortcode('rs_team', 'rs_team');


/**
 * Part of team shortcode
 * @param type $type
 * @return type
 */
if( !function_exists('rs_team_item')) {
  function rs_team_item( $item_args ) {
    extract($item_args);

    switch ($style) {
      case 'style1': ?>
        <div class="col-md-6 col-sm-6">
          <div class="team-item item-hov wow zoomIn" data-wow-delay="<?php echo esc_attr($delay); ?>s">
            <?php the_post_thumbnail('adios-small', array('class' => 'resp-img')); ?>
            <div class="team-desc item-layer">
              <div class="vertical-align w-full">
              <h4 class="h4"><a href="#"><?php the_title(); ?></a></h4>
                <span><?php echo esc_html($position); ?></span>
              </div>
            </div>
          </div>
        </div>
        <?php # code...
        break;
      case 'style2': ?>
        <div class="swiper-slide">
          <div class="padd-wrap wow zoomIn" data-wow-delay="<?php echo esc_attr($delay); ?>s">
           <div class="team-item item-hov">
            <?php the_post_thumbnail('adios-small-alt', array('class' => 'resp-img')); ?>
            <div class="team-desc item-layer">
              <div class="vertical-align w-full">
              <h4 class="h4"><a href="#"><?php the_title(); ?></a></h4>
                <span><?php echo esc_html($position); ?></span>
              </div>
            </div>
           </div>
          </div>
        </div>
        <?php
        break;

      default:
        # code...
        break;
    }
  }
}
