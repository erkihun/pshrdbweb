<?php

namespace App\Enums;

enum HomeSection: string
{
    case HERO = 'home.hero';
    case SERVICES_HIGHLIGHT = 'home.services_highlight';
    case NEWS_HIGHLIGHT = 'home.news_highlight';
    case STATS = 'home.stats';
    case FOOTER_LINKS = 'home.footer_links';

    public static function keys(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
