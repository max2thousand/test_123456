<?php

/**
 * testScraper actions.
 *
 * @package    game_notifier
 * @subpackage testScraper
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class testScraperActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $url = sfConfig::get('app_superenalotto_home');
        $divsToExplore = explode(',', sfConfig::get('app_superenalotto_home_divs'));
        $historyUrl = sfConfig::get('app_superenalotto_history_base_url');
        $year = '2011';
        SuperEnalottoScraper::getSuperEnalottoHistoryByYear($historyUrl, $year);
        //SuperEnalottoScraper::getSuperEnalottoLastGame($url, $divsToExplore);
        die;
    }

}
