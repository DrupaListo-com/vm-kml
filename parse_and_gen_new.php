#!/usr/bin/env php
<?php
// require(__DIR__.'/config.php');

$in_fl='Концепция за веломаршрути в Област Русе ver 4.kml';
$out_fl='output_with_geocoded.kml';

if ($argc > 1){
  $in_fl=$argv[1];
  echo "Input file: $in_fl\n";
}

if ($argc > 2){
  $out_fl=$argv[2];
  echo "Output file: $out_fl\n";
}

$xml=new DOMDocument();
$xml->preserveWhiteSpace = false; 
$xml->load($in_fl);
$xpath = new DOMXPath($xml);
$root = $xml->documentElement;
$doc=$root->getElementsByTagName('Document')->item(0);
$xpath->registerNamespace('a', 'http://earth.google.com/kml/2.2');

$base=$root->getElementsByTagName('Placemark')->item(0);

echo "List of all elements\n";
$err_cnt=0;

$all_marks=$root->getElementsByTagName('Placemark');
$len=$all_marks->length;
for ($i = $all_marks->length; --$i >= 0; ) {
  $pmark=$all_marks->item($i);
  if ($pmark->firstChild->nodeName != 'name') echo "Warning!!! First child not <name>\n";
  $name=$pmark->firstChild->nodeValue;
  $name=preg_replace('/\s+/', ' ',trim($name));
  
  echo "$name\n";
//   $layerId=mb_substr($name,0,1);
//   if (array_key_exists($layerId,$LAYERS) ){
//     $ind=mb_substr($name,0,5);
//     echo "OK $name\n";
//     $choices["$ind"]="$name";
//     
//     $new_string="За да коментираш тази велоалея/обект кликни тук: http://velo-ruse.eu/f/$ind <BR>\n<BR>\n";
//     //$old_desc=$xpath->query('styleUrl',$pmark)->item(0);
//   $old_desc=$pmark->childNodes->item(1);
// //     var_dump($pmark->childNodes->item(0));
// //     var_dump($pmark->childNodes->item(1));
// //     var_dump($pmark->childNodes->item(2));
// 
//     $old_desc_val=$old_desc->nodeValue;
//     $new_desc=$xml->createElement('description');
//     $cdata=$xml->createCDATASection($new_string.$old_desc_val);
//     $new_desc->appendChild($cdata);
//     $pmark->replaceChild($new_desc,$old_desc);
//      
//   } else {
//     echo "ERR!!! Element with name '$name' is not recodnised\n";
//     $err_cnt++;
//   }
}
echo "-----\n";
echo "Total number of errors: $err_cnt\n";

$xml->formatOutput = true; 
$xml->save($out_fl);

?>