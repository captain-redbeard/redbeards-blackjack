<?php
/**
 * @author captain-redbeard
 * @since 20/02/16
 */
namespace Redbeard\Models;

use Redbeard\Models\DeckManager;

class GameManager
{
    private $debug;
    public $deckcount;
    public $deckmanager;
    public $handcount;
    public $usercards;
    public $dealercards;
    public $deal;
    public $gameover;
    public $payout;
    public $blind;
    public $blackjack;
    public $coins;
    public $bet;
    public $betamount;
    public $result;
    public $endmessage;
    
    public function __construct()
    {
        $this->debug = false;
        $this->sound = true;
        $this->deckmanager = new DeckManager();
        $this->handcount = 0;
        $this->usercards = [];
        $this->dealercards = [];
        $this->deal = false;
        $this->gameover = true;
        $this->payout = true;
        $this->blackjack = false;
        $this->blind = true;
        $this->coins = 1000;
        $this->bet = 0;
        $this->betamount = 0;
        $this->result = -1;
        $this->endmessage = "";
    }
    
    public function init($deck_count, $debug)
    {
        $this->debug = $debug;
        $this->deckcount = $deck_count;
        $this->deckmanager->init($deck_count);
    }
    
    public function reset()
    {
        $this->__construct();
        $this->init($this->deckcount, $this->debug);
        
        return true;
    }
    
    public function shuffle()
    {
        $this->deckmanager = new DeckManager();
        $this->deckmanager->init($this->deckcount);
        
        return true;
    }
    
    public function placeBets()
    {
        $this->gameover = false;
        $this->usercards = [];
        $this->dealercards = [];
        $this->endmessage = '';
        
        return true;
    }
    
    public function increaseBet($amount)
    {
        if (!$this->deal && $this->coins > 0) {
            $this->bet += $amount;
            
            if ($this->bet > $this->coins) {
                $this->bet = $this->coins;
            }
            
            return true;
        } else {
            $this->endmessage = 'Not enough coins.';
            return false;
        }
    }
    
    public function decreaseBet($amount)
    {
        if (!$this->deal && $this->coins > 0) {
            $this->bet -= $amount;
            
            if ($this->bet < 1 || $this->coins < 1) {
                $this->bet = $this->betamount;
            }
            
            return true;
        } else {
            $this->endmessage = 'Not enough coins.';
            return false;
        }
    }
    
    public function clearBet()
    {
        if (!$this->deal && $this->coins > 0) {
            $this->bet = 0;
            return true;
        }
        
        return false;
    }
    
    public function setBetAmount($amount)
    {
        $this->betamount = $amount;
    }
    
    public function deal()
    {
        if ($this->coins - $this->bet >= 0 && $this->coins > 0 && $this->bet > 0) {
            if ($this->usercards == null) {
                $this->gameover = false;
                $this->payout = false;
                $this->deal = true;
                $this->blind = true;
                $this->coins -= $this->bet;
                $this->usercards = [];
                $this->dealercards = [];
                $this->endmessage = "";
                
                //Draw user cards
                for ($i = 0; $i < 2; $i++) {
                    array_push(
                        $this->usercards,
                        $this->deckmanager->drawCard()
                    );
                }

                //Check for user blackjack
                if ($this->deckmanager->getHandValue($this->usercards) == 21) {
                    $this->blackjack = true;
                }
                
                //Draw dealer cards
                for ($i = 0; $i < 2; $i++) {
                    array_push(
                        $this->dealercards,
                        $this->deckmanager->drawCard()
                    );
                }
                    
                return true;
            }
        } elseif ($this->bet == 0) {
            $this->endmessage = 'Bet must be greater than 0.';
            return false;
        } else {
            $this->endmessage = 'Not enough coins.';
            return false;
        }
    }
    
