<?php

class AjaxRequests
{
  public function __construct()
  {
    return $this;
  }

  public function get_term_offer()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'getTravelOfferContent'));
  }

  public function getTravelOfferContent()
  {
    echo 'CLASS';
    print_r($_POST);
    die();
  }

}
