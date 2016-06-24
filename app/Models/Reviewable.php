<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

trait Reviewable
{
    /**
     * The user that created the record.
     */
    public function creator()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    /**
     * Gets formatted status which explains what the state of the record means.
     *
     * @return string
     */
    public function getReadableStatus(): string
    {
        $statuses = [
            'Submitted' => 'Pending Approval',
            'RejectedInfo' => 'Rejected (Invalid Information)',
            'RejectedDuplicate' => 'Rejected (Already Submitted)',
            'RejectedSource' => 'Rejected (Invalid Source)',
            'Accepted' => 'Live',
            'Superseded' => 'Superseded'
        ];

        return $statuses[$this->status];
    }

    /**
     * Finds previous revisions of a record.
     * Limited to 20 revisions.
     *
     * @return array
     */
    public function getPreviousRevisions(): array
    {
        $revisions = [];
        $previousId = $this->previous_id;

        //Find up to 20 previous IDs
        for ($i=0; $i<20; $i++) {
            //If there is a previous revision find it and add it to the revision array.
            if ($previousId !== null) {
                $revisions[] = $previousId;
                $previousRevision = DB::table($this->getTable())->where('id', $previousId)->first();
                $previousId = $previousRevision->previous_id;
            } else {
                break;
            }
        }

        return $revisions;
    }

    /**
     * Determines if a specific user can edit a record
     *
     * @param int $userId The user to check
     * @return bool
     */
    public function canEdit(int $userId): bool
    {
        return $this->status == 'Submitted' && $this->user_id == $userId;
    }

    /**
     * Determines if a specific user can supersede a record
     *
     * @param int $userId The user to check
     * @return bool
     */
    public function canSupersede(int $userId): bool
    {
        return $this->status == 'Accepted';
    }

    /**
     * Determines if a specific user can delete a record
     *
     * @param int $userId The user to check
     * @return bool
     */
    public function canDelete(int $userId): bool
    {
        return $this->canEdit($userId);
    }

    /**
     * Scope function to only select records that a specific user can edit.
     *
     * @param $query
     * @param int $userId The user to select with
     * @return mixed
     */
    public function scopeCanEdit($query, int $userId)
    {
        return $query->where('status', 'Submitted')->where('user_id', $userId);
    }

    /**
     * Scope function to only select records that a specific user can supersede.
     *
     * @param $query
     * @param int $userId The user to select with
     * @return mixed
     */
    public function scopeCanSupersede($query, int $userId)
    {
        return $query->where('status', 'Accepted');
    }

    /**
     * Scope function to only select records that a specific user can delete.
     *
     * @param $query
     * @param int $userId The user to select with
     * @return mixed
     */
    public function scopeCanDelete($query, int $userId)
    {
        return $query->canEdit($userId);
    }

    /**
     * Scope function to determine if a user can do at least one of: Edit, Supersede, or Delete on a record.
     *
     * @param $query
     * @param int $userId The user to select on
     * @param bool $edit True if edit should be included
     * @param bool $supersede True if supersede should be included
     * @param bool $delete True if delete should be included
     * @return mixed
     */
    public function scopeCanDoEither($query, int $userId, bool $edit = false, bool $supersede = false, bool $delete =false)
    {
        //Early return if no permissions are required
        if (!$edit && !$supersede && !$delete) {
            return $query;
        }

        $query->where(function ($query) use ($userId, $edit, $supersede, $delete) {

            if ($edit) {
                $query->orWhere( function($query) use ($userId) {
                    $query->canEdit($userId);
                });
            }

            if ($supersede) {
                $query->orWhere( function($query) use ($userId) {
                    $query->canSupersede($userId);
                });
            }

            if ($delete) {
                $query->orWhere( function($query) use ($userId) {
                    $query->canDelete($userId);
                });
            }

        });

        return $query;
    }

    /**
     * Scope function to only select records with a specific status.
     *
     * @param $query
     * @param array $statuses The statuses to select from (Submitted, Approved, RejectedMeta, RejectedSource,
     *      RejectedDuplicate, Superseded)
     * @return mixed
     */
    public function scopeStatus($query, array $statuses) {
        return $query->whereIn('status', $statuses);
    }
}