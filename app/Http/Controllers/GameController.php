<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deck;
use App\BjGame;
use App\library\GameRunner;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{

    public function show()
    {
        $bjGame=Auth::user()->currentGame();
        if(!$bjGame)
        {
            return view('game',['game_active'=>0]);
        }
        else
        {
            return view('game',['game_active'=>1,'game'=>$bjGame]);
        }
    }

    public function createGame()
    {
        $deck=Deck::create();
        $deck->game()->save(BjGame::create());
        $bjGame=Auth::user()->currentGame();
        $game_runner=new GameRunner($bjGame);
        $game_runner->shuffleDeck();
        return redirect()->route('showGame');
    }

    public function takeCard()
    {
        $bjGame=Auth::user()->currentGame();
        $game_runner=new GameRunner($bjGame);
        $game_runner->hit();
        return redirect()->route('showGame');

    }

    public function stand()
    {
        $bjGame=Auth::user()->currentGame();
        $game_runner=new GameRunner($bjGame);
        $game_runner->stand();
        return redirect()->route('showGame');
    }

    public function startNewTurn()
    {
        $bjGame=Auth::user()->currentGame();
        $game_runner=new GameRunner($bjGame);
        $game_runner->startTurn();
        return redirect()->route('showGame');
    }
}
