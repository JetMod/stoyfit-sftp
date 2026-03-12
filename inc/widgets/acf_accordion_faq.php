<?php
/**
* Block Name: Accordion list
*
*/

$acf_block_accordion_css = get_field('acf_block_accordion_css');
?>
<?php if( have_rows('acf_block_accordion_list') ): ?>
    <div class="tm-content-accordion-list<?php if( !empty( $acf_block_accordion_css ) ): ?> <?= $acf_block_accordion_css ?><?php endif; ?>">
        <?php while (have_rows('acf_block_accordion_list')) : the_row();
                $acc_title = get_sub_field('acf_block_accordion_list_title');
                $acc_text = get_sub_field('acf_block_accordion_list_text');
        ?>
            <div>
                
                <div class="tm-content-accordion-list__title">
                    <?= $acc_title ?>
                </div>

                <div class="tm-content-accordion-list__text">
                    <?= $acc_text ?>
                </div>
               
                
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>