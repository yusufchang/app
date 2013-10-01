<?php
/**
 * FOR TESTING ONLY!!!! BETA VERSION!!!
 * User: krzychu
 * Date: 01.10.13
 * Time: 14:32
 * To change this template use File | Settings | File Templates.
 */
ini_set('display_errors',0);
class WikiCleanService extends Service {

	/**
	 * @param $text wikitext
	 * @return array 'text'=> cleaned text, 'images' => array of images
	 */
	public function cleanMethod1($text)
	{
		$sl = strlen($text) - 1 ;
		$out='';
		$i=-1;
		$tags_start = array('[['=>'t_tag','{{'=>'t_templ1','{|'=>'t_templ2');
		$tags_stop  = array(']]'=>'t_tag','}}'=>'t_templ1','|}'=>'t_templ2' );
		$images=array();
		while($i < $sl){
			$i++;

			//change & to &amp;
			if($text[$i]==='&')
			{
				$out.='&amp;';
				continue;
			}

			$char = $text[$i];

			if( $i < $sl   )
			{
				$char .= $text[$i+1];
			}

			//remove font variant attributes
			if($char==="''")
			{
				while($text[++$i]==="'");
				$i--;
				continue;
			}

			//remove lists
			if($char==="\n*")
			{
				while($text[++$i]==='*');
				$i--;
				continue;
			}

			//convert wikitext tag to xml tag
			if(isset($tags_start[$char]))
			{
				$i++;
				$out.='<'.$tags_start[$char].'>';

			}	else
			{
				$out.=$text[$i];
			}

		}


		$out2 = '';
		$i = strlen($out);
		while($i > 0){
			$i--;
			if($i > 0)
			{
				$char =$out[$i-1].$out[$i];
			}
			else{
				$char = $out[0];
			}
			//conver wikitext tag (end) to xml tag
			if(isset($tags_stop[$char]))
			{
				$i--;
				$out2.=strrev('</'.$tags_stop[$char].'>');

			}	else
			{
				$out2.=$out[$i];
			}
		}

		$out = strrev($out2);
		//parse xml - don't show errors
		libxml_use_internal_errors(1);
		$doc = new DOMDocument();
		$doc->loadHTML('<?xml encoding="UTF-8"?>'.$out);
		//find main body
		$bodys = $doc->getElementsByTagName ( 'body');
		$mainbody = $bodys->item(0);

		$replace=[];
		$todel=[];
		$images = [];

		for($i=0;$i<$mainbody->childNodes->length;$i++){
			$tag = $mainbody->childNodes->item($i);
			if(isset($tag->tagName))
			{
			//	echo "NAME:{$tag->tagName} value:{$tag->nodeValue}\n";
				// same as [[ ...... ]]
				if(in_array($tag->tagName,['t_tag']))
				{
					if( preg_match('/^([a-z0-9]+):([^\|]+)(\|?)/i',$tag->nodeValue,$m))
					{
						//looking for images
						if(in_array(substr($m[2],-4), array('.jpg','.png','.gif','.jpe','jpeg')))
						{
							$images[$m[2]] = true;
						}
						//echo "REMOVE {$tag->nodeValue}\n";
						//$tag->nodeValue='';
						//not needed anymore
						$todel[] = $tag;
					}

				}
				else
				{
					//other tags - delete them
					//$tag->nodeValue='';
					$todel[] = $tag;
				}

			}
		}

		//execute deletion
		 foreach($todel as $v){
			$mainbody->removeChild($v);

		}

		//find [[...]] tags
		$tags= $doc->getElementsByTagName('t_tag');
		$len = $tags->length;
		for($i=0;$i<$len;$i++)
		{
			$tag = $tags->item($i);
			$replace[] = $tag;
		}

		//now replace tags [[ ... ]] with text content
		foreach($replace as $tag){
			//echo "TAG:{$tag->nodeValue}\n";
			if(preg_match('/^([^:]+)(\|.+)$/',$tag->nodeValue,$m))
			{
				$newelement = new  DOMText(substr($m[2],1));
				//$tag->parentNode->replaceChild($newelement,$tag);
				$tag->parentNode->replaceChild($newelement,$tag);

			}
			elseif(preg_match('/\|(.+)$/',$tag->nodeValue,$m))
			{
				$newelement = new  DOMText($m[1]);

				$tag->parentNode->replaceChild($newelement,$tag);

			}
			else {

				$newelement = new  DOMText($tag->nodeValue);

				$tag->parentNode->replaceChild($newelement,$tag);

			}

		}

		$res = array('text'=>$mainbody->nodeValue, 'images'=>$images);

		libxml_use_internal_errors(0);

		return $res;


	}


