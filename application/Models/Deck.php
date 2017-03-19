<?php
/**
 * @author captain-redbeard
 * @since 20/02/16
 */
namespace Blackjack\Models;

class Deck
{
    public $cards = [];
    public $used = [];
    
    public function __construct()
    {
        $cstring = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
        $tstring = ['D', 'H', 'S', 'C'];
        $this->used = [];
        
        for ($a = 0; $a < count($tstring); $a++) {
            for ($b = 0; $b < count($cstring); $b++) {
                array_push(
                    $this->cards,
                    $cstring[$b] . $tstring[$a]
                );
            }
        }
    }
    
    public function getCards()
    {
        return $this->cards;
    }
    
    public function getUsedCards()
    {
        return $this->used;
    }
    
    public function hasCard($card)
    {
        foreach ($this->used as $usedCard) {
            if ($usedCard === $card) {
                return true;
            }
        }
        
        return false;
    }
}