    public function hit()
    {
        if ($this->usercards != null && $this->deckmanager->getHandValue($this->usercards) < 22) {
            
            //Add card to hand
            if ($this->deckmanager->getHandValue($this->usercards) != 21) {
                array_push(
                    $this->usercards,
                    $this->deckmanager->drawCard()
                );
            }
            
            //Check for user blackjack
            if ($this->deckmanager->getHandValue($this->usercards) == 21) {
                $this->blackjack = true;
            }
            
            //Check for bust
            if ($this->deckmanager->getHandValue($this->usercards) > 21) {
                $this->gameover = true;
                $this->deal = false;
                $this->endmessage = "User bust.";
                $this->result = 2;
                return false;
            }
            
            return true;
        }
        
        return false;
    }
    
    public function stand()
    {
        $userwin = false;
        $draw = false;
        
        if ($this->usercards != null) {
            //Add cards to dealer until 17 or hit if soft 17
            if ($this->deckmanager->getHandValue($this->usercards) > $this->deckmanager->getHandValue($this->dealercards) || 
                $this->deckmanager->getHandValue($this->dealercards) < 17 || 
                ($this->deckmanager->isSoft($this->dealercards) && $this->deckmanager->getHandValue($this->dealercards) == 17)) {
                do {
                    array_push(
                        $this->dealercards,
                        $this->deckmanager->drawCard()
                    );
                } while ($this->deckmanager->getHandValue($this->usercards) > $this->deckmanager->getHandValue($this->dealercards) || 
                    $this->deckmanager->getHandValue($this->dealercards) < 17 || 
                    ($this->deckmanager->isSoft($this->dealercards) && $this->deckmanager->getHandValue($this->dealercards) == 17));
            }
            
            //Find the winner message
            if ($this->deckmanager->getHandValue($this->usercards) > 21) {
                $this->endmessage = 'User bust.';
            } elseif ($this->deckmanager->getHandValue($this->dealercards) > 21) {
                $this->endmessage = 'Dealer bust.';
                $userwin = true;
            } elseif ($this->deckmanager->getHandValue($this->usercards) < $this->deckmanager->getHandValue($this->dealercards)) {
                $this->endmessage = 'User loses.';
            } elseif ($this->deckmanager->getHandValue($this->usercards) > $this->deckmanager->getHandValue($this->dealercards)) {
                $this->endmessage = 'Dealer loses.';
                $userwin = true;
            } elseif ($this->deckmanager->getHandValue($this->usercards) == $this->deckmanager->getHandValue($this->dealercards)) {
                if ($this->deckmanager->isNaturalBlackJack($this->dealercards) && !$this->deckmanager->isNaturalBlackJack($this->usercards)) {
                    //If dealer has blackjack and user does not = Dealer wins
                    $this->endmessage = 'Blackjack, user loses.';
                } elseif (!$this->deckmanager->isNaturalBlackJack($this->dealercards) && $this->deckmanager->isNaturalBlackJack($this->usercards)) {
                    //If user has blackjack and dealer does not = User wins
                    $this->endmessage = 'Blackjack, dealer loses.';
                    $userwin = true;
                } else {
                    //Else both hands are same value and draw
                    $this->endmessage = 'Push, draw';
                    $draw = true;
                }                
            }
            
            //Check if the user has Blackjack
            if ($userwin && $this->deckmanager->isNaturalBlackJack($this->usercards)) {
                $this->endmessage = 'Blackjack, dealer loses.';
            }
            
            $this->gameover = true;
            $this->blind = false;
            $this->deal = false;
        }
        
        //Return result
        if ($userwin) {
            $this->result = 0;
        } elseif($draw) {
            $this->result = 1;
        } else {
            $this->result = 2;
        }
            
        return $this->result;
    }
    
