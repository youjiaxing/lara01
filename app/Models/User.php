<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_admin
 * @property int $is_active
 * @property string|null $activation_token
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Status[] $statuses
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereActivationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $user) {
            $user->activation_token = Str::random(30);
        });
    }

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->getAttribute('email'))));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function statuses()
    {
        return $this->hasMany(Status::class, 'user_id', 'id');
    }

    /**
     * @return Status|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function feed()
    {
        // 关注的人 以及 自己的所有微博
        $userIds = $this->followings()->pluck("followers.user_id")->toArray();
//        $userIds = $this->followings->pluck("id")->toArray();
        $userIds[] = $this->id;

        return Status::whereIn("user_id", $userIds)->with("user")->orderBy("created_at", "desc");
//        return $this->statuses()->orderBy("created_at", "desc");
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, "followers", "user_id", "follower_id")->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, "followers", "follower_id", "user_id")->withTimestamps();
    }

    public function follow($userIds)
    {
        $this->followings()->sync(is_array($userIds) ? $userIds : func_get_args(), false);
    }

    public function unfollow($userIds)
    {
        $this->followings()->detach(is_array($userIds) ? $userIds : func_get_args());
    }

    public function isFollowing($userId)
    {
        return $this->followings->contains($userId);
//        return $this->followings()->allRelatedIds()->contains($userId);
    }
}
