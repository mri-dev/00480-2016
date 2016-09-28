<div class="transfer">
  <div class="transfer-zone-header">
    <h2><span class="city"><?=$name?></span></h2>
  </div>
  <div class="transfer-block">
    <? if(!empty($transfers) || !$transfers): ?>
    <div class="transfer-list">
      <div class="trans-group">
        <div class="title">
          <h3>Csoportos transzfer</h3>
        </div>
          <div class="transfer-header">
            <div class="airport">Indulás</div>
            <div class="dropoff">Végállomás</div>
            <div class="price-one-way">Jegyár</div>
            <div class="price-return">Return jegyár</div>
          </div>
        <div class="transfer-group-list">
          <? $group_pub = 0; foreach ($transfers as $transfer) { if($transfer['service_type'] != 'group') continue; $group_pub++;?>
            <div class="transfer" data-airport="<?=$transfer['pickup_zone_id']?>" data-zone="<?=$transfer['dropoff_zone_id']?>">
              <div class="airport"><?=$airports[$transfer['pickup_zone_id']]['name']?></div>
              <div class="dropoff"><?=$transfer['dropoff_zone']?></div>
              <div class="price-one-way"><?=$transfer['one_way_price']?>€</div>
              <div class="price-return"><?=$transfer['return_price']?>€</div>
            </div>
          <? } ?>
        </div>
        <? if($group_pub == 0): ?>
        <div class="no-transfer-group">
          Jelenleg nincs elérhető csoportos transzfer.
        </div>
        <? endif; ?>
      </div>

      <div class="trans-private">
        <div class="title">
          <h3>Privát transzfer</h3>
        </div>
            <div class="transfer-header">
              <div class="airport">Indulás</div>
              <div class="dropoff">Végállomás</div>
              <div class="pax">Személyek száma</div>
              <div class="price-one-way">Jegyár</div>
              <div class="price-return">Return jegyár</div>
            </div>
            <div class="transfer-group-list">
              <? $group_pri = 0; foreach ($transfers as $transfer) { if($transfer['service_type'] != 'private') continue; $group_pri++;?>
                  <div class="transfer" data-airport="<?=$transfer['pickup_zone_id']?>" data-zone="<?=$transfer['dropoff_zone_id']?>">
                    <div class="airport"><?=$airports[$transfer['pickup_zone_id']]['name']?></div>
                    <div class="dropoff"><?=$transfer['dropoff_zone']?></div>
                    <div class="pax"><?=$transfer['min_pax']?> - <?=$transfer['max_pax']?></div>
                    <div class="price-one-way"><?=$transfer['one_way_price']?>€</div>
                    <div class="price-return"><?=$transfer['return_price']?>€</div>
                  </div>
              <? } ?>
            </div>
            <? if($group_pri == 0): ?>
            <div class="no-transfer-group">
              Jelenleg nincs elérhető privát transzfer.
            </div>
            <? endif; ?>
      </div>
    </div>
    <? else: ?>
    <div class="no-search-result">
    <h3>Nem találtunk elérhető transzfert.</h3>
    A kiválasztott zónába jelenleg nincs transzfer szolgáltatás.
    </div>
    <? endif; ?>
  </div>
</div>
