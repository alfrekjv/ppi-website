<?php
class APP_Controller_Community extends APP_Controller_Application {

	function index() {

		$activity = array();

        $filter = $this->get('filter');
		$filtered = false;
        switch($filter) {
            case 'twitter':
	            $filtered = true;
                $activity = $this->getTwits();
                break;

            case 'github':
	            $filtered = true;
                $activity = $this->getGithub();
                break;

            case 'all':
            default:
               $activity = $this->getGithub() + $this->getTwits();
               break;
        }

		krsort($activity);

		$this->addCSS('community');
		$this->addJS('community');
		$this->render('community/index', compact('activity', 'filtered'));

	}

	function livechat() {
		$this->render('community/livechat');
	}

    function contributors() {
        $this->addCSS('contributors');
		$this->addJS('contributors');
        $this->render('community/contributors');
    }

    protected function getTwits() {

        $config = PPI_Helper::getConfig();
        $twitterFeeds = $config->community->twitterFeeds->toArray();
        if(!empty($twitterFeeds)) {
            foreach($twitterFeeds as $twitterFeed) {
                $activity = $this->parseTweets($twitterFeed);
            }
        }

        return $activity;
    }

    protected function getGithub() {

        $config = PPI_Helper::getConfig();
        $githubFeeds = $config->community->githubFeeds->toArray();
        if(!empty($githubFeeds)) {
            $activity = $this->parseGithubFeeds($githubFeeds);
        }

        return $activity;
    }

	protected function parseGithubFeeds($feeds) {

		$cacheName = 'ppi-website-github-feeds';
		$cache = PPI_Helper::getCache();
		if($cache->exists($cacheName)) {
			return $cache->get($cacheName);
		}

		foreach($feeds as $feed) {
			$xml = simplexml_load_file($feed);
			if(isset($xml->entry)) {
				foreach($xml->entry as $commit){
					$timestamp = strtotime( (string) $commit->updated);
					list($day, $month, $year) = explode('/', date('d/m/Y', $timestamp));
					if($year == 2011) {
						$title = (string) $commit->title;
						if(strlen($title) > 70) {
							$title = trim(substr($title, 0, 70)). '..';
						}
                        $title = htmlentities($title);
						$activity[$timestamp] = array(
							'source' => 'github',
							'url'	 => (string) $commit->link['href'],
							'title'	 => $title . ' on ' . date('jS M Y \a\t g:i a', $timestamp)
						);
					}
				}
			}
		}
		krsort($activity);

		$cache->set($cacheName, $activity, 60*60);

		return $activity;
	}

	protected function parseTweets($username, $maxtweets = 5) {

		$cacheName = 'ppi-website-twitter-feeds';
		$cache = PPI_Helper::getCache();
		if($cache->exists($cacheName)) {
			return $cache->get($cacheName);
		}

		$tweets = simplexml_load_file("http://twitter.com/statuses/user_timeline/" . $username . ".rss");
		foreach ( $tweets->channel->item as $tweet ) {
			// Loop to limitate nr of tweets.
			if ($maxtweets == 0) {
				break;
			} else {
				$twit = $tweet->description;  //Fetch the tweet itself

				//Remove the preceding 'username: '
				$twit = htmlentities(substr(strstr($twit, ': '), 2, strlen($twit)), ENT_QUOTES, 'UTF-8');

				// Convert URLs into hyperlinks
				$twit = preg_replace("/(http:\/\/)(.*?)\/([\w\.\/\&\=\?\-\,\:\;\#\_\~\%\+]*)/", "<a href=\"\\0\">\\0</a>", $twit);

				// Convert usernames (@) into links
				$twit = preg_replace("(@([a-zA-Z0-9\_]+))", "<a href=\"http://www.twitter.com/\\1\">\\0</a>", $twit);

				// Convert hash tags (#) to links
				$twit = preg_replace('/(^|\s)#(\w+)/', '\1<a href="http://search.twitter.com/search?q=%23\2">#\2</a>', $twit);

				//Specifically for non-English tweets, converts UTF-8 into ISO-8859-1
				//$twit = iconv("UTF-8", "ISO-8859-1//TRANSLIT", $twit);
                $twit = utf8_decode($twit);

                $status = (string) $tweet->link; // get the URL of the status..

				//Get the date it was posted
				$pubdate = strtotime($tweet->pubDate);
				$propertime = gmdate('F jS Y, H:i', $pubdate);  //Customize this to your liking

				//Store tweet and time into the array
				$tweet_item = array(
					'title'  => $twit,
					'url'    => $status,
					'date'   => $propertime,
					'source' => 'twitter'
				);

				$tweet_array[$pubdate] = $tweet_item;

				$maxtweets--;
			}
		}

		$cache->set($cacheName, $tweet_array, 60*60);

		// Return array
		return $tweet_array;
	}

}