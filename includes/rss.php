<?php
/**
 * RSS class to retrive the Feed.
 */
class Rss {

  /**
   * The RSS Feed url.
   */
  private $feed_url;
  
  /**
   * The constructor of the class.
   */
  public function __construct($feed_url) {
      $this->feed_url = $feed_url;
  }

  /**
   * Fetch the RSS .xml file.
   */
  public function fetch() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->feed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:136.0) Gecko/20100101 Firefox/136.0"]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch); 
    return $result;
  }

  /**
   * The method to get feed from url.
   *
   * @param $amount
   * The amount of feeds to be retrieved.
   */ 
  public function retrieve(int $amount = null) {
    $rss = simplexml_load_string($this->fetch());
    $rss_split = [];
    if ($rss) {
      if (is_null($amount)) {
        $amount = count($rss->channel->item);
      }
      $index = 0;
      foreach ($rss->channel->item as $item) {
	$last_update = strtotime($item->pubDate);
	$link = (string) $item->link;
	$title = (string) $item->title;
	$content = $item->children("content", true);
	$content_encoded = (string) $content->encoded;
	$content = strip_tags($content_encoded);
	if ($this->count_words($content) > 150) {
	  $content = $this->limit_words($content, 150) . '... <a href="'. $link .'" target="_blank" title="'. $title .'">Read More</a>';
	}
	$media = $item->children("media", true)->thumbnail;
	$thumbnail = is_null($media[0]) ? '' : $media[0]->attributes();
	$img = '';
	if (!empty($thumbnail)) {
	  $img = '<img width="' . $thumbnail['width'] . '" height="' . $thumbnail['height'] . '" src="' . $thumbnail['url'] . '">';
	}
	$rss_split[] = [
		          'date'        => date("d F Y", $last_update),
			  'link'        => $link,
			  'title'       => $title,
			  'content'	=> $content,
			  'thumbnail'   => $img,
	];
	if ($index < $amount) {
	  $index++;
	} else {
	  break;
	}
      }
    }
    return $rss_split;
  }

  /**
   * Rending and styling feeds.
   *
   * @param $amount
   * The amount of feeds to be retrieved.
   */
  function display(int $amount = null) {
    $rss_feeds = $this->retrieve($amount);
    $rss_container = '<div class="rss-container">';
    foreach($rss_feeds as $feed) {
      $rss_container .= '<div style="font-size:0.8em;font-weight:700;float:right;margin-top:1em;"><em>Published on ' . $feed['date'] .  '</em></div>
                         <div style="font-size:1.5em;margin-bottom:1em;">
                           <a href="'. $feed['link'] .'" target="_blank" title="' . $feed['title'] . '">' . $feed['title'] . '</a>
			 </div>
                         <div style="float:left; margin-right:1em;">' . $feed['thumbnail'] . '</div>
			 <div>' . $feed['content'] . '</div>
                         <hr>';
    }
    //$trim = str_replace('', '',$this->feed);
    $rss_container.='</div>';
    return $rss_container;
  }

  /**
   * Count words.
   *
   */
  function count_words($text) {
    $words = explode(" ",$text);
    return count($words);
  }

  /**
   * Limit words.
   *
   */
  function limit_words($text, $limit) {
    $words = explode(" ",$text);
    return implode(" ",array_splice($words,0,$limit));
  }
}

?>
