<?php

use Illuminate\Support\Facades\Broadcast;

use App\Models\User;
use App\Models\Company;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('channel-order-notification-admin.{companyId}', function (User $user, $companyId) {
    $company = Company::where('user_id', $user->id)->first();
    return $company->id == (int) $companyId;
});

Broadcast::channel('channel-order-status-updated.{clientId}', function ($client, $clientId) {
    return auth()->guard('client')->user()->id === (int) $clientId;
});

/*Broadcast::channel('channel-teste', function () {
    return true;
});*/
