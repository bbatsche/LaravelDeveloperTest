<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\PostData;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Utility for fetching posts data from JSON Placeholder API and transforming it into data objects.
 *
 * @implements EntityFetcherInterface<PostData>
 */
final class PostFetcherService implements EntityFetcherInterface
{
    public function __construct(
        private PlaceholderApiInterface $client
    ) {}

    public function fetchEntities(): Collection
    {
        $posts = Arr::map($this->client->getPosts(), fn ($post) => $this->transformPost($post));

        return PostData::collect($posts, Collection::class);
    }

    /**
     * Modify keys from JSON Placeholder API to be inline with what PostData expects.
     *
     * We could *probably* do this internally to the PostData class but enforcing a boundary
     * here means all data from this point *should* be consistent.
     */
    public function transformPost(array $post): array
    {
        $post['profile_id'] = $post['userId'];
        $post['post_id'] = $post['id'];
        unset($post['userId'], $post['id']);

        return $post;
    }
}
