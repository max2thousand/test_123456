<?php
/**
 * superenalotto_card actions.
 *
 * @package    game_notifier
 * @subpackage superenalotto_card
 * @author     Massimiliano Pardini
 */
class superenalotto_cardActions extends sfActions {   
    public function executeShowSuperenalottoCard(sfWebRequest $request) {
        //I generate a token to be used when submitting the form.
        //Once used, this token MUST BE DELETED from the current session
        if(!$this->getUser()->getGuardUser())
        {
            //if not logged, just give a warning
            $this->warning = "Devi effettuare il login prima di poter inserire una schedina";
        }
        else if ($this->getUser()->getGuardUser()) {
            $this->token = sha1(uniqid("", true) . session_id());
            $this->getUser()->setFlash('token', $this->token);
            $this->gameId = $request->getParameter('gameId', '');
            $this->contestId = $request->getParameter('contestId', '');
        }
        else
            $this->forward404('Attack detected');
    }

    public function executeCheckSaveCard(sfWebRequest $request) {
        $token = $request->getParameter('token', '');
        if ($this->getUser()->getGuardUser() && $request->isMethod('POST')
                && $this->getUser()->getFlash('token') != ""
                && $this->getUser()->getFlash('token') == $token) {
            //I check that all the parameters are good...
            $this->setAjaxLayout();
            $numbersPlayed = $request->getParameter('numbersPlayed', '');
            $numArray = count(explode(',', $numbersPlayed));
            $gameId = $request->getParameter('gameId', '');
            $contestId = $request->getParameter('contestId', '');
            $superstar = $request->getParameter('superstar', '');
            $user = $this->getUser()->getGuardUser();
            if ($user && $contestId > 0 && $gameId > 0 && $numArray >= 6 && $numArray <= 20) {
                $bet = new SuperenalottoBet();
                $bet->setContestId($contestId);
                $bet->setSuperstar($superstar);
                $bet->setGameId($gameId);
                $bet->setUserId($user->getId());
                $bet->setNumbersPlayed($numbersPlayed);
                $bet->save();
                return $this->renderText('OK');
            } else {
                return $this->renderText('ERROR');
            }
        }
        else
            $this->forward404('Attack detected');
    }

    private function setAjaxLayout() {
        sfView::NONE;
        $this->setLayout('false');
    }

}
