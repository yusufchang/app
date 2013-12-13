<h2><?=$campaign_data['name']?></h2>
<h3>Round <?=$round['ordinal']?> &emdash; <?=$round['name']?></h3>
<?php $counter = 0; ?>
<?php foreach ( $campaign_data['matchups_by_round'][$campaign_data['active_round']] as $matchup_id => $matchup ): ?>
    <?php if ($counter % 2 == 0): ?>
    <div class="row matchups">
    <?php endif; ?>
        <div class="matchup small-5 columns <?=$counter % 2 == 1 ? 'offset-1' : '' ?>">
            <div class="row">
                <div class="matchup-header">
                    <h4><?=$matchup['name']?></h4>
                    <p><?=$matchup['blurb']?></p>
                </div>
                <?php foreach ( $matchup['opponents'] as $opponent_id => $opponent ):?>
                <?php $opponent_data = $opponents[$opponent_id] ?>
                <div class="opponent small-6 columns">
                    <h4><a href="<?=$opponent_data['url']?>"><?=$opponent_data['name']?></a></h4>
                    <div class="thumb-wrapper">
                        <a href="<?=$opponent_data['url']?>"><img class="opponent-thumb" src="<?=$opponent_data['thumbnail']?>" alt=""/></a>
                    </div>
                    <div class="blurb-wrapper">
                        <p class="opponent-blurb"><?=$opponent_data['blurb']?></p>
                    </div>
                    <div class="voting row">
                        <div class="columns small-6 voting-left">
                            <button class="voting-button" data-matchup-id="<?=$matchup_id?>" data-opponent-id="<?=$opponent_id?>">VOTE!</button>
                        </div>
                        <div class="columns small-6 voting-right">
                            <strong class="voting-votes"><span class="voting-votes-vote_num"><?=$opponent['votes']?></span> Votes</strong>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php if (++$counter % 2 == 0): ?>
    </div>
    <?php endif; ?> 
<?php endforeach; ?>