<?php

require_once("models/MessageModel.inc.php");
require_once("actions/Action.inc.php");

class SignUpAction extends Action
{

	/**
	 * Traite les données envoyées par le formulaire d'inscription
	 * ($_POST['signUpLogin'], $_POST['signUpPassword'], $_POST['signUpPassword2']).
	 *
	 * Le compte est crée à l'aide de la méthode 'addUser' de la classe Database.
	 *
	 * Si la fonction 'addUser' retourne une erreur ou si le mot de passe et sa confirmation
	 * sont différents, on envoie l'utilisateur vers la vue 'SignUpForm' avec une instance
	 * de la classe 'MessageModel' contenant le message retourné par 'addUser' ou la chaîne
	 * "Le mot de passe et sa confirmation sont différents.";
	 *
	 * Si l'inscription est validée, le visiteur est envoyé vers la vue 'MessageView' avec
	 * un message confirmant son inscription.
	 *
	 * @see Action::run()
	 */
	public function run()
	{
		/* TODO  */
		$nickname = $_POST['signUpLogin'];
		$pass = $_POST['signUpPassword'];
		$pass2 = $_POST['signUpPassword2'];

		if(!empty($nickname) && !empty($pass) && !empty($pass2)){
			if ($pass === $pass2) {
				$result = $this->database->addUser($nickname, $pass);
				if ($result === true) {
					$this->setModel(new MessageModel);
					$this->getModel()->setMessage("félicitation vous êtes inscris");
					$this->setView(getViewByName("Message"));
				}else{
					$this->setModel(new MessageModel);
					$this->getModel()->setMessage($result);
					$this->setView(getViewByName("signUpForm"));	
				}
			}else{
				$this->setModel(new MessageModel);
				$this->getModel()->setMessage("Le mot de passe et sa confirmation sont différents");
				$this->setView(getViewByName("signUpForm"));
			}
		}else{
			$this->setModel(new MessageModel);
				$this->getModel()->setMessage("remplissez");
				$this->setView(getViewByName("signUpForm"));
		}
	}

	private function createSignUpFormView($message)
	{
		$this->setModel(new MessageModel());
		$this->getModel()->setMessage($message);
		$this->getModel()->setLogin($this->getSessionLogin());
		$this->setView(getViewByName("SignUpForm"));
	}
}
