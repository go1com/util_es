<?php

namespace go1\util_es\search;

use Elasticsearch\Client;
use go1\util\customer\CustomerEsSchema;
use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use RuntimeException;

class AccountSearch
{
    private $client;

    /**
     * @var int
     */
    private $portalId;

    /**
     * @var Search
     */
    private $search;

    /**
     * @var array
     */
    private $includeFields;

    /**
     * @var array
     */
    private $excludeFields;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->search = new Search;
    }

    public function portalId(int $portalId): self
    {
        $this->portalId = $portalId;

        return $this;
    }

    public function addQuery(BuilderInterface $query, $boolType = BoolQuery::MUST, $key = null): self
    {
        $this->search->addQuery($query, $boolType, $key);

        return $this;
    }

    public function offset(int $offset): self
    {
        if ($offset < 0 || $offset > 9999) {
            throw new RuntimeException('Offset must be great then 0 and less then 9999');
        }

        $this->search->setFrom($offset);

        return $this;
    }

    public function limit(int $limit): self
    {
        if ($limit > 9999) {
            throw new RuntimeException('Limit must be less then 9999');
        }

        $this->search->setSize($limit);

        return $this;
    }

    public function include(array $fields): self
    {
        $this->includeFields = $fields;

        return $this;
    }

    public function exclude(array $fields): self
    {
        $this->excludeFields = $fields;

        return $this;
    }

    public function run(): array
    {
        if ($this->portalId) {
            $this->search->addQuery(new TermQuery('metadata.instance_id', $this->portalId), BoolQuery::FILTER);
        }

        $params = [
            'index' => CustomerEsSchema::INDEX,
            'type'  => CustomerEsSchema::O_ACCOUNT,
            'body'  => $this->search->toArray(),
        ];

        $this->includeFields && $params['_source_include'] = $this->includeFields;
        $this->excludeFields && $params['_source_exclude'] = $this->excludeFields;

        return $this->client->search($params);
    }
}
