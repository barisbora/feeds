<?php namespace willvincent\Feeds;

use SimplePie;

class FeedsFactory {

  protected $config;

  protected $simplepie;

  /**
   * @param $config
   */
  public function __construct($config) {
    $this->config = $config;
  }

  /**
   * @param $feed_url string RSS URL
   * @param bool|false $force_feed
   *
   * @return SimplePie
     */
    public function make($feed_url, $force_feed = false) {
    $this->simplepie = new SimplePie();
    $this->configure();
    $this->simplepie->set_feed_url($feed_url);
    if ( $force_feed === true ) $this->simplepie->force_feed(true);
    if ( ! $this->config['strip_html_tags.disabled'] && ! empty( $this->config['strip_html_tags.tags'] ) && is_array($this->config['strip_html_tags.tags'])) {
       $this->simplepie->strip_htmltags( $this->config['strip_html_tags.tags'] );
     }else{
       $this->simplepie->strip_htmltags( false );
    }
    $this->simplepie->init();
    $this->simplepie->handle_content_type();

    return $this->simplepie;
  }

  protected function configure() {
    if ($this->config['cache.disabled']) {
      $this->simplepie->enable_cache(false);
    }
    else {
      $this->simplepie->set_cache_location($this->config['cache.location']);
      $this->simplepie->set_cache_duration($this->config['cache.life']);
    }
  }
}
