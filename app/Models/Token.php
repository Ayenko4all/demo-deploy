<?php

namespace App\Models;

use App\Options\TokenTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Token.
 *
 * @property int                             $id
 * @property null|string                     $email
 * @property null|string                     $user_id
 * @property string                          $token
 * @property null|array                      $data
 * @property string                          $bvn
 * @property string                          $type
 * @property bool                            $verified
 * @property null|\Illuminate\Support\Carbon $created_at
 * @property null|\Illuminate\Support\Carbon $updated_at
 *
 * @method static Builder|Token emailToken()
 * @method static \Database\Factories\TokenFactory factory(...$parameters)
 * @method static Builder|Token newModelQuery()
 * @method static Builder|Token newQuery()
 * @method static Builder|Token notVerified()
 * @method static Builder|Token query()
 * @method static Builder|Token verified()
 * @method static Builder|Token whereCreatedAt($value)
 * @method static Builder|Token whereEmail($value)
 * @method static Builder|Token whereUserId($value)
 * @method static Builder|Token whereId($value)
 * @method static Builder|Token whereToken($value)
 * @method static Builder|Token whereType($value)
 * @method static Builder|Token whereUpdatedAt($value)
 * @method static Builder|Token whereVerified($value)
 * @mixin \Eloquent
 */
class Token extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'verified' => 'boolean',
        'data' => 'array'
    ];


    public static function deleteToken($type, $column): void
    {
        self::whereType($type)
            ->where(function ($query) use ($column) {
                return $query->where('telephone', $column)->orWhere('email', $column);
            })
            ->delete();
    }

}
