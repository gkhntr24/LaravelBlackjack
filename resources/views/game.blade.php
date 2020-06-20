<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    html {
        width: 100%;
        height: 100%;
    }

    body {
        width: 100%;
        height: 100%;
        background: #128c5d;
    }

    .center_div {
        position: absolute;
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

    .delaer_area {
        position: absolute;
        width: 100%;
        left: 0;
        top: 0;
    }

    .player_area {
        position: absolute;
        width: 100%;
        left: 0;
        bottom: 0;
    }

    .user_icons {
        width: 100px;
        height: 100px;
        object-fit: cover;
        background: white;
        border-radius: 50%;
    }

    .card_hand {
        border: 1px solid white;
        margin-left: 0.2rem;
        margin-right: 0.2rem;
    }

    .game_state {
        position: absolute;
        left: 0%;
        top: 50%;
        -webkit-transform: translate(0, -50%);
        transform: translate(0, -50%);
        border: 1px solid white;
    }

    .hand_state {
        position: absolute;
        right: 0%;
        top: 40%;
        -webkit-transform: translate(0, -40%);
        transform: translate(0, -40%);
    }

</style>
<body>
<div class="container h-100 m-auto position-relative">
    @if($game_active)
        <div class="hand_state p-3 text-center">
            @if(Session::has('blackjack'))
                <h3 class="text-white"> {{Session::get('blackjack')}}</h3>
                <img style="width: 200px;height: 200px;" src="{{asset('images/blackjack.gif')}}">
            @endif
            @if(Session::has('user_busted'))
                <h3 class="text-white"> {{Session::get('user_busted')}}</h3>
                <img style="width: 200px;height: 200px;" src="{{asset('images/lose.gif')}}">
            @endif
            @if(Session::has('dealer_busted'))
                <h3 class="text-white"> {{Session::get('dealer_busted')}}</h3>
                <img style="width: 200px;height: 200px;" src="{{asset('images/userwin.gif')}}">
            @endif
            @if(Session::has('user_wins'))
                <h3 class="text-white"> {{Session::get('user_wins')}}</h3>
                <img style="width: 200px;height: 200px;" src="{{asset('images/userwin.gif')}}">
            @endif
            @if(Session::has('dealer_wins'))
                <h3 class="text-white"> {{Session::get('dealer_wins')}}</h3>
                <img style="width: 200px;height: 200px;" src="{{asset('images/lose.gif')}}">
            @endif
            @if(Session::has('draw'))
                <h3 class="text-white"> {{Session::get('draw')}}</h3>
                <img style="width: 200px;height: 200px;" src="{{asset('images/draw.gif')}}">
            @endif
        </div>
        <div class="game_state p-3">
            <p class="text-white font-weight-bold">Dealer Wins : {{$game->dealer_wins}}</p>
            <p class="text-white font-weight-bold mb-0">Player Wins : {{$game->player_wins}}</p>
        </div>
        <div class="row p-3 delaer_area">
            <div class="text-center col-9">
                <img src="{{asset('images/dealer.png')}}" class="user_icons">
                <div class="card_area m-3">
                    @if($game->currentHand())
                        @foreach($game->currentHand()->d_hand as $key=>$value)
                            <div class="card_hand d-inline-block">
                                <div class="card_ p-1">
                                    @if(count($game->currentHand()->d_hand)==2&&$key==1&&$game->currentHand()->state=='new')
                                        @php $value='closed'; @endphp
                                    @endif
                                    <img src="{{asset('images/cards/'.$value.'.gif')}}">
                                </div>
                            </div>
                        @endforeach
                        @if($game->currentHand()->state!='new')
                            <h3 class="text-white mt-3">{{$game->currentHand()->dealer_point}}</h3>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-3">
                <div class="deck position-relative text-center">
                    @if ($game->deck->is_done&&$game->currentHand()->state!='new')
                        <h3 class="text-white">Deste Bitti</h3>
                    @else
                        <h3 class="text-white">Deste : {{count($game->deck->cards)}}</h3>
                    @endif
                    @for($i=0;$i<20;$i++)
                        <div class="position-absolute" style="left: {{$i*10}}px">
                            <img src="{{asset('images/cards/closed.gif')}}">
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        <div class="row p-3 player_area">
            <div class="text-center col-9">
                <div class="card_area m-3">
                    @if($game->currentHand())
                        <h3 class="text-white mb-3">{{$game->currentHand()->player_point}}</h3>
                        @foreach($game->currentHand()->p_hand as $key=>$value)
                            <div class="card_hand d-inline-block">
                                <div class="card_ p-1">
                                    <img src="{{asset('images/cards/'.$value.'.gif')}}">
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <img src="{{asset('images/user1.png')}}" class="user_icons">
            </div>
            <div class="col-3 p-3">
                @if($game->currentHand() && $game->currentHand()->state=='new')
                    <div class="mb-3">
                        <form action="{{route('takeCard')}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">Hit</button>
                        </form>
                    </div>
                    <div class="mb-3">
                        <form action="{{route('stand')}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Stand</button>
                        </form>
                    </div>
                @else
                    @if (!$game->deck->is_done)
                        <div class="mb-3">
                            <form action="{{route('startNewTurn')}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">New Hand</button>
                            </form>
                        </div>
                    @endif
                    <div class="mb-3">
                        <form action="{{route('createGame')}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">New Game</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="center_div text-center">
            <form action="{{route('createGame')}}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Start Game</button>
            </form>
        </div>
    @endif
</div>
</body>
</html>
