<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Session;

class Helper
{
    public static function calculateHand($hand)
    {
        $counter = 0;
        $aces=0;
        foreach ($hand as $item) {
            if(intval($item) == 0){
                if($item[0] == 'A'){
                    $aces++;
                } else {
                    $counter +=10;
                }
            } else {
                $counter += intval($item);
            }
        }
        for ($i=0;$i<$aces;$i++)
        {
            if($counter+11 > 21 )
            {
                $counter+=1;
            }
            else
            {
                $counter+=11;
            }
        }
        return $counter;
    }

    public static function setFlashData($data)
    {
        switch ($data)
        {
            case 'blackjack':
                Session::flash('blackjack','Blacjack!! You Win');
                break;
            case 'user_busted':
                Session::flash('user_busted','User Busted! Dealer Wins');
            break;
            case 'dealer_busted':
                Session::flash('dealer_busted','Dealer Busted! You Win');
            break;
            case 'user_wins':
                Session::flash('user_wins','You Win');
            break;
            case 'dealer_wins':
                Session::flash('dealer_wins','Dealer Wins');
            break;
            case 'draw':
                Session::flash('draw','Draw');
            break;
        }
    }
}
