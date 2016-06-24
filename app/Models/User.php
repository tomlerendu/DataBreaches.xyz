<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'rank'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Determines if a user is a given rank
     *
     * @param mixed $rank The rank to check
     * @return bool
     */
    public function isRank($rank): bool
    {
        return $this->rank == $rank;
    }

    /**
     * Determines if a user has at least a given rank
     *
     * @param mixed $rank The rank to check
     * @return bool
     */
    public function isMinimumRank($rank): bool
    {
        return $this->rank >= $rank;
    }

    /**
     * Determines if a user has a given password
     *
     * @param string $password The password to check
     * @return bool
     */
    public function hasPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Updates a users password.
     *
     * @param string $password The given password
     */
    public function changePassword(string $password)
    {
        $this->password = Hash::make($password);
    }

    /**
     * Gets the users rank.
     *
     * @param string $value
     * @return mixed The enumerated rank value for the user
     */
    public function getRankAttribute(string $value)
    {
        $ranks = [
            'User' => UserRankEnum::User,
            'Editor' => UserRankEnum::Editor
        ];

        return $ranks[$value];
    }

    /**
     * Sets the users rank.
     *
     * @param mixed $value The enumerated rank value for the user
     */
    public function setRankAttribute($value)
    {
        $ranks = [
            UserRankEnum::User => 'User',
            UserRankEnum::Editor => 'Editor'
        ];

        $this->attributes['rank'] = $ranks[$value];
    }

    /**
     * Gets a trimmed username for the user. Will be a maximum of 12 characters in length.
     *
     * @return string The trimmed username
     */
    public function getTrimmedUsernameAttribute()
    {
        if (strlen($this->username) > 12) {
            return substr($this->username, 0, 9) . '...';
        }

        return $this->username;
    }

}
