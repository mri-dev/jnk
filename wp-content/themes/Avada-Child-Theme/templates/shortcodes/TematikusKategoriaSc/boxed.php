<?php
  $img = $travel->Image();
  $dest = $travel->showDestinations();
  $termv = $travel->showUtazasKategoria();
?>
<article class="program">
  <div class="wrapper">
    <a href="<?=$travel->Url()?>">
      <div class="image">
        <img src="<?=$img?>" alt="<?=$travel->Title()?>">
      </div>
      <div class="szalag">
        <div class="title">
          <?=$travel->Title()?>
          <div class="sub">
            <div class="cat"><?php echo implode($termv, ", "); ?></div>
            <div class="destinations"><?php echo implode($dest, ", "); ?></div>
          </div>
        </div>
      </div>
    </a>
  </div>
</article>
