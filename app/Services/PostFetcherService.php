<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\PostData;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class PostFetcherService
{
    public function __construct(
        private PlaceholderApiInterface $client
    ) {}

    /**
     * @return Collection<PostData>
     */
    public function fetchPosts(): Collection
    {
        $posts = Arr::map($this->client->getPosts(), fn ($post) => $this->transformPost($post));

        return PostData::collect($posts, Collection::class);
    }

    public function transformPost(array $post): array
    {
        $post['profile_id'] = $post['userId'];
        $post['post_id'] = $post['id'];
        unset($post['userId'], $post['id']);

        return $post;
    }
}
