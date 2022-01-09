<?php

return [
    'default' => 'm_league_base',
    
    // Mリーグベースの順位点とウマの計算
    // https://m-league.jp/about/
    'm_league_base' => [
        'origin' => 25000,
        'zero_point' => 30000,
        'order_point' => [
            1 => 50000,
            2 => 10000,
            3 => -10000,
            4 => -30000
        ]
    ]
];