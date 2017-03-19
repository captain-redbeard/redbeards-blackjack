<?php
/**
 * @author captain-redbeard
 * @since 14/01/17
 */
namespace Blackjack\Controllers;

use Redbeard\Crew\Controller;

class Home extends Controller
{
    public function __construct()
    {
        //Start session
        $this->startSession();
    }
    
    public function index($function = null, $action = null, $raw = null)
    {
        //New game
        if (!isset($_SESSION['gm'])) {
            $deckstouse = 8;
            $gm = $this->model('GameManager');
            $gm->init($deckstouse, false);
            $_SESSION['gm'] = $gm;
        } elseif ($_SESSION['gm']->gameover && $_SESSION['gm']->deckmanager->getCardsLeft() < 52) {
            $message = "Decks shuffled";
            $_SESSION['gm']->shuffle();
        }
        
        //Function
        $this->action($function, $action);
        
        //Check if raw
        if ($action === 'raw') {
            $raw = $action;
        }
        
        //View
        $this->view(
            ['game'],
            [
                'page' => 'game',
                'page_title' => $this->config('site.name'),
                'gm' => $_SESSION['gm'],
                'function' => $function,
                'action' => $action
            ],
            $raw !== null ? true : false
        );
    }
    
    private function action($function, $action)
    {
        if (isset($function)) {
            //Switch function
            switch ($function) {
                case "reset":
                    $_SESSION['gm']->reset();
                    $_SESSION['gm'] = null;
                    header("Location: " . $this->config('app.base_href'));
                    break;
                case "show":
                    $_SESSION['gm']->blind = false;
                    break;
                case "increase":
                    $_SESSION['gm']->increaseBet();
                    break;
                case "decrease":
                    $_SESSION['gm']->decreaseBet();
                    break;
                case "placebets":
                    $_SESSION['gm']->placeBets();
                    break;
                case "setbet":
                    switch($action){
                        case "one":
                            $_SESSION['gm']->increaseBet(1);
                            break;
                        case "five":
                            $_SESSION['gm']->increaseBet(5);
                            break;
                        case "twentyfive":
                            $_SESSION['gm']->increaseBet(25);
                            break;
                        case "onehundred":
                            $_SESSION['gm']->increaseBet(100);
                            break;
                    }
                    break;
                case "clearbet":
                    $_SESSION['gm']->clearBet();
                    break;
                case "deal":
                    $_SESSION['gm']->deal();
                    break;
                case "hit":
                    $_SESSION['gm']->hit();
                    break;
                case "stand":	
                    $_SESSION['gm']->stand();
                    break;
                case "surrender":
                    $_SESSION['gm']->surrender();
                    break;
                case "sound":
                    if ($_SESSION['gm']->sound) {
                        $_SESSION['gm']->sound = false;
                    } else {
                        $_SESSION['gm']->sound = true;
                    }
                default:
                    break;
            }
        }
    }
}