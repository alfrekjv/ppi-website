<div id="header">
    <div class="header-container">
        <div class="fl header-left"></div>
        <div class="fl rel header-middle">
            <a href="<?php echo $baseUrl; ?>" title="PPI Framework"><img class="abs" src="<?php echo $baseUrl; ?>images/icons-logo.png" alt="PPI Logo"></a>
        	<div class="all-news-container" style="display: none;">
        	<?php
	        if(isset($allNews)):
				foreach($allNews as $news):
					if($news['source'] == 'github'):
			?>
					<div class="news-item">
						<a href="<?php echo $news['url'] ?>"><img src="<?php echo $baseUrl; ?>images/github.png" alt="Github Logo"><?php echo $news['title']?></a>
					</div>
					<?php elseif($news['source'] == 'ppi'): ?>
					<div class="news-item"><img src="<?php echo $baseUrl; ?>images/ppi.png" alt="PPI Logo"><?php echo $news['title'] ?></div>
					<?php
					endif;
				endforeach;
			endif;
	        ?>
			</div>
		</div>
        <div class="fl header-right"></div>
    </div>
</div>
<div class="clearfix"></div>
<div class="header-separator"></div>