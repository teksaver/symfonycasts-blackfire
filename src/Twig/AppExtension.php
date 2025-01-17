<?php

namespace App\Twig;

use App\Entity\User;
use App\Service\CommentHelper;
use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $commentHelper;

    public function __construct(CommentHelper $commentHelper, private CacheInterface $cache)
    {
        $this->commentHelper = $commentHelper;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('user_activity_text', [$this, 'getUserActivityText']),
        ];
    }

    public function getUserActivityText(User $user): string
    {
        $key = sprintf('user_activity-Text_%s', $user->getId());

        return $this->cache->get($key, function(CacheItemInterface $item) use ($user){
            $item->expiresAfter(time:3600);
            return $this->computeUserActivityText($user);
        });
      
    }

    private function computeUserActivityText(User $user) : string{
        $commentCount = $this->commentHelper->countRecentCommentsForUser($user);

        if ($commentCount > 50) {
            return 'bigfoot fanatic';
        }

        if ($commentCount > 30) {
            return 'believer';
        }

        if ($commentCount > 20) {
            return 'hobbyist';
        }

        return 'skeptic';
    }
}
