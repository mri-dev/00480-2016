<h2>Új ajánlatkérés érkezett!</h3>

<h3>Személyes adatok:</h3>
<table>
  <tr>
    <td>
      Megszólítás:
    </td>
    <td>
      <strong><?=$_POST['megszolitas']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      Vezetéknév:
    </td>
    <td>
      <strong><?=$_POST['vezeteknev']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      Keresztnév:
    </td>
    <td>
      <strong><?=$_POST['keresztnev']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      Telefonszám:
    </td>
    <td>
      <strong><?=$_POST['telefon']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      E-mail cím:
    </td>
    <td>
      <strong><?=$_POST['email']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      Cím
    </td>
    <td>
      <strong><?=$_POST['cim']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      Születési dátum:
    </td>
    <td>
      <strong><?=$_POST['szuletesi_datum']?></strong>
    </td>
  </tr>
</table>
<br>
<h4>Kiválasztott konfiguráció:</h4>
<table>
  <tr>
    <td>
      Személyek száma:
    </td>
    <td>
      <strong><?=$_POST['room']['people']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      Szoba típusa:
    </td>
    <td>
      <strong><?=$_POST['room']['name']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      Kalkulált ár:
    </td>
    <td>
      <strong><?=$_POST['room']['price']?></strong>
    </td>
  </tr>
</table>
<br>
<h4>Utazási ajánlat adatai:</h4>
<table>
  <tr>
    <td>
      Weboldal linkje:
    </td>
    <td>
      <strong><a href="<?=$_POST['term']['url']?>"><?=$_POST['term']['url']?></a></strong>
    </td>
  </tr>
  <tr>
    <td>
      Utazás azonosítója:
    </td>
    <td>
      <strong><?=$_POST['term']['id']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      Utazás ideje:
    </td>
    <td>
      <strong><?=$_POST['term']['date_from']?> &mdash; <?=$_POST['term']['date_to']?></strong>
    </td>
  </tr>
  <tr>
    <td>
      Ellátás:
    </td>
    <td>
      <strong><?=$_POST['term']['board']?></strong>
    </td>
  </tr>
</table>
<br><br>
Ajánlat elküldésének ideje: <?=date('Y-m-d H:i:s')?>
