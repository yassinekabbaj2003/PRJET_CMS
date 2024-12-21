<?php declare(strict_types = 1);

namespace MailPoet\EmailEditor\Integrations\MailPoet\Patterns;

if (!defined('ABSPATH')) exit;


use MailPoet\EmailEditor\Integrations\MailPoet\Patterns\Library\DefaultContent;
use MailPoet\EmailEditor\Integrations\MailPoet\Patterns\Library\DefaultContentFull;
use MailPoet\Util\CdnAssetUrl;

class PatternsController {
  private CdnAssetUrl $cdnAssetUrl;

  public function __construct(
    CdnAssetUrl $cdnAssetUrl
  ) {
    $this->cdnAssetUrl = $cdnAssetUrl;
  }

  public function registerPatterns(): void {
    $patterns = [];
    $patterns[] = new DefaultContentFull($this->cdnAssetUrl);
    $patterns[] = new DefaultContent($this->cdnAssetUrl);
    foreach ($patterns as $pattern) {
      register_block_pattern($pattern->get_namespace() . '/' . $pattern->get_name(), $pattern->get_properties());
    }
  }
}
