<?php

namespace App\Utils;

class SearchQueryUtil
{
    public static function getSearchKeyword(array $queryParam)
    {
        return isset($queryParam['q']) ? $queryParam['q'] : '';
    }

    public static function getSearchType(array $queryParam)
    {
        return array_filter([
            'subject' => isset($queryParam['s']),
            'content' => isset($queryParam['c']),
            'author' => isset($queryParam['a'])
        ]);
    }

    public static function getKeywordQuery($searchKeyword, $searchType)
    {
        $keywordQuery = [];
        
        if($searchKeyword) {
            $keywordQuery['q'] = $searchKeyword;
        }

        if(isset($searchType['subject'])) {
            $keywordQuery['s'] = 'on';
        }
        if(isset($searchType['content'])) {
            $keywordQuery['c'] = 'on';
        }
        if(isset($searchType['author'])) {
            $keywordQuery['a'] = 'on';
        }

        return $keywordQuery;
    }
}
