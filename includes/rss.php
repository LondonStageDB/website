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
   */ 
  public function retrieve() {
    $rss = simplexml_load_string($this->fetch());
    $rss_split = [];
    if ($rss) {
      foreach ($rss->channel->item as $item) {
        $title = (string) $item->title;
        $link   = (string) $item->link;
        $description = (string) $item->description;
        $last_update = strtotime($item->pubDate);
	$rss_split[] = '<div>Published on ' . date("m-d-Y h:i:sa", $last_update) .  '</div>
		        <div>
                          <a href="'.$link.'" target="_blank" title="">'.$title.'</a>
			</div>
                        <div>' . $description . '</div><hr>';
      }
    }
    return $rss_split;
  }

  /**
   * Rending and styling feeds.
   */
  function display() {
    $rss_split = $this->retrieve();
    $rss_data = '<div class="rss-container">';
    foreach($rss_split as $feed) {
      $rss_data .= $feed;
    }
    //$trim = str_replace('', '',$this->feed);
    $rss_data.='</div>';
    return $rss_data;
  }
}

?>
