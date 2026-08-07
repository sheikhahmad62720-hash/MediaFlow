<?php

namespace App\Repositories;

use App\DTOs\Filters\DownloadFilters;
use App\Models\Download;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DownloadRepository
{
    public function find(string $id): ?Download
    {
        return Download::where('id', $id)->first();
    }

    public function create(array $data): Download
    {
        return Download::create($data);
    }

    public function update(string $id, array $data): Download
    {
        $download = $this->find($id);

        if ($download === null) {
            throw new ModelNotFoundException('Download not found.');
        }

        $download->update($data);

        return $download->fresh();
    }

    public function paginate(DownloadFilters $filters, ?string $userId = null): LengthAwarePaginator
    {
        return $this->query($filters, $userId)->paginate($filters->perPage);
    }

    protected function query(DownloadFilters $filters, ?string $userId = null): Builder
    {
        return Download::query()
            ->with('platform')
            ->when($userId, fn (Builder $q) => $q->where('user_id', $userId))
            ->when($filters->status, fn (Builder $q) => $q->where('status', $filters->status))
            ->when($filters->platform, fn (Builder $q) => $q->whereHas('platform', fn (Builder $sq) => $sq->where('slug', $filters->platform)))
            ->when($filters->format, fn (Builder $q) => $q->where('format', $filters->format))
            ->when($filters->type, fn (Builder $q) => $q->where('media_type', $filters->type))
            ->when($filters->search, function (Builder $q, string $term): void {
                $q->where(function (Builder $sq) use ($term): void {
                    $sq->where('title', 'like', '%'.$term.'%')
                        ->orWhere('source_url', 'like', '%'.$term.'%')
                        ->orWhere('file_name', 'like', '%'.$term.'%');
                });
            })
            ->orderBy($filters->sort ?? 'created_at', $filters->direction ?? 'desc');
    }
}
