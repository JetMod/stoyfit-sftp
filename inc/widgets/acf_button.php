<?php

/**
 * Block Name: Button
 *
 */

$acf_block_button_css = get_field('acf_block_button_css');
$acf_block_button_link = get_field('acf_block_button_link');
$acf_block_button_text = get_field('acf_block_button_text');
$acf_block_button_big = get_field('acf_block_button_big');
$acf_block_button_small = get_field('acf_block_button_small');
$acf_block_button_download = get_field('acf_block_button_download');
$acf_block_button_center = get_field('acf_block_button_center');
$acf_block_button_white = get_field('acf_block_button_white');
$acf_block_button_popup = get_field('acf_block_button_popup');
$acf_block_button_out = get_field('acf_block_button_out');
?>

<div class="tm-button-block<?php if (!empty($acf_block_button_css)) : ?> <?= $acf_block_button_css ?><?php endif; ?>">
    <div class="tm-content-btn<?php if (empty($acf_block_button_small)) : ?> tm-content-btn_middle<?php endif; ?><?php if (!empty($acf_block_button_big)) : ?> tm-content-btn_big<?php endif; ?><?php if (!empty($acf_block_button_center)) : ?> tm-content-btn_centered<?php endif; ?><?php if (!empty($acf_block_button_white)) : ?> tm-content-btn_white<?php endif; ?>">
        <a href="<?=$acf_block_button_link ?>"<?php if (!empty($acf_block_button_popup)) : ?> class="tm-popup"<?php endif; ?><?php if (!empty($acf_block_button_download)) : ?> download<?php endif; ?><?php if (!empty($acf_block_button_out)) : ?> target="_blank"<?php endif; ?>><span><?= $acf_block_button_text ?></span></a>
    </div>
</div>