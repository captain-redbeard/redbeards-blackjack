<?php
//Handle payout
if ($data['gm']->gameover && !$data['gm']->payout) {
    $data['gm']->payout();
}


//Dealer cards
$d0 = ($data['gm']->deal && count($data['gm']->usercards) === 2) ? 'd0' : '';
$d1 = ($data['gm']->deal && count($data['gm']->usercards) === 2) ? 'd1' : '';
$dealerHidden = (count($data['gm']->dealercards) > 0) ? $data['gm']->getDealerHiddenTotal() : '';
$dealerTotal = (count($data['gm']->dealercards) > 0) ? $data['gm']->getDealerTotal() : '';

//User cards
$userTotal = (count($data['gm']->usercards) > 0) ? $data['gm']->getUserTotal() : '';

//Clear bet
$clearBetClass = (!$data['gm']->deal && !$data['gm']->gameover) ? 'clear-bet' : 'disabled-bet';
$clearBetLink = (!$data['gm']->deal && !$data['gm']->gameover) ? 'href=' . $data['BASE_HREF'] . '/home/index/clearbet' : '';

//Deal
$dealClass = !$data['gm']->deal ? 'green-button' : 'disabled-button';
$dealLink = !$data['gm']->deal ?
    'data-function="deal" data-action="" href="' . $data['BASE_HREF'] . '/home/index/deal"' :
    '';

//Hit
$hitClass = ($data['gm']->deal && !$data['gm']->blackjack) ? 'green-button' : 'disabled-button';
$hitLink = ($data['gm']->deal && !$data['gm']->blackjack) ?
    'data-function="hit" data-action="" href="' . $data['BASE_HREF'] . '/home/index/hit\"' :
    '';

//Stand
$standClass = $data['gm']->deal ? 'green-button' : 'disabled-button';
$standLink = $data['gm']->deal ?
    'data-function="stand" data-action="" href="' . $data['BASE_HREF'] . '/home/index/stand"' :
    '';

//Surrender
$surrenderClass = ($data['gm']->deal && !$data['gm']->blackjack) ? 'red-button' : 'disabled-button';
$surrenderLink = ($data['gm']->deal && !$data['gm']->blackjack) ?
    'data-function="surrender" data-action="" href="' . $data['BASE_HREF'] . '/home/index/surrender"' :
    '';

//Place bet
$placeBetClass = !$data['gm']->deal ? 'green-button' : 'disabled-button';
$placeBetLink = !$data['gm']->deal ?
    'data-function="placebets" data-action="" href="' . $data['BASE_HREF'] . '/home/index/placebets"' :
    '';

//One
$oneClass = (!$data['gm']->deal && !$data['gm']->gameover) ? 'white-coin' : 'disabled-coin';
$oneLink = (!$data['gm']->deal && !$data['gm']->gameover) ?
    'data-function="setbet" data-action="one" href="' . $data['BASE_HREF'] . '/home/index/setbet/one"' :
    '';

//Five
$fiveClass = (!$data['gm']->deal && !$data['gm']->gameover) ? 'red-coin' : 'disabled-coin';
$fiveLink = (!$data['gm']->deal && !$data['gm']->gameover) ?
    'data-function="setbet" data-action="five" href="' . $data['BASE_HREF'] . '/home/index/setbet/five"' :
    '';

//Twenty five
$twentyFiveClass = (!$data['gm']->deal && !$data['gm']->gameover) ? 'green-coin' : 'disabled-coin';
$twentyFiveLink = (!$data['gm']->deal && !$data['gm']->gameover) ?
    'data-function="setbet" data-action="twentyfive" href="' . $data['BASE_HREF'] . '/home/index/setbet/twentyfive"' :
    '';

//One hundred
$oneHundredClass = (!$data['gm']->deal && !$data['gm']->gameover) ? 'black-coin' : 'disabled-coin';
$oneHundredLink = (!$data['gm']->deal && !$data['gm']->gameover) ?
    'data-function="setbet" data-action="onehundred" href="' . $data['BASE_HREF'] . '/home/index/setbet/onehundred"' :
    '';

$dealSound = ($data['gm']->sound && $data['function'] === 'deal') ?
    '<embed class="hidden" src="' . $data['BASE_HREF']. '/sounds/deal.mp3" hidden="TRUE" autostart="TRUE"></embed>' :
    '';
$betSound = ($data['gm']->sound && $data['function'] === 'setbet') ?
    '<embed class="hidden" src="' . $data['BASE_HREF'] . '/sounds/chips.mp3" hidden="TRUE" autostart="TRUE"></embed>' :
    '';
