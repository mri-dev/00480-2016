<?php

class AjaxRequests
{
  public function __construct()
  {
    return $this;
  }

  public function send_travel_request()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'sendTravelRequest'));
    add_action( 'wp_ajax_nopriv_'.__FUNCTION__, array( $this, 'sendTravelRequest'));
  }

  public function sendTravelRequest()
  {
    extract($_POST);
    $return = array(
      'error' => 0,
      'msg'   => '',
      'missing_elements' => [],
      'missing' => 0,
      'passed_params' => false
    );

    $return['passed_params'] = $_POST;

    if(empty($_POST['keresztnev'])) $return['missing_elements'][] = 'keresztnev';
    if(empty($_POST['vezeteknev'])) $return['missing_elements'][] = 'vezeteknev';
    if(empty($_POST['cim'])) $return['missing_elements'][] = 'cim';
    if(empty($_POST['telefon'])) $return['missing_elements'][] = 'telefon';
    if(empty($_POST['szuletesi_datum'])) $return['missing_elements'][] = 'szuletesi_datum';
    if(empty($_POST['email'])) $return['missing_elements'][] = 'email';

    if(!empty($return['missing_elements'])) {
      $return['error']  = 1;
      $return['msg']    = 'Kérjük, hogy töltse ki az összes mezőt az ajánlatkérés elküldéséhez.';
      $return['missing']= count($return['missing_elements']);
      $this->returnJSON($return);
    }

    $to       = get_option('admin_email');
    $subject  = 'Utazási ajánlatkérés: '.$_POST['vezeteknev'] . ' '.$_POST['keresztnev'];

    ob_start();
  	  include(locate_template('templates/mails/utazasi-ajanlatkero-ertesites.php'));
      $message = ob_get_contents();
		ob_end_clean();

    //add_filter( 'wp_mail_from', array($this, 'getMailSender') );
    add_filter( 'wp_mail_from_name', array($this, 'getMailSenderName') );
    add_filter( 'wp_mail_content_type', array($this, 'getMailFormat') );

    $headers    = array();
    $headers[]  = 'Reply-To: '.$_POST['vezeteknev'].' '.$_POST['keresztnev'].' <'.$_POST['email'].'>';

    /* */
    $alert = wp_mail( $to, $subject, $message, $headers );

    if(!$alert) {
      $return['error']  = 1;
      $return['msg']    = 'Az ajánlatkérését jelenleg nem tudtuk elküldeni. Próbálja meg később.';
      $this->returnJSON($return);
    }
    /* */

    echo json_encode($return);
    die();
  }

  public function getMailFormat(){
      return "text/html";
  }

  public function getMailSender($default)
  {
    return get_option('admin_email');
  }

  public function getMailSenderName($default)
  {
    return get_option('blogname', 'Wordpress');
  }

  private function returnJSON($array)
  {
    echo json_encode($array);
    die();
  }

}
?>
