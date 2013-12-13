$(document).ready(function(){
	jQuery('.voting-button').on('click', function(){
		var self = $(this);
		var vote_num = self.closest('.voting').find('.voting-votes').find('.voting-votes-vote_num');
		var curr_val = parseInt(vote_num.text());
		vote_num.text(curr_val+1);
		self.closest('.matchup').find('.opponent .voting .voting-button').attr('disabled', 'disabled');
		jQuery.post('http://wikiabrackets.herokuapp.com/api/vote/', {'matchup': self.data('matchup-id'), 'opponent': self.data('opponent-id')});
	});
});