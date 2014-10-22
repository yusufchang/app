<?php

class EntertainmentHubOnlyRssModelTest extends WikiaBaseTest {

	public function setUp() {
		$dir = dirname( __FILE__ ) . '/../';
		$this->setupFile = $dir . 'HubRssFeed.setup.php';

		parent::setUp();
	}

	/**
	 * @dataProvider deduplicationDataProvider
	 */
	public function testDeduplication($lastDuplicatesFromDb, $dataFromHubs) {
		$mockModel = $this->getMockBuilder('EntertainmentHubOnlyRssModel')
			->setMethods( [
				'getLastDuplicatesFromDb',
				'getLastFeedTimestamp',
				'getDataFromHubs',
				'getArticleDetail',
				'addFeedsToDb',
				'getArticleDescription'
			] )
			->getMock();

		$mockModel->expects( $this->any() )
			->method( 'getLastDuplicatesFromDb' )
			->will( $this->returnValue( $lastDuplicatesFromDb ) );

		$mockModel ->expects( $this->any() )
			->method( 'getLastFeedTimestamp' )
			->will( $this->returnValue( 150000000 ) );

		$mockModel ->expects( $this->any() )
			->method( 'getArticleDetail' )
			->will( $this->returnValue( ['img' => '', 'title' => ''] ) );

		/**
		 * This kinda sucks. We need to override the default behavior of this
		 * method to confirm if it received good argument...
		 */
		$mockModel ->expects( $this->any() )
			->method( 'addFeedsToDb' )
			->will( $this->returnArgument(0) );

		$mockModel ->expects( $this->any() )
			->method( 'getArticleDescription' )
			->will( $this->returnValue( '' ) );


		$mockModel ->expects( $this->any() )
			->method( 'getDataFromHubs' )
			->will( $this->returnValue($dataFromHubs) );

		$feedData = $mockModel->generateFeedData();

		$this->assertGreaterThanOrEqual(count($feedData),count($dataFromHubs),'We should generate no more feeds than source gave us');
		$feedCounts = [];
		$hubCounts = [];
		$timestampCounts = [];

		foreach($feedData as $item) {
			empty($feedCounts[$item['url']])?$feedCounts[$item['url']]=1:$feedCounts[$item['url']]++;
			empty($timestampCounts[$item['timestamp']])?$timestampCounts[$item['timestamp']]=1:$timestampCounts[$item['timestamp']]++;
		}
		foreach($dataFromHubs as $item) {
			empty($hubCounts[$item['url']])?$hubCounts[$item['url']]=1:$hubCounts[$item['url']]++;
		}

		foreach($feedCounts as $count) {
			$this->assertEquals($count, 1, 'Each url should occur no more than once in a feed');
		}
		foreach($timestampCounts as $count) {
			$this->assertEquals($count, 1, 'Each timestamp should occur no more than once in a feed');
		}

//		var_dump($feedData);

	}


