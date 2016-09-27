<div class="transfer">
  <div class="transfer-zone-header">
    <h2><?=$zone_parent['name']?> / <span class="city"><?=$name?></span></h2>
  </div>
  <div class="transfer-block">
    <? if($airports_count != 0): ?>
    <div class="airports">
        <label>Repterek: </label>
        <ul>
          <? foreach( $airports as $airport_id => $airport ): ?>
          <li><div class="airport" data-id="<?=$airport_id?>"><label class="airport-code"><?=$airport['code']?></label> <?=$airport['name']?></div></li>
        <? endforeach; ?>
        </ul>
    </div>
    <? endif; ?>
    <? if(!empty($transfers) || !$transfers): ?>
    <div class="transfer-list">
    <? foreach ($transfers as $transfer) { ?>
      <div class="transfer">
        <?=$airport['name']?> > <?=$transfer['dropoff_zone']?>
      </div>
    <? } ?>
    </div>
    <? else: ?>
    <div class="no-search-result">
    <h3>Nem találtunk elérhető transzfert.</h3>
    A kiválasztott zónába jelenleg nincs transzfer szolgáltatás.
    </div>
    <? endif; ?>
    <pre>
      <? //print_r($transfers); ?>
    </pre>
  </div>
</div>
