#!/usr/bin/env php
<?php

//API styleUrl
// https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=AIzaSyAWw8txKY5Ch_HcBQx69sE-XcWqTTwXOdQ'
define('API_KEY','AIzaSyAWw8txKY5Ch_HcBQx69sE-XcWqTTwXOdQ');
define('API_URL_PREF','https://maps.googleapis.com/maps/api/geocode/json?address=');
define('API_URL_SUFF','&key='.API_KEY);

//Save some requests
define('COORDS_IVANOVO','25.9561771,43.6845558,0.0');
$in_fl='Места за настаняване (egov.bg).kml';
$out_fl='output_nastaniavane_with_geocoded.kml';

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
  
  echo "Name:$name\n";
  
  if ($pmark->childNodes->length != 5){
    echo "ERROR!!!! Not enough children of $name";
    $err_cnt++;
    continue;
  }
  
  $address_tag=$pmark->childNodes->item(4)->tagName;
  $address_value=$pmark->childNodes->item(4)->firstChild->nodeValue;
  echo "Tagname = $address_tag\n";
  if ($address_tag == 'Point'){
    echo "Has <Point> tag! Value=$address_value\n";
  } else {
    echo "Has <Address> tag! Address value=$address_value\n";
    //Save some API calls for the most repeated value
    if ($address_value == 'Област Русе, Иваново'){
      $coords=COORDS_IVANOVO;
    } else {
      //Geocode the address
//       echo "json = $address_value";
      $json = file_get_contents(API_URL_PREF.urlencode($address_value).API_URL_SUFF);
      $obj = json_decode($json,true);
//       print_r($obj);
      $lat= $obj['results'][0]['geometry']['location']['lat'];
      $lon= $obj['results'][0]['geometry']['location']['lng'];
      $coords="$lon,$lat,0.0";
      echo "cooords=$coords\n";
      sleep(0.3);
    }
    
    $NewPoint_tag=new DOMElement('Point');
    $pmark->appendChild($NewPoint_tag);
    $NewCoord_tag=new DOMElement('coordinates',"$coords");
    $NewPoint_tag->appendChild($NewCoord_tag);
  }
  echo "==================================\n";
  
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