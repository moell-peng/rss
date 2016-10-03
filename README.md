## moell/rss
moell/rss is a package that follows the RSS 2.0 standard

### RSS specification
[http://www.rssboard.org/rss-specification](http://www.rssboard.org/rss-specification)

### 中文README
[README](README_zh.md)

### Requirement
PHP >= 5.4.0

### Installation
```shell
composer require "moell/rss:1.*"
```
### Provides an interface
```php

public function setEncode($encode); //默认UTF-8

public function channel(array $channel);

public function item(array $item);

public function items(array $items);

public function build();

public function fastBuild(array $channel, array $item);

public function __toString();
```

### Usage
```php
$rss = new \Moell\Rss\Rss();

$channel = [
    'title' => 'title',
    'link'  => 'http://moell.cn',
    'description' => 'description',
    'category' => [
        'value' => 'html',
        'attr' => [
            'domain' => 'http://www.moell.cn'
        ]
    ]
];

$rss->channel($channel);

$items = [];
for($i = 0; $i < 2; $i++) {
    $item = [
        'title' => "title".$i,
        'description' => 'description',
        'source' => [
            'value' => 'moell.cn',
            'attr' => [
                'url' => 'http://www.moell.cn'
            ]
        ]
    ];
    $items[] = $item;
    $rss->item($item);
}

echo $rss;	//Get xml

//Other acquisition methods
$rss->build()->asXML();

$rss->fastBuild($channel, $items)->asXML();

$rss->channel($channel)->items($items)->build()->asXML();
```
### Generate results
```xml
<?xml version="1.0" encoding="UTF-8"?>
<rss
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
    <title>title</title>
    <link>http://moell.cn</link>
    <description>description</description>
    <category domain="http://www.moell.cn">html</category>
    <item>
        <title>title0</title>
        <description>description</description>
        <source url="http://www.moell.cn">moell.cn</source>
    </item>
    <item>
        <title>title1</title>
        <description>description</description>
        <source url="http://www.moell.cn">moell.cn</source>
    </item>
</rss>
```

### License
MIT
