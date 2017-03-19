<?php
/**
 * @author captain-redbeard
 * @since 20/02/16
 */
namespace Blackjack\Models;

use Blackjack\Models\Deck;

class DeckManager
{
    private $cardCount;
    private $typeCount;
    private $cstring = [];
    private $tstring = [];
    public $decks = [];
    
    public function __construct()
    {
        $this->cardCount = 12;
        $this->typeCount = 3;
        $this->cstring = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
        $this->tstring = ['D', 'H', 'S', 'C'];
    }
    
    public function init($deck_count)
    {
        for ($i = 0; $i < $deck_count; $i++) {
            array_push(
                $this->decks,
                new Deck()
            );
        }
    }
    
    public function getDeckCount()
    {
        return count($this->decks);
    }
    
    public function getCardCount()
    {
        $count = 0;
        
        foreach ($this->decks as $deck) {
            $count += count($deck->cards);
        }
        
        return $count;
    }
    
    public function getCardsLeft()
    {
        $count = 0;
        
        foreach ($this->decks as $deck) {
            $count += count($deck->used);
        }
        
        return ($this->getCardCount() - $count);
    }
    
    public function drawCard()
    {
        if ($this->getCardsLeft() > 0) {
            $deck = null;
            
            do {
                $cardNumber = random_int(0, $this->cardCount);
                $cardType = random_int(0, $this->typeCount);
                $card = $this->cstring[$cardNumber] . $this->tstring[$cardType];
                
                //Find the deck where the card is available
                $deck = $this->isCardAvailable($card);
                
                //Add the card to the used array
                if ($deck != null) {
                    array_push(
                        $deck->used,
                        $card
                    );
                }
            } while ($deck == null);
            
            return $card;
        } else {
            return -1;
        }
    }
    
    public function isSoft($cards)
    {
        $soft = true;
        
        foreach ($cards as $card) {
            if (!is_numeric(substr($card, 0, -1)) && substr($card, 0, -1) != "A") {
                $soft = false;
            }
        }
        
        return $soft;
    }
    
    public function isNaturalBlackJack($cards)
    {
        if (count($cards) == 2 && $this->getHandValue($cards) == 21) {
            return true;
        }
        
        return false;
    }
    
    public function getHandValue($cards)
    {
        $value = $this->getRawHandValue($cards);
        $aceCount = $this->getAceCount($cards);
        
        if ($value > 21) {
            if ($aceCount == 1) {
                $value -= 10;
            } elseif ($aceCount > 1) {
                $timesby = 1;
                $newvalue = 0;
                
                while (($value - $newvalue) > 21 && $timesby <= $aceCount && $timesby < 22) {
                    $newvalue = ($timesby * 10);
                    $timesby++;
                }
                
                $value -= $newvalue;
            }
        }
        
        return $value;
    }
    
    private function getRawHandValue($cards)
    {
        $value = 0;
        $aceCount = $this->getAceCount($cards);
        
        foreach ($cards as $card) {
            $value += $this->getCardValue($card, $aceCount);
        }
        
        return $value;
    }
    
    private function getCardValue($card, $aceCount)
    {
        $value = 0;
        $card = substr($card, 0, -1);
        
        //Switch through cards
        switch ($card) {
            case "J":
                $value = 10;
                break;
            case "Q":
                $value = 10;
                break;
            case "K":
                $value = 10;
                break;
            case "A":
                $value = 11;
                break;
            default:
                $value = $card;
                break;
        }
        
        return $value;
    }
    
    private function getAceCount($cards)
    {
        $count = 0;
        
        if (count($cards) > 0) {
            foreach ($cards as $card) {
                if (substr($card, 0, -1) == "A") {
                    $count++;
                }
            }
        }
        
        return $count;
    }
    
    private function isCardAvailable($card)
    {
        $random = mt_rand(0, (count($this->decks) -1));
        
        //Check a random deck
        if (!$this->decks[$random]->hasCard($card)) {
            return $this->decks[$random];
        }
        
        //Else check all decks
        foreach ($this->decks as $deck) {
            if (!$deck->hasCard($card)) {
                return $deck;
            }
        }
                
        return null;
    }
}
