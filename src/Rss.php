<?php

/**
 * @author Moell <moell91@foxmail.com>
 */

namespace Moell\Rss;

use Moell\Rss\Contracts\RssContract;

class Rss implements RssContract
{
    protected $encode = 'UTF-8';

    protected $channel;

    protected $items;

    public function setEncode($encode)
    {
        $this->encode = $encode;
        return $this;
    }

    /**
     * channel
     *
     * @param array $channel
     * 必选参数
     * - title 频道名称
     * - link 与频道关联的Web站点或者站点区域的Url
     * - description 频道描述
     *
     * 可选参数
     * - language
     * - compyright
     * - managingEditor 责任编辑的Email地址
     * - webMaster 频道相关网站管理员的Email地址
     * - pushDate   发布日期
     * - lastBuildDate 最后修改日期
     * - category
     * - ganerator
     * - docs
     * - cloud
     * - ttl
     * - images
     * - rating
     * - textInput
     * - skipHours
     * - skipDays
     */
    public function channel(array $channel)
    {
        if (!isset($channel['title']) || !isset($channel['link']) || !isset($channel['description'])) {
            throw new \Exception('Required parameters:title,link,description');
        }

        $this->channel = $channel;

        return $this;
    }

    /**
     * item
     *
     * @param array $item
     *
     * - title
     * - link
     * - descrition
     * - author
     * - category
     * - comments
     * - enclosure
     * - guid
     * - pushDate
     * - source
     */
    public function item(array $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param array $items
     */
    public function items(array $items)
    {
        $this->items = $items;

        return $this;
    }


    /**
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    public function build()
    {
        if (empty($this->items) || empty($this->channel)) {
            throw new \Exception('Please set the required data');
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="'.$this->encode.'"?><rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" version="2.0"></rss>');

        $this->addChannel($xml);

        $this->addItem($xml);

        return $xml;
    }

    /**
     * @param $xml
     */
    private function addItem($xml)
    {
        foreach ($this->items as $it) {
            $item = $xml->addChild('item');
            foreach ($it as $key => $value) {
                $this->addNode($key, $value, $item);
            }
        }
    }

    /**
     * add channel
     *
     * @param $xml
     */
    private function addChannel($xml)
    {
        foreach ($this->channel as $key => $value) {
            $this->addNode($key, $value, $xml);
        }
    }

    /**
     * @param $key
     * @param $value
     * @param $xml
     */
    private function addNode($key, $value, $xml)
    {
        if (is_array($value)) {
            if ($value['value'] != "") {
                $node = $xml->addChild($key, $value['value']);

                if (is_array($value['attr']) && !empty($value['attr'])) {
                    foreach ($value['attr'] as $attrKey =>$attr) {
                        $node->addAttribute($attrKey, $attr);
                    }
                }
            }
        } else {
            $xml->addChild($key, $value);
        }
    }


    /**
     * @param array $channel
     * @param array $items
     */
    public function fastBuild(array $channel, array $items)
    {
        $this->channel($channel);
        $this->items($items);
        return $this->build();
    }

    public function __toString()
    {
        return $this->build()->asXml();
    }

}