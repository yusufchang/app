/**
 * Regular expression rules table for Tamil 99 input method
 * @author Junaid P V ([[user:Junaidpv]])
 * @date 2010-11-20
 * License: GPLv3
 */

 // Normal rules
var rules = [
['([க-ஹ])h','a', '$1க'],
['([க-ஹ])b','a', '$1ங'],
['([க-ஹ])\\[','a', '$1ச'],
['([க-ஹ])E','a', '$1ஜ'],
['([க-ஹ])\\]','a', '$1ஞ'],
['([க-ஹ])o','a', '$1ட'],
['([க-ஹ])p','a', '$1ண'],
['([க-ஹ])l','a', '$1த'],
['([க-ஹ]);','a', '$1ந'],
['([க-ஹ])i','a', '$1ன'],
['([க-ஹ])j','a', '$1ப'],
['([க-ஹ])k','a', '$1ம'],
["([க-ஹ])'",'a', '$1ய'],
['([க-ஹ])m','a', '$1ர'],
['([க-ஹ])u','a', '$1ற'],
['([க-ஹ])n','a', '$1ல'],
['([க-ஹ])y','a', '$1ள'],
['([க-ஹ])/','a', '$1ழ'],
['([க-ஹ])v','a', '$1வ'],
// void
['([க-ஹ])W','a', '$1ஷ'],
['([க-ஹ])Q','a', '$1ஸ'],
['([க-ஹ])R','a', '$1ஹ'],


['க்கh', '','க்கக'],
['ங்ஙb', '','ங்ஙங'],
['ச்ச\\[', '','ச்சச'],
['ஜ்ஜE', '','ஜ்ஜஜ'],
['ஞ்ஞ\\]', '','ஞ்ஞஞ'],
['ட்டo', '','ட்டட'],
['ண்ணp', '','ண்ணண'],
['த்தl', '','த்தத'],
['ந்ந;', '','ந்நந'],
['ன்னi', '','ன்னன'],
['ப்பj', '','ப்பப'],
['ம்மk', '','ம்மம'],
["ய்ய'", '','ய்யய'],
['ர்ரm', '','ர்ரர'],
['ற்றu', '','ற்றற'],
['ல்லn', '','ல்லல'],
['ள்ளy', '','ள்ளள'],
['ழ்ழ/', '','ழ்ழழ'],
['வ்வv', '','வ்வவ'],
// void
['ஷ்ஷW', '','ஷ்ஷஷ'],
['ஸ்ஸQ', '','ஸ்ஸஸ'],
['ஹ்ஹR', '','ஹ்ஹஹ'],

['([க-ஹ])q', '','$1ா'],
['([க-ஹ])s', '','$1ி'],
['([க-ஹ])w', '','$1ீ'],
['([க-ஹ])d', '','$1ு'],
['([க-ஹ])e', '','$1ூ'],
['([க-ஹ])g', '','$1ெ'],
['([க-ஹ])t', '','$1ே'],
['([க-ஹ])r', '','$1ை'],
['([க-ஹ])c', '','$1ொ'],
['([க-ஹ])x', '','$1ோ'],
['([க-ஹ])z', '','$1ௌ'],

['([க-ஹ])a', '','$1'],

['கh', '','க்க'],
['ஙb', '','ங்ங'],
['ச\\[', '','ச்ச'],
['ஜE', '','ஜ்ஜ'],
['ஞ\\]', '','ஞ்ஞ'],
['டo', '','ட்ட'],
['ணp', '','ண்ண'],
['தl', '','த்த'],
['ந;', '','ந்ந'],
['னi', '','ன்ன'],
['பj', '','ப்ப'],
['மk', '','ம்ம'],
["ய'", '','ய்ய'],
['ரm', '','ர்ர'],
['றu', '','ற்ற'],
['லn', '','ல்ல'],
['ளy', '','ள்ள'],
['ழ/', '','ழ்ழ'],
['வv', '','வ்வ'],
// void
['ஷW', '','ஷ்ஷ'],
['ஸQ', '','ஸ்ஸ'],
['ஹR', '','ஹ்ஹ'],

['நl', '','ந்த'],
['ஙh', '','ங்க'],
['னu', '','ன்ற'],
['ணo', '','ண்ட'],
['ஞ\\[', '','ஞ்ச'],
['மj', '','ம்ப'],

['q', '','ஆ'],
['w', '','ஈ'],
['e', '','ஊ'],
['r', '','ஐ'],
['t', '','ஏ'],
['y', '','ள'],
['u', '','ற'],
['i', '','ன'],
['o', '','ட'],
['p', '','ண'],
['\\[', '','ச'],
['\\]', '','ஞ'],

['a', '','அ'],
['s', '','இ'],
['d', '','உ'],
['f', '','்'],
['g', '','எ'],
['h', '','க'],
['j', '','ப'],
['k', '','ம'],
['l', '','த'],
[';', '','ந'],
["'", '','ய'],

['z', '','ஔ'],
['x', '','ஓ'],
['c', '','ஒ'],
['v', '','வ'],
['b', '','ங'],
['n', '','ல'],
['m', '','ர'],
//',', '','ஆ'],
//'\\.', '','ஆ'],
['/', '','ழ'],

['Q', '','ஸ'],
['W', '','ஷ'],
['E', '','ஜ'],
['R', '','ஹ'],
['T', '','க்ஷ'],
['Y', '','ஸ்ரீ'],
['U', '','ற'],
['I', '','ன'],
['O', '','['],
['P', '',']'],
//'\\{', '','ஆ'],
//'\\}', '','ஆ'],

['A', '','௹'],
['S', '','௺'],
['D', '','௸'],
['F', '','ஃ'],
['G', '','எ'],
['H', '','க'],
['J', '','ப'],
['K', '','"'],
['L', '',':'],
['\\:', '',';'],
['"', '',"'"],

['Z', '','௳'],
['X', '','௴'],
['C', '','௵'],
['V', '','௶'],
['B', '','௷'],
['N', '','ல'],
['M', '','/'],

['\\\\0', '','\u0be6'],
['\\\\1', '','௧'],
['\\\\2', '','௨'],
['\\\\3', '','௩'],
['\\\\4', '','௪'],
['\\\\5', '','௫'],
['\\\\6', '','௬'],
['\\\\7', '','௭'],
['\\\\8', '','௮'],
['\\\\9', '','௯']
];

jQuery.narayam.addScheme( 'ta-99', {
	'namemsg': 'narayam-ta-99',
	'extended_keyboard': false,
	'lookbackLength': 4,
	'keyBufferLength': 1,
	'rules': rules
} );