?>
            <section>
                <div class="card-table">
                    <div class="card-holder">
                        <?php if (count($data['gm']->dealercards) > 0 && $data['gm']->blind) { ?>
                        
                        <div class="card <?=$d0;?> ch"></div>
                        <div class="card <?=$d1;?> c<?=strtolower($data['gm']->dealercards[1]);?> not-first"></div>
                        <div class="card-value"><?=$dealerHidden;?></div>
                        <?php } else { for ($i = 0; $i < count($data['gm']->dealercards); $i++) { ?>
                            
                        <div class="card <?php if ($data['gm']->deal && count($data['gm']->usercards) === 2) echo 'd' . $i; echo 'c' . strtolower($data['gm']->dealercards[$i]); if ($i > 0) echo ' not-first'; ?>"></div>
                        <?php } ?>
                        
                        <div class="card-value"><?=$dealerTotal;?></div>
                        <?php } ?>
                        
                    </div>
                    
                    <div class="table-rules">
                        <h3>Blackjack pays 2 to 1</h3>
                        <h6>Dealer must draw until 16 and hit on soft 17</h6>
                    </div>

                    <div class="card-holder">
                        <?php for ($i = 0; $i < count($data['gm']->usercards); $i++) { ?>
                        
                        <div class="card <?php if ($data['gm']->deal && count($data['gm']->usercards) === 2) echo 'c' . $i; ?> <?php echo 'c' . strtolower($data['gm']->usercards[$i]); if ($i > 0) echo ' not-first'; ?>"></div>
                        <?php } ?>
                        
                        <div class="cb"></div>
                        <div class="card-value"><?=$userTotal;?></div>
                    </div>
                    
                    <div class="cb"></div>

                    <div class="hand-result">
                        <h4><?=$data['gm']->endmessage;?></h4>
                    </div>
                
                    <div class="table-action-bar">
                        <div class="action-divider tl">
                            <span class="icon button-bet"></span>
                            <div class="money-font" title="Your Bet"><?=$data['gm']->bet;?></div>
                            <a name="game-action" data-function="clearbet" data-action="" class="icon <?=$clearBetClass;?>" <?=$clearBetLink;?> title="Clear bet"></a>
                            
                            <div class="cb"></div>
                            
                            <span class="icon button-money"></span>
                            <div class="money-font" title="Your Coins"><?=$data['gm']->coins;?></div>
                        </div>
                        
                        <div class="action-divider action-spacer tc">
                            <?php if (!$data['gm']->gameover) { ?>
                            
                            <a name="game-action" class="button icon button-deal raw-button <?=$dealClass;?>" <?=$dealLink;?> title="Deal"></a>
                            <a name="game-action" class="button icon button-hit raw-button <?=$hitClass;?>" <?=$hitLink;?> title="Hit"></a>
                            <a name="game-action" class="button icon button-stand raw-button <?=$standClass;?>" <?=$standLink;?> title="Stand"></a>
                            <a name="game-action" class="button icon button-surrender raw-button <?=$surrenderClass;?>" <?=$surrenderLink;?> title="Surrender"></a>
                            <?php } else { ?>
                                
                            <a name="game-action" class="button icon button-bets raw-button <?=$placeBetClass;?>" <?=$placeBetLink;?> title="Place bets"></a> 
                            <?php } ?>
                            
                        </div>
                        
                        <div class="action-divider tr">
                            <a name="game-action" class="raw-coin coin-font <?=$oneClass;?>" <?=$oneLink;?>>1</a>
                            <a name="game-action" class="raw-coin coin-font <?=$fiveClass;?>" <?=$fiveLink;?>>5</a>
                            <a name="game-action" class="raw-coin coin-font <?=$twentyFiveClass;?>" <?=$twentyFiveLink;?>>25</a>
                            <a name="game-action" class="raw-coin coin-font <?=$oneHundredClass;?>" <?=$oneHundredLink;?>>100</a>
                        </div>
                        
                        <div class="cb"></div>
                    </div>
                
                </div>
                
                <?=$dealSound;?>
                <?=$betSound;?>
                
            </section>
                
            <div class="container">
                <hr>
                
                <div class="fl button-font">
                    <a class="raw-button red-button" data-function="reset" data-action="" href="<?=$data['BASE_HREF'];?>/home/index/sound"><?php echo $data['gm']->sound ? 'Disable Sound' : 'Enable Sound'; ?></a>
                </div>
                
                <div class="fr button-font">
                    <a class="raw-button red-button" data-function="reset" data-action="" href="<?=$data['BASE_HREF'];?>/home/index/reset">Reset</a>
                    <a class="raw-button blue-button" data-function="show" data-action="" href="<?=$data['BASE_HREF'];?>/home/index/show">Show Dealers</a>
                </div>
                
                <div class="cb"></div>
                <hr>
                
                <div class="w49 fl">
                    <h6>Rules</h6>
                    <ul>
                        <li>Dealer must hit on Soft 17</li>
                        <li>Blackjack pays 1.5</li>
                        <li>Blackjack beats 21</li>
                        <li>No splits</li>
                        <li>No doubles</li>
                        <li>Push gives money back</li>
                        <li>Surrender gives 0.5 back</li>
                        <li>Decks shuffled at less than 52 cards</li>
                    </ul>
                </div>
                
                <div class="w49 fr tr">
                    <h6>Debug</h6>
                    Deck Count:  <?=$data['gm']->deckmanager->getDeckCount();?> <br/>
                    Card Count:  <?=$data['gm']->deckmanager->getCardCount();?> <br/>
                    Cards Left:  <?=$data['gm']->deckmanager->getCardsLeft();?> <br/>
                    Hand Count:  <?=$data['gm']->handcount;?>
                    
                </div>
                
                <div class="cb"></div>
                
                <h6>Used cards debug</h6>
                <pre>
                <?php
                    echo '\n';
                    foreach ($data['gm']->deckmanager->decks as $deck) {
                        print_r($deck->used);
                    }
                ?>
                
                </pre>
            </div>
