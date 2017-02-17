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

    $utasok_szama = count($_POST['utasok']['keresztnev']);
    $utasok = $_POST['utasok'];

    if(empty($_POST['keresztnev'])) $return['missing_elements'][] = 'keresztnev';
    if(empty($_POST['vezeteknev'])) $return['missing_elements'][] = 'vezeteknev';
    if(empty($_POST['cim'])) $return['missing_elements'][] = 'cim';
    if(empty($_POST['telefon'])) $return['missing_elements'][] = 'telefon';
    if(empty($_POST['szuletesi_datum'])) $return['missing_elements'][] = 'szuletesi_datum';
    if(empty($_POST['email'])) $return['missing_elements'][] = 'email';

    for ($i=0; $i < $utasok_szama ; $i++)
    {
      $ui = $i + 1;
      if(empty($utasok['keresztnev'][$i])) $return['missing_elements'][] = 'keresztnev_utas_'.$ui;
      if(empty($utasok['vezeteknev'][$i])) $return['missing_elements'][] = 'vezeteknev_utas_'.$ui;
      if(empty($utasok['szuletesi_datum'][$i])) $return['missing_elements'][] = 'szuletesi_datum_utas_'.$ui;
    }

    if(!empty($return['missing_elements'])) {
      $return['error']  = 1;
      $return['msg']    = 'Kérjük, hogy töltse ki az összes mezőt a megrendelés küldéséhez.';
      $return['missing']= count($return['missing_elements']);
      $this->returnJSON($return);
    }

    // Validate Email
    $email = $this->test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $return['error']  = 1;
      $return['msg']    = 'Nem megfelelő e-mail cím. Kérjük, hogy email címet adjon meg. Formátum minta.: mail@example.com';
      $return['missing']= count($return['missing_elements']);
      $return['missing_elements'][]= 'email';
      $this->returnJSON($return);
    }


    $to       = get_option('admin_email');
    $subject  = 'Megrendelés érkezett: '.$_POST['vezeteknev'] . ' '.$_POST['keresztnev'].' - '.$utasok_szama.' főre';

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
      $return['msg']    = 'A megrendelést jelenleg nem tudtuk elküldeni. Próbálja meg később.';
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

  private function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

}
?>
