<div class="gp" style="display: none;">
	<g:plusone callback="testCallback"></g:plusone>
</div>
<div class="community-page">
	<div class="left-side">
		<img src="<?= $baseUrl; ?>images/community/community.jpg" alt="Community">
	</div>
    <div class="content-box">
		<h1>Activity Stream</h1>
        <div class="topcontent">
                <div class="filter">Filter: <a href="<?= $baseUrl; ?>community/" id="all">All</a> - <a href="<?= $baseUrl; ?>community/index/filter/twitter" id="twitter">Twitter</a> - <a href="<?= $baseUrl; ?>community/index/filter/github" id="github">Github</a> </div>
                <div class="back-to-homepage"><a href="<?= $baseUrl; ?>">Back to Homepage</a></div>
        </div>

        <div class="activity-stream">
            <div class="feeds">
            <?php
            foreach($activity as $item):
                $feedImage  = $baseUrl . 'images/community/' . $item['source'] . '.png';
            ?>
                <div class="feed source-<?= $item['source']; ?>">
                    <div class="image">
                        <img class="fl <?= $item['source']; ?>" src="<?= $feedImage; ?>" alt="<?= $item['source']; ?>" />
                    </div>
                    <div class="content">
                        <div class="description"><?= $item['title']; ?></div>

                        <div class="actions">
                            <a href="<?= $item["url"];?>" target="_blank" class="readmore">Read More</a>
	                        <!--
                            <div class="social gplus-here"></div>
                            -->
                        </div>

                    </div>
                </div>
            <?php
            endforeach;
            ?>
            </div>
        </div>
    </div>
	<div class="clear"></div>
</div>