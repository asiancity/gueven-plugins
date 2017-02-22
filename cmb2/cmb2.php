<?php
add_action('admin_head', 'ath_admin_css');

function ath_admin_css() {
  echo '<style>
    .extra-form .cmb-row{
      padding:5px 0 !important;
      margin:0 !important;
    }
    .extra-form .regular-text{
      width:90%
    }
    .extra-form-2 .cmb-repeat-group-field{
      padding:5px 0 !important;
      margin-bottom:0 !important;
    }
    .extra-form-2 .cmb-td{
      padding:0 0 5px 0 !important
    }
    .extra-form-2 .cmb-row.cmb-repeat-row{
      padding-top: 0 !important
    }
  </style>';
}
?>
