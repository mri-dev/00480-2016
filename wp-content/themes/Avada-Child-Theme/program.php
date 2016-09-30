<?php get_header(); ?>
<?php
  $program = new ViasaleProgram($wp_query->query_vars['program_id']);
?>
<div id="content" class="full-width program-content" stlye="max-width:100%;">
  <h1 class="program-title"><?=$program->getProgramName()?></h1>
  <div class="program-info">
    <?
      $profilimg = $program->getProfilImage();
    ?>
    <div class="profil-img">
      <a href="<?=$profilimg['url']?>" class="fusion-lightbox" data-title="<?=$program->getProgramName()?>" data-rel="iLightbox[g<?=$program->getProgramID()?>]"><img src="<?=$profilimg['url']?>" alt="<?=$program->getProgramName()?>" /></a>
    </div>
    <div class="description">
      <h2 class="desc-title">Program leírása</h2>
      <div class="desc-content">
        <?
          $desc = $program->getDescriptions();
          if($desc):
            if(!is_array($desc)) { echo nl2br($desc); } else {
              foreach( $desc as $de ):
        ?>
        <h2><?php echo $de['name']; ?></h2>
        <p>
          <?php echo nl2br($de['description']); ?>
        </p>
        <? endforeach; } else:  ?>
        Nincs leírás a programról.
        <? endif; ?>
      </div>
      <? $price = $program->getPrice(); if($price): ?>
      <div class="price">
        <h2>Program ára: <strong><?=$price?>€</strong>*</h2>
        <em>* Az ár tájékoztató jellegű. Az árváltozás jogát fenntartjuk.</em>
      </div>
      <? endif; ?>
    </div>
  </div>
  <?
    $images = $program->getMoreImages();
    if(count($images)):
  ?>
  <div class="galery-container">

    <h2 class="galery-title">Képek (<?=count($images)?>)</h2>
    <div class="image-set">
      <?php foreach ($images as $iid => $img): ?>
        <div class="image-set-holder">
        <a href="<?php echo $img['url']; ?>" class="fusion-lightbox" data-title="<?=$program->getProgramName()?>" data-rel="iLightbox[g<?=$program->getProgramID()?>]"><img class="img-responsive" src="<?php echo $img['url']; ?>" alt="<?=$program->getProgramName()?>"></a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <? endif; ?>
  <pre>
    <? //print_r($program->getProfilImage()); ?>
  </pre>
</div>
<?php get_footer();
