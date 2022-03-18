<?php

namespace App\Listeners;

use App\Models\ConsultantInvite;
use App\Models\UserRelation;
use App\Models\UserRelations;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateChildUserListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        /** @var User $event */
        $user = $event->user;
        $inviteToken = $user->invite_token;

        // find the token
        $invitation = ConsultantInvite::where('token', $inviteToken)->first();
        if (!$invitation) {
            return;
        }

        if (!$invitation->email) {
            $invitation->email = $user->email;
            $invitation->save();
        }

        $parentUser = $invitation->user;

        if (!$parentUser) {
            return;
        }

        $role = $parentUser->role->first();
        $childUserType = ConsultantInvite::INVITATION_TYPE[$role] ?? null;
        if (!$childUserType) {
            return;
        }

        // Create a record in user relations..
        UserRelation::create([
            'child_id' => $user->id,
            'level' => 1,
            'parent_id' => $parentUser->id,
            'status' => UserRelation::ACTIVE_STATUS['ACTIVE'],
            'type' => $childUserType
        ]);

    }
}
