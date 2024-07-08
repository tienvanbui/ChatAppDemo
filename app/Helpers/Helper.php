<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('get_file_url')) {
    function get_file_url($filePath): ?string
    {
        if ($filePath && !parse_url($filePath, PHP_URL_SCHEME)) {
            return Storage::url($filePath);
        }

        return $filePath;
    }
}

if (!function_exists('get_basic_user_info')) {
    function get_basic_user_info($user = null)
    {
        return [
            'id' => $user?->id,
            'name' => $user?->name,
            'avatar' => get_user_avatar($user),
        ];
    }
}

if (!function_exists('get_user_avatar')) {
    function get_user_avatar($user, $expectDefault = true): ?string
    {
        if ($user?->profile_photo_path) {
            return  get_file_url($user?->profile_photo_path);
        }

        if ($expectDefault) {
            return env('APP_URL') . '/images/default-avatar.jpg';
        }

        return null;
    }
}

if (!function_exists('get_current_user_login')) {
    function get_current_user_login()
    {
        // return \App\Models\User::find(2);
        return auth('web')->user();
    }
}

// Chat helper

if (!function_exists('get_conversation_users')) {
    function get_conversation_users($users)
    {
        if (!$users) {
            return [];
        }

        return $users->map(fn ($user) => get_basic_user_info($user));
    }
}
