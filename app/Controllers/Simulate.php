<?php
/**
 * @author captain-redbeard
 * @since 14/01/17
 */
namespace Redbeard\Controllers;

use Redbeard\Models\GameManager;

class Simulate extends Controller
{
    public function index()
    {
        //Create new game manager
        $gm = new GameManager();
        $gm->init(8, true);
        $gm->coins = 1000000;
        $gm->increaseBet(1);
        
        $games = 10000;
        $totalUserWinCount = 0;
        $totalDealerWinCount = 0;
        $totalDrawCount = 0;
        $result = -1;
        
        //Simulate games
        for ($x = 0; $x < $games; $x++) {
            
            //Shuffle
            if ($gm->gameover && $gm->deckmanager->getCardsLeft() < 52) {
                $gm->shuffle();
            }
            
            //Place bets
            $gm->placeBets();
            
            //Deal
            $gm->deal();
            
            //User hit or stand
            if ($gm->getUserTotal() < 12 || ($gm->deckmanager->isSoft($gm->getUserCards()) && $gm->getUserTotal() == 17)) {
                do {
                    $gm->hit();
                } while ($gm->getUserTotal() < 12 || ($gm->deckmanager->isSoft($gm->getUserCards()) && $gm->getUserTotal() == 17));
            }
            
            //Get message / result
            $result = $gm->stand();
            
            //Payout
            $gm->payout();
            
            //Switch result
            switch ($result) {
                case 0: //User win
                    $totalUserWinCount++;
                    break;
                case 1: //Draw
                    $totalDrawCount++;
                    break;
                case 2: //Dealer Wins
                    $totalDealerWinCount++;
                    break;
                case 3: //User surrender
                    break;
            }
        }
        
        //View
        $this->view(
            ['simulate'],
            [
                'page' => 'simulate',
                'page_title' => 'Simulation - ' . $this->config('site.name'),
                'user_win_count' => $totalUserWinCount,
                'dealer_win_count' => $totalDealerWinCount,
                'draw_count' => $totalDrawCount,
                'game_count' => $games
            ],
            false
        );
    }
}