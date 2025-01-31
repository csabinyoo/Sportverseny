<?php

namespace App\Policies;

use App\Models\User;
use App\Models\member_results_at_station;
use Illuminate\Auth\Access\Response;

class MemberResultsAtStationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, member_results_at_station $memberResultsAtStation): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, member_results_at_station $memberResultsAtStation): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, member_results_at_station $memberResultsAtStation): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, member_results_at_station $memberResultsAtStation): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, member_results_at_station $memberResultsAtStation): bool
    {
        //
    }
}
