<?php


namespace App\library;


use App\BjGame;
use App\BjHands;
use Helper;
class GameRunner
{
    private $game;

    public function __construct(BjGame $game)
    {
        $this->game=$game;
    }

    public function shuffleDeck()
    {
        $this->game->deck->shuffleDeck();
    }

    public function startTurn()
    {
        $this->game->hands()->save(BjHands::create());
        $this->deal_cards();
    }

    public function hit()
    {
        $this->game->currentHand()->update([
            'p_hand'=>collect($this->game->currentHand()->p_hand)->merge($this->game->deck->TakeCard())
        ]);
        $this->game->deck->save();
        $this->checkPoint();
    }

    public function checkPoint()
    {
        $hand=Helper::calculateHand($this->game->currentHand()->p_hand);
        if($hand > 21)
        {
            $this->game->currentHand()->finishGame();
        }
        elseif($hand == 21)
        {
            $this->dealerHitUntilStand();
        }
        else
        {
            $this->setPlayerPoint();
        }
    }

    public function stand()
    {
        $this->dealerHitUntilStand();
    }

    public function dealerCanHit() // 16 olmuş mu
    {
        $counter=Helper::calculateHand($this->game->currentHand()->d_hand);
        if ($counter>16)
        {
            $this->game->currentHand()->finishGame();
            return false;
        }
        else
        {
            return true;
        }
    }

    public function dealerHit()
    {
        $this->game->currentHand()->update([
            'd_hand'=>collect($this->game->currentHand()->d_hand)->merge($this->game->deck->TakeCard())
        ]);
        $this->game->deck->save();
    }

    public function dealerHitUntilStand() // 16 olana veya 21 i geçene kadar kart çek
    {
        while ($this->dealerCanHit())
        {
            $this->dealerHit();
        }
    }


    public function deal_cards()
    {
        //print_r($this->game->currentHand()->p_hand);
        $this->game->currentHand()->update([
           'p_hand'=>$this->game->deck->TakeCard(2),
           'd_hand'=>$this->game->deck->TakeCard(2)
        ]);
        $this->game->deck->save();
        $this->checkPoint();
    }

    public function setPlayerPoint()
    {
        $point_dealer=Helper::calculateHand($this->game->currentHand()->d_hand);
        $point_player=Helper::calculateHand($this->game->currentHand()->p_hand);
        $this->game->currentHand()->update([
            'player_point'=>$point_player,
            'dealer_point'=>$point_dealer
        ]);
        $this->game->deck->save();
    }


}
