<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase {
  public function test_init() {
    $name = 'plop';
    $uri = 'http://plop.plop';
    $description = 'plop plop, plop plop plop! Plop plop plop...';
    $version = '0.1';
    $wpVersion = '5.6';
    $phpVersion = '7.3';
    $author = 'plop';
    $authorUri = 'http://author.plop.plop';
    $license = 'plop-v2.1';
    $licenseUri = 'http://license.plop.plop';
    $textDomain = 'plop';
    $domainPath = 'plop';

    $plugin = \Dbuild\WpPlugin\Plugin::Init(
      $name,
      $uri,
      $description,
      $version,
      $wpVersion,
      $phpVersion,
      $author,
      $authorUri,
      $license,
      $licenseUri,
      $textDomain,
      $domainPath
    );
    $this->assertEquals($name, $plugin->name);
    $this->assertEquals($uri, $plugin->uri);
    $this->assertEquals($description, $plugin->description);
    $this->assertEquals($version, $plugin->version);
    $this->assertEquals($wpVersion, $plugin->wpVersion);
    $this->assertEquals($phpVersion, $plugin->phpVersion);
    $this->assertEquals($author, $plugin->author);
    $this->assertEquals($authorUri, $plugin->authorUri);
    $this->assertEquals($license, $plugin->license);
    $this->assertEquals($licenseUri, $plugin->licenseUri);
    $this->assertEquals($textDomain, $plugin->textDomain);
    $this->assertEquals($domainPath, $plugin->domainPath);

    $plugin = \Dbuild\WpPlugin\Plugin::Init();
    $this->assertEquals($name, $plugin->name);
  }
}