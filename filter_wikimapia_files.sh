#!/bin/bash

FILES="Други (wikimapia).kml
Места за настаняване (wikimapia).kml
Религиозни обекти (wikimapia).kml
Ресторанти (wikimapia).kml"

SAVEIFS=$IFS
IFS=$(echo -en "\n\b")
for f in $FILES;do
  ls -l "$f"
  sed -e 's:&amp;quot;:":g' -e 's:quot;:":g' -e 's:<br> View or update this place information at Wikimapia.*ge)::' "$f" >save_$f
done
IFS=$SAVEIFS

