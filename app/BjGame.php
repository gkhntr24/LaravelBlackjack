<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BjGame extends Model
{

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::creating(function (self $game)
        {
            $game->user_id=Auth::id();
        });
    }


    public function deck()
    {
        return $this->belongsTo('App\Deck','deck_id','id');
    }

    public function hands()
    {
        return $this->hasMany('App\BjHands','bjgame_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function currentHand()
    {
        return $this->hands()->latest()->first();
    }

    public function userWin()
    {
        $this->player_wins++;
        $this->save();
    }

    public function dealerWin()
    {
        $this->dealer_wins++;
        $this->save();
    }


}
