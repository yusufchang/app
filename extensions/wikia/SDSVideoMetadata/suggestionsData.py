#/usr/bin/python
# -*- coding: utf-8 -*- 

import httplib
import json
import time

server = 'dev-adam:9292'
path = '/api/v0.1/'
object_url = 'http://sds.wikia.com/'
collection = 'video151'

data = {
	'wikia:VideoGame': [
		{
			'shortId': 'ufo_enemy_unknown',
			'schema:name': 'UFO: Enemy Unknown',
			'schema:datePublished': '1995-10-25',
			'schema:genre': 'Strategy game',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage01'}],
		},
		{
			'shortId': 'the_witcher',
			'schema:name': 'The Witcher',
			'schema:datePublished': '2007-10-26',
			'schema:genre': 'Action role-playing',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage02'}],
		},
		{
			'shortId': 'morrowind',
			'schema:name': 'The Elder Scrolls III: Morrowind',
			'schema:datePublished': '2002-05-01',
			'schema:genre': 'Action role-playing',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage03'}],
		},
		{
			'shortId': 'call_of_duty',
			'schema:name': 'Call of Duty',
			'schema:datePublished': '2003-10-29',
			'schema:genre': 'FPS',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage04'}],
		},
		{
			'shortId': 'call_of_duty_2',
			'schema:name': 'Call of Duty 2',
			'schema:datePublished': '2005-10-25',
			'schema:genre': 'FPS',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage05'}],
		},
		{
			'shortId': 'call_of_duty_3',
			'schema:name': 'Call of Duty 3',
			'schema:datePublished': '2006-11-07',
			'schema:genre': 'FPS',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage06'}],
		},
		{
			'shortId': 'call_of_duty_4_modern_warfare',
			'schema:name': 'Call of Duty 4: Modern Warfare',
			'schema:datePublished': '2007-11-07',
			'schema:genre': 'FPS',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage07'}],
		},
		{
			'shortId': 'call_of_duty_4_modern_warfare_2',
			'schema:name': 'Call of Duty: Modern Warfare 2',
			'schema:datePublished': '2009-02-11',
			'schema:genre': 'FPS',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage08'}],
		},
		{
			'shortId': 'call_of_duty_world_at_war',
			'schema:name': 'Call of Duty: World at War',
			'schema:datePublished': '2008-06-09',
			'schema:genre': 'FPS',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage09'}],
		},
		{
			'shortId': 'call_of_duty_black_ops',
			'schema:name': 'Call of Duty: Black Ops',
			'schema:datePublished': '2010-11-09',
			'schema:genre': 'FPS',
			'schema:photos': [{'id': object_url + collection + '/sugestionsImage10'}],
		},
	],
	'schema:AudioObject': [
		{
			'shortId': 'sound_track_1',
			'schema:name': 'Sound Track 1',
		},
		{
			'shortId': 'sound_track_2',
			'schema:name': 'Sound Track 2',
		},
		{
			'shortId': 'sound_track_3',
			'schema:name': 'Sound Track 3',
		},
		{
			'shortId': 'sound_track_4',
			'schema:name': 'Sound Track 4',
		},
		{
			'shortId': 'sound_track_5',
			'schema:name': 'Sound Track 5',
		},
		{
			'shortId': 'sound_track_6',
			'schema:name': 'Sound Track 6',
		},
		{
			'shortId': 'sound_track_7',
			'schema:name': 'Sound Track 7',
		},
		{
			'shortId': 'sound_track_8',
			'schema:name': 'Sound Track 8',
		},
		{
			'shortId': 'sound_track_9',
			'schema:name': 'Sound Track 9',
		},
		{
			'shortId': 'sound_track_10',
			'schema:name': 'Sound Track 10',
		},
	],
	'schema:TVSeries': [
		{
			'shortId': 'greys_anatomy',
			'schema:name': "Grey's Anatomy",
		},
		{
			'shortId': 'the_walking_dead',
			'schema:name': "The Walking Dead",
		},
		{
			'shortId': 'the_big_bang_theory',
			'schema:name': "The Big Bang Theory",
			'schema:season': [{'id:': object_url + collection + '/the_big_bang_theory_1'},
				{'id:': object_url + collection + '/the_big_bang_theory_2'},
				{'id:': object_url + collection + '/the_big_bang_theory_3'},
				{'id:': object_url + collection + '/the_big_bang_theory_4'},
				{'id:': object_url + collection + '/the_big_bang_theory_5'},
				{'id:': object_url + collection + '/the_big_bang_theory_6'},
			]
		},
		{
			'shortId': 'the_vampire_diaries',
			'schema:name': "The Vampire Diaries",
		},
		{
			'shortId': 'how_i_met_your_mother',
			'schema:name': "How I Met Your Mother",
		},
		{
			'shortId': 'supernatural',
			'schema:name': "Supernatural",
		},
		{
			'shortId': 'the_mentalist',
			'schema:name': "The Mentalist",
		},
		{
			'shortId': 'ncis',
			'schema:name': "NCIS",
		},
		{
			'shortId': 'criminal_minds',
			'schema:name': "Criminal Minds",
		},
		{
			'shortId': 'person_of_interest',
			'schema:name': "Person of Interest",
		},
	],
	'schema:TVSeason': [
		{
			'shortId': 'the_big_bang_theory_1',
			'schema:name': 'The Big Bang Theory Season 1',
			'schema:startDate': '2007-09-24',
			'schema:endDate': '2008-05-19',
			'schema:seasonNumber': 1,
			'schema:partOfTVSeries': [{'id': object_url + collection + '/the_big_bang_theory'}],
		},
		{
			'shortId': 'the_big_bang_theory_2',
			'schema:name': 'The Big Bang Theory Season 2',
			'schema:startDate': '2008-09-22',
			'schema:endDate': '2009-05-11',
			'schema:seasonNumber': 2,
			'schema:partOfTVSeries': [{'id': object_url + collection + '/the_big_bang_theory'}],
		},
		{
			'shortId': 'the_big_bang_theory_3',
			'schema:name': 'The Big Bang Theory Season 3',
			'schema:startDate': '2009-09-23',
			'schema:endDate': '2010-05-24',
			'schema:seasonNumber': 3,
			'schema:partOfTVSeries': [{'id': object_url + collection + '/the_big_bang_theory'}],
		},
		{
			'shortId': 'the_big_bang_theory_4',
			'schema:name': 'The Big Bang Theory Season 4',
			'schema:startDate': '2010-09-23',
			'schema:endDate': '2011-05-19',
			'schema:seasonNumber': 4,
			'schema:partOfTVSeries': [{'id': object_url + collection + '/the_big_bang_theory'}],
		},
		{
			'shortId': 'the_big_bang_theory_5',
			'schema:name': 'The Big Bang Theory Season 5',
			'schema:startDate': '2011-09-22',
			'schema:endDate': '2012-05-10',
			'schema:seasonNumber': 5,
			'schema:partOfTVSeries': [{'id': object_url + collection + '/the_big_bang_theory'}],
		},
		{
			'shortId': 'the_big_bang_theory_6',
			'schema:name': 'The Big Bang Theory Season 6',
			'schema:startDate': '2012-09-27',
			'schema:endDate': '2013-05-19',
			'schema:seasonNumber': 6,
			'schema:partOfTVSeries': [{'id': object_url + collection + '/the_big_bang_theory'}],
		},
	],
	'schema:Movie': [
		{
			'shortId': 'iron_man_3',
			'schema:name': 'Iron Man 3',
			'schema:genre': 'Action',
			'schema:copyrightYear': 2013,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage01'}],
		},
		{
			'shortId': 'life_of_pi',
			'schema:name': 'Life of Pi',
			'schema:genre': 'Adventure',
			'schema:copyrightYear': 2012,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage02'}],
		},
		{
			'shortId': 'the_lion_king',
			'schema:name': 'The Lion King',
			'schema:genre': 'Animation',
			'schema:copyrightYear': 1994,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage03'}],
		},
		{
			'shortId': 'hitchcock',
			'schema:name': 'Hitchcock',
			'schema:genre': 'Biography',
			'schema:copyrightYear': 2012,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage04'}],
		},
		{
			'shortId': 'looper',
			'schema:name': 'Looper',
			'schema:genre': 'Crime',
			'schema:copyrightYear': 2012,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage05'}],
		},
		{
			'shortId': 'argo',
			'schema:name': 'Argo',
			'schema:genre': 'Drama',
			'schema:copyrightYear': 2012,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage06'}],
		},
		{
			'shortId': 'total_recall_2012',
			'schema:name': 'Total Recall',
			'schema:genre': 'Sci-Fi',
			'schema:copyrightYear': 2012,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage07'}],
		},
		{
			'shortId': 'total_recall_1990',
			'schema:name': 'Total Recall',
			'schema:genre': 'Sci-Fi',
			'schema:copyrightYear': 1990,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage08'}],
		},
		{
			'shortId': 'true_grit',
			'schema:name': 'True Grit',
			'schema:genre': 'Western',
			'schema:copyrightYear': 2010,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage09'}],
		},
		{
			'shortId': 'the_cabin_in_the_woods',
			'schema:name': 'The Cabin in the Woods',
			'schema:genre': 'Horror',
			'schema:copyrightYear': 2011,
			'schema:associatedMedia': [{'id': object_url + collection + '/sugestionsImage10'}],
		},
	],
	'schema:MusicRecording': [
		{
			'shortId': 'czarne_slonce', 
			'schema:name': 'Czarne Słońca'
		},
		{
			'shortId': 'her ghost in the fog', 
			'schema:name': 'Her ghost in the fog'
		},
		{
			'shortId': 'unforgiven', 
			'schema:name': 'Unforgiven'
		},
		{
			'shortId': 'octavarium', 
			'schema:name': 'Octavarium'
		},
		{
			'shortId': 'jester_race', 
			'schema:name': 'Jester Race'
		},
		{
			'shortId': 'ashes_to_ashes', 
			'schema:name': 'Ashes to Ashes'
		},
		{
			'shortId': 'bohemian_rapsody', 
			'schema:name': 'Bohemian Rapsody'
		},
		{
			'shortId': 'fugative', 
			'schema:name': 'Fugative'
		},
		{
			'shortId': 'estranged', 
			'schema:name': 'Estranged'
		},
		{
			'shortId': 'in_bloom', 
			'schema:name': 'In Bloom'
		},
	],
	'schema:MusicGroup': [
		{
			'shortId': 'kult', 
			'schema:name': 'Kult'
		},
		{
			'shortId': 'cradle_of_filth', 
			'schema:name': 'Cradle of Filth'
		},
		{
			'shortId': 'metallica', 
			'schema:name': 'Metallica'
		},
		{
			'shortId': 'in_flames', 
			'schema:name': 'In Flames'
		},
		{
			'shortId': 'dream_theater', 
			'schema:name': 'Drem Theater'
		},
		{
			'shortId': 'faith_no_more', 
			'schema:name': 'Faith No More'
		},
		{
			'shortId': 'queen', 
			'schema:name': 'Queen'
		},
		{
			'shortId': 'iron_maiden', 
			'schema:name': 'Iron Maiden'
		},
		{
			'shortId': 'guns_n_roses', 
			'schema:name': "Guns'n'Roses"
		},
		{
			'shortId': 'nirvana', 
			'schema:name': 'Nirvana'
		},
	],
	'schema:Ogranization': [
		{
			'shortId': 'sony', 
			'schema:name': 'Sony'
		},
		{
			'shortId': 'wikia', 
			'schema:name': 'Wikia'
		},
		{
			'shortId': 'google', 
			'schema:name': 'Google'
		},
		{
			'shortId': 'amazon', 
			'schema:name': 'Amazon'
		},
		{
			'shortId': 'activision', 
			'schema:name': 'Activision'
		},
		{
			'shortId': 'valve', 
			'schema:name': 'Valve'
		},
		{
			'shortId': 'facebook', 
			'schema:name': 'Facebook'
		},
		{
			'shortId': 'ign', 
			'schema:name': 'IGN'
		},
		{
			'shortId': 'id_software', 
			'schema:name': 'ID Software'
		},
		{
			'shortId': 'cd_project', 
			'schema:name': 'CD Project'
		},
	],
	'schema:Place': [
		{
			'shortId': 'stary_rynek',
			'schema:name': 'Stary rynek',
			'photo': [{'id' : object_url + collection + '/sugestionsImage01'}]
		},
		{
			'shortId': 'wilda',
			'schema:name': 'Wilda',
			'photo': [{'id' : object_url + collection + '/sugestionsImage02'}]
		},
		{
			'shortId': 'debiec',
			'schema:name': 'Dębiec',
			'photo': [{'id' : object_url + collection + '/sugestionsImage03'}]
		},
		{
			'shortId': 'lazarz',
			'schema:name': 'Łazarz',
			'photo': [{'id' : object_url + collection + '/sugestionsImage04'}]
		},
		{
			'shortId': 'grunwald',
			'schema:name': 'Grunwald',
			'photo': [{'id' : object_url + collection + '/sugestionsImage05'}]
		},
		{
			'shortId': 'piatkowo',
			'schema:name': 'Piątkowo',
			'photo': [{'id' : object_url + collection + '/sugestionsImage06'}]
		},
		{
			'shortId': 'winogrady',
			'schema:name': 'Winogrady',
			'photo': [{'id' : object_url + collection + '/sugestionsImage07'}]
		},
		{
			'shortId': 'rataje',
			'schema:name': 'Rataje',
			'photo': [{'id' : object_url + collection + '/sugestionsImage08'}]
		},
		{
			'shortId': 'jezyce',
			'schema:name': 'Jeżyce',
			'photo': [{'id' : object_url + collection + '/sugestionsImage09'}]
		},
		{
			'shortId': 'staroleka',
			'schema:name': 'Starołęka',
			'photo': [{'id' : object_url + collection + '/sugestionsImage10'}]
		},
	],
	'schema:Recipe': [
		{
			'shortId' : 'zupa_pieczarkowa',
			'schema:name': 'Zupa pieczarkowa',
			'schema:image': 'http://images4.wikia.nocookie.net/__cb20120702093321/nandytest/images/thumb/8/81/Pieczarkowa.jpg/100px-Pieczarkowa.jpg',
			'schema:description': 'Opis zupy pieczarkowej'
		},
		{
			'shortId' : 'dorsz',
			'schema:name': 'Dorsz',
			'schema:image': 'http://images4.wikia.nocookie.net/__cb20120623103108/nandytest/images/thumb/4/4d/Dorsz.jpg/100px-Dorsz.jpg',
			'schema:description': 'Opis dorsza'
		},
		{
			'shortId' : 'kotlet_rybny',
			'schema:name': 'Kotlet rybny',
			'schema:image': 'http://images3.wikia.nocookie.net/__cb20130215100743/nandytest/images/thumb/d/da/Kotlet_rybny.jpg/100px-Kotlet_rybny.jpg',
			'schema:description': 'Opis kotleta rybnego'
		},
		{
			'shortId' : 'pierogi_ruskie',
			'schema:name': 'Pierogi ruskie',
			'schema:image': 'http://images2.wikia.nocookie.net/__cb20120623103735/nandytest/images/thumb/9/9c/Pierogi_ruskie.jpg/100px-Pierogi_ruskie.jpg',
			'schema:description': 'Opis pierogów ruskich'
		},
		{
			'shortId' : 'bar_salatkowy',
			'schema:name': 'Bar sałatkowy',
			'schema:image': 'http://images1.wikia.nocookie.net/__cb20120623103049/nandytest/images/thumb/4/45/Bar_salatkowy.jpg/100px-Bar_salatkowy.jpg',
			'schema:description': 'Opis baru sałatkowego'
		},
		{
			'shortId' : 'zestaw_piatkowy',
			'schema:name': 'Kotlet schabowy (mały), szpinak, jajko',
			'schema:image': 'http://images3.wikia.nocookie.net/__cb20120623103606/nandytest/images/thumb/a/a2/Maly_schabowy_szpinak_jajko.jpg/100px-Maly_schabowy_szpinak_jajko.jpg',
			'schema:description': 'Opis zestawu piątkowego'
		},
		{
			'shortId' : 'pyry_z_gzikiem',
			'schema:name': 'Pyry z gzikiem',
			'schema:image': 'http://images4.wikia.nocookie.net/__cb20130219090929/nandytest/images/thumb/5/53/Pyry_z_gzikiem_%28male%29.jpg/100px-Pyry_z_gzikiem_%28male%29.jpg',
			'schema:description': 'Opis pyr z gzikiem'
		},
		{
			'shortId' : 'nalesniki_z_serem_na_slodko',
			'schema:name': 'Naleśniki z serem na słodko',
			'schema:image': 'http://images2.wikia.nocookie.net/__cb20120808090015/nandytest/images/thumb/a/ae/Nalesniki.jpg/100px-Nalesniki.jpg',
			'schema:description': 'Opis naleśników na słodko'
		},
		{
			'shortId' : 'zeberka',
			'schema:name': 'Żeberka',
			'schema:image': 'http://images4.wikia.nocookie.net/__cb20120623113421/nandytest/images/thumb/1/1a/Zeberka.jpg/100px-Zeberka.jpg',
			'schema:description': 'Opis żeberek'
		},
		{
			'shortId' : 'pieczen',
			'schema:name': 'Pieczeń',
			'schema:image': 'http://images2.wikia.nocookie.net/__cb20120623103707/nandytest/images/thumb/4/49/Pieczen.jpg/100px-Pieczen.jpg',
			'schema:description': 'Opis pieczeni'
		},
	],
	'schema:ImageObject': [
		{
			'shortId' : 'sugestionsImage01',
			'schema:contentURL': 'http://images4.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/8/80/Wikia-Visualization-Main%2Cl5r.png/160px-0%2C480%2C19%2C319-Wikia-Visualization-Main%2Cl5r.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'l5r'
		},
		{
			'shortId' : 'sugestionsImage02',
			'schema:contentURL': 'http://images2.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/4/49/Wikia-Visualization-Main%2Chimym.png/160px-0%2C320%2C19%2C219-Wikia-Visualization-Main%2Chimym.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'himym'
		},		
		{
			'shortId' : 'sugestionsImage03',
			'schema:contentURL': 'http://images3.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/2/21/Wikia-Visualization-Main%2Csonsofanarchy.png/160px-0%2C480%2C19%2C319-Wikia-Visualization-Main%2Csonsofanarchy.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'sonsofanarchy'
		},		
		{
			'shortId' : 'sugestionsImage04',
			'schema:contentURL': 'http://images3.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/7/79/Wikia-Visualization-Main%2Creddeadredemption.png/160px-0%2C320%2C19%2C219-Wikia-Visualization-Main%2Creddeadredemption.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'reddeadredemption'
		},		
		{
			'shortId' : 'sugestionsImage05',
			'schema:contentURL': 'http://images3.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/3/3b/Wikia-Visualization-Main%2Ctwistedmetal.png/160px-0%2C480%2C33%2C333-Wikia-Visualization-Main%2Ctwistedmetal.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'twistedmetal'
		},		
		{
			'shortId' : 'sugestionsImage06',
			'schema:contentURL': 'http://images4.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/0/06/Wikia-Visualization-Main%2Cclashofclans.png/160px-0%2C903%2C37%2C601-Wikia-Visualization-Main%2Cclashofclans.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'clashofclans'
		},		
		{
			'shortId' : 'sugestionsImage07',
			'schema:contentURL': 'http://images2.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/c/cb/Wikia-Visualization-Main%2Cikusabaanimation.png/160px-0%2C650%2C28%2C434-Wikia-Visualization-Main%2Cikusabaanimation.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'ikusabaanimation'
		},		
		{
			'shortId' : 'sugestionsImage08',
			'schema:contentURL': 'http://images2.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/7/75/Wikia-Visualization-Main%2Ctouhou.png/160px-0%2C320%2C19%2C219-Wikia-Visualization-Main%2Ctouhou.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'touhou'
		},		
		{
			'shortId' : 'sugestionsImage09',
			'schema:contentURL': 'http://images2.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/e/e5/Wikia-Visualization-Main%2Cdofus.png/160px-0%2C320%2C19%2C219-Wikia-Visualization-Main%2Cdofus.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'dofus'
		},		
		{
			'shortId' : 'sugestionsImage10',
			'schema:contentURL': 'http://images2.wikia.nocookie.net/__cb62043/wikiaglobal/images/thumb/1/14/Wikia-Visualization-Main%2Ctheformula1.png/160px-341%2C1054%2C0%2C445-Wikia-Visualization-Main%2Ctheformula1.png',
			'schema:height': 160,
			'schema:width': 100,
			'schema:name': 'theformula1'
		},		
	]
}

