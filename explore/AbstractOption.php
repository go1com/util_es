<?php

namespace go1\util_es\explore;

use Assert\LazyAssertion;
use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Search;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractOption implements OptionInterface
{
    public function params(): array
    {
        return [];
    }

    public function validate(LazyAssertion $assertion, FilterOption $filter, Request $req)
    {
    }

    public function query(Search $search, BuilderInterface $query, FilterOption $filter, Request $req)
    {
    }
}