	public function cleanMethod2($text)
	{
		//remove all html tags
		libxml_use_internal_errors(1);
		$doc =  new DOMDocument();
		$doc->loadHTML('<?xml encoding="UTF-8"?>'.$text);
		$bodys = $doc->getElementsByTagName ( 'body');
		$mainbody = $bodys->item(0);
		$text=$mainbody->textContent;
		//var_dump($text);
		//die();
		libxml_use_internal_errors(0);
		$depth = 0;
		$sl = strlen($text);

		$tmp = array();
		$lasts = array();
		$i = -1;
		$tags = array('[['=>']]','{{'=>'}}','{|'=>'|}');
		$omit = array('{{','{|');
		$images=array();
		while($i<$sl){
			$i++;

			/*if($text[$i]==='&')
			{
				$tmp[$depth].='&amp;';
				continue;
			}
			*/
			$char =$text[$i].$text[$i+1];

			if($char[0]==='[' && $char[1] !=='['){
				while($text[$i++]!==']' | $i>$sl);
				$i--;
				continue;
			}

			if(in_array($char,array_keys($tags)))
			{
				$i++;
				$depth ++;
				$lasts[$depth] = $char;
				$tmp [$depth]='';
				//echo "TAG START!!!!: $char-----\n";
			}else if(in_array($char,array_values($tags)))
			{
				//echo "ENDTAG!!!!: $char-----\n";
				$i++;
				$tag = $tmp [$depth];
				$last = $tags[$lasts[$depth]];

				$depth--;
				if($last!==$char) {
					//what to do with tag mismatch?
					continue; //die("MISMATCH $last:$char");
				}
				if($depth<0)
				{
					die('ERR');
				}

				$mustOmit = false;
				for($k=$depth ;$k>0 ;$k--)
				{
					if(in_array($lasts[$k],$omit))
					{
						$mustOmit = true;
						break;
					}

				}

				//omit tags
				if($mustOmit){
					$tag='';
				}
				else
				{
									//tag support - images
					if(preg_match('/^([a-z0-9]+):([^\|]+)(\|?).*$/i',$tag,$m))
					{
						//	var_dump($m);
						//$tag="+IMAGE_GOES_HERE:$m[2]+";
						$tag = '';//strtolower($m[1]);
						if(in_array(@substr($m[2],-4), array('.jpg','.png','.gif','.jpe','jpeg')))
						{
							//echo "IMAGE:{$m[2]}------\n";
							$images[$m[2]] = true;
						}
					}
					elseif(preg_match('/^([^:]+)(\|.+)$/',$tag,$m))
					{
						//convert [[...]] tags to its text content
						$tag = substr($m[2],1);
					}
					elseif(preg_match('/\|(.+)$/',$tag,$m))
					{
						$tag=$m[1];
					}
				}
				$tmp[$depth] .= $tag;

			}elseif($char==="''")
			{
				while($text[++$i]==="'");
				$i--;
				continue;
			}
			elseif($char==="\n*")
			{
				//convert list to tabs
				$tmp[$depth].="\n\t";
				while($text[++$i]==='*')
				{
					$tmp[$depth].="\t";
				}
				$i--;
				continue;
			}
			else
			{
				$tmp[$depth].=$text[$i];
			}
		}

		return array('text'=>(string)$tmp[0], 'images'=>$images);


	}

}