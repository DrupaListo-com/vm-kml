#!/usr/bin/env php
<?php
// require(__DIR__.'/config.php');

$in_fl='Концепция за веломаршрути в Област Русе ver 4.kml';

if ($argc > 1){
  $in_fl=$argv[1];
  echo "Input file: $in_fl\n";
}

$xml=new DOMDocument();
$xml->preserveWhiteSpace = false; 
$xml->load($in_fl);
$xpath = new DOMXPath($xml);
$root = $xml->documentElement;
$doc=$root->getElementsByTagName('Document')->item(0);


$base=$root->getElementsByTagName('Placemark')->item(0);

echo "List of all elements\n";
$err_cnt=0;

$all_marks=$root->getElementsByTagName('Placemark');
$len=$all_marks->length;
for ($i = $all_marks->length; --$i >= 0; ) {
  $pmark=$all_marks->item($i);
  if ($pmark->firstChild->nodeName != 'name') echo "Warning!!! First child not <name>\n";
  $name=preg_replace('/\s+/', ' ',trim($pmark->firstChild->nodeValue));
  echo "$name\n";
//   $layerId=mb_substr($name,0,1);
//   if (array_key_exists($layerId,$LAYERS) ){
//     $ind=mb_substr($name,0,5);
//     echo "OK $name\n";
//     $choices["$ind"]="$name";
//   } else {
//     echo "ERR!!! Element with name '$name' is not recodnised\n";
//     $err_cnt++;
//   }
}
echo "-----\n";
echo "Total number of errors: $err_cnt\n";
// echo "Choice string:\n";
// asort($choices);
//$string = preg_replace('/\s+/', ' ', trim($string));

// echo "['".implode("','", $choices)."']\n";

/* file_put_contents($options_array_file, "<?php\n\$ELEMENTS=".var_export($choices,TRUE)."\n?>\n"); 
*/
?>