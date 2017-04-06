<?php
function app_register_setting() {
	register_setting( 'general', 'contact_phone', 'strval' );
  register_setting( 'general', 'katalogus_link', 'strval' );

  add_settings_field(
      'contact_phone',
      __('Kapcsolattartó telefonszám', TD),
      'contact_phone_cb',
      'general'
  );
  add_settings_field(
      'katalogus_link',
      __('Katalógus link', TD),
      'katalogus_link_cb',
      'general'
  );
}
add_action( 'admin_init', 'app_register_setting' );

function contact_phone_cb()
{
  $option = get_option('contact_phone');
  echo '<input class="regular-text ltr" type="text" name="contact_phone" value="' . $option . '" />';
}
function katalogus_link_cb()
{
  $option = get_option('katalogus_link');
  echo '<input class="regular-text ltr" type="text" name="katalogus_link" value="' . $option . '" />';
}


?>
