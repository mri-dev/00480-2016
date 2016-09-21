<div class="sziget-nav-container">
  <ul>
    <? foreach($zones[0]['children'] as $sziget): $active = (sanitize_title($sziget['name']) === $sziget_slug) ? true : false; ?>
    <li class="<?=($active) ? 'current' : ''?>"><a href="<?=get_option('siteurl','/')?>/<?=SZIGET_SLUG?>/<?=sanitize_title($sziget['name'])?>"><?=$sziget['name']?></a></li>
    <? if( $sziget['children'] ):  ?>
    <ul class="sub sub-of-c<?=$sziget['id']?> <?=($active) ? 'opened' : ''?>">
      <? foreach ( $sziget['children'] as $varos ): ?>
      <li><a href="<?=get_option('siteurl','/')?>/<?=KERESO_SLUG?>/?zona=<?=$varos['id']?>"><?=$varos['name']?></a></li>
      <? endforeach; ?>
    </ul>
    <? endif; ?>
    <? endforeach; ?>
  </ul>
</div>
