<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SuperEnalottoScraper
 *
 * @author max2thousand
 */
class SuperEnalottoScraper {

    public static function getSuperEnalottoLastGame($url, $divsToExplore) {
        /**
         * The superenalotto home page has the last combination of numbers, defined in several divs,
         * CombVinc_boxNum1...CombVinc_boxNum6 contains the 6 numbers,
         * CombVinc_boxJolly, contains the jolly number for the extraction,
         * CombVinc_boxSuperStar, the superstar
         * To verify if it's a contest we do not have, we also scrape the div CombVinc_boxDate, 
         * and check it with the one onto the database
         */
        $arrayResults = GenericScraper::ScrapeUrl($url, $divsToExplore);
        print_r($arrayResults);
    }

    public static function getSuperEnalottoHistoryByYear($baseUrl, $year) {
        //Make the url...
        $resArr = array();

        for ($i = 1; $i < 7; $i++) {
            $url = $baseUrl . '/' . $year . '/?pagina=' . $i;
            print_r('loading url: ' . $url . '<br />');
            $arrayResults = GenericScraper::ScrapeUrl($url, array('.UltimoConcorsoInfo_boxBottom_datiGrigio', '.UltimoConcorsoInfo_boxBottom_datiBianco'));
            //divide elements on the space 
            foreach ($arrayResults as $key => $value) {
                $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $value);
                $explodedNumbers = explode(' ', trim($stripped));
                $numContest = $explodedNumbers[0] . ':' . $explodedNumbers[1];
                unset($explodedNumbers[0]);
                unset($explodedNumbers[1]);
                if (sizeof($explodedNumbers) > 0)
                    $resArr[$numContest] = $explodedNumbers;
            }
        }
        print_r($resArr);

        die;
    }

}

?>
