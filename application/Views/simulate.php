            <section>
                <strong>User Rules:</strong>
                <ul>
                    <li>Hit less than 12</li>
                    <li>Hit soft 17</li>
                </ul>
                
                <strong>Dealer Rules:</strong>
                <ul>
                    <li>Hit less than 17</li>
                    <li>Hit soft 17</li>
                    <li>Hit if total less than user</li>
                </ul>
                
                <strong>Statistics</strong>
                <ul>
                    <li>User wins: <?=$data['user_win_count'];?></li>
                    <li>Dealer wins: <?=$data['dealer_win_count'];?></li>
                    <li>Draws: <?=$data['draw_count'];?></li>
                    <li>Games: <?=$data['game_count'];?></li>
                </ul>
                
                <strong>Percentages</strong>
                <ul>
                    <li>User win percent: <?=(($data['user_win_count'] / $data['game_count']) * 100.0);?>%</li>
                    <li>Draw percent: <?=(($data['draw_count'] / $data['game_count']) * 100.0);?>%</li>
                    <li>Dealer win percent: <?=(($data['dealer_win_count'] / $data['game_count']) * 100.0);?>%</li>
                </ul>
            </section>
