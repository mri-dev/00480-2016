<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Új megrendelés</title>
<style media="screen">
  table {
  border-collapse: collapse;
  }
  table thead td {
  background: #f3f3f3;
  text-align: center;
  }
  table td {
  padding: 10px;
  border: 1px solid #eaeaea;
  }
</style>
</head>
<body>
  <h2>Új megrendelés érkezett!</h3>
  <h3>Megrendelő adatai:</h3>
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
    <tr>
      <td>
        Egyéb megjegyzés:
      </td>
      <td>
        <strong><?=$_POST['comment']?></strong>
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
  <?
  ?>
  <h4>Utasok személyes adatai:</h4>
  <table>
    <thead>
      <tr>
        <td></td>
        <? for($un = 0; $un < $utasok_szama; $un++): ?>
        <td><strong>Utas #<?=$un + 1?></strong></td>
        <? endfor; ?>
      </tr>
    </thead>
    <tr>
      <td>
        Megszólítás
      </td>
      <? for($un = 0; $un < $utasok_szama; $un++): ?>
      <td><strong><?=$_POST['utasok']['megszolitas'][$un]?></strong></td>
      <? endfor; ?>
    </tr>
    <tr>
      <td>
        Vezetéknév
      </td>
      <? for($un = 0; $un < $utasok_szama; $un++): ?>
      <td><strong><?=$_POST['utasok']['vezeteknev'][$un]?></strong></td>
      <? endfor; ?>
    </tr>
    <tr>
      <td>
        Keresztnév
      </td>
      <? for($un = 0; $un < $utasok_szama; $un++): ?>
      <td><strong><?=$_POST['utasok']['keresztnev'][$un]?></strong></td>
      <? endfor; ?>
    </tr>
    <tr>
      <td>
        Születési dátum
      </td>
      <? for($un = 0; $un < $utasok_szama; $un++): ?>
      <td><strong><?=$_POST['utasok']['szuletesi_datum'][$un]?></strong></td>
      <? endfor; ?>
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
</body>
</html>
