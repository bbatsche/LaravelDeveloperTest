<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\PostData;
use App\Models\Post;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

/**
 * @implements RepositoryInterface<PostData>
 */
final class PostRepository implements RepositoryInterface
{
    public function __construct(
        private Post $model,
    ) {}

    public function find(int $id): PostData
    {
        return PostData::from($this->model->with('profile')->findOrFail($id)->toArray());
    }

    /**
     * @return Collection<PostData>
     */
    public function all(): Collection
    {
        return PostData::collect($this->model->with('profile')->get()->toArray(), Collection::class);
    }

    public function exists(int $postId): bool
    {
        return $this->model->where('post_id', $postId)->count() > 0;
    }

    /**
     * @param  PostData  $post
     */
    public function create(Data $post): PostData
    {
        /** @var Post */
        $postModel = $this->model->create($post->toArray())->load('profile');

        return PostData::from($postModel->toArray());
    }
}
