<?php

/**
 * Class that provides the scraping for a website.
 *
 * @author max2thousand
 */
class GenericScraper {

    /**
     * Method that provides to scrape a website, 
     * accepting an url and an array of elements to look for.
     * It returns an associative array with the elements scraped
     */
    public static function ScrapeUrl($url, $divToScrape) {
        $arrayResults = array();
        $html = new simple_html_dom();
        $html->load(self::curlCallToUrl($url));
        foreach ($divToScrape as $key => $value) {
            $element = $html->find($value);
            //if the element isn't found, let's try to reload the page
            $numRetries = 0;
            while(!$element)
            {
                $html->load(self::curlCallToUrl($url));
                $numRetries++;
                if($numRetries > 5)
                    return $arrayResults;
            }
            if(sizeof($element)>1)
            {
                for($i = 0; $i < sizeof($element); $i++)
                    $arrayResults[$value.' '.$i] = trim(strip_tags($element[$i]->innertext));
            }
            else
                $arrayResults[$value] = trim(strip_tags($element[0]->innertext));
        }
        return $arrayResults;
    }

    private static function curlCallToUrl($url) {
        $curl = curl_init();

        // Setup headers - I used the same headers from Firefox version 2.0.0.6
        // below was split up because php.net said the line was too long. :/
        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Pragma: ";
        // browsers keep this blank.

        $referers = array("google.com", "yahoo.com", "msn.com", "ask.com", "live.com");
        $choice = array_rand($referers);
        $referer = "http://" . $referers[$choice] . "";

        $browsers = array("Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008092510 Ubuntu/8.04 (hardy) Firefox/3.0.3", "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20060918 Firefox/2.0", "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3", "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)");
        $choice2 = array_rand($browsers);
        $browser = $browsers[$choice2];

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, $browser);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_REFERER, $referer);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 7);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($curl);

        if ($data === false) {
            $data = curl_error($curl);
        }

        // execute the curl command
        curl_close($curl);
        // close the connection
        return $data;
        // and finally, return $html
    }

}

?>
