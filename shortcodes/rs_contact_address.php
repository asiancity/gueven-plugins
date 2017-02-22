<?php
function rs_contact_address(){
  $phone      = get_theme_mod('phone');
  $mail       = get_theme_mod('mail');
  $blogname   = get_bloginfo('name');
  $address    = get_theme_mod('address');
  $postcode   = get_theme_mod('postcode');
  $city       = get_theme_mod('city');

  $out   = '<div class="contact">';
  $out  .= '<div class="media"><i class="fa fa-home pull-left"></i><div class="media-body">'.$blogname.'<br />'.$address.'<br />'.$postcode.' '.$city.'</div></div>';
  $out  .= '<div class="media"><i class="fa fa-phone pull-left"></i><div class="media-body">'.$phone.'</div></div>';
  $out  .= '<div class="media"><i class="fa fa-envelope-o pull-left"></i><div class="media-body">'.$mail.'</div></div>';
  $out  .= '</div>';
  return $out;
}
add_shortcode('rs_contact_address', 'rs_contact_address');
?>