class SDSClient(object):
	def __init__(self, server, path, collection):
		self.server = server
		self.path = path
		self.collection = collection
	
	def call(self, method, shortId, body = None):
		conn = httplib.HTTPConnection(self.server)
		path = self.path + self.collection
		if shortId is not None:
			path += '/' + shortId
		conn.request(method, path + '?cb=' + str(time.time()), body)
		resp = conn.getresponse()
		if resp.status not in (200, 201):
			raise Exception('Invalid status code ' + str(resp.status) + ' while calling ' + method + ' ' + path)
		return resp.read()
		
	def get(self, shortId):
		return self.call('GET', shortId)
	
	def delete(self, shortId):
	    return self.call('DELETE', shortId)
	
	def create(self, body):
		return self.call('POST', None, body)

client = SDSClient(server, path, collection)

# remove previously create test data
for obj_type, objects in data.items():
	for sds_object in objects:
		if 'shortId' in sds_object:
			try:
				client.delete(sds_object['shortId'])
				print "Object " + obj_type + ' ' + sds_object['shortId'] + " removed"
			except:
				print "Did not delete object " + obj_type + ' ' + sds_object['shortId']
		
# do the creation below
for obj_type, objects in data.items():
	for sds_object in objects:
		if 'shortId' in sds_object:
			body = sds_object.copy()
			del(body['shortId'])
			body['id'] = object_url + collection + '/' + sds_object['shortId']
			try:
				client.create(json.dumps(body))
				print "Created object " + obj_type + ' ' + sds_object['shortId']
			except Exception, e:
				print "Did not create object " + obj_type + ' ' + sds_object['shortId'] + ' ('+str(e)+')'

