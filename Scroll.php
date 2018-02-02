<?php

namespace go1\util_es\explore;

use Elasticsearch\Client;

class Scroll
{
    public static function scroll(Client $client, array $options, $size = 100, $duration = '5m', $scrollId = null)
    {
        while (true) {
            $results = $scrollId
                ? $client->scroll(['scroll' => $duration, 'scroll_id' => $scrollId])
                : $client->search($options + array_filter(['size' => $size, 'scroll' => $duration]));

            if ($results['hits']['hits']) {
                $scrollId = $results['_scroll_id'];

                foreach ($results['hits']['hits'] as $row) {
                    yield $row;
                }
            }
            else {
                break;
            }
        }

        $scrollId && $client->clearScroll(['scroll' => $duration, 'scroll_id' => $scrollId]);
    }
}