    public function getWinner()
    {
        $userwin = false;
        $draw = false;
        
        if ($this->usercards != null) {            
            //Find the winner message
            if ($this->deckmanager->getHandValue($this->usercards) > 21) {
                $this->endmessage = 'User bust.';
            } elseif ($this->deckmanager->getHandValue($this->dealercards) > 21) {
                $this->endmessage = 'Dealer bust.';
                $userwin = true;
            } elseif ($this->deckmanager->getHandValue($this->usercards) < $this->deckmanager->getHandValue($this->dealercards)) {
                $this->endmessage = 'User loses.';
            } elseif ($this->deckmanager->getHandValue($this->usercards) > $this->deckmanager->getHandValue($this->dealercards)) {
                $this->endmessage = 'Dealer loses.';
                $userwin = true;
            } elseif ($this->deckmanager->getHandValue($this->usercards) == $this->deckmanager->getHandValue($this->dealercards)) {
                if ($this->deckmanager->isNaturalBlackJack($this->dealercards) && !$this->deckmanager->isNaturalBlackJack($this->usercards)) {
                    //If dealer has blackjack and user does not = Dealer wins
                    $this->endmessage = 'Blackjack, user loses.';
                } elseif (!$this->deckmanager->isNaturalBlackJack($this->dealercards) && $this->deckmanager->isNaturalBlackJack($this->usercards)) {
                    //If user has blackjack and dealer does not = User wins
                    $this->endmessage = 'Blackjack, dealer loses.';
                    $userwin = true;
                } else {
                    //Else both hands are same value and draw
                    $this->endmessage = 'Push, draw';
                    $draw = true;
                }                
            }
            
            //Check if the user has Blackjack
            if ($userwin && $this->deckmanager->isNaturalBlackJack($this->usercards)) {
                $this->endmessage = 'Blackjack, dealer loses.';
            }
        }
        
        //Return result
        if ($userwin) {
            $this->result = 0;
        } elseif ($draw) {
            $this->result = 1;
        } else {
            $this->result = 2;
        }
            
        return $this->result;
    }
    
    public function surrender()
    {
        if ($this->usercards != null) {
            $this->gameover = true;
            $this->blind = false;
            $this->deal = false;
            $this->result = 3;
            $this->endmessage = 'User surrendered.';
        }
        
        return true;
    }
 
    public function payout()
    {
        $this->payout = true;
        $this->blackjack = false;
        $this->handcount++;    
        
        if (!$this->debug) {            
            //Add user hand
            $userresult = 0;
            $dealerresult = 0;
                    
            switch ($this->result) {
                case 0: //User win
                    $userresult = -1;
                    $dealerresult = 0;
                    break;
                case 1: //Draw
                    $userresult = -1;
                    $dealerresult = -1;
                    break;
                case 2: //Dealer Wins
                    $userresult = 0;
                    $dealerresult = -1;
                    break;
                case 3: //User surrender
                    $userresult = 1;
                    $dealerresult = -1;
                    break;
            }
        }
        
        //Switch result
        switch ($this->result) {
            case 0: //User wins
                if ($this->deckmanager->isNaturalBlackJack($this->usercards)) {
                    $payout = ($this->bet * 2) + ($this->bet / 2); 
                } else {
                    $payout = ($this->bet * 2);
                }

                //Add coins
                $this->coins += $payout;
                break;
            case 1: //Draw
                //Add coins
                $this->coins += $this->bet;
                break;
            case 2: //Dealer wins
                break;
            case 3: //Surrender                
                //Add coins
                $this->coins += ($this->bet / 2);
                break;
        }
        
        return true;
    }
    
    public function getUserTotal()
    {
        return $this->deckmanager->getHandValue($this->usercards);
    }
    
    public function getDealerHiddenTotal()
    {
        return $this->deckmanager->getHandValue([$this->dealercards[1]]);
    }
    
    public function getDealerTotal()
    {
        return $this->deckmanager->getHandValue($this->dealercards);
    }
    
    public function getHandCount()
    {
        return $this->handcount;
    }
    
    public function getUserCards()
    {
        return $this->usercards;
    }
    
    public function getDealerCards()
    {
        return $this->dealercards;
    }
    
    public function getCoins()
    {
        return $this->coins;
    }
    
    public function getBet()
    {
        return $this->bet;
    }
    
    public function getMessage()
    {
        return $this->endmessage;
    }
    
    public function isSound()
    {
        return $this->sound;
    }
}
