<?php

namespace App\Enums;

enum GameStatus: string
{
    case LOBBY = 'lobby';
    case PLAYING = 'playing';
    case ENDED = 'ended';
}