	public function deduplicationDataProvider() {
		$timestamp = time();
		return [
			[
				'duplicates' => [
					'http://americantop40.wikia.com/wiki/October_13,_1984' => true,
					'http://marvel.wikia.com/User_blog:Gcheung28/Fan_Brain:_Marvel%27s_Agents_of_S.H.I.E.L.D._-_%22Face_My_Enemy%22' => true,
					'http://music.wikia.com/wiki/List_of_songs_whose_lyrics_do_not_mention_the_song_title' => true,
					'http://musichub.wikia.com/wiki/User_blog:Gcheung28/The_Ultimate_Mockingjay_Part_1_Soundtrack' => true,
					'http://southpark.wikia.com/wiki/Push_(Feeling_Good_on_a_Wednesday)' => true,
					'http://5sos.wikia.com/wiki/Derp_Con' => true,
					'http://onepiece.wikia.com/wiki/One_Piece_Music' => true,
					'http://walkingdead.wikia.com/wiki/Music_Portal' => true,
					'http://thehungergames.wikia.com/wiki/User_blog:Gcheung28/Second_Single_from_Mockingjay_Part_1_Released' => true,
					'http://mlp.wikia.com/wiki/User_blog:Gcheung28/ENTER_NOW:_My_Little_Pony_Equestria_Girls:_Rainbow_Rocks_Giveaway' => true
				],
				'entries' => [
					[
						'title' => 'RUMOR: Obi-Wan Kenobi Spinoff in the Works',
						'description' => 'Um, yes please? Rumor has it—at least according to MakingStarWars.Net—that an Obi-Wan Kenobi spinoff film is in the works. According to MSW\'s source, "the spin-off movies...were initially going to stay away from any Jedi or Sith characters. But I\'m hearing now that because of the popularity of Obi-Wan...that an art team is now working with a writer on concepts for an Obi-Wan movie.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140917230522/movieshub/images/1/1a/StarWarsKenobiCrop.png',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://starwarsfans.wikia.com/wiki/User_blog:Brandon_Rhea/RUMOR:_Obi-Wan_Kenobi_Spinoff_in_the_Works',
						'wikia_id' => '8476',
						'page_id' => '38931',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Exclusive Transformers: Age of Extinction Behind the Scenes Clip',
						'description' => 'Craving more Transformers?? No worries, we have an exclusive clip from Age of Extinction AND news about the upcoming DVD release just for you! ',
						'module' => 'community',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140916184358/movieshub/images/7/71/Age_of_Extinction-BLURAY.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://transformers.wikia.com/wiki/User_blog:Gcheung28/Exclusive_Transformers:_Age_of_Extinction_Behind_the_Scenes_Clip',
						'wikia_id' => '411',
						'page_id' => '57509',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Lorde\'s Mockingjay Part 1 Single Revealed',
						'description' => 'All hail Lorde! The curator of The Hunger Games: Mockingjay - Part 1\'s soundtrack has just released her single for the movie, and we are in awe! Check it out now!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20141002004734/movieshub/images/e/eb/Movieshub-Lorde-Mockingjay_pt2_single.jpg',
							'width' => '300',
							'height' => '258',
						],
						'timestamp' => $timestamp,
						'url' => 'http://thehungergames.wikia.com/wiki/User_blog:Gcheung28/Lorde%27s_Mockingjay_Part_1_Single_Revealed',
						'wikia_id' => '35171',
						'page_id' => '504244',
						'source' => 'hub_952442',
					],
					[
						'title' => 'News',
						'description' => 'News',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/Category:2014_NYCC_News',
						'wikia_id' => '778168',
						'page_id' => '3061',
						'source' => 'hub_952442',
					],
					[
						'title' => 'S.H.I.E.L.D. Trivia',
						'description' => 'Which comic nods did you catch this week?',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140924220924/movieshub/images/b/b7/Shield_S2_crosspromoposter.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Gcheung28/Fan_Brain:_Marvel%27s_Agents_of_S.H.I.E.L.D._-_%22Face_My_Enemy%22',
						'wikia_id' => '2233',
						'page_id' => '763380',
						'source' => 'hub_952442',
					],
					[
						'title' => 'MLP Giveaway',
						'description' => 'Enter to win an Equestria Girls doll, DVD, and more!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20141021235714/movieshub/images/6/6f/Rainbow_Rocks_Poster_2.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://mlp.wikia.com/wiki/User_blog:Gcheung28/ENTER_NOW:_My_Little_Pony_Equestria_Girls:_Rainbow_Rocks_Giveaway',
						'wikia_id' => '194308',
						'page_id' => '607523',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Fantasy Face-Off',
						'description' => 'It\'s a showdown in Mordor as two fantasy giants face-off in an epic battle! We\'ve assembled a line-up of some of the most famous fantasy characters and creatures that will be facing off against various characters from Middle-earth: Shadow of Mordor. Will the mighty Diablo beat the Dark Lord Sauron himself, or will Sauron come out victoriously? You get to decide who would be victorious in a one-on-one battle!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140910145855/movieshub/images/f/f1/FaceOff_SoM_Hubslider_330x210.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://shadowofmordor.wikia.com/wiki/User_blog:MarkvA/Fantasy_Face-Off',
						'wikia_id' => '863039',
						'page_id' => '16728',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Images',
						'description' => 'Images',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/New_York_Comic_Con_2014_WikiaLive_Photos',
						'wikia_id' => '778168',
						'page_id' => '3014',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Video',
						'description' => 'Video',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/New_York_Comic_Con_2014_WikiaLive_Videos',
						'wikia_id' => '778168',
						'page_id' => '3029',
						'source' => 'hub_952442',
					],
					[
						'title' => 'NYCC 2014',
						'description' => 'Watch for updates from the show.',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140915180325/movieshub/images/6/69/330px-Nycc.png',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/Portal:New_York_Comic_Con_2014',
						'wikia_id' => '778168',
						'page_id' => '3009',
						'source' => 'hub_952442',
					],
					[
						'title' => 'DC Tournament',
						'description' => 'Who will win? Vote for your favorite characters now!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20140924183004/movieshub/images/0/07/NBC_Constantine_Hubslider_330x210_R1.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://dc.wikia.com/wiki/User_blog:Gcheung28/DC_Heroes_vs._Villains_Bracket_Tournament',
						'wikia_id' => '2237',
						'page_id' => '408511',
						'source' => 'hub_952442',
					],
					[
						'title' => 'WIKIA FANNOTATION: X-Men: Days of Future Past',
						'description' => 'The new trailer for X-Men: Days of Future Past is here! How sharp is your X-Men vision? With your help, we\'ve highlighted many of the references and cool-spottings in the preview. Check out our latest Wikia Fannotation below, and leave a comment with anything else you saw in the new preview.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140430053938/comicshub/images/a/a4/Fan_Xmen_Hubslider_330x210.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Brian_Linder/X-Men:_Days_of_Future_Past_--_What_Did_You_Spot%3F',
						'wikia_id' => '2233',
						'page_id' => '711683',
						'source' => 'hub_952445',
					],
					[
						'title' => 'SDCC 2014 Marvel Contest of Champions Trailer',
						'description' => 'Exciting news, Marvel fans!! At San Diego Comic-Con’s Marvel Video Games panel, Kabam, the leader in the western world for free-to-play games for traditional players, announced Marvel Contest of Champions, an immersive, high-action, high-fidelity super hero combat game for mobile devices! In development at Kabam’s Vancouver studio, players fight their way through iconic locations from the Marvel Universe and collect their favorite Marvel super heroes and villains such as Iron Man, Captain America, Spider-Man and Thor to build their ultimate team of champions.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20140722235051/comicshub/images/a/a7/W-SDCC_Hubslider_Generic.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Gcheung28/SDCC_2014_Marvel_Contest_of_Champions_Trailer',
						'wikia_id' => '2233',
						'page_id' => '748673',
						'source' => 'hub_952445',
					],
					[
						'title' => 'San Diego Comic-Con 2014 Marvel Studios Panel Fan Reaction',
						'description' => 'Greetings, true believers! Didn\'t make it to Comic-Con this year? Worry not! We\'ve got an insider\'s p.o.v. for you from a fellow fan who attended the Marvel Studios Panel in your stead! Age of Ultron? Ant-Man? Guardians of the Galaxy 2? It\'s all there, and much more!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140813125429/comicshub/images/c/c9/Comicshub-marvel-SDCC-panel-FanReactions.jpg',
							'width' => '310',
							'height' => '200',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:XD1/San_Diego_Comic-Con_2014_Marvel_Studios_Panel_Fan_Reaction',
						'wikia_id' => '2233',
						'page_id' => '749539',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Comic Book Showcase: Gender Benders and Racelifts',
						'description' => 'This is bound to be a controversial episode, but an interesting and important topic: The changing of race and gender of established characters. This has been in the news recently with the \'changing\' of Thor to a woman. (In actuality, male Thor still exists, a woman has simply taken up his mantle.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140813124439/comicshub/images/d/dc/Comicshub-marvel-genderbender.jpg',
							'width' => '310',
							'height' => '200',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Jamie/Episode_16_-_Gender_Benders_and_Racelifts',
						'wikia_id' => '2233',
						'page_id' => '751051',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Transformers Battle',
						'description' => 'Who\'s your favorite \'bot? Vote now.',
						'module' => 'slider',
						'img' => [
								'url' => 'http://img2.wikia.nocookie.net/__cb20140916184757/comicshub/images/7/71/Age_of_Extinction-BLURAY.jpg',
								'width' => '330',
								'height' => '210',
							],
						'timestamp' => $timestamp,
						'url' => 'http://transformers.wikia.com/wiki/User_blog:Brian_Linder/Ultimate_Transformers_Showdown',
						'wikia_id' => '411',
						'page_id' => '57539',
						'source' => 'hub_952445',
					],
					[
						'title' => 'News',
						'description' => 'News',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/Category:2014_NYCC_News',
						'wikia_id' => '778168',
						'page_id' => '3061',
						'source' => 'hub_952445',
					],
					[
						'title' => 'S.H.I.E.L.D. Trivia',
						'description' => 'Which comic nods did you catch in Episode 4?',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20141002081030/comicshub/images/4/47/W-FANB-AoS_Hubslider_330x210.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Gcheung28/Fan_Brain:_Marvel%27s_Agents_of_S.H.I.E.L.D._-_%22Face_My_Enemy%22',
						'wikia_id' => '2233',
						'page_id' => '763380',
						'source' => 'hub_952445',
					],
					[
						'title' => 'MLP Giveaway',
						'description' => 'Enter to win an Equestria Girls doll, DVD, and more!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20141022000006/comicshub/images/6/6f/Rainbow_Rocks_Poster_2.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://mlp.wikia.com/wiki/User_blog:Gcheung28/ENTER_NOW:_My_Little_Pony_Equestria_Girls:_Rainbow_Rocks_Giveaway',
						'wikia_id' => '194308',
						'page_id' => '607523',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Sideshow Collectibles wants to give YOU an Iron Man',
						'description' => 'At San Diego Comic Con this year, I was given a special tour of Sideshow Collectibles\' amazing booth (see the slideshow at the end of this post],. At the end, they said they would like to give an Iron Man Mark 17: Heartbreaker Sixth Scale Figure by Hot Toys to our fans and users!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20140813130513/comicshub/images/0/07/Comicshub-Sideshow_Giveaway_Iron_Man.jpg',
							'width' => '740',
							'height' => '448',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Peteparker/Sideshow_Collectibles_wants_to_give_YOU_an_Iron_Man',
						'wikia_id' => '2233',
						'page_id' => '749903',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Peter Parker Quotes',
						'description' => '"You\'re the creep who\'s going to pay! I\'m going to get you, Goblin! I\'m going to destroy you slowly, and when you start begging for me to end it - I\'m going to remind you of one thing - You KILLED the woman I love! And for that you\'re going to DIE!"',
						'module' => 'community',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20140430061536/comicshub/images/0/08/Peterparker_spot.jpg',
							'width' => '328',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/Category:Peter_Parker_(Earth-616],/Quotes',
						'wikia_id' => '2233',
						'page_id' => '160012',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Images',
						'description' => 'Images',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/New_York_Comic_Con_2014_WikiaLive_Photos',
						'wikia_id' => '778168',
						'page_id' => '3014',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Video',
						'description' => 'Video',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/New_York_Comic_Con_2014_WikiaLive_Videos',
						'wikia_id' => '778168',
						'page_id' => '3029',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Wikia Live',
						'description' => 'New York Comic Con 2014 is happening October 9-12 at the Javitz Center in NYC. Can\'t make it to the show? Don\'t worry -- we\'ve got you! Check out all the <a href="https://twitter.com/search?q=%23WikiaLive">#WikiaLive\</a> coverage from the show on social media, and watch the <a href="http://reedpop.wikia.com/wiki/Portal:New_York_Comic_Con_2014">New York Comic Con portal</a> for the latest pics, video, and news from the show.',
						'module' => 'wikiaspicks',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20141008011415/comicshub/images/9/9c/W-NYCC_Wikia_Picks_500x142.jpg',
							'width' => '500',
							'height' => '142',
						],
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/Portal:New_York_Comic_Con_2014',
						'wikia_id' => '778168',
						'page_id' => '3009',
						'source' => 'hub_952445',
					],
					[
						'title' => 'SPOTLIGHT: Guardians of the Galaxy',
						'description' => 'The Guardians of the Galaxy is a group of heroes who opposed the Phalanx conquest of the Kree system (and many who had opposed Annihilus\' incursion into their universe],, and banded together in an attempt to prevent any further catastrophes from ever occurring.',
						'module' => 'community',
						'img' => [
								'url' => 'http://img3.wikia.nocookie.net/__cb20140430054438/comicshub/images/6/69/Rocket_spot.jpg',
								'width' => '330',
								'height' => '210',
							],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/Guardians_of_the_Galaxy_(Earth-616],',
						'wikia_id' => '2233',
						'page_id' => '102101',
						'source' => 'hub_952445',
					],
					[
						'title' => 'DC Tournament',
						'description' => 'Who will win? Vote for your favorite characters now!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140924190157/comicshub/images/0/07/NBC_Constantine_Hubslider_330x210_R1.jpg',
							'width' => '330',
							'height' => '210',
						],

						'timestamp' => $timestamp,
						'url' => 'http://dc.wikia.com/wiki/User_blog:Gcheung28/DC_Heroes_vs._Villains_Bracket_Tournament',
						'wikia_id' => '2237',
						'page_id' => '408511',
						'source' => 'hub_952445',
					],
					[
						'title' => 'The Walking Dead Comics',
						'description' => 'The Walking Dead is a monthly (biweekly since October 2013], black-and-white American comic that started in 2003, and was created and written by Robert Kirkman with artist Tony Moore. The current artists for the series are Charlie Adlard, Cliff Rathburn and Stefano Gaudiano. The comic is published by Image Comics and Skybound Entertainment.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20140430054734/comicshub/images/7/70/Twd_comic_spot.jpg',
							'width' => '330',
							'height' => '210',
						],

						'timestamp' => $timestamp,
						'url' => 'http://walkingdead.wikia.com/wiki/The_Walking_Dead_(Comic_Series],',
						'wikia_id' => '13346',
						'page_id' => '2398',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Hunger Games Single',
						'description' => '"Yellow Flicker Beat" and Mockingjay soundtrack',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20140929200512/musichub/images/e/ec/Lorde.jpg',
							'width' => '4928',
							'height' => '3280',
						],
						'timestamp' => $timestamp,
						'url' => 'http://thehungergames.wikia.com/wiki/User_blog:Gcheung28/Lorde%27s_Mockingjay_Part_1_Single_Revealed',
						'wikia_id' => '35171',
						'page_id' => '504244',
						'source' => 'hub_952443',
					],
					[
						'title' => 'Fan Playlist',
						'description' => 'The fans made a Mockingjay Part 1 OST. Check it out!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20141003223422/musichub/images/7/72/HG_soundtrack_Hubslider_330x210.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://musichub.wikia.com/wiki/User_blog:Gcheung28/The_Ultimate_Mockingjay_Part_1_Soundtrack',
						'wikia_id' => '952443',
						'page_id' => '2361',
						'source' => 'hub_952443',
					],
					[
						'title' => 'Lorde Immortalized on South Park',
						'description' => '"Push (Feeling Good on a Wednesday]," is a song from the Season Eighteen episode, "The Cissy". It is a parody song of the popular vocalist known as Lorde, sung by Randy Marsh as Lorde.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20141016225214/musichub/images/5/55/Randy_Lorde.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://southpark.wikia.com/wiki/Push_(Feeling_Good_on_a_Wednesday],',
						'wikia_id' => '835',
						'page_id' => '65762',
						'source' => 'hub_952443',
					],
					[
						'title' => 'Second Single from Mockingjay Part 1 Released',
						'description' => 'You heard Lorde\'s single "Yellow Flicker Beat", now check out the second single to be released from the The Hunger Games: Mockingjay - Part 1 soundtrack! Titled "This Is Not a Game," the 2nd single was helmed by the Chemical Brothers. The vocals you hear are from Miguel, and Lorde is on the track a bit too!',
							'module' => 'community',
							'img' => [
								'url' => 'http://img3.wikia.nocookie.net/__cb20141020223850/musichub/images/b/b4/Mj_poster_tagline.jpg',
								'width' => '330',
								'height' => '210',
							],
							'timestamp' => $timestamp,
							'url' => 'http://thehungergames.wikia.com/wiki/User_blog:Gcheung28/Second_Single_from_Mockingjay_Part_1_Released',
							'wikia_id' => '35171',
							'page_id' => '506812',
							'source' => 'hub_952443',
						],

					[
						'title' => 'Derp Con',
						'description' => 'Check out Derp Con from 5S0S now! Are you going?',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20141016165854/musichub/images/8/8a/Derpcon-5sos.png',
							'width' => '330',
							'height' => '210',
						],

						'timestamp' => $timestamp,
						'url' => 'http://5sos.wikia.com/wiki/Derp_Con',
						'wikia_id' => '718604',
						'page_id' => '5943',
						'source' => 'hub_952443',
					],
					[
						'title' => 'American Top 40',
						'description' => 'What was on the charts 30 years ago this week?',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140919182022/musichub/images/f/fb/AmericanTop40.png',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://americantop40.wikia.com/wiki/October_13,_1984',
						'wikia_id' => '229168',
						'page_id' => '2918',
						'source' => 'hub_952443',
					],
					[
						'title' => 'One Piece Music',
						'description' => 'As has become typical of long-running youth-oriented anime, One Piece has gone through a long succession of theme songs, performed by popular artists, since its debut on television. Though performed by popular singers and bands, most seem to be written specifically for the show, as nearly all of them reference treasure, the sea, or ships in some way. As of January 19, 2014, there have been 17 opening themes, 18 regular ending themes, and a number of film- and special endings as well. ',
						'module' => 'community',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20141017220044/musichub/images/8/87/One_Piece_Anime_Logo.png',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://onepiece.wikia.com/wiki/One_Piece_Music',
						'wikia_id' => '1081',
						'page_id' => '1615',
						'source' => 'hub_952443',
					],
					[
						'title' => 'Songs Whose Lyrics Do Not Mention the Song Title',
						'description' => 'This is a list of songs whose title is not referenced in the song lyrics. This should be limited only to songs which have lyrics. This list should not include songs where the title is implied in the song. For example "Country House" by Blur does not contain the exact phrase "Country House" but it does contain the lyrics "A very big house in the country". This song should not be included on the list. ',
						'module' => 'community',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20140430022332/musichub/images/1/1c/Beatleshero.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://music.wikia.com/wiki/List_of_songs_whose_lyrics_do_not_mention_the_song_title',
						'wikia_id' => '84',
						'page_id' => '7409',
						'source' => 'hub_952443',
					],
					[
						'title' => 'The Walking Dead Music Portal',
						'description' => 'This page contains music featured in The Walking Dead media. Specifically, AMC\'s The Walking Dead TV Series and Telltale Games: The Walking Dead. Warning: the music tracks below may contain major spoilers. Caution is advised!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20141020230927/musichub/images/f/f7/GlennMusician.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://walkingdead.wikia.com/wiki/Music_Portal',
						'wikia_id' => '13346',
						'page_id' => '50012',
						'source' => 'hub_952443',
					]
				]
			],
			[
				'duplicates' => [
					'http://walkingdead.wikia.com/wiki/Music_Portal' => true,
					'http://mlp.wikia.com/wiki/User_blog:Gcheung28/ENTER_NOW:_My_Little_Pony_Equestria_Girls:_Rainbow_Rocks_Giveaway' => true,
					'http://musichub.wikia.com/wiki/User_blog:Gcheung28/The_Ultimate_Mockingjay_Part_1_Soundtrack' => true,
					'http://southpark.wikia.com/wiki/Push_(Feeling_Good_on_a_Wednesday)' => true,
					'http://thehungergames.wikia.com/wiki/User_blog:Gcheung28/Second_Single_from_Mockingjay_Part_1_Released' => true,
					'http://music.wikia.com/wiki/List_of_songs_whose_lyrics_do_not_mention_the_song_title' => true,
					'http://americantop40.wikia.com/wiki/October_13,_1984' => true,
					'http://5sos.wikia.com/wiki/Derp_Con' => true,
					'http://onepiece.wikia.com/wiki/One_Piece_Music' => true,
					'http://marvel.wikia.com/User_blog:Gcheung28/Fan_Brain:_Marvel%27s_Agents_of_S.H.I.E.L.D._-_%22Face_My_Enemy%22' => true
				],
				'entries' => [
					[
						'title' => 'RUMOR: Obi-Wan Kenobi Spinoff in the Works',
						'description' => 'Um, yes please? Rumor has it—at least according to MakingStarWars.Net—that an Obi-Wan Kenobi spinoff film is in the works. According to MSW\'s source, "the spin-off movies...were initially going to stay away from any Jedi or Sith characters. But I\'m hearing now that because of the popularity of Obi-Wan...that an art team is now working with a writer on concepts for an Obi-Wan movie.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140917230522/movieshub/images/1/1a/StarWarsKenobiCrop.png',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://starwarsfans.wikia.com/wiki/User_blog:Brandon_Rhea/RUMOR:_Obi-Wan_Kenobi_Spinoff_in_the_Works',
						'wikia_id' => '8476',
						'page_id' => '38931',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Exclusive Transformers: Age of Extinction Behind the Scenes Clip',
						'description' => 'Craving more Transformers?? No worries, we have an exclusive clip from Age of Extinction AND news about the upcoming DVD release just for you! ',
						'module' => 'community',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140916184358/movieshub/images/7/71/Age_of_Extinction-BLURAY.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://transformers.wikia.com/wiki/User_blog:Gcheung28/Exclusive_Transformers:_Age_of_Extinction_Behind_the_Scenes_Clip',
						'wikia_id' => '411',
						'page_id' => '57509',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Lorde\'s Mockingjay Part 1 Single Revealed',
						'description' => 'All hail Lorde! The curator of The Hunger Games: Mockingjay - Part 1\'s soundtrack has just released her single for the movie, and we are in awe! Check it out now!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20141002004734/movieshub/images/e/eb/Movieshub-Lorde-Mockingjay_pt2_single.jpg',
							'width' => '300',
							'height' => '258',
						],
						'timestamp' => $timestamp,
						'url' => 'http://thehungergames.wikia.com/wiki/User_blog:Gcheung28/Lorde%27s_Mockingjay_Part_1_Single_Revealed',
						'wikia_id' => '35171',
						'page_id' => '504244',
						'source' => 'hub_952442',
					],
					[
						'title' => 'News',
						'description' => 'News',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/Category:2014_NYCC_News',
						'wikia_id' => '778168',
						'page_id' => '3061',
						'source' => 'hub_952442',
					],
					[
						'title' => 'S.H.I.E.L.D. Trivia',
						'description' => 'Which comic nods did you catch this week?',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140924220924/movieshub/images/b/b7/Shield_S2_crosspromoposter.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Gcheung28/Fan_Brain:_Marvel%27s_Agents_of_S.H.I.E.L.D._-_%22Face_My_Enemy%22',
						'wikia_id' => '2233',
						'page_id' => '763380',
						'source' => 'hub_952442',
					],
					[
						'title' => 'MLP Giveaway',
						'description' => 'Enter to win an Equestria Girls doll, DVD, and more!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20141021235714/movieshub/images/6/6f/Rainbow_Rocks_Poster_2.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://mlp.wikia.com/wiki/User_blog:Gcheung28/ENTER_NOW:_My_Little_Pony_Equestria_Girls:_Rainbow_Rocks_Giveaway',
						'wikia_id' => '194308',
						'page_id' => '607523',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Fantasy Face-Off',
						'description' => 'It\'s a showdown in Mordor as two fantasy giants face-off in an epic battle! We\'ve assembled a line-up of some of the most famous fantasy characters and creatures that will be facing off against various characters from Middle-earth: Shadow of Mordor. Will the mighty Diablo beat the Dark Lord Sauron himself, or will Sauron come out victoriously? You get to decide who would be victorious in a one-on-one battle!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140910145855/movieshub/images/f/f1/FaceOff_SoM_Hubslider_330x210.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://shadowofmordor.wikia.com/wiki/User_blog:MarkvA/Fantasy_Face-Off',
						'wikia_id' => '863039',
						'page_id' => '16728',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Images',
						'description' => 'Images',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/New_York_Comic_Con_2014_WikiaLive_Photos',
						'wikia_id' => '778168',
						'page_id' => '3014',
						'source' => 'hub_952442',
					],
					[
						'title' => 'Video',
						'description' => 'Video',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/New_York_Comic_Con_2014_WikiaLive_Videos',
						'wikia_id' => '778168',
						'page_id' => '3029',
						'source' => 'hub_952442',
					],
					[
						'title' => 'NYCC 2014',
						'description' => 'Watch for updates from the show.',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140915180325/movieshub/images/6/69/330px-Nycc.png',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/Portal:New_York_Comic_Con_2014',
						'wikia_id' => '778168',
						'page_id' => '3009',
						'source' => 'hub_952442',
					],
					[
						'title' => 'DC Tournament',
						'description' => 'Who will win? Vote for your favorite characters now!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20140924183004/movieshub/images/0/07/NBC_Constantine_Hubslider_330x210_R1.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://dc.wikia.com/wiki/User_blog:Gcheung28/DC_Heroes_vs._Villains_Bracket_Tournament',
						'wikia_id' => '2237',
						'page_id' => '408511',
						'source' => 'hub_952442',
					],
					[
						'title' => 'WIKIA FANNOTATION: X-Men: Days of Future Past',
						'description' => 'The new trailer for X-Men: Days of Future Past is here! How sharp is your X-Men vision? With your help, we\'ve highlighted many of the references and cool-spottings in the preview. Check out our latest Wikia Fannotation below, and leave a comment with anything else you saw in the new preview.',
						'module' => 'community',
						['img' => '[',
							'url' => 'http://img2.wikia.nocookie.net/__cb20140430053938/comicshub/images/a/a4/Fan_Xmen_Hubslider_330x210.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Brian_Linder/X-Men:_Days_of_Future_Past_--_What_Did_You_Spot%3F',
						'wikia_id' => '2233',
						'page_id' => '711683',
						'source' => 'hub_952445',
					],
					[
						'title' => 'SDCC 2014 Marvel Contest of Champions Trailer',
						'description' => 'Exciting news, Marvel fans!! At San Diego Comic-Con’s Marvel Video Games panel, Kabam, the leader in the western world for free-to-play games for traditional players, announced Marvel Contest of Champions, an immersive, high-action, high-fidelity super hero combat game for mobile devices! In development at Kabam’s Vancouver studio, players fight their way through iconic locations from the Marvel Universe and collect their favorite Marvel super heroes and villains such as Iron Man, Captain America, Spider-Man and Thor to build their ultimate team of champions.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20140722235051/comicshub/images/a/a7/W-SDCC_Hubslider_Generic.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Gcheung28/SDCC_2014_Marvel_Contest_of_Champions_Trailer',
						'wikia_id' => '2233',
						'page_id' => '748673',
						'source' => 'hub_952445',
					],
					[
						'title' => 'San Diego Comic-Con 2014 Marvel Studios Panel Fan Reaction',
						'description' => 'Greetings, true believers! Didn\'t make it to Comic-Con this year? Worry not! We\'ve got an insider\'s p.o.v. for you from a fellow fan who attended the Marvel Studios Panel in your stead! Age of Ultron? Ant-Man? Guardians of the Galaxy 2? It\'s all there, and much more!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140813125429/comicshub/images/c/c9/Comicshub-marvel-SDCC-panel-FanReactions.jpg',
							'width' => '310',
							'height' => '200',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:XD1/San_Diego_Comic-Con_2014_Marvel_Studios_Panel_Fan_Reaction',
						'wikia_id' => '2233',
						'page_id' => '749539',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Comic Book Showcase: Gender Benders and Racelifts',
						'description' => 'This is bound to be a controversial episode, but an interesting and important topic: The changing of race and gender of established characters. This has been in the news recently with the \'changing\' of Thor to a woman. (In actuality, male Thor still exists, a woman has simply taken up his mantle.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140813124439/comicshub/images/d/dc/Comicshub-marvel-genderbender.jpg',
							'width' => '310',
							'height' => '200',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Jamie/Episode_16_-_Gender_Benders_and_Racelifts',
						'wikia_id' => '2233',
						'page_id' => '751051',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Transformers Battle',
						'description' => 'Who\'s your favorite \'bot? Vote now.',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140916184757/comicshub/images/7/71/Age_of_Extinction-BLURAY.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://transformers.wikia.com/wiki/User_blog:Brian_Linder/Ultimate_Transformers_Showdown',
						'wikia_id' => '411',
						'page_id' => '57539',
						'source' => 'hub_952445',
					],
					[
						'title' => 'News',
						'description' => 'News',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/Category:2014_NYCC_News',
						'wikia_id' => '778168',
						'page_id' => '3061',
						'source' => 'hub_952445',
					],
					[
						'title' => 'S.H.I.E.L.D. Trivia',
						'description' => 'Which comic nods did you catch in Episode 4?',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20141002081030/comicshub/images/4/47/W-FANB-AoS_Hubslider_330x210.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Gcheung28/Fan_Brain:_Marvel%27s_Agents_of_S.H.I.E.L.D._-_%22Face_My_Enemy%22',
						'wikia_id' => '2233',
						'page_id' => '763380',
						'source' => 'hub_952445',
					],
					[
						'title' => 'MLP Giveaway',
						'description' => 'Enter to win an Equestria Girls doll, DVD, and more!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20141022000006/comicshub/images/6/6f/Rainbow_Rocks_Poster_2.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://mlp.wikia.com/wiki/User_blog:Gcheung28/ENTER_NOW:_My_Little_Pony_Equestria_Girls:_Rainbow_Rocks_Giveaway',
						'wikia_id' => '194308',
						'page_id' => '607523',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Sideshow Collectibles wants to give YOU an Iron Man',
						'description' => 'At San Diego Comic Con this year, I was given a special tour of Sideshow Collectibles\' amazing booth (see the slideshow at the end of this post],. At the end, they said they would like to give an Iron Man Mark 17: Heartbreaker Sixth Scale Figure by Hot Toys to our fans and users!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20140813130513/comicshub/images/0/07/Comicshub-Sideshow_Giveaway_Iron_Man.jpg',
							'width' => '740',
							'height' => '448',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/User_blog:Peteparker/Sideshow_Collectibles_wants_to_give_YOU_an_Iron_Man',
						'wikia_id' => '2233',
						'page_id' => '749903',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Peter Parker Quotes',
						'description' => '"You\'re the creep who\'s going to pay! I\'m going to get you, Goblin! I\'m going to destroy you slowly, and when you start begging for me to end it - I\'m going to remind you of one thing - You KILLED the woman I love! And for that you\'re going to DIE!"',
						'module' => 'community',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20140430061536/comicshub/images/0/08/Peterparker_spot.jpg',
							'width' => '328',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/Category:Peter_Parker_(Earth-616],/Quotes',
						'wikia_id' => '2233',
						'page_id' => '160012',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Images',
						'description' => 'Images',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/New_York_Comic_Con_2014_WikiaLive_Photos',
						'wikia_id' => '778168',
						'page_id' => '3014',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Video',
						'description' => 'Video',
						'module' => 'explore',
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/New_York_Comic_Con_2014_WikiaLive_Videos',
						'wikia_id' => '778168',
						'page_id' => '3029',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Wikia Live',
						'description' => 'New York Comic Con 2014 is happening October 9-12 at the Javitz Center in NYC. Can\'t make it to the show? Don\'t worry -- we\'ve got you! Check out all the <a href="https://twitter.com/search?q=%23WikiaLive">#WikiaLive\</a> coverage from the show on social media, and watch the <a href="http://reedpop.wikia.com/wiki/Portal:New_York_Comic_Con_2014">New York Comic Con portal</a> for the latest pics, video, and news from the show.',
						'module' => 'wikiaspicks',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20141008011415/comicshub/images/9/9c/W-NYCC_Wikia_Picks_500x142.jpg',
							'width' => '500',
							'height' => '142',
						],
						'timestamp' => $timestamp,
						'url' => 'http://reedpop.wikia.com/wiki/Portal:New_York_Comic_Con_2014',
						'wikia_id' => '778168',
						'page_id' => '3009',
						'source' => 'hub_952445',
					],
					[
						'title' => 'SPOTLIGHT: Guardians of the Galaxy',
						'description' => 'The Guardians of the Galaxy is a group of heroes who opposed the Phalanx conquest of the Kree system (and many who had opposed Annihilus\' incursion into their universe],, and banded together in an attempt to prevent any further catastrophes from ever occurring.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20140430054438/comicshub/images/6/69/Rocket_spot.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://marvel.wikia.com/Guardians_of_the_Galaxy_(Earth-616],',
						'wikia_id' => '2233',
						'page_id' => '102101',
						'source' => 'hub_952445',
					],
					[
						'title' => 'DC Tournament',
						'description' => 'Who will win? Vote for your favorite characters now!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20140924190157/comicshub/images/0/07/NBC_Constantine_Hubslider_330x210_R1.jpg',
							'width' => '330',
							'height' => '210',
						],

						'timestamp' => $timestamp,
						'url' => 'http://dc.wikia.com/wiki/User_blog:Gcheung28/DC_Heroes_vs._Villains_Bracket_Tournament',
						'wikia_id' => '2237',
						'page_id' => '408511',
						'source' => 'hub_952445',
					],
					[
						'title' => 'The Walking Dead Comics',
						'description' => 'The Walking Dead is a monthly (biweekly since October 2013], black-and-white American comic that started in 2003, and was created and written by Robert Kirkman with artist Tony Moore. The current artists for the series are Charlie Adlard, Cliff Rathburn and Stefano Gaudiano. The comic is published by Image Comics and Skybound Entertainment.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20140430054734/comicshub/images/7/70/Twd_comic_spot.jpg',
							'width' => '330',
							'height' => '210',
						],

						'timestamp' => $timestamp,
						'url' => 'http://walkingdead.wikia.com/wiki/The_Walking_Dead_(Comic_Series],',
						'wikia_id' => '13346',
						'page_id' => '2398',
						'source' => 'hub_952445',
					],
					[
						'title' => 'Hunger Games Single',
						'description' => '"Yellow Flicker Beat" and Mockingjay soundtrack',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20140929200512/musichub/images/e/ec/Lorde.jpg',
							'width' => '4928',
							'height' => '3280',
						],
						'timestamp' => $timestamp,
						'url' => 'http://thehungergames.wikia.com/wiki/User_blog:Gcheung28/Lorde%27s_Mockingjay_Part_1_Single_Revealed',
						'wikia_id' => '35171',
						'page_id' => '504244',
						'source' => 'hub_952443',
					],
					[
						'title' => 'Fan Playlist',
						'description' => 'The fans made a Mockingjay Part 1 OST. Check it out!',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20141003223422/musichub/images/7/72/HG_soundtrack_Hubslider_330x210.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://musichub.wikia.com/wiki/User_blog:Gcheung28/The_Ultimate_Mockingjay_Part_1_Soundtrack',
						'wikia_id' => '952443',
						'page_id' => '2361',
						'source' => 'hub_952443',
					],
					[
						'title' => 'Lorde Immortalized on South Park',
						'description' => '"Push (Feeling Good on a Wednesday]," is a song from the Season Eighteen episode, "The Cissy". It is a parody song of the popular vocalist known as Lorde, sung by Randy Marsh as Lorde.',
						'module' => 'community',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20141016225214/musichub/images/5/55/Randy_Lorde.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://southpark.wikia.com/wiki/Push_(Feeling_Good_on_a_Wednesday],',
						'wikia_id' => '835',
						'page_id' => '65762',
						'source' => 'hub_952443',
					],
					[
						'title' => 'Second Single from Mockingjay Part 1 Released',
						'description' => 'You heard Lorde\'s single "Yellow Flicker Beat", now check out the second single to be released from the The Hunger Games: Mockingjay - Part 1 soundtrack! Titled "This Is Not a Game," the 2nd single was helmed by the Chemical Brothers. The vocals you hear are from Miguel, and Lorde is on the track a bit too!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img3.wikia.nocookie.net/__cb20141020223850/musichub/images/b/b4/Mj_poster_tagline.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://thehungergames.wikia.com/wiki/User_blog:Gcheung28/Second_Single_from_Mockingjay_Part_1_Released',
						'wikia_id' => '35171',
						'page_id' => '506812',
						'source' => 'hub_952443',
					],

					[
						'title' => 'Derp Con',
						'description' => 'Check out Derp Con from 5S0S now! Are you going?',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20141016165854/musichub/images/8/8a/Derpcon-5sos.png',
							'width' => '330',
							'height' => '210',
						],

						'timestamp' => $timestamp,
						'url' => 'http://5sos.wikia.com/wiki/Derp_Con',
						'wikia_id' => '718604',
						'page_id' => '5943',
						'source' => 'hub_952443',
					],
					[
						'title' => 'American Top 40',
						'description' => 'What was on the charts 30 years ago this week?',
						'module' => 'slider',
						'img' => [
							'url' => 'http://img2.wikia.nocookie.net/__cb20140919182022/musichub/images/f/fb/AmericanTop40.png',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://americantop40.wikia.com/wiki/October_13,_1984',
						'wikia_id' => '229168',
						'page_id' => '2918',
						'source' => 'hub_952443',
					],
					[
						'title' => 'One Piece Music',
						'description' => 'As has become typical of long-running youth-oriented anime, One Piece has gone through a long succession of theme songs, performed by popular artists, since its debut on television. Though performed by popular singers and bands, most seem to be written specifically for the show, as nearly all of them reference treasure, the sea, or ships in some way. As of January 19, 2014, there have been 17 opening themes, 18 regular ending themes, and a number of film- and special endings as well. ',
						'module' => 'community',
						'img' => [
							'url' => 'http://img1.wikia.nocookie.net/__cb20141017220044/musichub/images/8/87/One_Piece_Anime_Logo.png',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://onepiece.wikia.com/wiki/One_Piece_Music',
						'wikia_id' => '1081',
						'page_id' => '1615',
						'source' => 'hub_952443',
					],
					[
						'title' => 'Songs Whose Lyrics Do Not Mention the Song Title',
						'description' => 'This is a list of songs whose title is not referenced in the song lyrics. This should be limited only to songs which have lyrics. This list should not include songs where the title is implied in the song. For example "Country House" by Blur does not contain the exact phrase "Country House" but it does contain the lyrics "A very big house in the country". This song should not be included on the list. ',
						'module' => 'community',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20140430022332/musichub/images/1/1c/Beatleshero.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://music.wikia.com/wiki/List_of_songs_whose_lyrics_do_not_mention_the_song_title',
						'wikia_id' => '84',
						'page_id' => '7409',
						'source' => 'hub_952443',
					],
					[
						'title' => 'The Walking Dead Music Portal',
						'description' => 'This page contains music featured in The Walking Dead media. Specifically, AMC\'s The Walking Dead TV Series and Telltale Games: The Walking Dead. Warning: the music tracks below may contain major spoilers. Caution is advised!',
						'module' => 'community',
						'img' => [
							'url' => 'http://img4.wikia.nocookie.net/__cb20141020230927/musichub/images/f/f7/GlennMusician.jpg',
							'width' => '330',
							'height' => '210',
						],
						'timestamp' => $timestamp,
						'url' => 'http://walkingdead.wikia.com/wiki/Music_Portal',
						'wikia_id' => '13346',
						'page_id' => '50012',
						'source' => 'hub_952443',
					]
				]
			],
		];
	}
}
