<div class="downloads-container content-box">
	<div class="page-header">
		<h1 class="fl">Downloads</h1>
		<div class="fr back-to-homepage">Back to homepage</div>
		<div class="clear"></div>
	</div>
	<div class="downloads-item-container">
		<?php
		foreach($downloads as $download):
		?>
		<div class="downloads-item">
			<div class="header">
				<img class="fl logo" src="<?php echo $baseUrl; ?>images/icons-download.png">
				<div class="fl">
					<div class="project-title"><?= $download['title']; ?></div>
					<div class="project-shortdesc"><?= $download['shortdesc']; ?></div>
				</div>
				<div class="fr download-links">
					<span class="link"><a href="<?= $download['tarlink']; ?>" title="">tar.gz</a></span>
					<span class="link"><a href="<?= $download['ziplink']; ?>" title="">zip</a></span>
				</div>
				<div class="clear"></div>
			</div>
			<div class="desc">
				<p><?= $download['desc']; ?></p>
			</div>
		</div>
		<?php
		endforeach;
		?>
	</div>
</div>