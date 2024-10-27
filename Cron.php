
<?php
/*
PHP script for downloading profile information from Printables.com and sending to zivyobraz.eu API
Recomended cron interval is 2 hour.

ABOUT SCRIPT:
The information is searched by the css class in the HTML code - "count svelte-156i68x"


MIT License
Copyright (c) 2024 3Dpetr_kolos

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/

$dom = new DOMDocument();

//Printables profile URL
$url = 'https://www.printables.com/@3Dpetr_kolos';

//Import URL WIth key - like = http://in.zivyobraz.eu/?import_key=SeCrEtApIkEy
$importURL = 'http://in.zivyobraz.eu/?import_key=SeCrEtApIkEy';


$html = file_get_contents($url);
$dom->loadHTML($html);

// Searching all elements with name span
$printablesElements = $dom->getElementsByTagName('span');
$c = 0;
$statsDownloads = 0;
$statsHearts = 0;
$statsFollowers = 0;
//All elements
foreach ($printablesElements as $htmlElement) {
    //filer elements by CSS class
    if ($htmlElement->getAttribute('class') === 'count svelte-156i68x') {
        // Get text from element
        $infoVariable =  $htmlElement->nodeValue;

        //First element is Download number
        if($c == 0){
            $c++;
            $statsDownloads = $infoVariable;
        }
        //Next is likes number
        else if($c == 1){
            $c++;
            $statsHearts = $infoVariable;
        }
        //Last element if Followers number
        else if($c == 2){
            $c++;
            $statsFollowers = $infoVariable;
        }

    }
}

if((!empty($statsDownloads)) && (!empty($statsHearts)) && (!empty($statsFollowers))){
    //GET zivyobraz API with parameters
    file_get_contents($importURL.'&printablesDownloads='.$statsDownloads.'&printablesHearts='.$statsHearts.'&printablesFollowers='.$statsFollowers);
    }

?>
