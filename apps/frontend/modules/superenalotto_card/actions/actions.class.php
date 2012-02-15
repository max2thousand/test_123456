<?php

/**
 * superenalotto_card actions.
 *
 * @package    game_notifier
 * @subpackage superenalotto_card
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class superenalotto_cardActions extends sfActions
{
 
 
  public function executeShowSuperenalottoCard(sfWebRequest $request)
  {
      
  }
  
  public function executeCheckSaveCard(sfWebRequest $request)
  {
      $this->setAjaxLayout();
      $numbersPlayed = $request->getParameter('numbersPlayed','');
      $gameId = $request->getParameter('gameId','');
      $contestId = $request->getParameter('contestId','');
      $superstar = $request->getParameter('superstar','');
      $user = $this->getUser()->getGuardUser();
      
      $bet = new SuperenalottoBet();
      
      $bet->setContestId($contestId);
      $bet->setSuperstar($superstar);
      $bet->setGameId($gameId);
      $bet->setUserId($user->getId());
      $bet->setNumbersPlayed($numbersPlayed);
      $bet->save();
      return $this->renderText('OK');
  }
  
  private function setAjaxLayout()
  {
      sfView::NONE;
      $this->setLayout('false');
  }
  
}
