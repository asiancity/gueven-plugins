<?php
/**
 *
 * RS Portfolio
 * @since 1.0.0
 * @version 1.0.0
 *
 */
function rs_blog_slider($atts, $content = '', $id = '') {
  global $post;
  wp_enqueue_script('adios-swiper');
  wp_enqueue_style('adios-swiper');
  extract(shortcode_atts(array(
    'id'             => '',
    'class'          => '',
    'limit'          => 4,
    'cats'           => '',
    'exclude_posts'  => '',
  ), $atts));

  $class = ( $class ) ? ' ' .$class : '';

  if (get_query_var('paged')) {
    $paged = get_query_var('paged');
  } elseif (get_query_var('page')) {
    $paged = get_query_var('page');
  } else {
    $paged = 1;
  }

  $args = array(
    'posts_per_page' => $limit,
    'meta_query'     => array(array('key' => '_thumbnail_id')),
    'orderby'        => 'date',
    'paged'          => $paged,
  );

  $args['tax_query'] = array(
    array(
      'taxonomy' => 'category',
      'field'    => 'id',
      'terms'    => explode(',', $cats),
    ),
  );

  if (!empty($exclude_posts)) {

    $exclude_posts_arr = explode(',',$exclude_posts);
    if (is_array($exclude_posts_arr) && count($exclude_posts_arr) > 0) {
      $args['post__not_in'] = array_map('intval',$exclude_posts_arr);
    }
  }

  ob_start();

  $the_query = new WP_Query($args);
  $max_num_pages = $the_query->max_num_pages;

  ?>
    <div class="poind-closest">
      <div class="top-baner swiper-anime bottom-margin slide-arow wow zoomIn" data-wow-delay="0.2s">
       <div class="swiper-container top-slider" data-autoplay="0" data-loop="0" data-speed="600" data-center="0" data-slides-per-view="1">
        <div class="swiper-wrapper">

          <?php
            $i = 0;
            while( $the_query->have_posts() ) : $the_query->the_post();
              $terms = wp_get_post_terms(get_the_ID(), 'category');
              $term_name = array();
              if (count($terms) > 0):
                foreach ($terms as $term):
                  $term_name[] = $term->name;
                endforeach;
              endif;
              $image_src  = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'adios-large-xxl' );
            ?>
             <div class="swiper-slide <?php echo ($i == 0) ? 'first-slide':''; ?>" data-val="<?php echo esc_attr($i); ?>">
              <div class="block-bg">
                <div class="bg-wrap">
                 <div class="bg" style="background-image:url(<?php echo esc_url($image_src[0]); ?>)"></div>
                   <div class="white-mobile-layer"></div>
                </div>
                <div class="title-style-1 vertical-align inside">
                 <div class="sub-title">
                  <h5 class="h5"><?php echo implode(', ', $term_name); ?></h5>
                 </div>
                <h2 class="h1"><?php the_title(); ?></h2>
                <div class="title-link">
                  <a href="<?php echo esc_url(get_the_permalink()); ?>" class="link-type-1"><?php echo esc_html__('visit blog', 'adios-addons'); ?><i class="icon-right-open-mini"></i></a>
                </div>
                </div>
              </div>
             </div>
            <?php
              $i++;
              endwhile;
              wp_reset_postdata();
            ?>
          </div>
          <div class="pagination vertical-point"></div>
       </div>
      </div>
    </div>
  <?php
  $output = ob_get_clean();
  return $output;
}

add_shortcode('rs_blog_slider', 'rs_blog_slider');
