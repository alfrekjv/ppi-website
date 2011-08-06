<div class="gp" style="display: none;">
	<g:plusone callback="testCallback"></g:plusone>
</div>
<div class="community-page">
	<div class="left-side">
		<section class="titlebox">
            <div class="image"><img src="<?= $baseUrl; ?>images/community/community.jpg" alt="Community"></div>
            <div class="text">Community</div>
        </section>

        <section class='elements'>
            <div class="singlebox">
                <a href="#">Contributors</a>
            </div>
            <div class="singlebox">
                <a href="#">Github</a>
            </div>
            <div class="singlebox">
                <a href="#">Twitter Feed</a>
            </div>
            <div class="singlebox">
                <a href="#">Artwork</a>
            </div>

            <div class="box">
                <h1>PPI IRC Network</h1>
                <p>
                    <strong>Server:</strong> irc.freenode.org
                    <strong>Channel:</strong> #ppi
                    <a href="irc://irc.freenode.org/ppi"><img src="<?= $baseUrl;?>images/community/sb-btnconnect.png" /></a>
                </p>
            </div>

            <div class="box">
                <h1>PPI Newsletter</h1>
                <div class="form">
                    Email: <input id="email" name="email" type="text" />
                    <p><a href="#" id="sb-subscribe"><img src="<?= $baseUrl;?>images/community/sb-btnsubscribe.png" /></a></p>
                </div>
            </div>
        </section>
	</div>
    <div class="content-box">
		<h1>Activity Stream</h1>
        <div class="topcontent">
                <div class="filter">Filter: <a href="<?= $baseUrl; ?>community/" class="filter-all">All</a> - <a href="<?= $baseUrl; ?>community/index/filter/twitter" class="filter-twitter">Twitter</a> - <a href="<?= $baseUrl; ?>community/index/filter/github" class="filter-github">Github</a> </div>
                <div class="back-to-homepage"><a href="<?= $baseUrl; ?>">Back to Homepage</a></div>
        </div>

        <div class="activity-stream <?= $filtered ? 'filtered' : ''; ?>">
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
