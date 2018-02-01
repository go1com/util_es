<?php

namespace go1\util_es\explore;

use Assert\LazyAssertion;
use ONGR\ElasticsearchDSL\BuilderInterface;
use ONGR\ElasticsearchDSL\Search;
use Symfony\Component\HttpFoundation\Request;

interface OptionInterface
{
    const PRIORITY_HIGH   = -100;
    const PRIORITY_NORMAL = 0;
    const PRIORITY_LOW    = 100;

    public function params(): array;

    public function priority(): int;

    public function validate(LazyAssertion $assertion, FilterOption $filter, Request $request);

    public function query(Search $search, BuilderInterface $query, FilterOption $filter, Request $request);
}
