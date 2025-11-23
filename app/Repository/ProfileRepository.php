<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\ProfileData;
use App\Models\Profile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class ProfileRepository
{
    public function __construct(
        private Profile $model,
    ) {}

    public function find(int $id): ProfileData
    {
        return ProfileData::from($this->model->with('address', 'company')->findOrFail($id)->toArray());
    }

    /**
     * @return Collection<ProfileData>
     */
    public function all(): Collection
    {
        return ProfileData::collect($this->model->all(), Collection::class);
    }

    public function exists(int $profileId): bool
    {
        return $this->model->where('profile_id', $profileId)->count() > 0;
    }

    public function create(ProfileData $profile): ProfileData
    {
        return DB::transaction(function () use ($profile): ProfileData {
            /** @var Profile */
            $profileModel = $this->model->create($profile->toArray());

            $profileModel->address()->create($profile->address->toArray());
            $profileModel->company()->create($profile->company->toArray());

            return ProfileData::from($profileModel);
        });
    }
}
